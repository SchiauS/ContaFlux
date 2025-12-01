<?php

namespace App\Http\Controllers;

use App\Models\AiSession;
use Illuminate\Http\Request;

class AiSessionController extends Controller
{
    public function index(Request $request)
    {
        $query = AiSession::withCount('messages')->latest();

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->integer('company_id'));
        }

        return $query->paginate();
    }

    public function show(AiSession $aiSession)
    {
        return $aiSession->load('messages');
    }

    public function destroy(AiSession $aiSession)
    {
        $aiSession->delete();

        return response()->noContent();
    }
}
