@echo off
REM ========================================
REM Git Push Script - MUST RUN AS ADMINISTRATOR
REM ========================================
REM Right-click this file and select "Run as administrator"

echo.
echo ========================================
echo Pushing Changes to GitHub
echo ========================================
echo.

cd /d "%~dp0"

echo Step 1: Configuring git user...
git config --global user.name "Rabiulhasanemon"
git config --global user.email "rabiulhasanemon@gmail.com"
if errorlevel 1 (
    echo ERROR: Could not configure git user
    pause
    exit /b 1
)

echo.
echo Step 2: Checking git status...
git status --short | findstr /C:"featured_category" /C:"category.php" /C:"product_showcase" /C:"all_categories"

echo.
echo Step 3: Adding files to staging...
git add catalog/controller/module/featured_category.php
git add catalog/controller/product/category.php
git add catalog/view/theme/ranger_fashion/template/extension/module/product_showcase_tabs.tpl
git add catalog/view/theme/ranger_fashion/template/module/featured_category.tpl
git add catalog/view/theme/ranger_fashion/template/product/all_categories.tpl

if errorlevel 1 (
    echo WARNING: Some files could not be added. Continuing anyway...
)

echo.
echo Step 4: Committing changes...
git commit -m "Add featured categories See All button, all categories page, and update Product Showcase Tabs discount style"

if errorlevel 1 (
    echo ERROR: Could not commit changes. Check permissions.
    echo Make sure you ran this script as Administrator!
    pause
    exit /b 1
)

echo.
echo Step 5: Pushing to GitHub...
git push origin main

if errorlevel 1 (
    echo ERROR: Could not push to GitHub. Check your internet connection and GitHub credentials.
    pause
    exit /b 1
)

echo.
echo ========================================
echo SUCCESS! Changes pushed to GitHub
echo ========================================
echo.
pause

