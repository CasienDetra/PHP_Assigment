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

    public function delete()
    {
        $id = $_POST['id'];
        $this->db->query("DELETE FROM menu_items WHERE id = ?", [$id]);
        return redirect('admin/dashboard');
    }
}
