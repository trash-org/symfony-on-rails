@echo off
cd ../..
php console orm:migrate:up
pause

REM use --withConfirm=0 for skip dialog
