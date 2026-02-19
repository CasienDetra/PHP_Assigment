<?php
require 'config.php';
$config = require 'config.php';

$mysqli = new mysqli(
    $config['database']['host'],
    $config['database']['username'],
    $config['database']['password'],
    $config['database']['db_name'],
    $config['database']['port']
);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Admin
$adminPass = 'admin1234';
$adminHash = password_hash($adminPass, PASSWORD_DEFAULT);
$stmt = $mysqli->prepare("UPDATE admin_users SET password_hash = ? WHERE email = 'admin@coffeeshop.com'");
$stmt->bind_param("s", $adminHash);
$stmt->execute();
echo "Admin password updated to '$adminPass'. Rows matched: " . $stmt->affected_rows . "\n";

// Staff
$staffPass = 'staff1234';
$staffHash = password_hash($staffPass, PASSWORD_DEFAULT);
$stmt = $mysqli->prepare("UPDATE staff SET password_hash = ? WHERE username = 'sokha_barista'");
$stmt->bind_param("s", $staffHash);
$stmt->execute();
echo "Staff password updated to '$staffPass'. Rows matched: " . $stmt->affected_rows . "\n";

$mysqli->close();
