# Verified project checkpoint

## Current state
Incomplete school-management foundation converted to a restricted free-demo profile. Render deployment is not yet verified. No real school data should be used.

## Implemented
Laravel 13.37 / PHP 8.3, React 19 / TypeScript / Inertia, PostgreSQL tenant schema, login/logout, branding/regional settings, campuses/departments, enrollment/list/search, dashboard counts and audits. New: durable bounded school logos, explicit demo/production profiles, database sessions/cache, synchronous demo jobs, idempotent protected owner bootstrap, Docker startup validation and advisory-locked direct-connection migrations, Render Free Blueprint and deployment runbook.

## Verified
PostgreSQL 16 and 18 CI: 22 tests / 50 assertions each, frontend build and dependency audits passed on 6438fe787729d004c38172d44fb201005ff36b54. Container build/smoke still running. See TEST_EVIDENCE.md for exact run.

GitHub and Render workspace access are verified. Neon organization is free; Vercel-managed project creation restrictions were handled using an isolated new school-management-demo branch and school_demo database. New branch only: br-purple-water-b71ljlvd, endpoint ep-royal-butterfly-b7ohev2s, max 0.25 CU, transaction pooler enabled. Existing primary branch remains untouched. Owner database connection verified on PostgreSQL 18.6.

## Next executable action
Finish Docker CI including startup, owner login and replacement persistence. Provision one Render service explicitly on free using protected settings; verify the returned HTTPS URL, app/database persistence and ownership. Record real observations here. Never create duplicate services or paid resources.

## Remaining scope
MFA, full permission configuration, guardians, academic structure, timetable, attendance, assignments, exams, finance/payroll, library, transport, hostel/welfare, inventory/procurement, reports and integrations remain unfinished. Browser/device acceptance, backup restoration, capacity testing and owner acceptance remain pending. Free demo disables bulk files, outbound messages, live payments, workers and schedules. Heroku is no longer the immediate target.
