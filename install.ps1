param(
    [switch]$IsElevated
)

# 1. Require elevation
if (-not ([Security.Principal.WindowsPrincipal][Security.Principal.WindowsIdentity]::GetCurrent()).IsInRole([Security.Principal.WindowsBuiltInRole]::Administrator)) {
    Write-Host "Elevating privileges..."
    Start-Process powershell -ArgumentList "-NoProfile -ExecutionPolicy Bypass -File `"$PSCommandPath`"" -Verb RunAs
    exit
}

$ScriptDir = Split-Path -Parent $MyInvocation.MyCommand.Definition

# Welcome
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "     Rose POS Windows Installer Script    " -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "1. Ask for installation directory and copy files if needed."
Write-Host "2. Ask to provide SQL database credentials."
Write-Host "3. Test connection to SQL."
Write-Host "4. Create database 'ospos' and import data."
Write-Host "5. Configure apache httpd.conf paths."
Write-Host "6. Update .env with database credentials."
Write-Host "7. Run start-service.bat"
Write-Host ""

# 1. Ask for installation directory
$DefaultDir = "C:\rose-pos"
$TargetDir = Read-Host "Enter installation directory (Default: $DefaultDir)"
if ([string]::IsNullOrWhiteSpace($TargetDir)) {
    $TargetDir = $DefaultDir
}
Write-Host "Installation Directory will be: $TargetDir`n" -ForegroundColor Green

# 2. Ask for MySQL credentials
$dbUsername = Read-Host "Enter MySQL Username (Default: root)"
if ([string]::IsNullOrWhiteSpace($dbUsername)) { $dbUsername = "root" }

$dbPassword = Read-Host "Enter MySQL Password (Default: 123456) [Type 'empty' for no password]"
if ([string]::IsNullOrWhiteSpace($dbPassword)) { $dbPassword = "123456" }
if ($dbPassword -eq "empty" -or $dbPassword -eq '""') { $dbPassword = "" }

$dbPort = Read-Host "Enter MySQL Port (Default: 3306)"
if ([string]::IsNullOrWhiteSpace($dbPort)) { $dbPort = "3306" }

$dbHost = "127.0.0.1"

Write-Host "Checking for MySQL client..."
# Detect MySQL client (mysql.exe)
$mysqlExe = "mysql.exe"
if (!(Get-Command $mysqlExe -ErrorAction SilentlyContinue)) {
    $commonPaths = @(
        "C:\Program Files\MySQL\*\bin",
        "C:\Program Files\MariaDB*\bin",
        "C:\xampp\mysql\bin",
        "C:\wamp64\bin\mysql\*\bin"
    )
    $found = $null
    foreach ($pathRegex in $commonPaths) {
        $found = Get-ChildItem -Path $pathRegex -Filter "mysql.exe" -ErrorAction SilentlyContinue | Select-Object -First 1
        if ($found) { break }
    }
    if ($found) {
        $mysqlExe = $found.FullName
    } else {
        Write-Host "ERROR: MySQL client (mysql.exe) not found!" -ForegroundColor Red
        Write-Host "Please ensure MySQL or MariaDB is installed on this machine and added to your system PATH." -ForegroundColor Yellow
        pause
        exit
    }
}
Write-Host "Found MySQL at: $mysqlExe" -ForegroundColor Green

# 3. Test Connection
while ($true) {
    Write-Host "Testing connection to MySQL at $dbHost on port $dbPort with username $dbUsername..."
    
    $processInfo = New-Object System.Diagnostics.ProcessStartInfo
    $processInfo.FileName = "cmd.exe"
    if ($dbPassword -ne "") {
        $processInfo.Arguments = "/c `"`"$mysqlExe`" -h `"$dbHost`" -P `"$dbPort`" -u `"$dbUsername`" -p`"$dbPassword`" -e `"SELECT 1;`"`""
    } else {
        $processInfo.Arguments = "/c `"`"$mysqlExe`" -h `"$dbHost`" -P `"$dbPort`" -u `"$dbUsername`" -e `"SELECT 1;`"`""
    }
    $processInfo.RedirectStandardError = $true
    $processInfo.RedirectStandardOutput = $true
    $processInfo.UseShellExecute = $false
    $processInfo.CreateNoWindow = $true

    $process = New-Object System.Diagnostics.Process
    $process.StartInfo = $processInfo
    $process.Start() | Out-Null
    $process.WaitForExit()

    if ($process.ExitCode -eq 0) {
        Write-Host "SUCCESS: Database connection established.`n" -ForegroundColor Green
        break
    } else {
        $err = $process.StandardError.ReadToEnd()
        Write-Host "FAILED: Could not connect to the database." -ForegroundColor Red
        Write-Host "Error Output: $err" -ForegroundColor Red
        Write-Host "Please provide correct credentials:`n"
        
        $dbUsername = Read-Host "Enter MySQL Username (Default: root)"
        if ([string]::IsNullOrWhiteSpace($dbUsername)) { $dbUsername = "root" }
        $dbPassword = Read-Host "Enter MySQL Password (Default: 123456) [Type 'empty' for no password]"
        if ([string]::IsNullOrWhiteSpace($dbPassword)) { $dbPassword = "123456" }
        if ($dbPassword -eq "empty") { $dbPassword = "" }
        $dbPort = Read-Host "Enter MySQL Port (Default: 3306)"
        if ([string]::IsNullOrWhiteSpace($dbPort)) { $dbPort = "3306" }
    }
}

# Copy files if the target dir is not the script dir
$ScriptDirTrim = $ScriptDir.TrimEnd('\')
$TargetDirTrim = $TargetDir.TrimEnd('\')

if ($ScriptDirTrim -ne $TargetDirTrim -and $TargetDirTrim -ne "") {
    Write-Host "Copying files from '$ScriptDirTrim' to '$TargetDirTrim'..."
    if (-not (Test-Path $TargetDirTrim)) {
        New-Item -ItemType Directory -Force -Path $TargetDirTrim | Out-Null
    }
    
    # Exclude copying the .git directory if present
    # Using robocopy for robustness with paths and speed
    $roboArgs = "`"$ScriptDirTrim`" `"$TargetDirTrim`" /E /Z /R:1 /W:1 /XD .git"
    Start-Process -FilePath "robocopy" -ArgumentList $roboArgs -NoNewWindow -Wait
    
    Write-Host "Copy complete.`n" -ForegroundColor Green
}

# 4. Create database and import
$dbName = "ospos"
$sqlPath = "$TargetDirTrim\opensourcepos\app\Database\database.sql"

Write-Host "Creating database '$dbName'..."
$createArgs = ""
if ($dbPassword -ne "") { 
    $createArgs = "/c `"`"$mysqlExe`" -h `"$dbHost`" -P `"$dbPort`" -u `"$dbUsername`" -p`"$dbPassword`" -e `"CREATE DATABASE IF NOT EXISTS $dbName;`"`"" 
} else {
    $createArgs = "/c `"`"$mysqlExe`" -h `"$dbHost`" -P `"$dbPort`" -u `"$dbUsername`" -e `"CREATE DATABASE IF NOT EXISTS $dbName;`"`""
}
Start-Process "cmd.exe" -ArgumentList $createArgs -NoNewWindow -Wait

Write-Host "Importing SQL structure into '$dbName'..."
$importArgs = ""
if ($dbPassword -ne "") { 
    $importArgs = "/c `"`"$mysqlExe`" -h `"$dbHost`" -P `"$dbPort`" -u `"$dbUsername`" -p`"$dbPassword`" $dbName < `"$sqlPath`"`"" 
} else {
    $importArgs = "/c `"`"$mysqlExe`" -h `"$dbHost`" -P `"$dbPort`" -u `"$dbUsername`" $dbName < `"$sqlPath`"`""
}
$importProcess = Start-Process "cmd.exe" -ArgumentList $importArgs -NoNewWindow -Wait -PassThru
if ($importProcess.ExitCode -eq 0) {
    Write-Host "SUCCESS: Import complete.`n" -ForegroundColor Green
} else {
    Write-Host "WARNING: Import failed or completed with warnings. The database might already be populated." -ForegroundColor Yellow
}

# 5. Apache / PHP paths
# httpd.conf and php.ini extension_dir are updated by start-service.bat

# 6. Update .env variables in the target directory
$envPath = "$TargetDirTrim\opensourcepos\.env"
Write-Host "Configuring database settings in .env file..."
if (Test-Path $envPath) {
    $envContent = Get-Content -Path $envPath -Raw
    
    # Replace default db config (we target database.default.*)
    $envContent = $envContent -replace '(?m)^database\.default\.username\s*=.*', "database.default.username = '$dbUsername'"
    $envContent = $envContent -replace '(?m)^database\.default\.password\s*=.*', "database.default.password = '$dbPassword'"
    $envContent = $envContent -replace '(?m)^database\.default\.port\s*=.*', "database.default.port = $dbPort"

    # We also do the same for development and tests if needed
    $envContent = $envContent -replace '(?m)^database\.development\.username\s*=.*', "database.development.username = '$dbUsername'"
    $envContent = $envContent -replace '(?m)^database\.development\.password\s*=.*', "database.development.password = '$dbPassword'"
    $envContent = $envContent -replace '(?m)^database\.development\.port\s*=.*', "database.development.port = $dbPort"
    
    Set-Content -Path $envPath -Value $envContent -NoNewline
    Write-Host "SUCCESS: .env modified.`n" -ForegroundColor Green
} else {
    Write-Host "WARNING: .env not found at $envPath" -ForegroundColor Yellow
}

# 7. Run start-service.bat (Apache Configuration and Installation implicitly handled in the target directory)
Write-Host "Executing start-service.bat to configure and install Apache..."
$startSvcBatch = "$TargetDirTrim\start-service.bat"
if (Test-Path $startSvcBatch) {
    # In case there's an original service running, start-service stops and uninstalls it before installing the fresh path
    $proc = Start-Process "cmd.exe" -ArgumentList "/c `"$startSvcBatch`"" -WorkingDirectory $TargetDirTrim -Wait -PassThru
    # NOTE: The script has a `pause` at the end, if the user doesn't press anything inside the child CMD window, we might hang here.
    # We could replace pause with exit in start-service.bat or just run it natively.
    # But start-service.bat has a pause, we should instruct the user to hit a key when it opens.
} else {
    Write-Host "ERROR: start-service.bat not found at $startSvcBatch" -ForegroundColor Red
}

Write-Host "Installation tasks finished!" -ForegroundColor Cyan
Write-Host "Press any key to exit..."
$Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown") | Out-Null
