param(
    [Parameter(Mandatory = $true)]
    [string] $NeonDatabaseUrl,
    [string] $MariaDbUrl = 'mysql://root@host.docker.internal:3306/db_qlnckh'
)

$ErrorActionPreference = 'Stop'
$root = Split-Path -Parent $PSScriptRoot
$dump = Join-Path $root 'db_qlnckh.sql'

if (-not (Test-Path -LiteralPath $dump)) {
    throw 'Missing restricted migration input db_qlnckh.sql. Obtain it from the dev team; do not put it in Git.'
}

docker info *> $null
if ($LASTEXITCODE -ne 0) {
    throw 'Docker Desktop must be running. The script uses the pgloader container and does not upload the dump to Vercel.'
}

# The dump must first be imported into the local MariaDB instance. pgloader then
# streams schema and data directly into Neon, preserving explicit primary-key IDs.
docker run --rm dimitri/pgloader:latest pgloader $MariaDbUrl $NeonDatabaseUrl
if ($LASTEXITCODE -ne 0) { throw 'pgloader migration failed.' }

if (-not (Get-Command psql -ErrorAction SilentlyContinue)) {
    throw 'Install PostgreSQL client tools (psql), then re-run this script to apply the post-import migration.'
}

Get-Content -Raw (Join-Path $root 'database\postgres\after-pgloader.sql') |
    & psql --dbname=$NeonDatabaseUrl --set ON_ERROR_STOP=on
if ($LASTEXITCODE -ne 0) { throw 'Post-import migration failed.' }

Write-Host 'Migration completed. Run database/postgres/verify-import.sql against MariaDB and Neon before deploying.'
