<?php
/**
 * WEB-ACCESSIBLE PASSWORD RESET TOOL
 * 
 * ⚠️ SECURITY WARNING ⚠️
 * DELETE THIS FILE IMMEDIATELY AFTER USE!
 * 
 * This file resets admin and staff passwords to defaults.
 * Leaving it accessible is a major security risk.
 */

// Prevent direct execution if not through web server
if (php_sapi_name() === 'cli') {
    die("This script must be run through a web browser.\n");
}

$config = require __DIR__ . '/config.php';
$submitted = $_SERVER['REQUEST_METHOD'] === 'POST';
$results = [];
$errors = [];

if ($submitted) {
    try {
        // Connect to database
        $mysqli = new mysqli(
            $config['database']['host'],
            $config['database']['username'],
            $config['database']['password'],
            $config['database']['db_name'],
            $config['database']['port']
        );

        if ($mysqli->connect_error) {
            throw new Exception("Database connection failed: " . $mysqli->connect_error);
        }

        // Reset Admin Password
        $adminPass = 'admin1234';
        $adminHash = password_hash($adminPass, PASSWORD_DEFAULT);
        $stmt = $mysqli->prepare("UPDATE admin_users SET password_hash = ? WHERE email = 'admin@coffeeshop.com'");
        $stmt->bind_param("s", $adminHash);
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            $results['admin'] = [
                'status' => 'success',
                'message' => "Admin password reset successfully",
                'rows' => $stmt->affected_rows
            ];
            
            // Verify the password works
            $verify = $mysqli->query("SELECT password_hash FROM admin_users WHERE email = 'admin@coffeeshop.com'");
            $row = $verify->fetch_assoc();
            $results['admin']['verified'] = password_verify($adminPass, $row['password_hash']);
        } else {
            $results['admin'] = [
                'status' => 'warning',
                'message' => "Admin user not found in database",
                'rows' => 0
            ];
        }

        // Reset Staff Password
        $staffPass = 'staff1234';
        $staffHash = password_hash($staffPass, PASSWORD_DEFAULT);
        $stmt = $mysqli->prepare("UPDATE staff SET password_hash = ? WHERE username = 'sokha_barista'");
        $stmt->bind_param("s", $staffHash);
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            $results['staff'] = [
                'status' => 'success',
                'message' => "Staff password reset successfully",
                'rows' => $stmt->affected_rows
            ];
            
            // Verify the password works
            $verify = $mysqli->query("SELECT password_hash FROM staff WHERE username = 'sokha_barista'");
            $row = $verify->fetch_assoc();
            $results['staff']['verified'] = password_verify($staffPass, $row['password_hash']);
        } else {
            $results['staff'] = [
                'status' => 'warning',
                'message' => "Staff user not found in database",
                'rows' => 0
            ];
        }

        $mysqli->close();

    } catch (Exception $e) {
        $errors[] = $e->getMessage();
    }
}

// Get current database status
$dbStatus = [];
try {
    $mysqli = new mysqli(
        $config['database']['host'],
        $config['database']['username'],
        $config['database']['password'],
        $config['database']['db_name'],
        $config['database']['port']
    );

    if ($mysqli->connect_error) {
        $dbStatus['error'] = "Cannot connect: " . $mysqli->connect_error;
    } else {
        $adminCheck = $mysqli->query("SELECT email, name FROM admin_users WHERE email = 'admin@coffeeshop.com'");
        $staffCheck = $mysqli->query("SELECT username, full_name FROM staff WHERE username = 'sokha_barista'");
        
        $dbStatus['admin_exists'] = $adminCheck && $adminCheck->num_rows > 0;
        $dbStatus['staff_exists'] = $staffCheck && $staffCheck->num_rows > 0;
        
        if ($dbStatus['admin_exists']) {
            $dbStatus['admin_data'] = $adminCheck->fetch_assoc();
        }
        if ($dbStatus['staff_exists']) {
            $dbStatus['staff_data'] = $staffCheck->fetch_assoc();
        }
        
        $mysqli->close();
    }
} catch (Exception $e) {
    $dbStatus['error'] = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Tool</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .warning {
            background: #dc3545;
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            border: 3px solid #c82333;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }
        .warning h2 {
            margin-bottom: 10px;
            font-size: 24px;
        }
        .warning p {
            font-size: 16px;
            line-height: 1.6;
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
            font-size: 28px;
        }
        .status {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .status.success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        .status.error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        .status.info {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
        }
        .status.warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
        }
        .btn {
            background: #667eea;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #5568d3;
        }
        .credentials {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            border-left: 4px solid #667eea;
            margin: 20px 0;
        }
        .credentials h3 {
            color: #333;
            margin-bottom: 15px;
        }
        .credential-item {
            margin-bottom: 10px;
            padding: 10px;
            background: white;
            border-radius: 3px;
        }
        .credential-item strong {
            display: inline-block;
            width: 120px;
            color: #667eea;
        }
        .credential-item code {
            background: #e9ecef;
            padding: 3px 8px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
        }
        .db-info {
            background: #e7f3ff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .db-info h3 {
            color: #0066cc;
            margin-bottom: 10px;
        }
        .checkmark {
            color: #28a745;
            font-weight: bold;
        }
        .crossmark {
            color: #dc3545;
            font-weight: bold;
        }
        .delete-instructions {
            background: #fff3cd;
            border: 2px solid #ffc107;
            padding: 20px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .delete-instructions h3 {
            color: #856404;
            margin-bottom: 10px;
        }
        .delete-instructions ol {
            margin-left: 20px;
            color: #856404;
        }
        .delete-instructions li {
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="warning">
            <h2>⚠️ SECURITY WARNING ⚠️</h2>
            <p><strong>DELETE THIS FILE IMMEDIATELY AFTER USE!</strong></p>
            <p>This file (web_reset.php) allows anyone to reset your passwords. Leaving it on your server is a major security risk. Delete it as soon as you've successfully logged in.</p>
        </div>

        <div class="card">
            <h1>🔐 Password Reset Tool</h1>
            
            <?php if (!empty($dbStatus['error'])): ?>
                <div class="status error">
                    <strong>Database Connection Error:</strong><br>
                    <?php echo htmlspecialchars($dbStatus['error']); ?>
                </div>
            <?php else: ?>
                <div class="db-info">
                    <h3>Current Database Status:</h3>
                    <p>
                        Admin User (admin@coffeeshop.com): 
                        <?php echo $dbStatus['admin_exists'] ? '<span class="checkmark">✓ Found</span>' : '<span class="crossmark">✗ Not Found</span>'; ?>
                        <?php if ($dbStatus['admin_exists']): ?>
                            - <?php echo htmlspecialchars($dbStatus['admin_data']['name']); ?>
                        <?php endif; ?>
                    </p>
                    <p>
                        Staff User (sokha_barista): 
                        <?php echo $dbStatus['staff_exists'] ? '<span class="checkmark">✓ Found</span>' : '<span class="crossmark">✗ Not Found</span>'; ?>
                        <?php if ($dbStatus['staff_exists']): ?>
                            - <?php echo htmlspecialchars($dbStatus['staff_data']['full_name']); ?>
                        <?php endif; ?>
                    </p>
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <?php foreach ($errors as $error): ?>
                    <div class="status error">
                        <strong>Error:</strong> <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if ($submitted && !empty($results)): ?>
                <div class="status success">
                    <strong>✓ Password Reset Complete!</strong>
                </div>

                <?php foreach ($results as $type => $result): ?>
                    <div class="status <?php echo $result['status']; ?>">
                        <strong><?php echo ucfirst($type); ?>:</strong> <?php echo $result['message']; ?>
                        (<?php echo $result['rows']; ?> row(s) affected)
                        <?php if (isset($result['verified'])): ?>
                            <br>Password Verification: <?php echo $result['verified'] ? '<span class="checkmark">✓ SUCCESS</span>' : '<span class="crossmark">✗ FAILED</span>'; ?>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

                <div class="credentials">
                    <h3>🎉 Use These Credentials to Login:</h3>
                    
                    <div class="credential-item">
                        <strong>Admin Login:</strong><br>
                        Email: <code>admin@coffeeshop.com</code><br>
                        Password: <code>admin1234</code>
                    </div>
                    
                    <div class="credential-item">
                        <strong>Staff Login:</strong><br>
                        Username: <code>sokha_barista</code><br>
                        Password: <code>staff1234</code>
                    </div>
                </div>

                <div class="delete-instructions">
                    <h3>⚠️ NEXT STEPS - CRITICAL!</h3>
                    <ol>
                        <li><strong>Test the login</strong> - Go to your website and try logging in with the credentials above</li>
                        <li><strong>DELETE THIS FILE</strong> - Go to your hosting file manager or FTP and delete <code>web_reset.php</code></li>
                        <li><strong>Change your password</strong> - After logging in, change the default password to something secure</li>
                    </ol>
                </div>

                <div style="margin-top: 20px; text-align: center;">
                    <a href="login" style="display: inline-block; background: #28a745; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;">
                        Go to Login Page
                    </a>
                </div>

            <?php else: ?>
                <div class="status info">
                    <strong>What This Tool Does:</strong><br>
                    This will reset the passwords for both admin and staff accounts to their default values:
                    <ul style="margin-top: 10px; margin-left: 20px;">
                        <li>Admin (admin@coffeeshop.com) → password: admin1234</li>
                        <li>Staff (sokha_barista) → password: staff1234</li>
                    </ul>
                </div>

                <form method="POST">
                    <button type="submit" class="btn">🔄 Reset Passwords Now</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
