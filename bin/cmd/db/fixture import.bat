@echo off

set rootDir="%~dp0/../../.."
set eloquentBinDir=%rootDir%/vendor/php7lab/eloquent/bin

cd %eloquentBinDir%
php console db:fixture:import
pause

REM use --withConfirm=0 for skip dialog
