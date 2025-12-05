<?php

namespace App\Http\Controllers;

use App\Models\AiSession;
use Illuminate\Http\Request;

class AiSessionController extends Controller
{
    public function index(Request $request)
    {
        $companyId = $request->user()->company_id;

        return AiSession::withCount('messages')
            ->where('company_id', $companyId)
            ->latest()
            ->paginate();
    }

    public function show(AiSession $aiSession)
    {
        $this->authorizeCompany($aiSession->company_id);
        return $aiSession->load('messages');
    }

    public function destroy(AiSession $aiSession)
    {
        $this->authorizeCompany($aiSession->company_id);
        $aiSession->delete();

        return response()->noContent();
    }

    private function authorizeCompany(?int $companyId): void
    {
        if ($companyId !== auth()->user()->company_id) {
            abort(403, 'Nu ai acces la această conversație AI.');
        }
    }
}
