<?php require 'views/partials/head.php'; ?>

<div class="auth-wrapper">
    <div class="auth-card">
        <h2>Welcome Back</h2>
        <p class="subtitle">Enter your credentials to access the POS</p>

        <?php if (isset($error)) { ?>
            <div class="alert error"><?= $error ?></div>
        <?php } ?>

        <form action="/login" method="POST">
            <div class="form-group">
                <label for="username">Email or Username</label>
                <input type="text" name="username" id="username" required placeholder="Six_Seven">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required placeholder="••••••••">
            </div>

            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>
    </div>
</div>

<?php require 'views/partials/footer.php'; ?>
