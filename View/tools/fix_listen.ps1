# Cherche httpd.conf XAMPP et remplace Listen 127.0.0.1:8000 par Listen 0.0.0.0:8000
# (pour que le telephone sur le Wi-Fi puisse joindre Apache). Sauvegarde .bak avant modification.

$paths = @(
    'C:\xampp\apache\conf\httpd.conf',
    'D:\xampp\apache\conf\httpd.conf',
    "$env:ProgramFiles\xampp\apache\conf\httpd.conf",
    "${env:ProgramFiles(x86)}\xampp\apache\conf\httpd.conf"
)

foreach ($p in $paths) {
    if (-not (Test-Path -LiteralPath $p)) { continue }
    $lines = Get-Content -LiteralPath $p
    $out = @()
    $changed = $false
    foreach ($line in $lines) {
        if ($line -match '^\s*Listen\s+127\.0\.0\.1:8000\s*$') {
            $out += 'Listen 0.0.0.0:8000'
            $changed = $true
        } else {
            $out += $line
        }
    }
    if ($changed) {
        $bak = "$p.bak_econutri_$(Get-Date -Format 'yyyyMMddHHmmss')"
        Copy-Item -LiteralPath $p -Destination $bak -Force
        Set-Content -LiteralPath $p -Value $out -Encoding Default
        Write-Host "OK: modifie $p (sauvegarde: $bak). Redemarrez Apache dans XAMPP."
    } else {
        Write-Host "Rien a changer (deja OK ou pas de Listen 127.0.0.1:8000): $p"
    }
}
