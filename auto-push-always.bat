@echo off
REM ========================================
REM Auto-Push to GitHub - Always Active
REM ========================================
REM This script sets up automatic pushing to GitHub
REM Run this once to enable auto-push on every commit

echo ========================================
echo   Setting Up Auto-Push to GitHub
echo ========================================
echo.

REM Check if .git directory exists
if not exist ".git" (
    echo [ERROR] This is not a git repository!
    echo Please run this script from your project root.
    pause
    exit /b 1
)

REM Create post-commit hook if it doesn't exist
if not exist ".git\hooks\post-commit" (
    echo Creating post-commit hook...
    (
        echo @echo off
        echo REM Auto-push to GitHub after every commit
        echo echo.
        echo echo ========================================
        echo echo   Auto-Pushing to GitHub...
        echo echo ========================================
        echo echo.
        echo git push origin main
        echo if errorlevel 1 ^(
        echo     echo [WARNING] Auto-push failed. You may need to push manually.
        echo ^) else ^(
        echo     echo [SUCCESS] Code automatically pushed to GitHub!
        echo ^)
        echo echo.
    ) > .git\hooks\post-commit.bat
    
    echo [SUCCESS] Post-commit hook created!
) else (
    echo Post-commit hook already exists.
)

echo.
echo ========================================
echo   Setup Complete!
echo ========================================
echo.
echo From now on, every time you commit code:
echo   git commit -m "your message"
echo.
echo It will automatically push to GitHub!
echo.
echo To disable auto-push, delete:
echo   .git\hooks\post-commit.bat
echo.
pause

