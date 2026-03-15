@echo off
:: ===============================
:: Portable Apache Service Starter
:: ===============================

:: Service name
set SERVICE_NAME=RosePOSApache

:: Check for silent mode flag
set SILENT_MODE=0
if /I "%~1"=="--silent" set SILENT_MODE=1

:: Detect script folder (trailing backslash removed)
set "SCRIPT_DIR=%~dp0"
if "%SCRIPT_DIR:~-1%"=="\" set "SCRIPT_DIR=%SCRIPT_DIR:~0,-1%"

:: Build paths (forward slashes for Apache config)
set "APACHE_ROOT=%SCRIPT_DIR%\apache24"
set "HTTPD_EXE=%APACHE_ROOT%\bin\httpd.exe"
set "CONF=%APACHE_ROOT%\conf\httpd.conf"
set "SRVROOT=%APACHE_ROOT:\=/%"
set "PHPROOT=%SCRIPT_DIR%\php"
set "PHPROOT=%PHPROOT:\=/%"
set "DOCROOT=%SCRIPT_DIR%\opensourcepos\public"
set "DOCROOT=%DOCROOT:\=/%"

:: Add PHP dir to system PATH so the Apache service can find ICU DLLs (needed by php_intl.dll)
set "PHP_DIR=%SCRIPT_DIR%\php"
echo Ensuring PHP directory is in system PATH...
powershell -NoProfile -ExecutionPolicy Bypass -Command "$phpDir = '%PHP_DIR%'; $machPath = [Environment]::GetEnvironmentVariable('Path','Machine'); if ($machPath -notlike \"*$phpDir*\") { [Environment]::SetEnvironmentVariable('Path', $machPath + ';' + $phpDir, 'Machine'); Write-Host 'Added PHP to system PATH.' } else { Write-Host 'PHP already in system PATH.' }"

:: Check if Apache executable exists
if not exist "%HTTPD_EXE%" (
    echo ERROR: Apache executable not found at "%HTTPD_EXE%"
    if %SILENT_MODE%==0 pause
    exit /b 1
)

:: Update the three Define lines in httpd.conf with current paths
echo Configuring httpd.conf paths...
powershell -NoProfile -ExecutionPolicy Bypass -Command "$c = Get-Content -LiteralPath '%CONF%' -Raw; $c = $c -replace '(?m)^Define SRVROOT .*$', 'Define SRVROOT \"%SRVROOT%\"'; $c = $c -replace '(?m)^Define PHPROOT .*$', 'Define PHPROOT \"%PHPROOT%\"'; $c = $c -replace '(?m)^Define DOCROOT .*$', 'Define DOCROOT \"%DOCROOT%\"'; Set-Content -LiteralPath '%CONF%' -Value $c -NoNewline"
if %ERRORLEVEL% neq 0 (
    echo ERROR: Failed to configure paths in httpd.conf
    if %SILENT_MODE%==0 pause
    exit /b 1
)
echo Paths configured.

:: Remove stale service so a fresh install picks up new paths
sc query "%SERVICE_NAME%" >nul 2>&1
if not %ERRORLEVEL%==1060 (
    echo Removing old service "%SERVICE_NAME%"...
    "%HTTPD_EXE%" -k uninstall -n "%SERVICE_NAME%" >nul 2>&1
    timeout /t 2 /nobreak >nul
)

:: Install the service
echo Installing Apache service "%SERVICE_NAME%"...
"%HTTPD_EXE%" -k install -n "%SERVICE_NAME%"
if %ERRORLEVEL% neq 0 (
    echo Failed to install Apache service
    if %SILENT_MODE%==0 pause
    exit /b 1
)
echo Service installed successfully.

:: Start the service
echo Starting service "%SERVICE_NAME%"...
net start "%SERVICE_NAME%"
if %ERRORLEVEL% neq 0 (
    echo Failed to start service. Check logs or permissions.
    if %SILENT_MODE%==0 pause
    exit /b 1
)
echo Service started successfully.

if %SILENT_MODE%==0 pause
