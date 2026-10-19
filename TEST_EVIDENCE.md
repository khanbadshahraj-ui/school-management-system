# Test evidence

## Current demo candidate
Source commit: 6438fe787729d004c38172d44fb201005ff36b54.
GitHub Actions: https://github.com/khanbadshahraj-ui/school-management-system/actions/runs/39315836562

- PostgreSQL 16 and 18 jobs pass: 22 tests, 50 assertions per job.
- Coverage includes tenant scope/foreign-key enforcement, student enrollment/auditing, teacher denial, inactive accounts, logo upload/durable adapter/isolation/SVG rejection, repeatable owner bootstrap/conflict handling, secure demo configuration and dependency readiness without Redis.
- TypeScript/Vite production build, Composer audit and npm production audit pass.
- Local non-database tests pass: 8 tests, 9 assertions.
- Production Docker image build/startup and replacement smoke check are still running; no success claim yet.
- Neon school_demo owner connection verified against PostgreSQL 18.6 using the connector. No school data schema has yet been deployed there.
- Actual Render URL, owner login, provider restart/session/logo persistence and sleep/wake checks are pending.
- Browser/physical-device checks, backup restoration and load tests are not performed.

## Historical foundation
GitHub Actions run 39304069672 passed 10 tests / 19 assertions for the earlier foundation. Historical evidence is retained separately from current deployment claims. These checks do not establish completeness of the remaining school modules or real-data readiness.
