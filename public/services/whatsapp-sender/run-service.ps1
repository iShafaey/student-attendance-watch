$scriptPath = Split-Path -Path $MyInvocation.MyCommand.Definition -Parent
$pythonScript = "$scriptPath\wss-app.py"
Start-Process "python" -ArgumentList $pythonScript -Verb RunAs
