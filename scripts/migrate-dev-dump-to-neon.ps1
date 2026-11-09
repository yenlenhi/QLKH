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

& docker version --format '{{.Server.Version}}' 2>$null | Out-Null
if ($LASTEXITCODE -ne 0) {
    throw 'Docker Desktop must be running. The script uses the pgloader container and does not upload the dump to Vercel.'
}

# The dump must first be imported into the local MariaDB instance. pgloader v4
# streams schema and data directly into Neon, preserving explicit primary-key IDs.
# Its JDBC driver supports Neon's endpoint/SNI option, unlike pgloader v3.
$mariaDbUri = [Uri] $MariaDbUrl
$neonUri = [Uri] $NeonDatabaseUrl
$neonCredentialSeparator = $neonUri.UserInfo.IndexOf(':')
if ($neonCredentialSeparator -lt 1) {
    throw 'NeonDatabaseUrl must include a username and password.'
}

$neonUser = [Uri]::UnescapeDataString($neonUri.UserInfo.Substring(0, $neonCredentialSeparator))
$neonPassword = [Uri]::UnescapeDataString($neonUri.UserInfo.Substring($neonCredentialSeparator + 1))
$neonPort = if ($neonUri.IsDefaultPort) { '' } else { ":$($neonUri.Port)" }
$neonQuery = $neonUri.Query.TrimStart('?')
$neonEndpointId = $neonUri.Host.Split('.')[0]
$neonJdbcUrl = "jdbc:postgresql://$($neonUri.Host)$neonPort$($neonUri.AbsolutePath)?$neonQuery&user=$([Uri]::EscapeDataString($neonUser))&password=$([Uri]::EscapeDataString($neonPassword))&options=endpoint%3D$neonEndpointId"

$mariaUser = if ($mariaDbUri.UserInfo) { [Uri]::UnescapeDataString($mariaDbUri.UserInfo) } else { 'root' }
$mariaPort = if ($mariaDbUri.IsDefaultPort) { '' } else { ":$($mariaDbUri.Port)" }
$mariaJdbcUrl = "jdbc:mysql://$($mariaDbUri.Host)$mariaPort$($mariaDbUri.AbsolutePath)?user=$([Uri]::EscapeDataString($mariaUser))&useSSL=false&allowPublicKeyRetrieval=true"

docker run --rm ghcr.io/dimitri/pgloader-v4:latest --cast 'column tbl_scientific_curriculum_vitae.talent_category to smallint drop typemod' $mariaJdbcUrl $neonJdbcUrl
if ($LASTEXITCODE -ne 0) { throw 'pgloader migration failed.' }

$postImportSql = Get-Content -Raw (Join-Path $root 'database\postgres\after-pgloader.sql')

if (Get-Command psql -ErrorAction SilentlyContinue) {
    $postImportSql | & psql --dbname=$NeonDatabaseUrl --set ON_ERROR_STOP=on
} else {
    # Docker is already required for pgloader, so use its official Postgres image
    # as a client rather than requiring a local PostgreSQL installation.
    $postImportSql | docker run --rm -i postgres:16-alpine psql --dbname=$NeonDatabaseUrl --set ON_ERROR_STOP=on
}
if ($LASTEXITCODE -ne 0) { throw 'Post-import migration failed.' }

Write-Host 'Migration completed. Run database/postgres/verify-import.sql against MariaDB and Neon before deploying.'
