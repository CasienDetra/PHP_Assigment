<?php

namespace App\Controllers;

use App;

class AdminController
{
    protected $db;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
            return redirect('login');
        }
        $this->db = App::get('database');
    }

    public function index()
    {
        $items = $this->db->query("SELECT * FROM menu_items ORDER BY category, name")->get();
        return view('admin/dashboard', ['items' => $items]);
    }

    public function create()
    {
        return view('admin/create_menu');
    }

    public function store()
    {
        $name = $_POST['name'];
        $category = $_POST['category'];
        $price_usd = $_POST['price_usd'];
        $price_khr = $_POST['price_khr'];
        
        $image_path = null;

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['image'];
            
            // Check size (5MB = 5 * 1024 * 1024 bytes)
            if ($file['size'] > 5 * 1024 * 1024) {
                 die('File too large (Max 5MB)');
            }

            // Check type
            $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($file['type'], $allowed)) {
                die('Invalid file type. Only JPG, PNG, GIF, WEBP allowed.');
            }

            // Generate unique name
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid('img_', true) . '.' . $ext;
            $destination = 'public/uploads/' . $filename;
            
            // Move file
            // Make sure public/uploads exists
            if (!is_dir('public/uploads')) {
                mkdir('public/uploads', 0755, true);
            }

            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $image_path = '/' . $destination;
            } else {
                die('Failed to upload file.');
            }
        }

        $this->db->query(
            "INSERT INTO menu_items (name, category, price_usd, price_khr, image_path) VALUES (?, ?, ?, ?, ?)",
            [$name, $category, $price_usd, $price_khr, $image_path]
        );

        return redirect('admin/dashboard');
    }

    public function edit()
    {
        $id = $_GET['id'];
        $item = $this->db->query("SELECT * FROM menu_items WHERE id = ?", [$id])->find();
        
        if (!$item) {
            return redirect('admin/dashboard');
        }
        
        return view('admin/edit_menu', ['item' => $item]);
    }

    public function update()
    {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $category = $_POST['category'];
        $price_usd = $_POST['price_usd'];
        $price_khr = $_POST['price_khr'];
        
        $image_path = $_POST['current_image'];

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['image'];
            
            // Check size (5MB = 5 * 1024 * 1024 bytes)
            if ($file['size'] > 5 * 1024 * 1024) {
                 die('File too large (Max 5MB)');
            }

            // Check type
            $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($file['type'], $allowed)) {
                die('Invalid file type. Only JPG, PNG, GIF, WEBP allowed.');
            }

            // Generate unique name
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid('img_', true) . '.' . $ext;
            $destination = 'public/uploads/' . $filename;
            
            // Make sure public/uploads exists
            if (!is_dir('public/uploads')) {
                mkdir('public/uploads', 0755, true);
            }

            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $image_path = '/' . $destination;
            } else {
                die('Failed to upload file.');
            }
        }

        $this->db->query(
            "UPDATE menu_items SET name = ?, category = ?, price_usd = ?, price_khr = ?, image_path = ? WHERE id = ?",
            [$name, $category, $price_usd, $price_khr, $image_path, $id]
        );

        return redirect('admin/dashboard');
    }

    public function delete()
    {
        $id = $_POST['id'];
        $this->db->query("DELETE FROM menu_items WHERE id = ?", [$id]);
        return redirect('admin/dashboard');
    }

    // Admin user management
    public function manageAdmins()
    {
        $admins = $this->db->query("SELECT * FROM admin_users ORDER BY created_at DESC")->get();
        return view('admin/manage_admins', ['admins' => $admins]);
    }

    public function createAdmin()
    {
        return view('admin/create_admin');
    }

    public function storeAdmin()
    {
        $email = $_POST['email'];
        $name = $_POST['name'];
        $password = $_POST['password'];
        
        // Check if email already exists
        $existing = $this->db->query("SELECT * FROM admin_users WHERE email = ?", [$email])->find();
        if ($existing) {
            die('Email already exists');
        }
        
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        
        $this->db->query(
            "INSERT INTO admin_users (email, password_hash, name) VALUES (?, ?, ?)",
            [$email, $password_hash, $name]
        );

        return redirect('admin/manage-admins');
    }

    public function editAdmin()
    {
        $id = $_GET['id'];
        $admin = $this->db->query("SELECT * FROM admin_users WHERE id = ?", [$id])->find();
        
        if (!$admin) {
            return redirect('admin/manage-admins');
        }
        
        return view('admin/edit_admin', ['admin' => $admin]);
    }

    public function updateAdmin()
    {
        $id = $_POST['id'];
        $email = $_POST['email'];
        $name = $_POST['name'];
        
        // Check if email already exists for another admin
        $existing = $this->db->query("SELECT * FROM admin_users WHERE email = ? AND id != ?", [$email, $id])->find();
        if ($existing) {
            die('Email already exists');
        }
        
        // Update password if provided
        if (!empty($_POST['password'])) {
            $password_hash = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $this->db->query(
                "UPDATE admin_users SET email = ?, name = ?, password_hash = ? WHERE id = ?",
                [$email, $name, $password_hash, $id]
            );
        } else {
            $this->db->query(
                "UPDATE admin_users SET email = ?, name = ? WHERE id = ?",
                [$email, $name, $id]
            );
        }

        return redirect('admin/manage-admins');
    }

    public function deleteAdmin()
    {
        $id = $_POST['id'];
        
        // Prevent deleting yourself
        if ($id == $_SESSION['user_id']) {
            die('Cannot delete your own account');
        }
        
        $this->db->query("DELETE FROM admin_users WHERE id = ?", [$id]);
        return redirect('admin/manage-admins');
    }

    // Staff user management
    public function manageStaff()
    {
        $staff = $this->db->query("SELECT * FROM staff ORDER BY created_at DESC")->get();
        return view('admin/manage_staff', ['staff' => $staff]);
    }

    public function createStaff()
    {
        return view('admin/create_staff');
    }

    public function storeStaff()
    {
        $username = $_POST['username'];
        $full_name = $_POST['full_name'];
        $role = $_POST['role'];
        $phone = $_POST['phone'];
        $password = $_POST['password'];
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        // Check if username already exists
        $existing = $this->db->query("SELECT * FROM staff WHERE username = ?", [$username])->find();
        if ($existing) {
            die('Username already exists');
        }
        
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        
        $this->db->query(
            "INSERT INTO staff (username, password_hash, full_name, role, phone, is_active) VALUES (?, ?, ?, ?, ?, ?)",
            [$username, $password_hash, $full_name, $role, $phone, $is_active]
        );

        return redirect('admin/manage-staff');
    }

    public function editStaff()
    {
        $id = $_GET['id'];
        $staff = $this->db->query("SELECT * FROM staff WHERE id = ?", [$id])->find();
        
        if (!$staff) {
            return redirect('admin/manage-staff');
        }
        
        return view('admin/edit_staff', ['staff' => $staff]);
    }

    public function updateStaff()
    {
        $id = $_POST['id'];
        $username = $_POST['username'];
        $full_name = $_POST['full_name'];
        $role = $_POST['role'];
        $phone = $_POST['phone'];
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        // Check if username already exists for another staff
        $existing = $this->db->query("SELECT * FROM staff WHERE username = ? AND id != ?", [$username, $id])->find();
        if ($existing) {
            die('Username already exists');
        }
        
        // Update password if provided
        if (!empty($_POST['password'])) {
            $password_hash = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $this->db->query(
                "UPDATE staff SET username = ?, full_name = ?, role = ?, phone = ?, is_active = ?, password_hash = ? WHERE id = ?",
                [$username, $full_name, $role, $phone, $is_active, $password_hash, $id]
            );
        } else {
            $this->db->query(
                "UPDATE staff SET username = ?, full_name = ?, role = ?, phone = ?, is_active = ? WHERE id = ?",
                [$username, $full_name, $role, $phone, $is_active, $id]
            );
        }

        return redirect('admin/manage-staff');
    }

    public function deleteStaff()
    {
        $id = $_POST['id'];
        $this->db->query("DELETE FROM staff WHERE id = ?", [$id]);
        return redirect('admin/manage-staff');
    }

    public function toggleStaffStatus()
    {
        $id = $_POST['id'];
        $staff = $this->db->query("SELECT is_active FROM staff WHERE id = ?", [$id])->find();
        
        if ($staff) {
            $new_status = $staff['is_active'] ? 0 : 1;
            $this->db->query("UPDATE staff SET is_active = ? WHERE id = ?", [$new_status, $id]);
        }
        
        return redirect('admin/manage-staff');
    }
}
