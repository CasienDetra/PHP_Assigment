<?php require 'views/partials/head.php'; ?>

<div class="dashboard-header">
    <h1>Staff Management</h1>
    <div>
        <a href="/admin/dashboard" class="btn btn-secondary">← Back to Dashboard</a>
        <a href="/admin/staff/create" class="btn btn-primary">+ Add New Staff</a>
    </div>
</div>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Full Name</th>
                <th>Role</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($staff)) { ?>
                <tr>
                    <td colspan="8" style="text-align: center; color: var(--text-muted);">No staff members yet</td>
                </tr>
            <?php } else { ?>
                <?php foreach ($staff as $member) { ?>
                <tr>
                    <td><?= $member['id'] ?></td>
                    <td><?= $member['username'] ?></td>
                    <td><?= $member['full_name'] ?></td>
                    <td><span class="badge"><?= $member['role'] ?></span></td>
                    <td><?= $member['phone'] ?? 'N/A' ?></td>
                    <td>
                        <form action="/admin/staff/toggle-status" method="POST" style="display: inline-block;">
                            <input type="hidden" name="id" value="<?= $member['id'] ?>">
                            <button type="submit" class="btn <?= $member['is_active'] ? 'btn-success' : 'btn-secondary' ?> btn-sm">
                                <?= $member['is_active'] ? 'Active' : 'Inactive' ?>
                            </button>
                        </form>
                    </td>
                    <td><?= date('M d, Y', strtotime($member['created_at'])) ?></td>
                    <td>
                        <a href="/admin/staff/edit?id=<?= $member['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                        <form action="/admin/staff/delete" method="POST" onsubmit="return confirm('Are you sure you want to delete this staff member?');" style="display: inline-block;">
                            <input type="hidden" name="id" value="<?= $member['id'] ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php } ?>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php require 'views/partials/footer.php'; ?>
