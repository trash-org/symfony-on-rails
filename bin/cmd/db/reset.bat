@echo off
cd ../..
REM php console orm:migrate:down --withConfirm=0
php console orm:db:delete-all-tables --withConfirm=0
php console orm:migrate:up --withConfirm=0
php console orm:fixture:import --withConfirm=0
pause