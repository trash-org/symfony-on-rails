@echo off
cd ../..
php console orm:db:delete-all-tables
pause

REM use --withConfirm=0 for skip dialog
