@echo off
chcp 65001 >nul
setlocal
cd /d "%~dp0"

net session >nul 2>&1
if %errorlevel% neq 0 (
  echo Demande des droits administrateur...
  powershell -NoProfile -Command "Start-Process -FilePath '%~f0' -WorkingDirectory '%~dp0' -Verb RunAs"
  exit /b 0
)

echo.
echo === EcoNutri : ouvrir le reseau pour le QR (telephone) ===
echo.

echo [1/2] Regles pare-feu Windows (ports TCP 8000, 80, 443)...
netsh advfirewall firewall delete rule name="EcoNutri dev TCP 8000" >nul 2>&1
netsh advfirewall firewall delete rule name="EcoNutri dev TCP 80" >nul 2>&1
netsh advfirewall firewall delete rule name="EcoNutri dev TCP 443" >nul 2>&1
netsh advfirewall firewall add rule name="EcoNutri dev TCP 8000" dir=in action=allow protocol=TCP localport=8000 profile=any >nul
netsh advfirewall firewall add rule name="EcoNutri dev TCP 80" dir=in action=allow protocol=TCP localport=80 profile=any >nul
netsh advfirewall firewall add rule name="EcoNutri dev TCP 443" dir=in action=allow protocol=TCP localport=443 profile=any >nul
if %errorlevel% neq 0 (
  echo Erreur netsh. Verifiez que ce fichier a bien ete lance en administrateur.
  pause
  exit /b 1
)
echo OK pare-feu.

echo.
echo [2/2] Apache XAMPP (Listen sur tout le reseau pour le port 8000)...
powershell -NoProfile -ExecutionPolicy Bypass -File "%~dp0fix_listen.ps1"
if %errorlevel% neq 0 (
  echo Attention: script PowerShell en erreur (Apache non modifie ?).
)

echo.
echo Termine. Dans XAMPP : arretez puis redemarrez Apache.
echo Ensuite rechargez la page du blog sur le PC et rescannnez le QR avec le telephone.
echo.
pause
