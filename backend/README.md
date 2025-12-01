# Backend (Laravel) - ContaFlux

Acest director va conține aplicația Laravel. În prezent include doar fișiere stub care să fie copiate după generarea proiectului (vezi README din rădăcina repo-ului).

## Pași de instalare (după obținerea accesului la internet)
1. `composer create-project laravel/laravel .`
2. Copiază conținutul din `stubs/` peste folderele Laravel (app, config, routes etc.).
3. Rulează `php artisan key:generate` și setează variabilele din `.env` (cheie OpenAI, DB).
4. Adaugă migrații pentru tabelele de AI și documente, apoi `php artisan migrate`.
5. Pornire server: `php artisan serve`.

## Fișiere cheie din stubs
- `app/Services/OpenAIService.php` — integrează OpenAI (chat, summary, analiză).
- `app/Http/Controllers/AiController.php` — expune rute `/api/ai/*`.
- `config/openai.php` — configurare cheie/model/bază URL.
- `routes/api.php` — rutele API inițiale.
- `.env.example` — configurare de bază pentru DB și OpenAI.
