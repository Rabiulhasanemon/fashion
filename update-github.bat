@echo off
echo ========================================
echo Git Push to GitHub - Run as Administrator
echo ========================================
echo.

cd /d "E:\xampp\htdocs\fashiongit\fashion"

echo Configuring git user...
git config --global user.name "Rabiulhasanemon"
git config --global user.email "rabiulhasanemon@gmail.com"

echo.
echo Adding files...
git add catalog/controller/module/featured_category.php
git add catalog/controller/product/category.php
git add catalog/view/theme/ranger_fashion/template/extension/module/product_showcase_tabs.tpl
git add catalog/view/theme/ranger_fashion/template/module/featured_category.tpl
git add catalog/view/theme/ranger_fashion/template/product/all_categories.tpl

echo.
echo Committing changes...
git commit -m "Add featured categories See All button, all categories page, and update Product Showcase Tabs discount style"

echo.
echo Pushing to GitHub...
git push origin main

echo.
echo ========================================
echo Done!
echo ========================================
pause

