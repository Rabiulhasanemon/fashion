@echo off
REM Ultra Quick Push - No prompts, just push!
REM Double-click to instantly push all changes

git add .
git commit -m "Quick update - %date% %time%"
git push origin main

echo.
echo Done! Pushed to GitHub.
timeout /t 3 >nul

