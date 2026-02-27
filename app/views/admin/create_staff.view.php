<?php require 'views/partials/head.php'; ?>

<div class="header-with-back">
    <a href="/admin/manage-staff" class="back-link">← Back to Staff Management</a>
    <h1>Add New Staff</h1>
</div>

<div class="card form-card">
    <form action="/admin/staff/store" method="POST">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" required placeholder="e.g., john_barista">
        </div>

        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="full_name" required placeholder="e.g., John Doe">
        </div>

        <div class="form-group">
            <label>Role</label>
            <select name="role" required>
                <option value="">Select Role</option>
                <option value="Barista">Barista</option>
                <option value="Cashier">Cashier</option>
                <option value="Waiter">Waiter</option>
                <option value="Manager">Manager</option>
            </select>
        </div>

        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" placeholder="e.g., +855 12 345 678">
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required minlength="6" placeholder="Minimum 6 characters">
        </div>

        <div class="form-group">
            <label style="display: flex; align-items: center; gap: 10px;">
                <input type="checkbox" name="is_active" checked>
                <span>Active (Staff can login)</span>
            </label>
        </div>

        <button type="submit" class="btn btn-primary">Add Staff</button>
    </form>
</div>

<?php require 'views/partials/footer.php'; ?>
