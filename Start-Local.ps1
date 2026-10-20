param([switch]$Stop)
$ErrorActionPreference = 'Stop'
Set-Location -LiteralPath $PSScriptRoot

function New-LocalSecret([int]$Length) {
    $bytes = New-Object byte[] $Length
    $rng = [System.Security.Cryptography.RandomNumberGenerator]::Create()
    try { $rng.GetBytes($bytes) } finally { $rng.Dispose() }
    return [Convert]::ToBase64String($bytes)
}

try {
    if (-not (Get-Command docker -ErrorAction SilentlyContinue)) {
        throw 'Install Docker Desktop from docker.com, enable its WSL 2 Linux-container engine, and start it. Then double-click Run-Local.cmd again.'
    }
    & docker info *> $null
    if ($LASTEXITCODE -ne 0) { throw 'Docker Desktop is not ready. Start it, wait until its engine is running, then retry.' }
    & docker compose version *> $null
    if ($LASTEXITCODE -ne 0) { throw 'Docker Compose is missing. Update Docker Desktop, then retry.' }
    $settingsPath = Join-Path $PSScriptRoot '.env.local.demo'
    if ($Stop) {
        if (Test-Path -LiteralPath $settingsPath) {
            & docker compose --env-file $settingsPath -f compose.local.yaml stop
            if ($LASTEXITCODE -ne 0) { throw 'Could not stop the local containers.' }
        }
        Write-Host 'Local demo stopped. Your database is preserved.'
        exit 0
    }
    if (-not (Test-Path -LiteralPath $settingsPath)) {
        $appKey = 'base64:' + (New-LocalSecret 32)
        $databasePassword = New-LocalSecret 24
        $ownerPassword = New-LocalSecret 24
        @("APP_KEY=$appKey", "LOCAL_DB_PASSWORD=$databasePassword", "DEMO_INITIAL_PASSWORD=$ownerPassword") | Set-Content -LiteralPath $settingsPath -Encoding ASCII
        Write-Host 'Generated private local settings. Keep .env.local.demo; deleting it breaks access to existing data.'
    }
    Write-Host 'Starting the real application and PostgreSQL. The first Docker build can take several minutes.'
    & docker compose --env-file $settingsPath -f compose.local.yaml up -d --build --wait --wait-timeout 180
    if ($LASTEXITCODE -ne 0) { throw 'Startup failed. Keep your settings and data. Check Docker Desktop container logs, then retry.' }
    $settings = @{}
    Get-Content -LiteralPath $settingsPath | ForEach-Object {
        if ($_ -match '^([^=]+)=(.*)$') { $settings[$Matches[1]] = $Matches[2] }
    }
    Write-Host ''
    Write-Host 'Open: http://localhost:8000'
    Write-Host 'Email: owner@example.test'
    Write-Host ('Password: ' + $settings['DEMO_INITIAL_PASSWORD'])
    Write-Host 'Fictional-data demo only. Stop-Local.cmd stops the app without deleting records.'
    Start-Process 'http://localhost:8000'
    Read-Host 'Your sign-in details are above. Press Enter to close this window'
} catch {
    Write-Host $_.Exception.Message -ForegroundColor Red
    exit 1
}
