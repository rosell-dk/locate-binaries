
#!/usr/bin/env bash
set -euo pipefail

# Verify build environment
php --version
composer --version

# Ensure Xdebug is available and enabled for coverage
php -m | grep -i xdebug
export XDEBUG_MODE=coverage

# Validate Composer configuration
composer validate --strict

# Generate composer.lock (this is a library)
composer update --no-install

# Install dependencies
composer install --prefer-dist --no-progress

# Run tests
composer run-script test

# Static analysis
composer run-script phpstan

# Coding standards
composer run-script phpcs-all

# Extract total coverage
COVERAGE=$(grep -oP -m 1 'Lines:\s*\K[0-9.%]+' build/coverage.txt)

# Determine badge color
if grep -oP '(^9\d.)|(^100.)' <<< "$COVERAGE" > /dev/null; then
    COLOR=brightgreen
elif grep -oP '[87]\d.' <<< "$COVERAGE" > /dev/null; then
    COLOR=yellow
elif grep -oP '[65]\d.' <<< "$COVERAGE" > /dev/null; then
    COLOR=orange
else
    COLOR=red
fi

# Generate badge JSON
echo "{\"schemaVersion\":1,\"label\":\"coverage\",\"message\":\"$COVERAGE\",\"color\":\"$COLOR\"}" \
    > build/coverage-badge.json

echo "Coverage: $COVERAGE ($COLOR)"
