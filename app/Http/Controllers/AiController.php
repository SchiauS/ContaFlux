<?php

namespace App\Http\Controllers;

use App\Models\AiMessage;
use App\Models\AiSession;
use App\Services\OpenAIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AiController extends Controller
{
    public function __construct(private OpenAIService $openAIService)
    {
    }

    public function chat(Request $request)
    {
        $data = $request->validate([
            'session_id' => 'nullable|integer|exists:ai_sessions,id',
            'message' => 'required_without:prompt|nullable|string',
            'prompt' => 'required_without:message|nullable|string',
            'context' => 'array',
            'topic' => 'nullable|string',
        ]);

        $message = $data['message'] ?? $data['prompt'];

        $companyId = $request->user()->company_id;

        $sessionId = $data['session_id'] ?? null;

        $session = $sessionId
            ? AiSession::where('company_id', $companyId)->findOrFail($sessionId)
            : AiSession::create([
                'company_id' => $companyId,
                'user_id' => $request->user()?->id,
                'topic' => $data['topic'] ?? 'chat financiar',
                'model' => config('openai.model'),
            ]);

        $contextMessages = $session->messages()
            ->latest()
            ->take(10)
            ->get()
            ->reverse()
            ->map(fn (AiMessage $message) => [
                'role' => $message->role,
                'content' => $message->content,
            ])
            ->toArray();

        if (! empty($data['context'])) {
            $contextMessages = array_merge($contextMessages, $data['context']);
        }

        $reply = DB::transaction(function () use ($session, $data, $contextMessages, $message) {
            $session->messages()->create([
                'role' => 'user',
                'content' => $message,
            ]);

            $response = $this->openAIService->chat($message, $contextMessages, (string) $session->id);

            $session->messages()->create([
                'role' => 'assistant',
                'content' => $response['message'],
                'prompt_tokens' => data_get($response, 'usage.prompt_tokens'),
                'completion_tokens' => data_get($response, 'usage.completion_tokens'),
            ]);

            return $response;
        });

        return response()->json([
            'session_id' => $session->id,
            'message' => $reply['message'],
            'usage' => $reply['usage'],
        ]);
    }

    public function summary(Request $request)
    {
        $data = $request->validate([
            'period' => 'sometimes|string',
            'metrics' => 'array',
        ]);

        $summary = $this->openAIService->summary(
            $data['period'] ?? 'month',
            $data['metrics'] ?? []
        );

        return response()->json($summary);
    }

    public function analyze(Request $request)
    {
        $data = $request->validate([
            'payload' => 'required|array',
            'schema' => 'nullable|string',
        ]);

        $analysis = $this->openAIService->analyze($data['payload'], $data['schema'] ?? null);

        return response()->json($analysis);
    }
}
