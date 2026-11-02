
$ErrorActionPreference = 'Stop'

# Run external commands and check their exit codes
function Invoke-Checked {
    param(
        [string]$Command,
        [string[]]$Arguments
    )

    & $Command @Arguments

    if ($LASTEXITCODE -ne 0) {
        throw "$Command failed with exit code $LASTEXITCODE"
    }
}

# Verify build environment
Invoke-Checked php @('--version')
Invoke-Checked composer @('--version')

# Ensure Xdebug is available
$modules = & php -m
if ($LASTEXITCODE -ne 0) {
    throw "Could not retrieve PHP modules"
}

if ($modules -notcontains 'Xdebug') {
    throw "Xdebug is not installed or enabled"
}

$env:XDEBUG_MODE = 'coverage'

# Validate Composer configuration
Invoke-Checked composer @('validate', '--strict')

# Generate composer.lock
Invoke-Checked composer @('update', '--no-install')

# Install dependencies
Invoke-Checked composer @('install', '--prefer-dist', '--no-progress')

# Run tests
Invoke-Checked composer @('run-script', 'test')

# Static analysis
Invoke-Checked composer @('run-script', 'phpstan')

# Coding standards
Invoke-Checked composer @('run-script', 'phpcs-all')

# Extract total coverage
$coverageText = Get-Content 'build/coverage.txt' -Raw

$match = [regex]::Match(
    $coverageText,
    'Lines:\s*([0-9.]+%)'
)

if (-not $match.Success) {
    throw "Could not extract coverage from build/coverage.txt"
}

$coverage = $match.Groups[1].Value
$percentage = [double]::Parse(
    $coverage.TrimEnd('%'),
    [cultureinfo]::InvariantCulture
)

# Determine badge color
if ($percentage -ge 90) {
    $color = 'brightgreen'
}
elseif ($percentage -ge 80) {
    $color = 'yellow'
}
elseif ($percentage -ge 60) {
    $color = 'orange'
}
else {
    $color = 'red'
}

# Generate badge JSON
$badge = @{
    schemaVersion = 1
    label         = 'coverage'
    message       = $coverage
    color         = $color
}

New-Item -ItemType Directory -Force 'build' | Out-Null

$badge | ConvertTo-Json -Compress |
    Set-Content 'build/coverage-badge.json' -Encoding utf8

Write-Host "Coverage: $coverage ($color)"
