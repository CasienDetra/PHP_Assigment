<?php
// Debug script to check password hashes
$admin_pass = 'admin1234';
$staff_pass = 'staff1234';

$admin_hash_from_sql = '$2y$10$u/ERjvS5U/sZedD4TOf/uOSTKTRyzr/iRUeHsD5hi31lUsVxZ3tge'; // Copied from sql file view previously
$staff_hash_from_sql = '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm'; // Copied from sql file view previously

echo "Checking Admin Password:\n";
if (password_verify($admin_pass, $admin_hash_from_sql)) {
    echo "Admin password '$admin_pass' MATCHES hash.\n";
} else {
    echo "Admin password '$admin_pass' DOES NOT MATCH hash.\n";
    echo "New Admin Hash: " . password_hash($admin_pass, PASSWORD_DEFAULT) . "\n";
}

echo "\nChecking Staff Password:\n";
if (password_verify($staff_pass, $staff_hash_from_sql)) {
    echo "Staff password '$staff_pass' MATCHES hash.\n";
} else {
    echo "Staff password '$staff_pass' DOES NOT MATCH hash.\n";
    echo "New Staff Hash: " . password_hash($staff_pass, PASSWORD_DEFAULT) . "\n";
}

// Connect to DB and check what is actually there
$config = require 'app/config.php';
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

echo "\nDatabase Content:\n";
$res = $mysqli->query("SELECT * FROM admin_users");
while ($row = $res->fetch_assoc()) {
    echo "Admin: " . $row['email'] . " | Hash: " . $row['password_hash'] . "\n";
}

$res = $mysqli->query("SELECT * FROM staff");
while ($row = $res->fetch_assoc()) {
    echo "Staff: " . $row['username'] . " | Hash: " . $row['password_hash'] . "\n";
}
