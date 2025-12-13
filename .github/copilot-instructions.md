## Copilot instructions for Be Okay (Laravel)

Purpose: Help AI code assistants make productive, repository-specific edits quickly.

1) Big picture
- This is a Laravel app (MVC) with service classes and policies. Main entrypoints:
  - HTTP routes: [routes/web.php](routes/web.php)
  - Controllers: [app/Http/Controllers](app/Http/Controllers)
  - Models: [app/Models](app/Models)
  - Services: [app/Services/MidtransService.php](app/Services/MidtransService.php)
  - Views & frontend: [resources/views](resources/views) and Vite config [vite.config.js](vite.config.js)

2) Common workflows & commands
- Install dependencies: `composer install` and `npm install`.
- Copy and configure environment: create `.env` and set `DB_*`, `MIDTRANS_*` values.
- Run DB setup: `php artisan migrate --seed` (see README). Use XAMPP MySQL on Windows.
- Dev servers: `php artisan serve` and `npm run dev` (Vite + Tailwind).
- Run tests: `vendor/bin/phpunit` or `php artisan test` using [phpunit.xml](phpunit.xml).

3) Project-specific patterns
- Small service classes live in `app/Services/` (e.g., Midtrans payment wrapper). Prefer adding logic there for cross-controller reuse.
- Mailables: OTP mail is in [app/Mail/OTPCodeMail.php](app/Mail/OTPCodeMail.php). Use Mailables for email content.
- Policies: Authorization uses `app/Policies/*` (see `AuthServiceProvider` bindings in [bootstrap/app.php](bootstrap/app.php)).
- Helpers: Reusable helpers in [app/Helpers/NavigationHelper.php](app/Helpers/NavigationHelper.php) — add similarly named helper classes for shared UI logic.
- Factories & seeders: Check `database/factories` and `database/seeders` for synthetic data patterns when adding test data.

4) Integration & external dependencies
- Midtrans payment integration: config in [config/midtrans.php](config/midtrans.php) and wrapper in `app/Services/MidtransService.php` — update both when changing payment flows.
- Uses Mail, Queue, and storage — check `config/*.php` when adjusting drivers (mail, queue, filesystems).

5) When editing code
- Keep changes small and focused; preserve controller thinness — move business logic to `app/Services` or model methods.
- Respect existing policies and gates: add policy checks for sensitive edits (see `app/Policies/*`).
- Use existing factories for tests and seed data rather than inventing new data shapes.

6) Files to inspect for context (quick list)
- [app/Services/MidtransService.php](app/Services/MidtransService.php)
- [app/Mail/OTPCodeMail.php](app/Mail/OTPCodeMail.php)
- [routes/web.php](routes/web.php)
- [app/Http/Controllers](app/Http/Controllers)
- [database/migrations](database/migrations)
- [database/factories](database/factories)

7) Examples
- To add a new payment flow: update `app/Services/MidtransService.php`, then add config keys in `config/midtrans.php`, and update relevant controllers in `app/Http/Controllers`.
- To add a background task: create a job, wire queue driver in `config/queue.php`, and add to `.env`.

If anything here is unclear or you want more examples (tests, controller refactors, or specific feature patterns), tell me which area and I'll expand the file.
