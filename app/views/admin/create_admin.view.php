<?php require 'views/partials/head.php'; ?>

<div class="header-with-back">
    <a href="/admin/manage-admins" class="back-link">← Back to Admin Management</a>
    <h1>Add New Admin</h1>
</div>

<div class="card form-card">
    <form action="/admin/admins/store" method="POST">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="name" required placeholder="Ex: John Doe">
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required placeholder="admin@example.com">
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required minlength="6" placeholder="Minimum 6 characters">
        </div>

        <button type="submit" class="btn btn-primary">Add Admin</button>
    </form>
</div>

<?php require 'views/partials/footer.php'; ?>
