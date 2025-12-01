<?php

return [
    'key' => env('OPENAI_API_KEY'),
    'base_url' => env('OPENAI_BASE_URL', 'https://api.openai.com'),
    'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
];
