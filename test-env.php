<?php
// Diagnostic script to check environment variables
header('Content-Type: text/plain; charset=utf-8');

echo "Environment Variable Diagnostic\n";
echo "================================\n\n";

$vars = ['DB_HOST', 'DB_USER', 'DB_PASSWORD', 'DB_NAME', 'DB_PORT'];

foreach ($vars as $var) {
    echo "$var:\n";
    $getenv = getenv($var);
    echo "  getenv(): " . ($getenv !== false ? htmlspecialchars($getenv) : 'NOT SET') . "\n";
    echo "  \$_ENV: " . (isset($_ENV[$var]) ? htmlspecialchars($_ENV[$var]) : 'NOT SET') . "\n";
    echo "  \$_SERVER: " . (isset($_SERVER[$var]) ? htmlspecialchars($_SERVER[$var]) : 'NOT SET') . "\n";
    echo "\n";
}

echo "\nAll DB_ Environment Variables from getenv():\n";
echo "=============================================\n";
$allEnv = getenv();
foreach ($allEnv as $key => $value) {
    if (strpos($key, 'DB_') === 0) {
        echo "$key: " . htmlspecialchars($value) . "\n";
    }
}

echo "\n\nAll DB_ variables from \$_SERVER:\n";
echo "==================================\n";
foreach ($_SERVER as $key => $value) {
    if (strpos($key, 'DB_') === 0) {
        echo "$key: " . htmlspecialchars($value) . "\n";
    }
}

echo "\n\nIf you see 'NOT SET' above, you need to manually set these in Render Dashboard:\n";
echo "1. Go to your Render Dashboard\n";
echo "2. Select your web service (stdc-attendance-system)\n";
echo "3. Go to 'Environment' tab\n";
echo "4. Add these environment variables manually:\n";
echo "   - DB_HOST: (get this from your mysql-db service)\n";
echo "   - DB_USER: user\n";
echo "   - DB_PASSWORD: password\n";
echo "   - DB_NAME: attendancesystem\n";
echo "   - DB_PORT: 3306\n";
?>

