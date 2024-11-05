$scriptPath = Split-Path -Path $MyInvocation.MyCommand.Definition -Parent
$pythonScript = "$scriptPath\barcoder.py"
Start-Process "python" -ArgumentList $pythonScript -Verb RunAs
