# Verified project checkpoint

## Current state
Incomplete school-management foundation converted to a restricted free-demo profile. Render deployment is blocked by an account payment-information requirement; no Render service or live URL was created. No real school data should be used.

## Implemented
Laravel 13.37 / PHP 8.3, React 19 / TypeScript / Inertia, PostgreSQL tenant schema, login/logout, branding/regional settings, campuses/departments, enrollment/list/search, dashboard counts and audits. New: durable bounded school logos, explicit demo/production profiles, database sessions/cache, synchronous demo jobs, idempotent protected owner bootstrap, Docker startup validation and advisory-locked direct-connection migrations, Render Free Blueprint and deployment runbook.

## Verified
PostgreSQL 16 and 18 CI: 22 tests / 50 assertions each, frontend build and dependency audits passed on 6438fe787729d004c38172d44fb201005ff36b54. The exact Docker image builds and passes TLS PostgreSQL 18 startup, compiled assets, owner login/permissions, branding/logo upload, logout and persistence of school settings, logo and the authenticated database session across container replacement. Verified candidate a4850f8e8e8b37962f85bca710b26cf6b8c64893; Actions run 39316324172. See TEST_EVIDENCE.md.

GitHub and Render workspace access are verified. Neon organization is free; Vercel-managed project creation restrictions were handled using an isolated new school-management-demo branch and school_demo database. New branch only: br-purple-water-b71ljlvd, endpoint ep-royal-butterfly-b7ohev2s, max 0.25 CU, transaction pooler enabled. Existing primary branch remains untouched. Owner database connection verified on PostgreSQL 18.6.

## Deployment blocker and next action
On 2026-10-19 UTC, Render create_web_service was called with runtime=docker, plan=free, region=virginia and protected environment settings. It returned HTTP 402: "Payment information is required to complete this request." A subsequent list_services returned no services. No card was added, paid plan selected, or duplicate service created. No live URL exists and the application owner has not yet been bootstrapped on Neon.

The next supported check is the Render dashboard creation path, whose availability without a card remains unverified for this account. The browser tool requires approval before switching from a blocked connector to browser control. Do not enter payment details or change plans. If the dashboard also requires a card, retain this checkpoint and obtain a hosting decision within the $0 constraint. Do not claim deployment success from the CI image tests.

The tested changes are merged via PR #2; merge commit 6aa914c4f0a7f90b5bad7ac26b728982a776a0d6. Protected APP_KEY and initial owner hash were generated privately for deployment, not committed. Retrieve protected database settings through the connected Neon tool when resuming; do not request secrets in chat.

## Remaining scope
MFA, full permission configuration, guardians, academic structure, timetable, attendance, assignments, exams, finance/payroll, library, transport, hostel/welfare, inventory/procurement, reports and integrations remain unfinished. Browser/device acceptance, backup restoration, capacity testing and owner acceptance remain pending. Free demo disables bulk files, outbound messages, live payments, workers and schedules. Heroku is no longer the immediate target.
