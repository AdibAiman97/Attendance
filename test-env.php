<?php
// Diagnostic script to check environment variables
header('Content-Type: text/plain');

echo "Environment Variable Test\n";
echo "========================\n\n";

$vars = ['DB_HOST', 'DB_USER', 'DB_PASSWORD', 'DB_NAME', 'DB_PORT'];

foreach ($vars as $var) {
    echo "$var:\n";
    echo "  getenv(): " . (getenv($var) !== false ? getenv($var) : 'NOT SET') . "\n";
    echo "  \$_ENV: " . (isset($_ENV[$var]) ? $_ENV[$var] : 'NOT SET') . "\n";
    echo "  \$_SERVER: " . (isset($_SERVER[$var]) ? $_SERVER[$var] : 'NOT SET') . "\n";
    echo "\n";
}

echo "All Environment Variables:\n";
echo "==========================\n";
echo "getenv() all: " . print_r(getenv(), true) . "\n";
echo "\$_ENV: " . print_r($_ENV, true) . "\n";
echo "\$_SERVER (filtered):\n";
foreach ($_SERVER as $key => $value) {
    if (strpos($key, 'DB_') === 0) {
        echo "  $key: $value\n";
    }
}
?>

