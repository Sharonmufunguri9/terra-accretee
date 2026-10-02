$projectRoot = $PSScriptRoot
$phpExe = 'C:\Users\BridgetLaptop\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.4_Microsoft.Winget.Source_8wekyb3d8bbwe\php.exe'
$mailpitExe = 'C:\Users\BridgetLaptop\AppData\Local\Microsoft\WinGet\Packages\axllent.mailpit_Microsoft.Winget.Source_8wekyb3d8bbwe\mailpit.exe'

$phpIni = Join-Path $projectRoot 'php.ini'

Start-Process -FilePath $mailpitExe -ArgumentList '--listen 127.0.0.1:8025 --smtp 127.0.0.1:1025' -WindowStyle Hidden
Start-Process -FilePath $phpExe -ArgumentList '-c', $phpIni, '-S', 'localhost:8000' -WorkingDirectory $projectRoot -WindowStyle Hidden

Write-Host 'Site is running at http://localhost:8000'
Write-Host 'Mailpit is running at http://localhost:8025'
