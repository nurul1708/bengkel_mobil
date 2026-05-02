<?php
// Hash all passwords in database with bcrypt

echo "=== FIX PASSWORD BCRYPT ===\n\n";

$mysqli = new mysqli('localhost', 'root', '', 'bengkel_mobil');
if ($mysqli->connect_error) die('Connection failed: ' . $mysqli->connect_error);

// Get all users
$result = $mysqli->query("SELECT id, name, email, password FROM users");
$users = $result->fetch_all(MYSQLI_ASSOC);

echo "Found " . count($users) . " users\n\n";

foreach ($users as $user) {
    echo "Processing: " . $user['name'] . " (" . $user['email'] . ")\n";
    echo "  Current password: " . $user['password'] . "\n";
    
    // Check if already bcrypted (starts with $2)
    if (substr($user['password'], 0, 3) === '$2y' || substr($user['password'], 0, 3) === '$2a' || substr($user['password'], 0, 3) === '$2b') {
        echo "  ✓ Already bcrypted\n\n";
        continue;
    }
    
    // If password is short (not hashed), bcrypt it
    if (strlen($user['password']) < 20) {
        echo "  ! Detected plain text password\n";
        
        // Hash with bcrypt
        $hashedPassword = password_hash($user['password'], PASSWORD_BCRYPT);
        echo "  → New hash: " . substr($hashedPassword, 0, 40) . "...\n";
        
        // Update database
        $escapedHash = $mysqli->real_escape_string($hashedPassword);
        $updateQuery = "UPDATE users SET password = '" . $escapedHash . "' WHERE id = " . $user['id'];
        
        if ($mysqli->query($updateQuery)) {
            echo "  ✅ Password updated!\n";
        } else {
            echo "  ❌ Error updating: " . $mysqli->error . "\n";
        }
    } else {
        echo "  ℹ Password already long (" . strlen($user['password']) . " chars)\n";
    }
    
    echo "\n";
}

$mysqli->close();

echo "=== DONE ===\n";
echo "All passwords have been verified and updated\n";
