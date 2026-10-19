# Render Free and Neon Free deployment

## Runtime contract

Deploy the repository Dockerfile as one Render web service with plan `free`, no disk and no worker. Use a Neon organization confirmed to be on Free. Do not create Render PostgreSQL, which is unnecessary for this design. No keepalive requests or scheduler are provided. Cold starts and monthly free limits apply; no uptime guarantee is made.

Use `.env.demo.example` as the settings checklist. Production environment remains `APP_ENV=production` and `APP_DEBUG=false`, even for this fictional-data demo. `DEPLOYMENT_PROFILE=demo` and `DEMO_MODE=true` select the restricted demo behavior. APP_URL must be the actual HTTPS Render URL. APP_KEY is generated once and retained through every restart and deployment.

## Connections and secrets

Set DATABASE_URL to Neon's pooled connection and MIGRATION_DATABASE_URL to the corresponding direct endpoint, both with `sslmode=require`. Both must name the same branch/endpoint/database. Never place URLs, keys or owner passwords in Git or logs. PostgreSQL certificate verification can be strengthened with verify-full and the provider trust chain. The runtime uses nonpersistent, emulated prepared statements for transaction pooling; startup migration locks use a direct connection.

Set SESSION_DRIVER=database, CACHE_STORE=database, QUEUE_CONNECTION=sync, SESSION_SECURE_COOKIE=true and SESSION_ENCRYPT=true. MAIL_MAILER=array cannot send email. Bulk uploads, external messages and live payments must remain false. This profile does not implement unattended scheduled jobs or asynchronous long reports.

## First deployment

1. Verify tests and image checks on the candidate commit. Connect GitHub, Render and Neon through supported account access.
2. Provision an isolated Neon database on Free. A Vercel-managed organization can restrict project creation and endpoint configuration; use only supported operations and do not change unrelated resources.
3. Generate a stable random APP_KEY and a unique strong owner password in a protected execution environment. Hash the owner password with the application's password hasher. Set DEMO_OWNER_EMAIL, DEMO_OWNER_PASSWORD_HASH, DEMO_BOOTSTRAP_ENABLED=true and optionally DEMO_SEED=true in protected Render environment settings. No default production credentials exist.
4. Create the Render Docker service explicitly on `free`. Set the actual APP_URL and all profile variables before a successful boot. The image listens on Render's PORT. HTTP from Render's TLS proxy is trusted; hosts are restricted to APP_URL.
5. Startup validates the profile, retries the direct database connection/lock with a bound, applies normal forward migrations while holding a PostgreSQL advisory lock, and bootstraps the owner once. It then compiles Laravel configuration/routes/views and starts Apache. No paid pre-deploy command, shell or one-off task is required. Migration failures prevent serving.
6. Confirm /up, /ready, login, compiled assets, school settings, enrollment and logo. Replace/redeploy the container and verify data and sessions persist. Existing bootstrap users, passwords and school records must remain unchanged. Disable bootstrap after handover; owner credentials must be delivered through a private supported mechanism.

The current schema migrations are additive. Future deployments must remain compatible with both the old and new web instance during rollout. Large backfills belong in a separately planned migration, not unbounded container startup.

## Files and demonstration boundaries

PNG/JPEG logos are limited to 128 KiB and 16–1024 pixels in each dimension. A tenant-keyed database record persists them through container replacement; downloads require an active signed-in school account. SVG is rejected. This small-logo adapter is not a document store. No student documents or permanent files may be saved to Render's ephemeral filesystem.

## Backup, rollback and future hosting

Before meaningful schema changes, take an authorized encrypted PostgreSQL export to owner-controlled durable storage and verify restoration into an isolated database. Free-provider restore retention is limited and is not an independent backup. Do not store the only backup on the web container. Backup/restore evidence is still required before real data.

A Render application rollback does not roll back PostgreSQL migrations. Prefer forward fixes; never run migrate:fresh, destructive rollback or restore over existing data without explicit authorization and a reviewed backup. Retain APP_KEY when moving the database and app together, otherwise encrypted data/sessions may become unreadable.

To move later: build the same Dockerfile on the new host, configure HTTPS/proxies/allowed hosts, migrate a verified database backup, preserve keys, provide durable storage for enabled files, then change APP_URL and DNS. Domain purchase and DNS changes are outside the free initial demo. Domain mapping does not replace the hosting service. The stricter production profile requires Redis queues/sessions, durable object storage for enabled bulk uploads, and full operational/security acceptance; it must not be enabled by merely changing a label.

## Acceptance

The owner must personally confirm sign-in and school-admin permissions. Verify branding, campus creation, enrollment, persistence after replacement, logo isolation, teacher denial, secure cookies and logout. Record observed URLs, commit, test runs, provider plans and remaining limitations in PROJECT_STATUS.md. Physical iPhone/Android/macOS/Windows testing and real-data readiness must not be claimed from CI alone.

## Observed account blocker

The tested Docker candidate passed CI run 39316324172. An explicit Render Free Docker creation request on 2026-10-19 UTC returned HTTP 402 requiring payment information. No Render service was created. Dashboard creation without a card is not yet verified for this account. Do not add a card, select a paid plan or describe the app as deployed. The prepared source and free Neon branch/database remain available for continuation.
