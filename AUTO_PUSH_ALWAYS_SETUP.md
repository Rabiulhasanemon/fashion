# Auto-Push to GitHub - Always Active Setup

This guide will help you set up automatic pushing to GitHub every time you commit code.

## Quick Setup (Windows)

### Option 1: Run the Setup Script (Recommended)

1. **Double-click** `auto-push-always.bat` or run:
   ```cmd
   auto-push-always.bat
   ```

2. That's it! From now on, every commit will automatically push to GitHub.

### Option 2: Manual Setup

1. Create a file at `.git\hooks\post-commit.bat` with this content:
   ```batch
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
   ```

## How It Works

- Every time you run `git commit`, the post-commit hook automatically runs
- The hook executes `git push origin main` to push your changes to GitHub
- You'll see a message confirming the push was successful

## Usage

After setup, just commit normally:

```bash
git add .
git commit -m "Your commit message"
# Automatically pushes to GitHub!
```

## Disable Auto-Push

To disable automatic pushing:

1. Delete the file: `.git\hooks\post-commit.bat`
2. Or rename it: `.git\hooks\post-commit.bat.disabled`

## Troubleshooting

### Push Fails
- Check your internet connection
- Verify your GitHub credentials are set up
- Make sure you have push access to the repository

### Hook Not Running
- Make sure the file is named exactly: `post-commit.bat`
- Check that it's in the `.git\hooks\` directory
- Verify the file has execute permissions

## Notes

- The hook only runs after successful commits
- If the push fails, you'll see a warning but the commit will still be saved locally
- You can always push manually with `git push origin main` if needed

