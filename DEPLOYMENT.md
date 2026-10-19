# Deployment plan — not yet executed

1. Verify and pin PHP/Laravel/frontend runtime versions.
2. Build and test a minimal authenticated multi-school foundation on PostgreSQL.
3. Select a supported Heroku generation and corresponding build method using current official documentation.
4. Authenticate hosting access and obtain an explicit service budget.
5. Provision isolated staging resources within the authorized budget.
6. Configure secrets, stable encryption key, database TLS, storage and Redis.
7. Build frontend assets; release migrations; start web/worker/scheduler.
8. Verify health, authentication, private upload, persistence and actual background processing.
9. Complete module acceptance tests and restore a backup into isolation.
10. Deploy production with clean bootstrap; verify owner access.
11. Configure real DNS targets and TLS for the supplied domain.

No buildpack, release command or deployment is currently claimed to be tested.

## Prepared Cedar deployment candidate

The current Procfile targets the classic PHP buildpack path. For a supported Cedar application, use official heroku/nodejs followed by heroku/php. The Node build invokes heroku-postbuild to type-check and compile assets. Do not apply this buildpack configuration blindly to a Fir app; confirm the account's available generation first.

Set APP_ENV=production, APP_DEBUG=false, APP_URL to the exact HTTPS application host, SESSION_SECURE_COOKIE=true, REDIS_CLIENT=predis, Redis sessions/cache/queue, PostgreSQL DATABASE_URL with required TLS, and S3 storage credentials. Preserve APP_KEY across releases. Never put secrets into GitHub source files.

The trusted-host allowlist currently accepts only the configured APP_URL hostname; changing to a custom domain requires updating APP_URL. Multi-domain tenant resolution is not implemented yet. No add-ons have been purchased and no deployed build has been verified.
