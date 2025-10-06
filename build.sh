#!/bin/bash
set -e

echo "🚀 StudentLink Backend Build Script"
echo "===================================="

# Check if we're in a PHP environment
if command -v php &> /dev/null; then
    echo "✅ PHP is available: $(php --version | head -n1)"
else
    echo "❌ PHP is not available"
    exit 1
fi

# Check if composer is available
if command -v composer &> /dev/null; then
    echo "✅ Composer is available: $(composer --version | head -n1)"
else
    echo "❌ Composer is not available"
    exit 1
fi

echo "🎉 Build environment is ready!"
