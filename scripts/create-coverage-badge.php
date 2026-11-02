
<?php

$coverageFile = __DIR__ . '/../build/coverage.txt';
$badgeFile = __DIR__ . '/../build/coverage-badge.json';

if (!file_exists($coverageFile)) {
    fwrite(STDERR, "Coverage report not found: $coverageFile\n");
    exit(1);
}

$report = file_get_contents($coverageFile);

if (!preg_match('/Lines:\s*([0-9.]+%)/', $report, $matches)) {
    fwrite(STDERR, "Could not extract coverage percentage\n");
    exit(1);
}

$coverage = $matches[1];
$value = (float) $coverage;

$color = match (true) {
    $value >= 90 => 'brightgreen',
    $value >= 70 => 'yellow',
    $value >= 50 => 'orange',
    default => 'red',
};

$badge = [
    'schemaVersion' => 1,
    'label' => 'coverage',
    'message' => $coverage,
    'color' => $color,
];

file_put_contents(
    $badgeFile,
    json_encode($badge, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n"
);

echo "Coverage badge created: $coverage ($color)\n";
