# School Management System

Configurable Laravel 13 / PHP 8.3, React 19, TypeScript and PostgreSQL school-management foundation.

## Free demo deployment

The active target is **Render Free Docker + Neon Free PostgreSQL**, served from one HTTPS origin. Start with [the deployment runbook](docs/RENDER_NEON.md). `render.yaml`, `Dockerfile`, `.env.demo.example` and startup validation define the deployable demo. Heroku configuration is retained only as an optional future migration path.

The demo uses PostgreSQL sessions/cache, synchronous lightweight jobs, and small authenticated PNG/JPEG school logos stored in PostgreSQL. No Redis, paid worker, disk, object bucket, scheduler or paid integration is required. Documents, outbound messages, live payments and large exports remain disabled. Render may sleep; the database can also sleep. This is a fictional-data demonstration, not an always-on production service.

## Implemented workflows

Sign-in/logout, tenant isolation, school name/color/regional settings, school logo, campuses, departments, student enrollment/list/search and dashboard counts. Sensitive settings/enrollment/logo changes are audited. Demo owner bootstrap is repeatable and preserves existing passwords and records.

## Local development

1. Install PHP 8.3 with PDO PostgreSQL, Composer and Node 24; provide a dedicated local PostgreSQL database.
2. Run `composer install` and `npm ci`.
3. Copy `.env.example` to `.env` and set local database credentials. For a local non-TLS database only, set `DB_SSLMODE=disable`. Use `SESSION_DRIVER=database`, `CACHE_STORE=database`, `QUEUE_CONNECTION=sync` for development without Redis.
4. Run `php artisan key:generate` once, then `php artisan migrate`.
5. Run `php artisan school:bootstrap owner@example.org --name="Your School" --slug=your-school` in a private terminal; enter a strong password when prompted.
6. Run `npm run build` then `php artisan serve`.

## Verification

`npm run build` checks TypeScript and builds production assets. `php artisan test` requires an isolated PostgreSQL database named `school_test`; never point tests at the deployed demo. CI runs PostgreSQL integration tests, dependency audits and the exact Docker image with TLS PostgreSQL and container replacement checks. See TEST_EVIDENCE.md for completed results; workflow definitions alone are not evidence of passing tests.

## Remaining scope

This is an incomplete school-management foundation. MFA, detailed configurable permissions, guardians, academic setup, timetables, attendance, assignments, examinations, finance, payroll, library, transport, hostel, inventory, exports and provider integrations remain to be built. Urdu direction is supported; full translation, browser/device acceptance, backup restoration and capacity testing remain pending. Do not use real student data.
