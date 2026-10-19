# Test evidence

## Current demo candidate
Source commit: a4850f8e8e8b37962f85bca710b26cf6b8c64893.
GitHub Actions: https://github.com/khanbadshahraj-ui/school-management-system/actions/runs/39316324172

- PostgreSQL 16 and 18 jobs pass: 22 tests, 50 assertions per job.
- Coverage includes tenant scope/foreign-key enforcement, student enrollment/auditing, teacher denial, inactive accounts, logo upload/durable adapter/isolation/SVG rejection, repeatable owner bootstrap/conflict handling, secure demo configuration and dependency readiness without Redis.
- TypeScript/Vite production build, Composer audit and npm production audit pass.
- Local non-database tests pass: 8 tests, 9 assertions.
- Production Docker build passed, then booted against TLS-enabled PostgreSQL 18 with production HTTPS/proxy/session settings.
- HTTP smoke checks passed for /up, /ready, compiled assets, owner authentication and school-admin role, school branding update, authenticated PNG upload/read and logout.
- After removing/replacing the entire app container, the same authenticated session, school name and logo persisted; bootstrap did not overwrite school data.
- Container job 118278649473 completed successfully; both initial and replacement phases reported success.
- Neon school_demo owner connection verified against PostgreSQL 18.6 using the connector. No school data schema has yet been deployed there.
- Render free-service creation was rejected with HTTP 402 requiring payment information. Service listing remained empty. The dashboard was also tested with Free selected and opened an Add Card dialog; no details were entered and subsequent service listing remained empty. Actual Render URL, provider persistence and sleep/wake verification remain blocked. CI replacement tests are not live-provider tests.
- Browser/physical-device checks, backup restoration and load tests are not performed.

## Historical foundation
GitHub Actions run 39304069672 passed 10 tests / 19 assertions for the earlier foundation. Historical evidence is retained separately from current deployment claims. These checks do not establish completeness of the remaining school modules or real-data readiness.
