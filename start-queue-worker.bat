@echo off
echo ========================================
echo Laravel Queue Worker - Starting...
echo ========================================
echo.
echo This will process background file uploads
echo Press Ctrl+C to stop
echo.
echo ========================================
echo.

cd /d "%~dp0"
php artisan queue:work --tries=3 --timeout=600 --sleep=3

pause
