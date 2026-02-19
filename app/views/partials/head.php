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
                   <a href="/logout">Logout</a>
                <?php } ?>
            </div>
        </div>
    </nav>
    <div class="container main-content">
