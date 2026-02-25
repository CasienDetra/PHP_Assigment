<?php require 'views/partials/head.php'; ?>

<div class="header-with-back">
    <a href="/admin/dashboard" class="back-link">← Back to Dashboard</a>
    <h1>Edit Menu Item</h1>
</div>

<div class="card form-card">
    <form action="/admin/menu/update" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $item['id'] ?>">
        <input type="hidden" name="current_image" value="<?= $item['image_path'] ?>">
        
        <div class="form-group">
            <label>Item Name</label>
            <input type="text" name="name" required placeholder="Ex: Iced Latte" value="<?= $item['name'] ?>">
        </div>

        <div class="form-group">
            <label>Category</label>
            <select name="category" required>
                <option value="Coffee" <?= $item['category'] === 'Coffee' ? 'selected' : '' ?>>Coffee</option>
                <option value="Non-Coffee" <?= $item['category'] === 'Non-Coffee' ? 'selected' : '' ?>>Non-Coffee</option>
                <option value="Food" <?= $item['category'] === 'Food' ? 'selected' : '' ?>>Food</option>
                <option value="Dessert" <?= $item['category'] === 'Dessert' ? 'selected' : '' ?>>Dessert</option>
            </select>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Price (USD)</label>
                <input type="number" step="0.01" name="price_usd" required placeholder="2.50" value="<?= $item['price_usd'] ?>">
            </div>
            <div class="form-group">
                <label>Price (KHR)</label>
                <input type="number" step="100" name="price_khr" required placeholder="10000" value="<?= $item['price_khr'] ?>">
            </div>
        </div>

        <div class="form-group">
            <label>Current Image</label>
            <?php if ($item['image_path']) { ?>
                <img src="<?= $item['image_path'] ?>" alt="<?= $item['name'] ?>" style="max-width: 200px; display: block; margin: 10px 0;">
            <?php } else { ?>
                <p>No image uploaded</p>
            <?php } ?>
        </div>

        <div class="form-group">
            <label>Change Image (Max 5MB, optional)</label>
            <input type="file" name="image" accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary">Update Item</button>
    </form>
</div>

<?php require 'views/partials/footer.php'; ?>
