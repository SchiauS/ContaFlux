<?php

return [
    'base_url' => env('OPENAI_BASE_URL', 'https://api.openai.com'),
    'key' => env('OPENAI_API_KEY'),
    'model' => env('OPENAI_MODEL', 'gpt-4.1-mini'),
    'embeddings_model' => env('OPENAI_EMBEDDINGS_MODEL', 'text-embedding-3-small'),
];
