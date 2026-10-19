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
