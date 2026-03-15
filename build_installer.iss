[Setup]
AppName=Rose POS
AppVersion=1.0
DefaultDirName=C:\RosePOS
DefaultGroupName=Rose POS
OutputDir=.
OutputBaseFilename=RosePOS_Installer
Compression=lzma2
SolidCompression=yes
PrivilegesRequired=admin
SetupIconFile=compiler:SetupClassicIcon.ico

[Files]
; Bundle the entire project exactly as it is (excluding the installer script itself)
Source: "*"; DestDir: "{app}"; Excludes: "install.bat,install.ps1,build_installer.iss,RosePOS_Installer.exe"; Flags: ignoreversion recursesubdirs createallsubdirs

[Run]
; 1. Create the Database
Filename: "{code:GetMySQLPath}"; Parameters: "-h 127.0.0.1 -P {code:GetSQLPort} -u {code:GetSQLUser} {code:GetSQLPassParam} -e ""CREATE DATABASE IF NOT EXISTS ospos;"""; Flags: runhidden waituntilterminated; StatusMsg: "Creating Database..."

; 2. Import the Database SQL
Filename: "{cmd}"; Parameters: "/c """"{code:GetMySQLPath}"" -h 127.0.0.1 -P {code:GetSQLPort} -u {code:GetSQLUser} {code:GetSQLPassParam} ospos < ""{app}\opensourcepos\app\Database\database.sql"""""; Flags: runhidden waituntilterminated; StatusMsg: "Importing Database Tables..."

; 3. Run the Apache Service Starter (redirect output to log file)
Filename: "{cmd}"; Parameters: "/c """"{app}\start-service.bat"" --silent > ""{app}\apache_install.log"" 2>&1"""; WorkingDir: "{app}"; Flags: runhidden waituntilterminated; StatusMsg: "Installing & Starting Apache Service..."

[Code]
var
  SQLPage: TInputQueryWizardPage;
  MySQLPath: string;

procedure InitializeWizard;
begin
  { Create SQL Credentials GUI Page }
  SQLPage := CreateInputQueryPage(wpSelectDir,
    'Database Configuration', 'Provide MySQL credentials to initialize the database.',
    'Please specify your MySQL username, password, and port. The installer will test the connection before continuing.');

  SQLPage.Add('Username:', False);
  SQLPage.Add('Password:', True);
  SQLPage.Add('Port:', False);

  { Default values }
  SQLPage.Values[0] := 'root';
  SQLPage.Values[1] := '123456';
  SQLPage.Values[2] := '3306';
end;

function FindMySQL: boolean;
var
  CommonPaths: array of string;
  I: Integer;
begin
  { Native paths or environment variables should usually resolve this, but we can hardcode scanning for mysql.exe }
  CommonPaths := ['C:\Program Files\MySQL\MySQL Server 8.0\bin\mysql.exe',
                  'C:\Program Files\MySQL\MySQL Server 5.7\bin\mysql.exe',
                  'C:\Program Files\MariaDB 11.0\bin\mysql.exe',
                  'C:\Program Files\MariaDB 10.11\bin\mysql.exe',
                  'C:\xampp\mysql\bin\mysql.exe',
                  'C:\wamp64\bin\mysql\mysql5.7.36\bin\mysql.exe'];
                  
  MySQLPath := 'mysql.exe'; { assume path variable works }
  Result := True;
end;

function TestSQLConnection(User, Pass, Port: string): boolean;
var
  ResultCode: Integer;
  ParamStr: string;
begin
  if Pass <> '' then
    ParamStr := '-h 127.0.0.1 -P ' + Port + ' -u ' + User + ' -p"' + Pass + '" -e "SELECT 1;"'
  else
    ParamStr := '-h 127.0.0.1 -P ' + Port + ' -u ' + User + ' -e "SELECT 1;"';

  { Execute mysql.exe via cmd to test connection }
  if Exec('cmd.exe', '/c "mysql.exe ' + ParamStr + '"', '', SW_HIDE, ewWaitUntilTerminated, ResultCode) then
  begin
    if ResultCode = 0 then
      Result := True
    else
      Result := False;
  end else begin
    Result := False;
  end;
end;

function NextButtonClick(CurPageID: Integer): Boolean;
begin
  Result := True;
  
  if CurPageID = SQLPage.ID then
  begin
    FindMySQL();
    
    if not TestSQLConnection(SQLPage.Values[0], SQLPage.Values[1], SQLPage.Values[2]) then
    begin
      MsgBox('Failed to connect to MySQL.' + #13#10 + #13#10 + 
             'Please verify your Username, Password, and Port are correct.' + #13#10 + 
             'Also ensure MySQL or MariaDB is installed and added to your System PATH.', mbError, MB_OK);
      Result := False;
    end;
  end;
end;

function GetSQLUser(Param: string): string;
begin
  Result := SQLPage.Values[0];
end;

function GetSQLPassParam(Param: string): string;
begin
  if SQLPage.Values[1] <> '' then
    Result := '-p"' + SQLPage.Values[1] + '"'
  else
    Result := '';
end;

function GetSQLPort(Param: string): string;
begin
  Result := SQLPage.Values[2];
end;

function GetMySQLPath(Param: string): string;
begin
  Result := 'mysql.exe';
end;

procedure CurStepChanged(CurStep: TSetupStep);
var
  EnvPath: string;
  EnvLines: TArrayOfString;
  I: Integer;
begin
  if CurStep = ssPostInstall then
  begin
    { Update the .env file with the specified database credentials }
    EnvPath := ExpandConstant('{app}\opensourcepos\.env');
    if LoadStringsFromFile(EnvPath, EnvLines) then
    begin
      for I := 0 to GetArrayLength(EnvLines) - 1 do
      begin
        if Pos('database.default.username', EnvLines[I]) = 1 then
          EnvLines[I] := 'database.default.username = ''' + SQLPage.Values[0] + '''';
        if Pos('database.default.password', EnvLines[I]) = 1 then
          EnvLines[I] := 'database.default.password = ''' + SQLPage.Values[1] + '''';
        if Pos('database.default.port', EnvLines[I]) = 1 then
          EnvLines[I] := 'database.default.port = ' + SQLPage.Values[2];
          
        if Pos('database.development.username', EnvLines[I]) = 1 then
          EnvLines[I] := 'database.development.username = ''' + SQLPage.Values[0] + '''';
        if Pos('database.development.password', EnvLines[I]) = 1 then
          EnvLines[I] := 'database.development.password = ''' + SQLPage.Values[1] + '''';
        if Pos('database.development.port', EnvLines[I]) = 1 then
          EnvLines[I] := 'database.development.port = ' + SQLPage.Values[2];
      end;
      SaveStringsToFile(EnvPath, EnvLines, False);
    end;
  end;
end;
