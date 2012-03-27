@ECHO OFF
SET DATABASENAME=domain_checker

echo This file will execute the following tasks:
echo.   symfony doctrine build
echo.   mysql dummy data import
echo.     (located at %DUMMYDATA%\datadump.sql)
echo.
echo Running symfony coolpink:build --all --no-confirmation
echo.
php ../symfony coolpink:build --all --no-confirmation
echo.
echo Running symfony cc
echo.
php ../symfony cc
echo.
echo Running symfony plugin:publish-assets
echo.
php ../symfony plugin:publish-assets
echo.
echo.
echo Running dummy data import
mysql -u root %DATABASENAME% < datadump.sql
@ECHO OFF
echo.

echo All done!
pause