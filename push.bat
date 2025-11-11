@echo off
REM Quick Push to GitHub - Just double-click this file!
REM This script will add, commit, and push all changes to GitHub

echo ========================================
echo   Quick Push to GitHub
echo ========================================
echo.

REM Check if we're in a git repository
git rev-parse --git-dir >nul 2>&1
if errorlevel 1 (
    echo ERROR: Not a Git repository!
    echo Please run this from your project folder.
    pause
    exit /b 1
)

echo Step 1: Checking status...
git status --short
echo.

echo Step 2: Adding all changes...
git add .
echo.

echo Step 3: Committing changes...
set /p commit_msg="Enter commit message (or press Enter for default): "
if "%commit_msg%"=="" set commit_msg=Update code - %date% %time%
git commit -m "%commit_msg%"
echo.

echo Step 4: Pushing to GitHub...
git push origin main
echo.

echo ========================================
echo   Done! Code pushed to GitHub!
echo ========================================
echo.
pause

