# PowerShell Script - Auto-Push to GitHub Always
# This script sets up automatic pushing to GitHub on every commit

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Setting Up Auto-Push to GitHub" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Check if .git directory exists
if (-not (Test-Path ".git")) {
    Write-Host "[ERROR] This is not a git repository!" -ForegroundColor Red
    Write-Host "Please run this script from your project root." -ForegroundColor Red
    Read-Host "Press Enter to exit"
    exit 1
}

# Create post-commit hook
$hookPath = ".git\hooks\post-commit.bat"
$hookContent = @"
@echo off
REM Auto-push to GitHub after every commit
echo.
echo ========================================
echo   Auto-Pushing to GitHub...
echo ========================================
echo.
git push origin main
if errorlevel 1 (
    echo [WARNING] Auto-push failed. You may need to push manually.
) else (
    echo [SUCCESS] Code automatically pushed to GitHub!
)
echo.
"@

if (-not (Test-Path $hookPath)) {
    Set-Content -Path $hookPath -Value $hookContent
    Write-Host "[SUCCESS] Post-commit hook created!" -ForegroundColor Green
} else {
    Write-Host "Post-commit hook already exists." -ForegroundColor Yellow
    $overwrite = Read-Host "Do you want to overwrite it? (y/n)"
    if ($overwrite -eq "y" -or $overwrite -eq "Y") {
        Set-Content -Path $hookPath -Value $hookContent
        Write-Host "[SUCCESS] Post-commit hook updated!" -ForegroundColor Green
    }
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Setup Complete!" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "From now on, every time you commit code:" -ForegroundColor Green
Write-Host "  git commit -m `"your message`"" -ForegroundColor Yellow
Write-Host ""
Write-Host "It will automatically push to GitHub!" -ForegroundColor Green
Write-Host ""
Write-Host "To disable auto-push, delete:" -ForegroundColor Gray
Write-Host "  .git\hooks\post-commit.bat" -ForegroundColor Gray
Write-Host ""
Read-Host "Press Enter to exit"

