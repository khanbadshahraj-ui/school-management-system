You are my school management system project team: software architect, school operations analyst, full-stack developer, database engineer, UI/UX designer, QA engineer, and deployment engineer.

Carry out this project entirely through this ChatGPT Work conversation. Do not instruct me to switch to Codex, another AI coding application, or an external development editor.

I authorize ordinary project implementation, reversible project configuration, testing, and preparation for deployment. Obtain any genuinely required account access, paid-service authorization, or production approval through the supported mechanisms.

BUILD THE ACTUAL WORKING APPLICATION. Do not deliver another prompt, a plan-only answer, static mockups, or disconnected screens.

## 1. Verify what this Work session can actually do

Before choosing implementation details, inspect the available:

- Filesystem and code-execution tools.
- Browser and application connectors.
- Repository access.
- Dependency installation and network access.
- Testing facilities.
- Heroku and external-service access.
- Durable project storage and source-control capabilities.

Do not assume this session has a terminal, authenticated accounts, inherited files, unrestricted networking, or permission to deploy.

Use available tools directly. Where a necessary capability is missing, identify the exact limitation and the smallest supported connection or user action that resolves it.

Do not silently replace a working backend with browser localStorage, sample data, or a simulated database.

Distinguish:
1. Building through ChatGPT Work.
2. Hosting the finished application.
3. The infrastructure supporting its database, files, jobs, and integrations.

All project management and engineering interaction should remain in this Work conversation. External hosting and services are permitted and expected.

## 2. Hosting and stack decision

The intended production architecture is:

- Supported stable Laravel with compatible PHP.
- React, TypeScript, Inertia.js, and Vite.
- Tailwind CSS and accessible reusable components.
- PostgreSQL.
- Redis for shared cache, locks, and queues.
- Private S3-compatible object storage.
- Heroku web and worker processes.
- A supported scheduler.
- A custom domain after the application works on its initial hosting URL.

Verify current official compatibility and hosting support before locking versions.

Use a modular monolith, with frontend and backend served from one origin initially. Pin dependencies and preserve lockfiles.

Do not assume ChatGPT Sites can host Laravel or substitute its storage and runtime for this architecture. Check current Sites capabilities and permitted uses before using it for any optional preview.

The full school application must follow a hosting path suitable for its users, data, background services, and integrations.

If the preferred stack cannot be built or deployed using the actual Work tools, explain the specific incompatibility and recommend one concrete alternative with its consequences. Obtain my decision before materially changing the architecture.

Do not spend time building an incompatible foundation.

## 3. Product objective

Create a complete, white-label, configurable school management system that can serve:

- One independent school.
- Multiple campuses of one school.
- Multiple independent schools with isolated data.

Each school must be able to use its own name, monogram/logo, colors, contact information, academic structure, document templates, and approved domain.

Rebranding and routine school configuration must not require source-code changes.

Use a neutral temporary project name and fictional demonstration data until I provide real branding.

## 4. Deployment-first milestones

Design backward from the production environment.

### Milestone 1: verified foundation

Implement:

- Application skeleton and reproducible build.
- PostgreSQL persistence.
- Authentication and role permissions.
- School and campus ownership.
- Tenant isolation.
- Branding configuration.
- Private file storage.
- Background-job execution.
- Health checks.
- Deployment configuration.

Deploy the foundation to a separate staging Heroku application as early as tools, access, and budget permit.

Verify persistence after restart, sign-in, private uploads, compiled assets, and a real queued job.

### Milestone 2: school operations

Implement admissions, students, guardians, academic structure, timetables, attendance, fees, and initial portals.

### Milestone 3: academic delivery

Implement assignments, learning resources, examinations, grading, report cards, communication, and reporting.

### Milestone 4: supporting operations

Implement HR/payroll, library, transport, hostel, welfare, procurement, inventory, and assets.

### Milestone 5: production readiness

Complete integrated testing, security checks, performance measurements, backup restoration, and operational documentation.

### Milestone 6: production and owner handover

Deploy production, configure the approved domain, verify permissions, demonstrate workflows, and deliver the project.

These milestones organize work; they do not reduce final scope.

Continue automatically through routine milestones. Request a decision only when a genuine dependency, permission boundary, or material business choice requires it.

## 5. Configurability and access control

Provide administration screens for:

- School identity, logo, favicon, colors, addresses, and contact details.
- Campuses, departments, classes, sections, subjects, rooms, and houses.
- Academic years, terms, holidays, and working days.
- Grading scales, assessment weights, pass criteria, and result templates.
- Fee structures, concessions, fines, currencies, and numbering sequences.
- Attendance and leave policies.
- English and Urdu/RTL presentation.
- Notification and document templates.
- Validated custom fields.
- Module enablement and approved domains.

Administrators must add staff, departments, classes, sections, and subjects without code or database edits.

Provide configurable roles for platform administrators, school administrators, principals, campus administrators, admissions/reception, teachers, accountants, HR, librarians, transport/hostel staff, students, and guardians.

Enforce authorization on the server.

Tenant isolation must cover database queries, validation, relationships, jobs, files, caches, reports, exports, and audit records.

Guardians must access only children with verified relationships. Restrict sensitive welfare, health, payroll, and financial information.

Test cross-school access attempts automatically.

## 6. Complete functional scope

Implement these as connected workflows with persistent records, validation, permissions, search, filters, pagination, and useful error states.

### Admissions and students

Inquiries, applications, document review, admission decisions, enrollment, admission numbers, student profiles, guardians, siblings, emergency contacts, class allocation, transfers, withdrawals, graduation, ID cards, certificates, and validated CSV imports.

### Academic administration

Academic years, terms, curriculum and subject mapping, teacher assignments, rooms, timetable conflict detection, substitutions, calendar events, and year-end promotion with preserved history.

### Attendance

Daily and period attendance, staff attendance, late arrivals, absence alerts, corrections with reasons, leave approvals, and reporting.

Provide an integration boundary for attendance devices without claiming physical hardware is connected.

### Teaching and learning

Lessons, learning resources, homework, assignments, deadlines, submissions, feedback, grading, and class announcements.

### Examinations and results

Exam schedules, configurable assessment components, marks entry, moderation, approval, publication, grading rules, report cards, transcripts, analysis, locked marks, and audited corrections.

### Fees and finance

Fee structures, invoices, installments, concessions, scholarships, fines, partial payments, receipts, arrears, refunds, expenses, budgets, reconciliation, and a double-entry ledger for implemented transactions.

Use exact decimal calculations, atomic posting, unique receipt numbers, duplicate prevention, and reversal entries.

Support cash, bank transfer, and manual verification.

Online payment integration must use a configured provider with verified webhook signatures and idempotency. Until credentials exist, clearly distinguish a simulator from an operational gateway.

### HR and payroll

Staff profiles, employment records, leave, payroll periods, allowances, deductions, approval, payslips, and payment status.

Make jurisdiction-specific rules configurable rather than inventing statutory calculations.

### Library

Titles, copies, accession numbers/barcodes, issue, return, renewal, reservation, overdue reporting, and fines.

### Transport

Vehicles, drivers, routes, stops, capacity, allocations, fees, and maintenance.

Live tracking must require a real authorized integration.

### Hostel and welfare

Rooms, beds, allocations, capacity, fees, visitors, incidents, clinic visits, and restricted welfare records.

### Inventory, procurement, and assets

Suppliers, purchase requests, approvals, purchase orders, goods receipts, stock movements, asset allocation, maintenance, disposal, and appropriate financial integration.

### Portals and communication

Role-specific dashboards, parent/student access, announcements, events, meeting requests, in-app notifications, and configurable email/SMS/WhatsApp providers.

Test external communication using controlled destinations.

### Reports and administration

Academic, attendance, enrollment, financial, payroll, library, transport, hostel, and inventory reports.

Include appropriate school/campus/year/class/date filters, authorized PDF/CSV exports, background processing for large reports, audit search, and operational status.

## 7. Responsive interface

Build a modern, clear, accessible interface for:

- Windows laptops and desktops.
- macOS computers.
- iPhones using Safari.
- Android phones using Chrome.
- Tablets.

Adapt layouts to screen size while retaining equivalent workflow access.

Include touch-friendly controls, keyboard navigation, visible focus, accessible forms, mobile-friendly data views, loading states, empty states, useful errors, and print layouts.

Target WCAG 2.2 AA and document known gaps.

Do not claim physical-device testing when only browser emulation was performed.

PWA installation is optional. Do not cache sensitive records or authenticated responses offline by default.

## 8. Data integrity, security, and scaling

Implement:

- Secure authentication and session handling.
- Privileged-user MFA.
- CSRF protection and login throttling.
- Server-side validation and authorization.
- Private uploads and signed downloads.
- Safe file validation and export handling.
- Audit records for sensitive actions.
- Database constraints, indexes, transactions, and concurrency controls.
- Pagination and efficient queries.
- Idempotent background jobs and webhooks.
- Retry and failed-job handling.
- Shared sessions and stateless web processes.
- Secret and dependency scanning.
- Configurable retention and controlled deletion.

Do not expose secrets or unnecessary personal information in logs.

Run reproducible load tests and report measured capacity, resources, dataset size, concurrency, and bottlenecks. Do not promise unlimited scalability.

## 9. Heroku deployment and domain

Prepare and verify:

- Supported runtime and build configuration.
- Production frontend build.
- Web and worker processes.
- Scheduler with overlap prevention.
- Release-phase migrations.
- Environment validation.
- Liveness/readiness checks.
- Standard-output/error logs.
- Trusted proxies, allowed hosts, secure cookies, and HTTPS.
- Secure database connectivity and connection limits.

Use managed PostgreSQL, managed Redis, and external durable object storage. Never keep permanent uploads on an ephemeral application filesystem.

Generate the encryption key once and preserve it.

Separate staging and production secrets, databases, storage, and data.

Use non-destructive migrations where feasible. Document database implications of rollback.

Bootstrap the owner securely. Never deploy public demo credentials or demonstration students to production.

A custom domain connects to the deployed application. Configure actual DNS targets, TLS, canonical URLs, relevant callbacks, and redirects.

Verify DNS, HTTPS, login, assets, private files, and generated links.

Do not use an unverified domain to resolve a tenant.

## 10. Guide me through required access

Maintain a concise access checklist covering:

- Repository.
- Heroku account and application.
- Database.
- Redis.
- Object storage.
- Email and payment providers.
- Domain registrar/DNS.
- Application owner and staff roles.

Ask for the minimum necessary access through supported authentication.

When I must act, provide one clear step at a time, explain why, and verify completion before proceeding.

Do not request passwords, recovery codes, or secrets in ordinary chat.

Do not purchase services, change paid plans, or incur unspecified charges without authorization. Prepare the concrete deployment before requesting a budget decision.

Permissions must be verified; never claim they were granted automatically.

## 11. Testing, acceptance, and delivery

Use PostgreSQL-backed integration tests and browser tests where available.

Verify:

- Authentication, permissions, and tenant isolation.
- School branding and configuration.
- Admission through enrollment.
- Timetable conflicts and attendance.
- Assignment submission and feedback.
- Marks through approved publication.
- Invoice through payment, receipt, reconciliation, and reversal.
- Payroll, library, transport, hostel, and inventory workflows.
- Year-end promotion.
- Private storage, jobs, scheduler, and restart persistence.
- Production smoke tests.
- Backup restoration into an isolated environment.

Run type checks, appropriate linting, backend tests, production builds, dependency checks, and available browser tests.

Fix failures. Record untested areas honestly.

Deliver:

- Complete source and dependency lockfiles.
- Migrations and separate demo seeders.
- Environment templates without secrets.
- CI and deployment automation.
- Local startup instructions/scripts.
- Architecture and data-model documentation.
- School onboarding and administrator guides.
- Backup, restore, rollback, and hosting-migration runbooks.
- Requirements matrix and test evidence.
- Verified deployment URLs.
- Service costs, maintenance responsibilities, and remaining limitations.

Before declaring completion, verify that I can sign in, hold the intended owner permissions, configure a school, and complete the main workflows.

Give me a short practical acceptance checklist. Do not declare acceptance on my behalf.

## 12. Continuity and progress

Persist project status, requirements, architecture, deployment instructions, access status, and operational guidance in the project repository or supported durable project storage.

Do not rely solely on conversation memory or temporary files.

If interrupted by session or context limits, save the precise verified state, blockers, and next executable action.

Keep progress updates concise. Explain outcomes and necessary decisions in plain language.

Start now by checking this ChatGPT Work session’s actual capabilities and establishing the deployable foundation. Proceed with implementation rather than returning another prompt.