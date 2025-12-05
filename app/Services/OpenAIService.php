<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAIService
{
    public function chat(string $message, array $context = [], ?string $sessionId = null): array
    {
        $payload = [
            'model' => config('openai.model'),
            'messages' => [
                ['role' => 'system', 'content' => 'Assistant financiar pentru platforma ContaFlux. Raspunde concis si clar.'],
                ...$this->mapContextMessages($context),
                ['role' => 'user', 'content' => $message],
            ],
            'temperature' => 0.3,
        ];

        $response = $this->post('/v1/chat/completions', $payload);

        return [
            'session_id' => $sessionId,
            'message' => data_get($response, 'choices.0.message.content'),
            'usage' => data_get($response, 'usage'),
        ];
    }

    public function summary(string $period, array $metrics = []): array
    {
        $prompt = "Genereaza un rezumat financiar pentru perioada {$period}, cu indicatorii: "
            . implode(', ', $metrics ?: ['venituri', 'cheltuieli', 'marja'])
            . '. Returneaza bullet points concise.';

        $response = $this->post('/v1/chat/completions', [
            'model' => config('openai.model'),
            'messages' => [
                ['role' => 'system', 'content' => 'Esti un analist financiar concis.'],
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        return [
            'period' => $period,
            'summary' => data_get($response, 'choices.0.message.content'),
            'usage' => data_get($response, 'usage'),
        ];
    }

    public function analyze(array $payload, ?string $schema = null): array
    {
        $prompt = "Analizeaza date contabile conform schema: {$schema}. Date: " . json_encode($payload);

        $response = $this->post('/v1/chat/completions', [
            'model' => config('openai.model'),
            'messages' => [
                ['role' => 'system', 'content' => 'Expert contabil. Ofera raspunsuri structurate JSON.'],
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        return [
            'analysis' => data_get($response, 'choices.0.message.content'),
            'usage' => data_get($response, 'usage'),
        ];
    }

    private function post(string $path, array $payload): array
    {
        try {
            $response = Http::withToken(config('openai.key'))
                ->acceptJson()
                ->post(rtrim(config('openai.base_url'), '/') . $path, $payload)
                ->throw()
                ->json();
        } catch (RequestException $exception) {
            $response = $exception->response;

            if ($response?->status() === 429) {
                $apiMessage = data_get($response->json(), 'error.message');

                Log::warning('OpenAI rate limit hit', [
                    'message' => $apiMessage,
                    'headers' => [
                        'limit_requests' => $response->header('x-ratelimit-limit-requests'),
                        'remaining_requests' => $response->header('x-ratelimit-remaining-requests'),
                        'reset_requests' => $response->header('x-ratelimit-reset-requests'),
                        'limit_tokens' => $response->header('x-ratelimit-limit-tokens'),
                        'remaining_tokens' => $response->header('x-ratelimit-remaining-tokens'),
                        'reset_tokens' => $response->header('x-ratelimit-reset-tokens'),
                        'retry_after' => $response->header('retry-after'),
                    ],
                ]);

                $message = 'Serviciul AI a atins o limita temporara la furnizor. '
                    . ($response->header('retry-after')
                        ? 'Incercati din nou dupa ' . $response->header('retry-after') . ' secunde.'
                        : 'Incercati din nou in scurt timp.');

                if (! empty($apiMessage)) {
                    $message .= ' Mesaj API: ' . $apiMessage;
                }

                throw new HttpResponseException(response()->json([
                    'message' => $message,
                ], 429));
            }

            $apiMessage = data_get($response?->json(), 'error.message')
                ?? $response?->body()
                ?? $exception->getMessage();

            throw new HttpResponseException(response()->json([
                'message' => 'Serviciul AI a returnat o eroare: ' . $apiMessage,
            ], $response?->status() ?? 500));
        }

        Log::info('OpenAI request', [
            'path' => $path,
            'tokens' => data_get($response, 'usage.total_tokens'),
        ]);

        return $response;
    }

    private function mapContextMessages(array $context): array
    {
        return collect($context)
            ->map(fn ($item) => [
                'role' => $item['role'] ?? 'user',
                'content' => $item['content'] ?? '',
            ])
            ->all();
    }
}
