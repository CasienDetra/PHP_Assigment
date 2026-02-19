<?php

namespace App\Controllers;

use App;

class AuthController
{
    public function login()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['user_type'])) {
            if ($_SESSION['user_type'] === 'admin') {
                return redirect('admin/dashboard');
            } else {
                return redirect('staff/pos');
            }
        }
        return view('auth/login');
    }

    public function authenticate()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $emailOrUsername = $_POST['username'];
        $password = $_POST['password'];

        // Check Admin
        $db = App::get('database');
        
        // Check if admin (email based on schema) - Schema says admin uses email
        $admin = $db->query("SELECT * FROM admin_users WHERE email = ?", [$emailOrUsername])->find();

        if ($admin) {
            if (password_verify($password, $admin['password_hash'])) {
                $_SESSION['user_id'] = $admin['id'];
                $_SESSION['user_type'] = 'admin';
                $_SESSION['user_name'] = $admin['name'];
                return redirect('admin/dashboard');
            } else {
                 error_log("Admin login failed: password mismatch for $emailOrUsername");
            }
        } else {
             error_log("Admin login failed: user not found checks for $emailOrUsername");
        }

        // Check Staff (username based on schema)
        $staff = $db->query("SELECT * FROM staff WHERE username = ?", [$emailOrUsername])->find();

        if ($staff) {
            // Check if active
            if (!$staff['is_active']) {
                return view('auth/login', ['error' => 'Account is deactivated.']);
            }

            if (password_verify($password, $staff['password_hash'])) {
                $_SESSION['user_id'] = $staff['id'];
                $_SESSION['user_type'] = 'staff';
                $_SESSION['user_name'] = $staff['full_name'];
                return redirect('staff/pos');
            } else {
                error_log("Staff login failed: password mismatch for $emailOrUsername");
            }
        } else {
             error_log("Staff login failed: user not found checks for $emailOrUsername");
        }

        return view('auth/login', ['error' => 'Invalid credentials.']);
    }

    public function logout()
    {
        session_start();
        session_destroy();
        return redirect('login');
    }
}
