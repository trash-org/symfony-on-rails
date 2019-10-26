@echo off
cd ../..
php console orm:migrate:down
pause

REM use --withConfirm=0 for skip dialog
