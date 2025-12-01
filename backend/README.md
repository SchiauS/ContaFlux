# Backend (Laravel) - ContaFlux

Directorul conține aplicația Laravel generată complet în `app/`. Include migrații pentru companii, conturi, tranzacții, task-uri și istoricul conversațiilor AI.

## Pași de instalare
1. `cd app`
2. Copiază `.env.example` în `.env` și setează `OPENAI_API_KEY` + setările de DB (implicit SQLite).
3. `composer install`
4. `php artisan key:generate`
5. `php artisan migrate`
6. `php artisan serve` (API disponibil pe `http://localhost:8000`).

## Endpoint-uri cheie
- CRUD: `/api/companies`, `/api/accounts`, `/api/transactions`, `/api/tasks`
- Dashboard: `/api/dashboard/summary`
- AI: `/api/ai/chat`, `/api/ai/summary`, `/api/ai/analyze`, `/api/ai-sessions`
- Healthcheck: `/api/health`

## Configurație OpenAI
Valorile se setează în `.env`:
```
OPENAI_API_KEY=sk-...
OPENAI_MODEL=gpt-4o-mini
OPENAI_BASE_URL=https://api.openai.com
```

## Alte fișiere utile
- `config/openai.php` – configurare serviciu OpenAI
- `app/Services/OpenAIService.php` – wrapper simplu peste API-ul OpenAI
- `app/Http/Controllers/*` – controlere API (AI, dashboard și CRUD)
- `database/migrations/*` – tabelele de date și AI
