"""Create CI-only settings. Never use these credentials for a deployed service."""
import base64
import subprocess
from pathlib import Path

result = subprocess.run(["docker", "run", "--rm", "--entrypoint", "php", "school-demo:ci", "-r", "echo password_hash('CI-only-owner-password', PASSWORD_BCRYPT);"], check=True, capture_output=True, text=True)
settings = {
    "APP_ENV": "production", "APP_DEBUG": "false", "APP_URL": "https://school.example.test",
    "APP_KEY": "base64:" + base64.b64encode(b"c" * 32).decode(),
    "DEPLOYMENT_PROFILE": "demo", "DEMO_MODE": "true", "DB_CONNECTION": "pgsql", "DB_SSLMODE": "require",
    "DATABASE_URL": "postgresql://school:ci-only-password@postgres/school_demo?sslmode=require",
    "MIGRATION_DATABASE_URL": "postgresql://school:ci-only-password@postgres/school_demo?sslmode=require",
    "SESSION_DRIVER": "database", "SESSION_SECURE_COOKIE": "true", "SESSION_ENCRYPT": "true",
    "CACHE_STORE": "database", "QUEUE_CONNECTION": "sync", "MAIL_MAILER": "array",
    "LOG_CHANNEL": "stderr", "BULK_UPLOADS_ENABLED": "false", "EXTERNAL_MESSAGES_ENABLED": "false", "LIVE_PAYMENTS_ENABLED": "false",
    "DEMO_BOOTSTRAP_ENABLED": "true", "DEMO_OWNER_EMAIL": "owner@example.test", "DEMO_OWNER_PASSWORD_HASH": result.stdout,
    "DEMO_SEED": "true", "PORT": "10000",
}
Path("/tmp/school-ci.env").write_text("".join(f"{key}={value}\n" for key, value in settings.items()))
