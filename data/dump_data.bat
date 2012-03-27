@ECHO OFF
echo This file will dump the current database to file to be used later by load_data.bat
echo.
echo Cancel the task NOW (Ctrl-C) if you don't want to do this.
echo.
echo.
pause

cd %MYSQL%
echo.
echo Dumping database
mysqldump --user=root domain_checker -t -n --complete-insert > datadump.sql
echo.

echo All done!
echo.

pause
