<?php require BASE_PATH . '/resources/views/layout/header.php'; ?>

<div class="modern-register-container">

    <div class="modern-register-card">

        <h1>Create Account ✨</h1>

        <p class="subtitle">
            Join your Media Library today
        </p>

        <?php if (!empty($error)): ?>
            <div class="error-box">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="post"
              action="<?= BASE_URL ?>/Public/index.php?page=register-submit">

            <div class="input-group">
                <input type="text"
                       name="name"
                       placeholder="Full Name"
                       required>
            </div>

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

            <div class="input-group">
                <input type="password"
                       name="confirm_password"
                       placeholder="Confirm Password"
                       required>
            </div>

            <button type="submit" class="register-btn">
                Create Account
            </button>

        </form>

        <p class="bottom-text">
            Already have an account?
            <a href="<?= BASE_URL ?>/Public/index.php?page=login">
                Login
            </a>
        </p>

    </div>

</div>

<?php require BASE_PATH . '/resources/views/layout/footer.php'; ?>