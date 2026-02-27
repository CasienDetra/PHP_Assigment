<?php require 'views/partials/head.php'; ?>

<div class="header-with-back">
    <a href="/admin/manage-staff" class="back-link">← Back to Staff Management</a>
    <h1>Edit Staff</h1>
</div>

<div class="card form-card">
    <form action="/admin/staff/update" method="POST">
        <input type="hidden" name="id" value="<?= $staff['id'] ?>">
        
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" required placeholder="e.g., john_barista" value="<?= $staff['username'] ?>">
        </div>

        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="full_name" required placeholder="e.g., John Doe" value="<?= $staff['full_name'] ?>">
        </div>

        <div class="form-group">
            <label>Role</label>
            <select name="role" required>
                <option value="">Select Role</option>
                <option value="Barista" <?= $staff['role'] === 'Barista' ? 'selected' : '' ?>>Barista</option>
                <option value="Cashier" <?= $staff['role'] === 'Cashier' ? 'selected' : '' ?>>Cashier</option>
                <option value="Waiter" <?= $staff['role'] === 'Waiter' ? 'selected' : '' ?>>Waiter</option>
                <option value="Manager" <?= $staff['role'] === 'Manager' ? 'selected' : '' ?>>Manager</option>
            </select>
        </div>

        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" placeholder="e.g., +855 12 345 678" value="<?= $staff['phone'] ?? '' ?>">
        </div>

        <div class="form-group">
            <label>New Password (leave blank to keep current)</label>
            <input type="password" name="password" minlength="6" placeholder="Leave blank to keep current password">
        </div>

        <div class="form-group">
            <label style="display: flex; align-items: center; gap: 10px;">
                <input type="checkbox" name="is_active" <?= $staff['is_active'] ? 'checked' : '' ?>>
                <span>Active (Staff can login)</span>
            </label>
        </div>

        <button type="submit" class="btn btn-primary">Update Staff</button>
    </form>
</div>

<?php require 'views/partials/footer.php'; ?>
