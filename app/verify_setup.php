<?php

chdir(__DIR__);

// Check if files exist
$files = [
    'core/Router.php',
    'controllers/AuthController.php',
    'controllers/AdminController.php',
    'controllers/StaffController.php',
    'views/auth/login.view.php',
    'views/admin/dashboard.view.php',
    'views/staff/pos.view.php',
    'public/css/style.css',
];

$missing = [];
foreach ($files as $f) {
    if (! file_exists($f)) {
        $missing[] = $f;
    }
}

if (count($missing) > 0) {
    echo 'Missing files: '.implode(', ', $missing)."\n";
    exit(1);
}

// Check DB Connection
$config = require 'config.php';
$mysqli = new mysqli(
    $config['database']['host'],
    $config['database']['username'],
    $config['database']['password'],
    $config['database']['db_name'],
    $config['database']['port']
);

if ($mysqli->connect_errno) {
    echo 'DB Connection Failed: '.$mysqli->connect_error."\n";
    exit(1);
}

echo "All files present and DB connection successful.\n";
echo "Initial Verification Passed.\n";
