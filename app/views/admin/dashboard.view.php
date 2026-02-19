<?php require 'views/partials/head.php'; ?>

<div class="dashboard-header">
    <h1>Menu Management</h1>
    <a href="/admin/menu/create" class="btn btn-primary">+ Add New Item</a>
</div>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price (USD)</th>
                <th>Price (KHR)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item) { ?>
            <tr>
                <td>
                    <?php if ($item['image_path']) { ?>
                        <img src="<?= $item['image_path'] ?>" alt="<?= $item['name'] ?>" class="item-thumb">
                    <?php } else { ?>
                        <div class="no-image">No Img</div>
                    <?php } ?>
                </td>
                <td><?= $item['name'] ?></td>
                <td><span class="badge"><?= $item['category'] ?></span></td>
                <td>$<?= number_format($item['price_usd'], 2) ?></td>
                <td>៛<?= number_format($item['price_khr'], 0) ?></td>
                <td>
                    <form action="/admin/menu/delete" method="POST" onsubmit="return confirm('Are you sure?');">
                        <input type="hidden" name="id" value="<?= $item['id'] ?>">
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php require 'views/partials/footer.php'; ?>
