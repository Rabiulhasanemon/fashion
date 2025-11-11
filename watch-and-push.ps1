# PowerShell Script for Auto-Push to GitHub
# This script watches for file changes and automatically pushes to GitHub

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Auto-Push to GitHub - File Watcher" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Watching for file changes..." -ForegroundColor Green
Write-Host "Press Ctrl+C to stop" -ForegroundColor Yellow
Write-Host ""

$lastCommit = git rev-parse HEAD
$projectPath = Get-Location

# FileSystemWatcher to monitor file changes
$watcher = New-Object System.IO.FileSystemWatcher
$watcher.Path = $projectPath
$watcher.IncludeSubdirectories = $true
$watcher.EnableRaisingEvents = $true

# Exclude .git directory and other unnecessary files
$excludePatterns = @('.git', 'node_modules', '.cache', 'deploy.log', 'error_log')

$action = {
    $path = $Event.SourceEventArgs.FullPath
    $changeType = $Event.SourceEventArgs.ChangeType
    
    # Check if file should be excluded
    $shouldExclude = $false
    foreach ($pattern in $excludePatterns) {
        if ($path -like "*\$pattern\*" -or $path -like "*\$pattern") {
            $shouldExclude = $true
            break
        }
    }
    
    if (-not $shouldExclude) {
        Write-Host "[$changeType] $path" -ForegroundColor Gray
        
        # Wait 2 seconds for file to finish writing
        Start-Sleep -Seconds 2
        
        # Check if there are actual changes
        git add . 2>$null
        $status = git status --porcelain
        
        if ($status) {
            Write-Host "[$(Get-Date -Format 'HH:mm:ss')] Changes detected! Pushing to GitHub..." -ForegroundColor Yellow
            
            $commitMessage = "Auto-update: $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')"
            git commit -m $commitMessage 2>$null
            
            $pushResult = git push origin main 2>&1
            if ($LASTEXITCODE -eq 0) {
                Write-Host "[SUCCESS] Code pushed to GitHub!" -ForegroundColor Green
            } else {
                Write-Host "[ERROR] Push failed: $pushResult" -ForegroundColor Red
            }
            Write-Host ""
        }
    }
}

# Register event handlers
Register-ObjectEvent -InputObject $watcher -EventName "Changed" -Action $action | Out-Null
Register-ObjectEvent -InputObject $watcher -EventName "Created" -Action $action | Out-Null
Register-ObjectEvent -InputObject $watcher -EventName "Deleted" -Action $action | Out-Null
Register-ObjectEvent -InputObject $watcher -EventName "Renamed" -Action $action | Out-Null

Write-Host "File watcher started. Monitoring: $projectPath" -ForegroundColor Green
Write-Host ""

# Keep script running
try {
    while ($true) {
        Start-Sleep -Seconds 1
    }
} finally {
    $watcher.EnableRaisingEvents = $false
    $watcher.Dispose()
    Write-Host "File watcher stopped." -ForegroundColor Yellow
}

