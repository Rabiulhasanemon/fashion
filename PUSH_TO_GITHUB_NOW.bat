@echo off
title Push Changes to GitHub - Run as Administrator
color 0A
echo.
echo ============================================================
echo   PUSHING CHANGES TO GITHUB
echo ============================================================
echo.
echo This script will:
echo   1. Configure git user
echo   2. Stage all modified files
echo   3. Commit changes
echo   4. Push to GitHub
echo.
echo ============================================================
echo.

cd /d "%~dp0"

echo [1/4] Configuring git user...
git config --global user.name "Rabiulhasanemon"
git config --global user.email "rabiulhasanemon@gmail.com"
if errorlevel 1 (
    color 0C
    echo.
    echo ERROR: Could not configure git user!
    echo Make sure you ran this as Administrator!
    pause
    exit /b 1
)
echo OK

echo.
echo [2/4] Staging files...
git add catalog/controller/module/featured_category.php
git add catalog/controller/product/category.php
git add catalog/view/theme/ranger_fashion/template/extension/module/product_showcase_tabs.tpl
git add catalog/view/theme/ranger_fashion/template/module/featured_category.tpl
git add catalog/view/theme/ranger_fashion/template/product/all_categories.tpl
if errorlevel 1 (
    color 0E
    echo WARNING: Some files could not be staged
) else (
    echo OK
)

echo.
echo [3/4] Committing changes...
git commit -m "Add featured categories See All button, all categories page, and update Product Showcase Tabs discount style"
if errorlevel 1 (
    color 0C
    echo.
    echo ERROR: Could not commit!
    echo This usually means permission issues.
    echo Make sure you ran this script as Administrator!
    echo.
    pause
    exit /b 1
)
echo OK

echo.
echo [4/4] Pushing to GitHub...
git push origin main
if errorlevel 1 (
    color 0C
    echo.
    echo ERROR: Could not push to GitHub!
    echo Check your internet connection and GitHub credentials.
    pause
    exit /b 1
)
echo OK

color 0A
echo.
echo ============================================================
echo   SUCCESS! Changes pushed to GitHub
echo ============================================================
echo.
pause

