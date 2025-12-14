@echo off
echo ========================================
echo Pushing all code to GitHub
echo ========================================
echo.

echo Step 1: Adding all files...
git add -A
if %errorlevel% neq 0 (
    echo Error adding files!
    pause
    exit /b 1
)

echo.
echo Step 2: Committing changes...
git commit -m "Update all code to GitHub - %date% %time%"
if %errorlevel% neq 0 (
    echo No changes to commit or commit failed!
)

echo.
echo Step 3: Pushing to GitHub...
git push origin main
if %errorlevel% neq 0 (
    echo Error pushing to GitHub!
    pause
    exit /b 1
)

echo.
echo ========================================
echo SUCCESS! All code pushed to GitHub
echo ========================================
pause


