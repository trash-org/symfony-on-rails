@echo off
cd ../..
php console orm:fixture:export
pause

REM use --withConfirm=0 for skip dialog
