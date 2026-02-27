<?php

namespace App\Controllers;

use App;

class StaffController
{
    protected $db;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (! isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'staff') {
            return redirect('login');
        }
        $this->db = App::get('database');
    }

    public function index()
    {
        $items = $this->db->query('SELECT * FROM menu_items WHERE is_available = 1')->get();

        // Group by category for better UI
        $menu = [];
        foreach ($items as $item) {
            $menu[$item['category']][] = $item;
        }

        return view('staff/pos', ['menu' => $menu]);
    }

    public function storeOrder()
    {
        // Expecting JSON payload or form data. let's use form data with hidden inputs or JSON.
        // For detailed pos, Javascript usually sends JSON.

        $input = json_decode(file_get_contents('php://input'), true);

        if (! $input) {
            // Fallback to POST if not json
            exit('Invalid data');
        }

        $staff_id = $_SESSION['user_id'];
        $order_type = $input['order_type'];
        $total_usd = $input['total_usd'];
        $total_khr = $input['total_khr'];
        $items = $input['items']; // Array of {id, qty, price_usd, price_khr}

        // Insert Order
        $this->db->query(
            "INSERT INTO orders (staff_id, order_type, total_usd, total_khr, status) VALUES (?, ?, ?, ?, 'Pending')",
            [$staff_id, $order_type, $total_usd, $total_khr]
        );

        // Get inserted ID. Database class doesn't have lastInsertId method yet.
        // We need to modify Database.php to get insert_id.
        // Or we can use $this->db->connection->insert_id directly since property is public.

        $order_id = $this->db->connection->insert_id;

        // Insert Order Items
        foreach ($items as $item) {
            $this->db->query(
                'INSERT INTO order_items (order_id, menu_item_id, quantity, item_price_usd, item_price_khr) VALUES (?, ?, ?, ?, ?)',
                [$order_id, $item['id'], $item['qty'], $item['price_usd'], $item['price_khr']]
            );
        }

        // Ensure no previous output (warnings, etc.) corrupts the JSON
        if (ob_get_length()) {
            ob_clean();
        }
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'order_id' => $order_id,
            'total_usd' => $total_usd,
            'total_khr' => $total_khr,
            'items' => $items,
            'date' => date('Y-m-d H:i:s'),
        ]);
        exit;
    }

    public function viewOrders()
    {
        // Get all orders with staff info
        $orders = $this->db->query('
            SELECT o.*, s.full_name as staff_name 
            FROM orders o 
            LEFT JOIN staff s ON o.staff_id = s.id 
            ORDER BY o.created_at DESC
        ')->get();

        return view('staff/orders', ['orders' => $orders]);
    }

    public function viewOrderDetails()
    {
        $order_id = $_GET['id'];

        // Get order info
        $order = $this->db->query('
            SELECT o.*, s.full_name as staff_name 
            FROM orders o 
            LEFT JOIN staff s ON o.staff_id = s.id 
            WHERE o.id = ?
        ', [$order_id])->find();

        if (! $order) {
            return redirect('staff/orders');
        }

        // Get order items
        $items = $this->db->query('
            SELECT oi.*, m.name as item_name, m.image_path
            FROM order_items oi
            LEFT JOIN menu_items m ON oi.menu_item_id = m.id
            WHERE oi.order_id = ?
        ', [$order_id])->get();

        return view('staff/order_details', ['order' => $order, 'items' => $items]);
    }
}
