@echo off
REM Auto-Push to GitHub - Watches for file changes and automatically pushes
REM Run this script and it will monitor your files and push to GitHub automatically

echo ========================================
echo   Auto-Push to GitHub - Running...
echo ========================================
echo.
echo This script will watch for file changes
echo and automatically push to GitHub.
echo.
echo Press Ctrl+C to stop
echo.

:loop
REM Check for changes
git add . >nul 2>&1
git diff --cached --quiet
if errorlevel 1 (
    echo [%date% %time%] Changes detected! Pushing to GitHub...
    git commit -m "Auto-update: %date% %time%" >nul 2>&1
    git push origin main >nul 2>&1
    if errorlevel 1 (
        echo [ERROR] Failed to push. Check your connection.
    ) else (
        echo [SUCCESS] Code pushed to GitHub!
    )
    echo.
)

REM Wait 30 seconds before checking again
timeout /t 30 /nobreak >nul
goto loop

