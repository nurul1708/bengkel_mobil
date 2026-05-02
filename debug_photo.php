<?php
$mysqli = new mysqli('localhost', 'root', '', 'bengkel_mobil');

// Check connection
if ($mysqli->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}

// Get admin user
$result = $mysqli->query("SELECT id, name, email, photo FROM users WHERE role='admin' LIMIT 1");
$user = $result->fetch_assoc();

echo "=== DATABASE CHECK ===\n";
echo "User Name: " . $user['name'] . "\n";
echo "Email: " . $user['email'] . "\n";
echo "Photo DB Value: " . ($user['photo'] ?? 'NULL') . "\n";
echo "\n";

// Check files in storage
echo "=== FILE SYSTEM CHECK ===\n";
$profilesDir = __DIR__ . '/storage/app/public/profiles';
echo "Profiles Directory: " . $profilesDir . "\n";
echo "Directory Exists: " . (is_dir($profilesDir) ? 'YES' : 'NO') . "\n";

if (is_dir($profilesDir)) {
    $files = scandir($profilesDir);
    echo "Files in directory: " . count($files) . "\n";
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            echo "  - $file\n";
        }
    }
}

// Check symlink
echo "\n=== SYMLINK CHECK ===\n";
$symlinkPath = __DIR__ . '/public/storage';
echo "Symlink path: " . $symlinkPath . "\n";
echo "Symlink exists: " . (file_exists($symlinkPath) ? 'YES' : 'NO') . "\n";
echo "Is link: " . (is_link($symlinkPath) ? 'YES' : 'NO') . "\n";
if (is_link($symlinkPath)) {
    echo "Points to: " . readlink($symlinkPath) . "\n";
}

echo "\n";
$mysqli->close();
