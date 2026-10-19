# Test evidence

| Check | Observed result |
|---|---|
| npm run build | TypeScript and Vite passed |
| php artisan test --filter=TenantContextTest\|HealthTest | 4 passed, 5 assertions |
| PHP syntax | 25 files, zero errors |
| PostgreSQL CI suite | 10 tests passed, 19 assertions, GitHub Actions run 39304069672 |
| Composer validate | Valid, synchronized lock |
| Composer dependency audit during resolution | No known advisories reported |
| npm audit --omit=dev | 0 vulnerabilities |
| Route cache / Blade cache | Compiled |
| HTTP /up | 200 |
| HTTP /login | 200 |
| HTTP /ready | 503, database unavailable |
| Browser/physical device QA | Not performed |
| Heroku/domain deployment | Not performed |

GitHub Actions verified migrations and the implemented enrollment/tenant-isolation contracts against PostgreSQL. These checks do not establish completeness of remaining modules, browser compatibility, or production readiness.

Verified source commit: 4bf1f76ab496712cea6e314b731e06ee42a8dd8f.
Run: https://github.com/khanbadshahraj-ui/school-management-system/actions/runs/39304069672

