param(
    [Parameter(Mandatory = $true)]
    [string] $LoginPayloadPath,
    [string] $DeploymentUrl = 'https://qlnckh-private-staging.vercel.app'
)

$payloadPath = (Resolve-Path -LiteralPath $LoginPayloadPath).Path
$deployment = $DeploymentUrl.TrimEnd('/')

$loginOutput = (& rtk vercel curl '/QLNCKH/index_api.php' --deployment $deployment -- --silent --show-error --request POST --header 'Content-Type: application/json' --data-binary "@$payloadPath" 2>$null) -join "`n"
$loginJson = (($loginOutput -split "`r?`n" | Where-Object { $_ -match '^\s*\{"status"' } | Select-Object -Last 1) | ConvertFrom-Json)
if ($loginJson.status -ne 'success' -or -not $loginJson.token) {
    throw 'Staging smoke login failed.'
}

$authorization = "Authorization: Bearer $($loginJson.token)"
foreach ($detect in @('sidebar_get', 'get_notifications', 'get_users', 'project_get', 'project_trash_get', 'user_project_stats', 'dashboard_stats')) {
    $apiOutput = (& rtk vercel curl "/QLNCKH/index_api.php?detect=$detect" --deployment $deployment -- --silent --show-error --header $authorization 2>$null) -join "`n"
    $apiJson = (($apiOutput -split "`r?`n" | Where-Object { $_ -match '^\s*\{"status"' } | Select-Object -Last 1) | ConvertFrom-Json)

    if ($apiJson.status -ne 'success') {
        Write-Output ("FAIL $detect`: " + $apiJson.message)
        exit 1
    }

    if ($detect -eq 'dashboard_stats' -and ($null -eq $apiJson.data.overview -or $null -eq $apiJson.data.type_stats -or $null -eq $apiJson.data.available_years)) {
        throw 'Dashboard response is missing required statistics.'
    }
}

Write-Output 'PASS: dashboard, sidebar, notifications, users, project list/trash, and user project stats APIs returned successfully.'
