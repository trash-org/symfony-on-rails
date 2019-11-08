@echo off

set rootDir="%~dp0/../../.."
set eloquentBinDir=%rootDir%/vendor/php7lab/eloquent/bin

cd %eloquentBinDir%
php console db:delete-all-tables
pause

REM use --withConfirm=0 for skip dialog
