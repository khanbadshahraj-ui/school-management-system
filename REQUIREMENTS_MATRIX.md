# Requirements matrix

| Requirement | Status | Evidence |
|---|---|---|
| Authentication and privileged MFA | Login/logout implemented; MFA pending | Login renders; database login verification pending |
| Tenant isolation and role permissions | Initial roles, queries and constraints verified; full permissions editor pending | PostgreSQL isolation and cross-school constraint tests pass in GitHub Actions |
| School branding and academic configuration | Name/colors/region and campus/department foundation; remainder pending | Build passes; teacher settings denial verified; complete configuration coverage pending |
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
| Security and accessibility | Not implemented | None |
| Backup restoration and performance | Not implemented | None |
| Heroku staging and production | Not implemented | None |
| Custom domain and owner handover | Not implemented | None |
