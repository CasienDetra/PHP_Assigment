<?php
// Mock server environment
chdir(__DIR__ . '/app');
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/';

// Helpers to capture output
function test_request($uri, $method = 'GET', $post = []) {
    $_SERVER['REQUEST_URI'] = $uri;
    $_SERVER['REQUEST_METHOD'] = $method;
    $_POST = $post;
    
    // We cannot easily 'require' index.php multiple times because of function redeclarations and singletons.
    // So this verification script checks file existence mostly and maybe runs one basic test or DB check.
    return true; 
}

// Check if files exist
$files = [
    'core/Router.php',
    'controllers/AuthController.php',
    'controllers/AdminController.php',
    'controllers/StaffController.php',
    'views/auth/login.view.php',
    'views/admin/dashboard.view.php',
    'views/staff/pos.view.php',
    'public/css/style.css'
];

$missing = [];
foreach ($files as $f) {
    if (!file_exists($f)) {
        $missing[] = $f;
    }
}

if (count($missing) > 0) {
    echo "Missing files: " . implode(', ', $missing) . "\n";
    exit(1);
}

// Check DB Connection
require 'config.php';
$mysqli = new mysqli(
    $config['database']['host'],
    $config['database']['username'],
    $config['database']['password'],
    $config['database']['db_name'],
    $config['database']['port']
);

if ($mysqli->connect_error) {
    echo "DB Connection Failed: " . $mysqli->connect_error . "\n";
    exit(1);
}

echo "All files present and DB connection successful.\n";
echo "Initial Verification Passed.\n";
