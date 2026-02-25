<?php require 'views/partials/head.php'; ?>

<div class="header-with-back">
    <a href="/admin/dashboard" class="back-link">← Back to Dashboard</a>
    <h1>Add Menu Item</h1>
</div>

<div class="card form-card">
    <form action="/admin/menu/store" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Item Name</label>
            <input type="text" name="name" required placeholder="Ex: Iced Latte">
        </div>

        <div class="form-group">
            <label>Category</label>
              <div class="header-with-back">
                <a href="/admin/dashboard" class="back-link">Add New Category</a>
              </div>
            <select name="category" required>
                <option value="Coffee"> Coffee</option>
                <option value="Non-Coffee"> Non-Coffee</option>
                <option value="Food"> Food</option>
                <option value="Dessert"> Dessert</option>
            </select>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Price (USD)</label>
                <input type="number" step="0.01" name="price_usd" required placeholder="2.50">
            </div>
            <div class="form-group">
                <label>Price (KHR)</label>
                <input type="number" step="100" name="price_khr" required placeholder="10000">
            </div>
        </div>

        <div class="form-group">
            <label>Image (Max 5MB)</label>
            <input type="file" name="image" accept="image/*" required>
        </div>

        <button type="submit" class="btn btn-primary">Add Item</button>
    </form>
</div>

<?php require 'views/partials/footer.php'; ?>
