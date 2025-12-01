# Arhitectură ContaFlux

## Obiectiv
Platformă web care combină analiză financiar-contabilă cu asistență AI și suport pentru productivitate (taskuri, SOP-uri, alerte).

## Stack propus
- **Backend**: Laravel + PHP 8.4, baza de date PostgreSQL/MySQL.
- **AI**: API OpenAI (chat completions, embeddings pentru căutare în documente interne).
- **Frontend**: Bootstrap 5 + jQuery; Chart.js pentru grafice; integrabil ulterior în Vite/Laravel Mix.

## Module backend
- **Autentificare & roluri**: Sanctum/Passport; roluri `manager`, `contabil`, `controller`.
- **Încărcare & prelucrare documente**: facturi, extrase; coadă pentru OCR/analiză; mapare automată pe conturi/centre de cost.
- **Analiză AI**:
  - Chat contextual pe baza datelor financiare ale organizației.
  - Rezumate periodice (lunare/săptămânale) cu indicatori: venituri, cheltuieli, marje, cashflow, rotația stocurilor.
  - Sugestii de clasificare/validare tranzacții.
- **Productivitate**: generare checklist-uri/SOP-uri per rol și perioadă; planificare taskuri.

## Structură fișiere (după generarea Laravel)
- `app/Services/OpenAIService.php` — invocări către API (chat + embeddings), cu logare și rate limiting.
- `app/Http/Controllers/AiController.php` — expune rute `/api/ai/chat`, `/api/ai/analyze`, `/api/ai/summary`.
- `app/Http/Controllers/DocumentController.php` — upload + declanșare analiză AI/embeddings.
- `app/Models/` — `Document`, `AiSession`, `AiMessage`, `KpiSnapshot`, `TaskTemplate` etc.
- `config/openai.php` — chei și setări model (gpt-4.1-mini etc.).
- `routes/api.php` — rutele API (chat, summary, uploads, tasks).
- `database/migrations/*` — tabele pentru sesiuni AI, mesaje, documente, taskuri.

## Fluxuri API
1. **Chat financiar**
   - POST `/api/ai/chat` cu `session_id`, `message`, `context` (opțional).
   - Controller → `OpenAIService::chat()` → răspuns JSON cu mesaj AI + scoruri.
   - Mesajele se salvează în `ai_messages` pentru istoric.
2. **Analiză document**
   - POST `/api/documents` (multipart), salvează fișierul, generează embeddings + recomandări de contare.
3. **Rapoarte și KPI**
   - GET `/api/ai/summary?period=month` — returnează insight-uri text + valori KPI.
4. **Checklist/SOP-uri**
   - POST `/api/ai/tasks` cu rol/periodicitate → returnează listă de taskuri sugerate.

## Considerații de securitate & conformitate
- Anonimizați datele sensibile înainte de trimiterea către API; folosiți câmpuri agregate.
- Rate limiting per utilizator și audit al prompturilor/rezultatelor.
- Control al versiunilor modelelor și testare A/B pentru prompturi.

## Pași următori
- Generare proiect Laravel în `backend` (vezi README principal).
- Adăugare migrații și modele pentru `ai_sessions`, `ai_messages`, `documents`, `kpi_snapshots`, `task_templates`.
- Implementare cozi (Redis/Database) pentru procesarea documentelor.
- Integrare frontend în Vite + rute API conectate.
