# Crea .env a partir de .env.example con secretos aleatorios. No pisa un .env existente.
$root = Split-Path -Parent $PSScriptRoot
$target = Join-Path $root '.env'
if (Test-Path $target) { Write-Output '.env ya existe: no se modifica.'; exit 0 }

$rng = [System.Security.Cryptography.RandomNumberGenerator]::Create()
function New-RandomBytes([int]$count) {
    $bytes = New-Object byte[] $count
    $rng.GetBytes($bytes)
    return ,$bytes
}
function New-HexSecret([int]$count) {
    $bytes = New-RandomBytes $count
    return -join ($bytes | ForEach-Object { $_.ToString('x2') })
}

$values = @{
    APP_KEY             = 'base64:' + [Convert]::ToBase64String((New-RandomBytes 32))
    JWT_SECRET          = New-HexSecret 32
    DB_PASSWORD         = New-HexSecret 16
    MYSQL_ROOT_PASSWORD = New-HexSecret 16
    SEED_ADMIN_PASSWORD = New-HexSecret 8
}
$utf8 = New-Object Text.UTF8Encoding $false
$lines = [IO.File]::ReadAllLines((Join-Path $root '.env.example'), $utf8) | ForEach-Object {
    if ($_ -match '^([A-Z_]+)=$' -and $values.ContainsKey($Matches[1])) {
        $Matches[1] + '=' + $values[$Matches[1]]
    } else { $_ }
}
[IO.File]::WriteAllText($target, (($lines -join "`n") + "`n"), $utf8)
Write-Output '.env creado con secretos aleatorios.'
