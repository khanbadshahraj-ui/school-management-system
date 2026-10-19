# Operational runbook — design pending implementation

Backups must include PostgreSQL and a documented object-storage preservation strategy. Restore into an isolated environment and validate tenant ownership, record counts, financial reconciliation and private-file access before calling backups verified.

Application rollback must consider database compatibility: reverting code does not reverse schema or data changes. Stop or drain incompatible jobs during a cutover. Keep APP_KEY stable. Keep staging/test messages directed at controlled destinations.

Performance, error rate, queue delay, failed jobs, database connections, storage and backup failures require monitoring. Exact commands will be recorded after implementation and verification.
