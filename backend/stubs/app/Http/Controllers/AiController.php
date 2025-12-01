<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\OpenAIService;

class AiController extends Controller
{
    public function __construct(private OpenAIService $openAIService)
    {
    }

    public function chat(Request $request)
    {
        $data = $request->validate([
            'session_id' => 'nullable|string',
            'message' => 'required|string',
            'context' => 'array'
        ]);

        $response = $this->openAIService->chat(
            $data['message'],
            $data['session_id'] ?? null,
            $data['context'] ?? []
        );

        return response()->json($response);
    }

    public function summary(Request $request)
    {
        $period = $request->query('period', 'month');
        $metrics = $request->input('metrics', []);

        $summary = $this->openAIService->summary($period, $metrics);

        return response()->json($summary);
    }

    public function analyze(Request $request)
    {
        $data = $request->validate([
            'payload' => 'required|array',
            'schema' => 'nullable|string'
        ]);

        $analysis = $this->openAIService->analyze($data['payload'], $data['schema'] ?? null);

        return response()->json($analysis);
    }
}
