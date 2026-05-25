<?php require BASE_PATH . '/resources/views/layout/header.php'; ?>

<div class="modern-login-container">

    <div class="modern-login-card">

        <h1>Welcome Back ✨</h1>

        <p class="subtitle">
            Login to continue to your Media Library
        </p>

        <?php if (!empty($error)): ?>
            <div class="error-box">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="post"
              action="<?= BASE_URL ?>/Public/index.php?page=login-submit">

            <div class="input-group">
                <input type="email"
                       name="email"
                       placeholder="Email Address"
                       required>
            </div>

            <div class="input-group">
                <input type="password"
                       name="password"
                       placeholder="Password"
                       required>
            </div>

            <button type="submit" class="login-btn">
                Login
            </button>

        </form>

        <p class="bottom-text">
            Don't have an account?

            <a href="<?= BASE_URL ?>/Public/index.php?page=register">
                Register
            </a>
        </p>

    </div>

</div>

<?php require BASE_PATH . '/resources/views/layout/footer.php'; ?>