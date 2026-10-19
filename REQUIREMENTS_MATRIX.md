# Requirements matrix

| Requirement | Status | Evidence |
|---|---|---|
| Authentication and privileged MFA | Login/logout implemented; MFA pending | Application tests and production-container login/logout pass; privileged MFA remains pending |
| Tenant isolation and role permissions | Initial roles, queries and constraints verified; full permissions editor pending | PostgreSQL isolation and cross-school constraint tests pass in GitHub Actions |
| School branding and academic configuration | Name/colors/region, persistent PNG/JPEG logo and campus/department foundation; remainder pending | Build, logo persistence/isolation, SVG rejection and teacher denial verified in PostgreSQL CI |
| Admissions and enrollment | Not implemented | None |
| Student and guardian records | Initial student enrollment/list/search; guardian relationships pending | Enrollment and audit persistence verified in PostgreSQL CI |
| Timetables and conflict detection | Not implemented | None |
| Student and staff attendance | Not implemented | None |
| Assignments and learning resources | Not implemented | None |
| Examinations and published results | Not implemented | None |
| Fees and double-entry finance | Not implemented | None |
| HR and payroll | Not implemented | None |
| Library | Not implemented | None |
| Transport | Not implemented | None |
| Hostel and welfare | Not implemented | None |
| Procurement inventory and assets | Not implemented | None |
| Communication providers and portals | Not implemented | None |
| Reports and exports | Not implemented | None |
| Security and accessibility | CSRF, throttling, secure deployment guards and initial roles implemented; MFA/accessibility acceptance pending | PostgreSQL and guard tests pass |
| Backup restoration and performance | Not implemented | None |
| Render Free and Neon Free demo | Docker/configuration and replacement tests pass; Render account blocks creation | Neon Free branch/database provisioned; Docker CI passes; Render create returned HTTP 402 |
| Custom domain and owner handover | Not implemented | None |
