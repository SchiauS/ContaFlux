# ContaFlux Backend (Laravel 12)

Aplicatie Laravel completă pentru platforma ContaFlux: gestionează companii, conturi, tranzacții, task-uri și sesiuni AI pentru asistentul financiar. API-ul expune resurse REST și endpoint-uri OpenAI.

## Instalare
1. `cd backend/app`
2. Copiază `.env.example` în `.env` și setează `OPENAI_API_KEY` + eventual conexiunea la MySQL dacă nu folosești SQLite.
3. Instalează dependențele PHP: `composer install`
4. Generează cheia aplicației: `php artisan key:generate`
5. Rulează migrațiile: `php artisan migrate`
6. Pornește serverul de dezvoltare: `php artisan serve`

## API-uri principale
- **Companii / Conturi / Tranzacții / Task-uri**: rute `apiResource` (`/api/companies`, `/api/accounts`, `/api/transactions`, `/api/tasks`).
- **Sesiuni AI**: `/api/ai-sessions` (listare, detalii, ștergere).
- **Asistent AI**: `/api/ai/chat`, `/api/ai/summary`, `/api/ai/analyze` folosind `OpenAIService`.
- **Dashboard**: `/api/dashboard/summary` (total debit/credit și task-uri deschise pentru perioada curentă).
- **Healthcheck**: `/api/health`.

## Structură cheie
- `app/Services/OpenAIService.php` – wrapper simplu peste API-ul OpenAI.
- `app/Http/Controllers/*` – controlere REST și AI.
- `database/migrations/*` – tabele pentru companii, conturi, tranzacții, task-uri și istoric conversații AI.

## Testing
- Rulează testele Laravel: `php artisan test`

## Notă
Aplicația folosește implicit SQLite (fișierul `database/database.sqlite`). Pentru MySQL/PostgreSQL modifică `DB_CONNECTION` și variabilele aferente din `.env`.
