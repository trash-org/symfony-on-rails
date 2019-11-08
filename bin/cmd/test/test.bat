@echo off

set rootDir="%~dp0/../../.."

cd %rootDir%
phpunit
pause