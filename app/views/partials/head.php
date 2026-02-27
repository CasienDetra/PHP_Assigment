<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffee POS</title>
    <link rel="stylesheet" href="<?= $_SERVER['REQUEST_SCHEME'] ?>://<?= $_SERVER['HTTP_HOST'] ?>/public/css/style.css">
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
                       <a href="javascript:void(0);" onclick="if(typeof toggleCart === 'function') toggleCart();" class="cart-nav-link" id="cart-nav-icon">
                           <span>Cart</span>
                           <span class="cart-nav-badge" id="cart-nav-badge">0</span>
                       </a>
                   <?php } ?>
                   <a href="/logout">Logout</a>
                <?php } ?>
            </div>
        </div>
    </nav>
    <div class="container main-content">
