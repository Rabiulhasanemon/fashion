# Git Push Script for Fashion Project
# IMPORTANT: Run this script as Administrator (Right-click PowerShell -> Run as Administrator)

Write-Host "=== Git Push Script ===" -ForegroundColor Green
Write-Host ""

# Configure git user (using global config since local has permission issues)
Write-Host "Configuring git user..." -ForegroundColor Yellow
git config --global user.name "Rabiulhasanemon"
git config --global user.email "rabiulhasanemon@gmail.com"

# Check git status
Write-Host ""
Write-Host "Current git status:" -ForegroundColor Yellow
git status --short

# Add files
Write-Host ""
Write-Host "Adding files to git..." -ForegroundColor Yellow
git add catalog/controller/module/featured_category.php
if ($LASTEXITCODE -ne 0) { Write-Host "Warning: Could not add featured_category.php" -ForegroundColor Red }

git add catalog/controller/product/category.php
if ($LASTEXITCODE -ne 0) { Write-Host "Warning: Could not add category.php" -ForegroundColor Red }

git add catalog/view/theme/ranger_fashion/template/extension/module/product_showcase_tabs.tpl
if ($LASTEXITCODE -ne 0) { Write-Host "Warning: Could not add product_showcase_tabs.tpl" -ForegroundColor Red }

git add catalog/view/theme/ranger_fashion/template/module/featured_category.tpl
if ($LASTEXITCODE -ne 0) { Write-Host "Warning: Could not add featured_category.tpl" -ForegroundColor Red }

git add catalog/view/theme/ranger_fashion/template/product/all_categories.tpl
if ($LASTEXITCODE -ne 0) { Write-Host "Warning: Could not add all_categories.tpl" -ForegroundColor Red }

# Check if files were added
Write-Host ""
Write-Host "Files staged:" -ForegroundColor Yellow
git status --short

# Commit
Write-Host ""
Write-Host "Committing changes..." -ForegroundColor Yellow
git commit -m "Add featured categories See All button, all categories page, and update Product Showcase Tabs discount style"
if ($LASTEXITCODE -ne 0) { 
    Write-Host "Error: Could not commit. You may need to run as Administrator." -ForegroundColor Red
    exit 1
}

# Push to GitHub
Write-Host ""
Write-Host "Pushing to GitHub..." -ForegroundColor Yellow
git push origin main
if ($LASTEXITCODE -ne 0) { 
    Write-Host "Error: Could not push. Check your internet connection and GitHub credentials." -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host "=== Success! Changes pushed to GitHub ===" -ForegroundColor Green

