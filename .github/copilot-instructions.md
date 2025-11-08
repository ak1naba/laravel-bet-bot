## Quick orientation for AI coding agents

This repository contains a containerized Laravel application located inside the `app/` directory. The goal of these notes is to help an AI agent be productive immediately by pointing to the project shape, development workflows, and known quirks.

1. Big picture
   - The Laravel project root is `app/` (artisan, `composer.json`, `package.json` live there). Treat `app/` as the application root.
   - Runtime is containerized via `docker-compose.yml`. Services: `app` (PHP-FPM), `webserver` (nginx) and `db` (Postgres).
   - Frontend tooling uses Vite (`app/package.json`) and Tailwind; realtime uses Laravel Echo + pusher-js.

2. Key files & locations to inspect
   - `app/artisan`, `app/composer.json`, `app/package.json` — primary scripts and dependencies.
   - `app/routes/web.php` and `app/Http/Controllers/` — HTTP endpoints and controllers.
   - `app/Telegram/` — Telegram bot commands and handlers (core domain integration).
   - `app/Models/` — domain models (Bet, Event, Market, Odd, Team, etc.).
   - `database/migrations/` and `database/seeders/` — schema and seed data.
   - `docker/Dockerfile` and `docker-compose.yml` — container build/runtime behavior and mounted volumes.
   - `docker/nginx/default.conf` — nginx routing mapped to container port 80 (host 8000).

3. Important workflows (concrete commands)
   - Containerized quick start (recommended):
     - docker-compose up -d --build
     - docker-compose exec app bash
     - cd /var/www  # maps to repo `app/`
     - composer install
     - cp .env.example .env && php artisan key:generate
     - php artisan migrate --seed
   - Local dev task (non-Docker or in-container): `composer dev` (defined in `app/composer.json`) — runs `php artisan serve`, `queue:listen`, `npm run dev` concurrently.
   - Frontend: inside `app/` run `npm run dev` (Vite) or `npm run build`.

4. Project-specific conventions & gotchas
   - Application lives under the repository path `app/`. Always run artisan/composer/npm commands from that folder or from the container where `/var/www` maps to `app/`.
   - Octane is listed in `app/composer.json` (high-performance server). The provided `docker/Dockerfile` does NOT install Swoole or RoadRunner. If you need to run Octane in containers, add Swoole or RoadRunner to the image and enable the extension.
   - Docker mounts `./app` into containers. File permission issues are common — storage and bootstrap/cache may require chmod/chown inside the container (examples in `app/README.md`).
   - Port mappings: host `8000` → nginx container port `80`; app container exposes `6001` (likely used for websocket / broadcasting).

5. Integrations to be aware of
   - Telegram: `irazasyed/telegram-bot-sdk` is used; handlers in `app/Telegram/` implement commands and flows.
   - Broadcasting: `laravel-echo`, `pusher-js` (frontend) and Laravel broadcasting server-side. Check `app/Events/BroadcastEvent.php` and `resources/js` for Echo usage.
   - Database: Postgres configured through `docker-compose.yml` (env vars: `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).

6. Tests, linting, formatting
   - Tests live in `app/tests/` (use `phpunit` from inside the container). `phpunit` is available in composer dev dependencies.
   - `laravel/pint` is present for formatting (run via composer scripts if added).

7. When editing code, prefer low-risk changes
   - Maintain PSR-4 autoloading (`App\` → `app/`).
   - Use existing service classes in `app/Services/` and keep controller actions thin.

If anything above is unclear or you'd like the instructions tuned to a specific task (tests, adding Octane with Swoole, CI setup), tell me which area to expand. 
