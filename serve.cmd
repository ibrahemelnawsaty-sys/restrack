@echo off
REM Restrack — run the app locally using the bundled portable PHP.
REM Double-click this file, then open http://127.0.0.1:8000 in your browser.
cd /d "%~dp0"
".dev-tools\php\php.exe" artisan serve
