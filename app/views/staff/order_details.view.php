<?php require 'views/partials/head.php'; ?>

<div class="header-with-back">
    <a href="/staff/orders" class="back-link">← Back to Orders</a>
    <h1>Order #<?= $order['id'] ?></h1>
</div>

<div class="card">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
        <div>
            <p><strong>Staff:</strong> <?= $order['staff_name'] ?? 'N/A' ?></p>
            <p><strong>Order Type:</strong> <span class="badge"><?= $order['order_type'] ?></span></p>
            <p><strong>Status:</strong> <span class="badge <?= $order['status'] === 'Completed' ? 'badge-success' : 'badge-warning' ?>"><?= $order['status'] ?></span></p>
        </div>
        <div>
            <p><strong>Date:</strong> <?= date('M d, Y H:i', strtotime($order['created_at'])) ?></p>
            <p><strong>Total (USD):</strong> $<?= number_format($order['total_usd'], 2) ?></p>
            <p><strong>Total (KHR):</strong> ៛<?= number_format($order['total_khr'], 0) ?></p>
        </div>
    </div>

    <h3 style="margin-top: 30px; margin-bottom: 15px; border-bottom: 1px solid #4d4d4d; padding-bottom: 10px;">Order Items</h3>
    
    <table class="table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Price (USD)</th>
                <th>Price (KHR)</th>
                <th>Subtotal (USD)</th>
                <th>Subtotal (KHR)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item) { ?>
            <tr>
                <td>
                    <?php if ($item['image_path']) { ?>
                        <img src="<?= $item['image_path'] ?>" alt="<?= $item['item_name'] ?>" class="item-thumb">
                    <?php } else { ?>
                        <div class="no-image">No Img</div>
                    <?php } ?>
                </td>
                <td><?= $item['item_name'] ?></td>
                <td><?= $item['quantity'] ?></td>
                <td>$<?= number_format($item['item_price_usd'], 2) ?></td>
                <td>៛<?= number_format($item['item_price_khr'], 0) ?></td>
                <td>$<?= number_format($item['item_price_usd'] * $item['quantity'], 2) ?></td>
                <td>៛<?= number_format($item['item_price_khr'] * $item['quantity'], 0) ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php require 'views/partials/footer.php'; ?>
