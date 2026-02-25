<?php require 'views/partials/head.php'; ?>

<div class="dashboard-header">
    <h1>Admin User Management</h1>
    <div>
        <a href="/admin/dashboard" class="btn btn-secondary">← Back to Dashboard</a>
        <a href="/admin/admins/create" class="btn btn-primary">+ Add New Admin</a>
    </div>
</div>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($admins as $admin) { ?>
            <tr>
                <td><?= $admin['id'] ?></td>
                <td><?= $admin['name'] ?></td>
                <td><?= $admin['email'] ?></td>
                <td><?= date('M d, Y', strtotime($admin['created_at'])) ?></td>
                <td>
                    <a href="/admin/admins/edit?id=<?= $admin['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                    <?php if ($admin['id'] != $_SESSION['user_id']) { ?>
                    <form action="/admin/admins/delete" method="POST" onsubmit="return confirm('Are you sure you want to delete this admin?');" style="display: inline-block;">
                        <input type="hidden" name="id" value="<?= $admin['id'] ?>">
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                    <?php } else { ?>
                    <span class="badge">Current User</span>
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php require 'views/partials/footer.php'; ?>
