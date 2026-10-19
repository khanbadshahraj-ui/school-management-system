# School Management System

AI Driven School Management System

## Foundation checkpoint

This is an incomplete implementation, not a finished or deployed school system. The source currently implements login, tenant-scoped student enrollment, school identity/color/regional settings, campuses, departments and dashboard counts. No production URL exists.

## Local prerequisites
PHP 8.3+ with PDO PostgreSQL, Composer, Node 24, PostgreSQL 16 and Redis 7. Use durable private S3-compatible storage when file workflows are implemented.

## Setup
1. Run `composer install` and `npm ci`.
2. Copy `.env.example` to `.env`; configure your own PostgreSQL and Redis connection. Keep `.env` private.
3. Run `php artisan key:generate` once for a new environment.
4. Run `php artisan migrate` against that environment.
5. Run `php artisan school:bootstrap owner@example.org --name="Your School" --slug=your-school` in a protected interactive terminal. It prompts privately for a password.
6. Run `npm run build` then `php artisan serve` for development.

## Verification
`npm run build` checks TypeScript and compiles assets. `php artisan test` requires an isolated PostgreSQL database named school_test. Never run RefreshDatabase tests against production.

The CI workflow provisions PostgreSQL and Redis on an external runner. It has not been executed remotely. The Procfile is a deployment candidate, not evidence of Heroku deployment. Read PROJECT_STATUS.md before relying on any claimed behavior.

## Known limitations
No MFA, logo uploads, fine-grained role editor, guardian linkage, complete academic setup, timetable, attendance, results, fee ledger, payroll, library, transport, hostel, inventory, exports or live provider integrations yet. Urdu layout direction is implemented; translations are not. Browser and database integration checks are pending. Application must not be used with real school data before security and workflow acceptance tests.
