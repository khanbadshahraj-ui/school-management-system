# Architecture

## Active demonstration
Laravel 13 / PHP 8.3, React 19 / TypeScript / Inertia and PostgreSQL form one modular monolith. A multi-stage Docker image compiles the frontend and production Composer dependencies. Apache serves the public directory on Render's assigned PORT behind its HTTPS proxy.

Render Free runs one web service. Neon Free supplies an isolated school_demo database on the school-management-demo branch of an existing Vercel-managed free project. That account blocks separate project creation, so its existing primary branch and databases remain untouched. The demo endpoint has a 0.25 CU compute ceiling and provider-controlled suspension. Render and Neon share the US East region group for this account.

## Durability and profiles
PostgreSQL stores schools, users, students, campuses, departments, audit records, encrypted sessions, cache and bounded school logos. PNG/JPEG logos use a replaceable SchoolLogoStorage contract and database adapter; ordinary documents are disabled. No permanent content relies on Render's local disk.

Runtime queries use the Neon transaction pooler; migrations and their advisory lock use a direct connection. Both URLs must identify the same endpoint/database with TLS. An idempotent bootstrap creates an initial owner only when explicitly enabled and preserves an existing account/password. Startup validates settings, migrates forward and builds Laravel caches before starting the server.

The demo profile uses sync jobs, database sessions/cache and a non-sending mail transport. Bulk files, external messages and live payments are disabled. Production remains a separate stricter profile requiring appropriate Redis/background services and durable object storage for enabled files. The demo has no worker or unattended scheduler and is not an always-on service.

## Tenant boundary
Tenant records carry school_id. The authenticated user's membership selects the school; request-supplied school identifiers do not select tenancy. A scoped context fails closed and is cleared after use. Composite foreign keys protect cross-school student/campus relationships. Logo requests select only the signed-in school. Initial roles enforce admin-only branding and organization changes; a complete configurable permission system remains pending.

## Future modules and hosting
Future finance must use exact decimals, atomic ledger posting, idempotency and reversal records. Academic records must retain year-specific enrollment history. These contracts are requirements, not currently implemented finance or academic modules.

The Docker image can move to another HTTPS host using environment configuration. Retain APP_KEY and migrate/restore the database with verification. A domain changes DNS/canonical URL and does not replace hosting. No provider credentials belong in Git. Application rollback does not undo database migrations.
