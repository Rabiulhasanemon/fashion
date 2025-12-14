# PowerShell script to push all code to GitHub
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Pushing all code to GitHub" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

Write-Host "Step 1: Adding all files..." -ForegroundColor Yellow
git -c core.pager=cat add -A
if ($LASTEXITCODE -ne 0) {
    Write-Host "Error adding files!" -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host "Step 2: Committing changes..." -ForegroundColor Yellow
$commitMessage = "Update all code to GitHub - $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')"
git -c core.pager=cat commit -m $commitMessage
if ($LASTEXITCODE -ne 0) {
    Write-Host "No changes to commit or commit failed!" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "Step 3: Pushing to GitHub..." -ForegroundColor Yellow
git -c core.pager=cat push origin main
if ($LASTEXITCODE -ne 0) {
    Write-Host "Error pushing to GitHub!" -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Green
Write-Host "SUCCESS! All code pushed to GitHub" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green


