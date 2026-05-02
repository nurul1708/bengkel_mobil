<?php
$mysqli = new mysqli('localhost', 'root', '', 'bengkel_mobil');
if ($mysqli->connect_error) die('Connection failed: ' . $mysqli->connect_error);

// Clear photo for admin user
$query = "UPDATE users SET photo = NULL WHERE role = 'admin' LIMIT 1";
$result = $mysqli->query($query);

if ($result) {
    echo "Admin user photo cleared successfully\n";
    echo "Rows affected: " . $mysqli->affected_rows . "\n";
} else {
    echo 'Error: ' . $mysqli->error . "\n";
}

$mysqli->close();
