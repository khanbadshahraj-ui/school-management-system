# Deployment

The active target is **Render Free Docker + Neon Free PostgreSQL**. Follow [the current runbook](docs/RENDER_NEON.md) and `.env.demo.example`. `render.yaml` explicitly selects free; the connected Render creation tool also requires an explicit free plan. No payment method, paid worker, Redis, scheduler, disk or Render PostgreSQL is needed.

Docker startup runs secure profile validation, bounded direct-connection migration locking, forward migrations, optional idempotent owner bootstrap and Laravel cache compilation. Preserve APP_KEY through every deployment. Use pooled PostgreSQL for runtime and direct PostgreSQL for migration locking. Both require TLS.

The service can sleep and has free monthly quotas. /up is database-independent; /ready intentionally checks dependencies. Do not poll /ready or create keepalive traffic to defeat sleeping. The Blueprint selects /up; if created through a connector that cannot set healthCheckPath, Render's default port check remains database-independent and that configuration gap must be recorded.

Only fictional records belong in this demo. Small PNG/JPEG logos are durable database records; general uploads, live messages and payments are disabled. Free hosting is not a production readiness claim.

Heroku Procfile/buildpack hints are retained as a future migration option only. They are not the active deployment requirement or evidence of a deployed application. Future production requires reviewed operations, MFA/permissions, external durable files where enabled, backup restoration and capacity testing.
