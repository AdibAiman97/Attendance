<?php
// Function to get environment variable (works with both Docker and Apache-PHP)
function getEnvVar($key, $default = '')
{
    // Method 1: Try $_ENV (works in Docker and most PHP environments)
    if (isset($_ENV[$key]) && !empty($_ENV[$key])) {
        return $_ENV[$key];
    }

    // Method 2: Try getenv() (standard PHP method)
    $value = getenv($key);
    if ($value !== false && $value !== '') {
        return $value;
    }

    // Method 3: Try $_SERVER (works in Apache environments)
    if (isset($_SERVER[$key]) && !empty($_SERVER[$key])) {
        return $_SERVER[$key];
    }

    return $default;
}

// Read database configuration from environment variables
// Fallback to local XAMPP values for local development
$host = getEnvVar("DB_HOST", "localhost");
$user = getEnvVar("DB_USER", "root");
$pass = getEnvVar("DB_PASSWORD", "");
$db = getEnvVar("DB_NAME", "attendancesystem");
$port = getEnvVar("DB_PORT", "3306");

// Create database connection
$conn = new mysqli($host, $user, $pass, $db, $port);
if ($conn->connect_error) {
    die("Failed To Connect to database: " . $conn->connect_error);
}
