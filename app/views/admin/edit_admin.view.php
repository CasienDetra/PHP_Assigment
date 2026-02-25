<?php require 'views/partials/head.php'; ?>

<div class="header-with-back">
    <a href="/admin/manage-admins" class="back-link">← Back to Admin Management</a>
    <h1>Edit Admin</h1>
</div>

<div class="card form-card">
    <form action="/admin/admins/update" method="POST">
        <input type="hidden" name="id" value="<?= $admin['id'] ?>">
        
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="name" required placeholder="Ex: John Doe" value="<?= $admin['name'] ?>">
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required placeholder="admin@example.com" value="<?= $admin['email'] ?>">
        </div>

        <div class="form-group">
            <label>New Password (leave blank to keep current)</label>
            <input type="password" name="password" minlength="6" placeholder="Leave blank to keep current password">
        </div>

        <button type="submit" class="btn btn-primary">Update Admin</button>
    </form>
</div>

<?php require 'views/partials/footer.php'; ?>
