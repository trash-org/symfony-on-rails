@echo off
cd ../..
php console orm:fixture:import
pause

REM use --withConfirm=0 for skip dialog
