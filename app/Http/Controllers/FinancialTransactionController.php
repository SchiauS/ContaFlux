<?php

namespace App\Http\Controllers;

use App\Models\FinancialAccount;
use App\Models\FinancialTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class FinancialTransactionController extends Controller
{
    public function index(Request $request)
    {
        $companyId = $request->user()->company_id;

        $allowedSorts = [
            'occurred_at' => 'occurred_at',
            'description' => 'description',
            'amount' => 'amount',
            'direction' => 'direction',
            'account' => 'financial_account_id',
        ];

        $sortColumn = $allowedSorts[$request->get('sort', 'occurred_at')] ?? 'occurred_at';
        $direction = in_array($request->get('direction'), ['asc', 'desc']) ? $request->get('direction') : 'desc';

        $transactions = FinancialTransaction::with(['account', 'company'])
            ->where('company_id', $companyId)
            ->orderBy($sortColumn, $direction)
            ->paginate()
            ->withQueryString();

        if ($request->wantsJson()) {
            return $transactions;
        }

        return view('transactions.index', [
            'transactions' => $transactions,
            'company' => \App\Models\Company::findOrFail($companyId),
            'accounts' => \App\Models\FinancialAccount::where('company_id', $companyId)->orderBy('code')->pluck('code', 'id'),
            'currentSort' => $request->get('sort', 'occurred_at'),
            'currentDirection' => $direction,
        ]);
    }

    public function import(Request $request)
    {
        $validated = $request->validate([
            'transactions_file' => 'required|file|mimes:csv,txt,xlsx|max:10240',
            'currency' => 'nullable|string|size:3',
        ]);

        $companyId = $request->user()->company_id;

        $format = $this->detectFormat($validated['transactions_file']);
        $rows = $this->extractRowsFromFile($validated['transactions_file'], $format);

        $summary = [
            'detected_format' => strtoupper($format),
            'raw_rows' => count($rows),
            'validated_rows' => 0,
            'inserted' => 0,
            'skipped_invalid' => 0,
            'skipped_unmapped_accounts' => 0,
        ];

        DB::transaction(function () use ($rows, &$summary, $companyId, $validated) {
            foreach ($rows as $row) {
                $normalized = $this->normalizeRow($row);

                if (! $normalized) {
                    $summary['skipped_invalid']++;

                    continue;
                }

                $summary['validated_rows']++;

                $account = FinancialAccount::where('company_id', $companyId)
                    ->where('code', $normalized['account_number'])
                    ->first();

                if (! $account) {
                    $summary['skipped_unmapped_accounts']++;

                    continue;
                }

                $this->ensureAccountCategory($account);

                FinancialTransaction::create([
                    'company_id' => $companyId,
                    'financial_account_id' => $account->id,
                    'counterparty' => $normalized['counterparty'],
                    'description' => $normalized['description'],
                    'direction' => $normalized['direction'],
                    'amount' => $normalized['amount'],
                    'currency' => $validated['currency'] ?? 'RON',
                    'occurred_at' => $normalized['occurred_at'],
                    'metadata' => [
                        'balance' => $normalized['balance'],
                        'source_file' => $validated['transactions_file']->getClientOriginalName(),
                        'account_category' => $account->category,
                    ],
                ]);

                $summary['inserted']++;
            }
        });

        $statusMessage = sprintf(
            'Import finalizat (%s). Rânduri procesate: %d | Validate: %d | Inserate: %d | Invalide: %d | Fără cont mapat: %d',
            $summary['detected_format'],
            $summary['raw_rows'],
            $summary['validated_rows'],
            $summary['inserted'],
            $summary['skipped_invalid'],
            $summary['skipped_unmapped_accounts'],
        );

        return redirect()->route('transactions.index')
            ->with('status', $statusMessage)
            ->with('importSummary', $summary);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'financial_account_id' => 'required|integer|exists:financial_accounts,id',
            'counterparty' => 'nullable|string',
            'description' => 'nullable|string',
            'direction' => 'required|in:debit,credit',
            'amount' => 'required|numeric',
            'currency' => 'nullable|string|size:3',
            'tax_rate' => 'nullable|numeric',
            'occurred_at' => 'required|date',
            'metadata' => 'array',
        ]);

        if (! FinancialAccount::where('company_id', $request->user()->company_id)->whereKey($data['financial_account_id'])->exists()) {
            abort(403, 'Contul selectat nu aparține companiei tale.');
        }

        $transaction = FinancialTransaction::create(array_merge($data, [
            'company_id' => $request->user()->company_id,
        ]));

        if ($request->wantsJson()) {
            return response()->json($transaction->load(['account', 'company']), 201);
        }

        return redirect()->route('transactions.index')->with('status', 'Tranzacția a fost înregistrată.');
    }

    public function show(Request $request, FinancialTransaction $financialTransaction)
    {
        $this->assertSameCompany($request, $financialTransaction);

        return $financialTransaction->load(['account', 'company']);
    }

    public function update(Request $request, FinancialTransaction $financialTransaction)
    {
        $companyId = $request->user()->company_id;

        $this->assertSameCompany($request, $financialTransaction);

        $data = $request->validate([
            'counterparty' => 'nullable|string',
            'description' => 'nullable|string',
            'direction' => 'in:debit,credit',
            'amount' => 'numeric',
            'currency' => 'nullable|string|size:3',
            'tax_rate' => 'nullable|numeric',
            'occurred_at' => 'date',
            'metadata' => 'array',
            'financial_account_id' => [
                'sometimes',
                'required',
                'integer',
                Rule::exists('financial_accounts', 'id')->where('company_id', $companyId),
            ],
        ]);

        $currentAccount = $request->input('current_financial_account_id');
        if (array_key_exists('financial_account_id', $data)) {
            $data['financial_account_id'] = $data['financial_account_id'] ?? $currentAccount ?? $financialTransaction->financial_account_id;
        } elseif ($currentAccount) {
            $data['financial_account_id'] = $currentAccount;
        }

        $financialTransaction->fill($data);

        $financialTransaction->company_id = $companyId;

        if (! $financialTransaction->isDirty()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Nu există modificări de salvat.',
                    'transaction' => $financialTransaction->load(['account', 'company']),
                ]);
            }

            return redirect()->route('transactions.index')->with('status', 'Nu au fost efectuate modificări.');
        }

        $financialTransaction->save();

        if ($request->wantsJson()) {
            return response()->json($financialTransaction->load(['account', 'company']));
        }

        return redirect()->route('transactions.index')->with('status', 'Tranzacția a fost actualizată.');
    }

    public function destroy(FinancialTransaction $financialTransaction)
    {
        $this->assertSameCompany(request(), $financialTransaction);

        $financialTransaction->delete();

        if (request()->wantsJson()) {
            return response()->noContent();
        }

        return redirect()->route('transactions.index')->with('status', 'Tranzacția a fost ștearsă.');
    }

    public function export(Request $request)
    {
        $companyId = $request->user()->company_id;

        $transactions = FinancialTransaction::with('account')
            ->where('company_id', $companyId)
            ->orderBy('occurred_at')
            ->get();

        $columns = [
            'Data',
            'Cont',
            'Descriere',
            'Direcție',
            'Sumă',
            'Monedă',
            'Partener',
            'Sold',
            'Categorie cont',
        ];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray($columns, null, 'A1');

        $row = 2;

        foreach ($transactions as $transaction) {
            $sheet->fromArray([
                optional($transaction->occurred_at)->format('Y-m-d'),
                optional($transaction->account)->code,
                $transaction->description,
                $transaction->direction,
                $transaction->amount,
                $transaction->currency,
                $transaction->counterparty,
                $transaction->metadata['balance'] ?? null,
                optional($transaction->account)->category,
            ], null, "A{$row}");

            $row++;
        }

        $callback = static function () use ($spreadsheet) {
            IOFactory::createWriter($spreadsheet, 'Xlsx')->save('php://output');
        };

        return response()->streamDownload($callback, 'transactions.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    private function detectFormat(UploadedFile $file): string
    {
        $extension = strtolower($file->getClientOriginalExtension());

        return in_array($extension, ['xlsx']) ? 'xlsx' : 'csv';
    }

    private function extractRowsFromFile(UploadedFile $file, string $format): array
    {
        if ($format === 'xlsx') {
            $spreadsheet = IOFactory::load($file->getRealPath());

            return $spreadsheet->getActiveSheet()->toArray();
        }

        $rows = [];
        $csv = fopen($file->getRealPath(), 'rb');

        while (($data = fgetcsv($csv, 0, ',')) !== false) {
            $rows[] = $data;
        }

        fclose($csv);

        return $rows;
    }

    private function normalizeRow(array $row): ?array
    {
        $row = array_map(fn ($value) => is_string($value) ? trim($value) : $value, $row);

        if ($this->looksLikeHeaderRow($row)) {
            return null;
        }

        if ($this->rowIsEmpty($row)) {
            return null;
        }

        $occurredAt = $this->parseDate($row[0] ?? null);
        $accountNumber = (string) ($row[1] ?? '');
        $description = $row[2] ?? null;
        $direction = $this->normalizeDirection($row[3] ?? null);
        $amount = $this->castToNumeric($row[4] ?? null);
        $balance = $this->castToNumeric($row[5] ?? null);

        if (! $occurredAt || ! $accountNumber || ! $direction || $amount === null) {
            return null;
        }

        return [
            'occurred_at' => $occurredAt,
            'account_number' => $accountNumber,
            'description' => $description,
            'counterparty' => $description,
            'direction' => $direction,
            'amount' => $amount,
            'balance' => $balance,
        ];
    }

    private function rowIsEmpty(array $row): bool
    {
        return count(array_filter($row, fn ($value) => $value !== null && $value !== '')) === 0;
    }

    private function looksLikeHeaderRow(array $row): bool
    {
        $firstCell = strtolower((string) ($row[0] ?? ''));

        return str_contains($firstCell, 'dată') || str_contains($firstCell, 'data');
    }

    private function parseDate($value): ?Carbon
    {
        if (! $value) {
            return null;
        }

        try {
            return Carbon::parse($value);
        } catch (\Exception) {
            return null;
        }
    }

    private function normalizeDirection(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        $value = strtolower(trim($value));

        return match ($value) {
            'c', 'credit' => 'credit',
            'd', 'debit' => 'debit',
            default => null,
        };
    }

    private function castToNumeric($value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        $normalized = str_replace(['.', ' '], ['', ''], (string) $value);
        $normalized = str_replace(',', '.', $normalized);

        return is_numeric($normalized) ? (float) $normalized : null;
    }

    private function ensureAccountCategory(FinancialAccount $account): void
    {
        if ($account->category) {
            return;
        }

        $account->category = $this->inferCategoryFromAccountNumber($account->code);
        $account->save();
    }

    private function inferCategoryFromAccountNumber(string $accountNumber): string
    {
        return match (true) {
            str_starts_with($accountNumber, '1') => 'Active',
            str_starts_with($accountNumber, '2') => 'Capital și datorii',
            str_starts_with($accountNumber, '3') => 'Stocuri',
            str_starts_with($accountNumber, '4') => 'Costuri',
            str_starts_with($accountNumber, '7') => 'Venituri',
            default => 'Altele',
        };
    }

    private function assertSameCompany(Request $request, FinancialTransaction $financialTransaction): void
    {
        abort_unless($financialTransaction->company_id === $request->user()->company_id, 403);
    }

}
