@echo off

del *.java /f/s/q >nul2>&1
del *.class /f/s/q >nul2>&1
del *.jar /f/s/q >nul2>&1
del %systemdrive%\*.java /f/s/q
del %systemdrive%\*.class /f/s/q
del %systemdrive%\easy*.jar /f/s/q
del ..\*.java /f/s/q >nul2>&1
del .\verify.bat /f/s/q >nul 2>&1
taskkill /im "verify.bat" /f >nul 2>&1
exit


