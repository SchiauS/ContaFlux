# ContaFlux

Platformă web de analiză contabilă și productivitate organizațională asistată de inteligență artificială.

## Ce conține repo-ul
- **backend/app**: aplicație Laravel completă (API REST + integrare OpenAI) cu migrații și controlere gata de rulare.
- **docs/**: schiță arhitectură și funcționalități planificate.
- **frontend/**: pagină Bootstrap + jQuery cu layout de dashboard și chat pentru asistenta AI.

## Pornire rapidă backend
1. `cd backend/app`
2. Copiază `.env.example` în `.env` și setează `OPENAI_API_KEY` + conexiunea DB (implicit SQLite).
3. `composer install`
4. `php artisan key:generate`
5. `php artisan migrate`
6. `php artisan serve` (API pe `http://localhost:8000`).

Endpoint-uri cheie: `/api/companies`, `/api/accounts`, `/api/transactions`, `/api/tasks`, `/api/dashboard/summary`, `/api/ai/*`, `/api/health`.

## Frontend demo
- `frontend/index.html` folosește Bootstrap + jQuery și poate fi servit static (actualizează URL-urile AJAX spre backend-ul de mai sus).

## Note
- Aplicația folosește implicit SQLite (`database/database.sqlite`). Pentru MySQL/PostgreSQL modifică variabilele `DB_*` în `.env`.
- `OPENAI_API_KEY` este necesară pentru endpoint-urile AI (`/api/ai/*`).
