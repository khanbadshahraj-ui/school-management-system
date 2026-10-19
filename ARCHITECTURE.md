# Architecture decision

## Target
Laravel 13 / PHP 8.3+, React with TypeScript and Inertia, PostgreSQL, Redis, private S3-compatible storage; modular monolith hosted externally on Heroku. Confirm resolved dependency compatibility before pinning versions.

## Tenant boundary
A school owns campus, academic and operational records. Every tenant table carries school_id. Tenant identifiers come from authenticated school membership, never from untrusted request bodies. Composite foreign keys prevent cross-school relationships. Tenant-scoped uniqueness applies to admissions, receipts and academic codes. Background jobs carry explicit school identity; file paths and caches use tenant namespaces. Platform access is exceptional and audited.

## Data and finance
Normalized relational records; JSONB only for validated configuration and custom fields. Decimal money with explicit currency. Financial posting uses transactions, row locks, idempotency keys and reversals. Posted ledger history is retained. Attendance and grading retain the relevant academic-year enrollment rather than overwriting student history.

## Deployment
Separate staging and production. Stateless web dynos, shared sessions, queue worker, supported scheduler, managed PostgreSQL/Redis, durable object storage. Release migrations must preserve compatibility with the preceding web release. Domain configuration changes the host rather than moving servers. Never regenerate the encryption key at release time.

## Sources checked
- https://laravel.com/docs/13.x/deployment — minimum PHP 8.3.
- https://devcenter.heroku.com/articles/php-support — PHP platform support.
