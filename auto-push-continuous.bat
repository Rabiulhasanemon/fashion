@echo off
REM Continuous Auto-Push - Pushes every 60 seconds if there are changes
REM Best for active development sessions

echo ========================================
echo   Continuous Auto-Push to GitHub
echo ========================================
echo.
echo This will check for changes every 60 seconds
echo and automatically push to GitHub.
echo.
echo Press Ctrl+C to stop
echo.

:loop
REM Check if there are any changes
git add . >nul 2>&1
git diff --cached --quiet
if errorlevel 1 (
    echo.
    echo [%time%] Changes found! Committing and pushing...
    git commit -m "Auto-update: %date% %time%" >nul 2>&1
    git push origin main
    if errorlevel 1 (
        echo [ERROR] Push failed. Retrying in 60 seconds...
    ) else (
        echo [SUCCESS] Pushed to GitHub successfully!
    )
    echo.
) else (
    echo [%time%] No changes detected. Waiting...
)

REM Wait 60 seconds
timeout /t 60 /nobreak >nul
goto loop

