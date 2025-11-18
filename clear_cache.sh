#!/bin/bash
# Clear Cache Script for OpenCart
# This script clears cache files but keeps the directories

echo "=== Clearing OpenCart Cache ==="
echo ""

# Define cache directories
CACHE_DIRS=(
    "system/cache"
    "system/cache/html"
)

TOTAL_DELETED=0

for dir in "${CACHE_DIRS[@]}"; do
    if [ -d "$dir" ]; then
        echo "Clearing: $dir"
        COUNT=$(find "$dir" -type f | wc -l)
        find "$dir" -type f -delete
        echo "  ✓ Deleted $COUNT files"
        TOTAL_DELETED=$((TOTAL_DELETED + COUNT))
    else
        echo "  ⚠ Directory not found: $dir"
    fi
done

echo ""
echo "=== Summary ==="
echo "Total files deleted: $TOTAL_DELETED"
echo "✅ Cache cleared successfully!"
echo ""

