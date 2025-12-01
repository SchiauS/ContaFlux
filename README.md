# ContaFlux

Platformă web de analiză contabilă și productivitate organizațională asistată de inteligență artificială.

## Ce conține repo-ul acum
- **docs/**: schiță arhitectură și funcționalități planificate.
- **backend/stubs/**: fișiere Laravel pregătite pentru a fi copiate după generarea aplicației (controllers, servicii, config, rute, .env exemplar).
- **frontend/**: pagină Bootstrap + jQuery cu layout de dashboard și chat pentru asistenta AI.

## Pași rapizi de pornire (după ce ai acces la internet)
1. Creează aplicația Laravel în folderul `backend`:
   ```bash
   cd backend
   composer create-project laravel/laravel .
   ```
2. Copiază stubs-urile peste structura Laravel generată:
   ```bash
   cp -R stubs/app app
   cp -R stubs/config config
   cp stubs/routes/api.php routes/api.php
   cp stubs/.env.example .env
   ```
3. Instalează cheile de aplicație și dependențele frontend:
   ```bash
   php artisan key:generate
   npm install && npm run build   # dacă folosești Vite
   ```
4. Setează variabilele de mediu (`OPENAI_API_KEY`, date DB) în `.env`.
5. Rulează migrațiile și serverul:
   ```bash
   php artisan migrate
   php artisan serve
   ```
6. Deschide `frontend/index.html` (sau integrează-l în `resources/views`) și actualizează URL-urile din AJAX pentru backend.

## Notă despre dependențe
Mediul curent nu are acces la rețea pentru a descărca pachetele Composer/NPM, de aceea repo-ul conține doar structura pregătitoare. După ce ai acces la internet, rulează pașii de mai sus pentru a completa instalarea.
