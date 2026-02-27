<?php require 'views/partials/head.php'; ?>

<div class="dashboard-header">
    <h1>Orders History</h1>
    <a href="/staff/pos" class="btn btn-primary">← Back to POS</a>
</div>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Staff</th>
                <th>Type</th>
                <th>Total (USD)</th>
                <th>Total (KHR)</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($orders)) { ?>
                <tr>
                    <td colspan="8" style="text-align: center; color: var(--text-muted);">No orders yet</td>
                </tr>
            <?php } else { ?>
                <?php foreach ($orders as $order) { ?>
                <tr>
                    <td>#<?= $order['id'] ?></td>
                    <td><?= $order['staff_name'] ?? 'N/A' ?></td>
                    <td><span class="badge"><?= $order['order_type'] ?></span></td>
                    <td>$<?= number_format($order['total_usd'], 2) ?></td>
                    <td>៛<?= number_format($order['total_khr'], 0) ?></td>
                    <td>
                        <span class="badge <?= $order['status'] === 'Completed' ? 'badge-success' : 'badge-warning' ?>">
                            <?= $order['status'] ?>
                        </span>
                    </td>
                    <td><?= date('M d, Y H:i', strtotime($order['created_at'])) ?></td>
                    <td>
                        <a href="/staff/orders/view?id=<?= $order['id'] ?>" class="btn btn-primary btn-sm">View</a>
                    </td>
                </tr>
                <?php } ?>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php require 'views/partials/footer.php'; ?>
