<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffee POS</title>
    <link rel="stylesheet" href="/public/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="/" class="brand"> Coffee Phum </a>
            <div class="nav-links">
                <?php if (isset($_SESSION['user_id'])) { ?>
                   <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin') { ?>
                       <a href="/admin/dashboard">Menu</a>
                       <a href="/admin/manage-staff">Staff</a>
                       <a href="/admin/manage-admins">Admins</a>
                   <?php } ?>
                   <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'staff') { ?>
                       <a href="/staff/pos">POS</a>
                       <a href="/staff/orders">Orders</a>
                       <a href="#" onclick="toggleCart(); return false;" class="cart-nav-link" id="cart-nav-icon">
                           <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                               <circle cx="9" cy="21" r="1"></circle>
                               <circle cx="20" cy="21" r="1"></circle>
                               <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                           </svg>
                           <span class="cart-nav-badge" id="cart-nav-badge">0</span>
                       </a>
                   <?php } ?>
                   <a href="/logout">Logout</a>
                <?php } ?>
            </div>
        </div>
    </nav>
    <div class="container main-content">
