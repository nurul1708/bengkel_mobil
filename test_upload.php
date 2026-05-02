<?php
echo "=== TESTING FILE UPLOAD ===\n\n";

// Create test image
$imagePath = __DIR__ . '/storage/app/public/profiles/test-image.png';
$profilesDir = __DIR__ . '/storage/app/public/profiles';

// Ensure directory exists
if (!is_dir($profilesDir)) {
    echo "Creating directory: " . $profilesDir . "\n";
    mkdir($profilesDir, 0755, true);
}

echo "Directory: " . $profilesDir . "\n";
echo "Directory exists: " . (is_dir($profilesDir) ? "YES" : "NO") . "\n";
echo "Directory writable: " . (is_writable($profilesDir) ? "YES" : "NO") . "\n";

// Create a simple test file (Base64 PNG)
$pngBase64 = "iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAYAAACNMs+9AAAAFUlEQVR42mNkYPhfAQAFZgElFfB8+QAAAABJRU5ErkJggg==";
$pngData = base64_decode($pngBase64);

// Simulate the upload
$filename = time() . '-test-photo.png';
$fullPath = $profilesDir . '/' . $filename;

echo "\n--- Attempting to save file ---\n";
echo "Target path: " . $fullPath . "\n";

if (file_put_contents($fullPath, $pngData)) {
    echo "✓ File saved successfully!\n";
    echo "File size: " . filesize($fullPath) . " bytes\n";
    echo "File exists check: " . (file_exists($fullPath) ? "YES" : "NO") . "\n";
    
    // Now update database
    echo "\n--- Updating database ---\n";
    $mysqli = new mysqli('localhost', 'root', '', 'bengkel_mobil');
    if ($mysqli->connect_error) die('Connection failed: ' . $mysqli->connect_error);
    
    $relativePath = 'profiles/' . $filename;
    $query = "UPDATE users SET photo = '" . $mysqli->real_escape_string($relativePath) . "' WHERE role = 'admin' LIMIT 1";
    $result = $mysqli->query($query);
    
    if ($result) {
        echo "✓ Database updated successfully!\n";
        echo "Photo path saved: " . $relativePath . "\n";
        
        // Verify
        $verifyQuery = "SELECT photo FROM users WHERE role = 'admin' LIMIT 1";
        $verifyResult = $mysqli->query($verifyQuery);
        $row = $verifyResult->fetch_assoc();
        echo "Verified in DB: " . $row['photo'] . "\n";
    } else {
        echo "✗ Database error: " . $mysqli->error . "\n";
    }
    
    $mysqli->close();
} else {
    echo "✗ Failed to save file!\n";
}

echo "\n=== TEST COMPLETE ===\n";
