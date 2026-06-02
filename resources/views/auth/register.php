<?php require BASE_PATH . '/resources/views/layout/header.php'; ?>

<?php
$errors = $errors ?? [];
$old = $old ?? [];
?>

<div class="auth-wrapper">

    <div class="auth-card">

        <h1>Create Account ✨</h1>
        <p class="subtitle">Join your Media Library today</p>

        <form method="post" action="?page=register-submit">

            <!-- NAME -->
            <div class="form-group">
                <input type="text"
                       name="name"
                       placeholder="Full Name"
                       value="<?= htmlspecialchars(is_array($old['name'] ?? '') ? implode(', ', $old['name']) : ($old['name'] ?? '')) ?>"
                       class="<?= !empty($errors['name']) ? 'input-error' : '' ?>">

                <?php if (!empty($errors['name'])): ?>
                    <div class="error-text">
                        <?= htmlspecialchars(is_array($errors['name']) ? implode(', ', $errors['name']) : $errors['name']) ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- EMAIL -->
            <div class="form-group">
                <input type="email"
                       name="email"
                       placeholder="Email Address"
                       value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                       class="<?= !empty($errors['email']) ? 'input-error' : '' ?>">

                <?php if (!empty($errors['email'])): ?>
                    <div class="error-text">
                        <?= htmlspecialchars(is_array($errors['email']) ? implode(', ', $errors['email']) : $errors['email']) ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- PASSWORD -->
            <div class="form-group">
                <input type="password"
                       name="password"
                       placeholder="Password"
                       class="<?= !empty($errors['password']) ? 'input-error' : '' ?>">

                <?php if (!empty($errors['password'])): ?>
                    <div class="error-text">
                        <?= htmlspecialchars(is_array($errors['password']) ? implode(', ', $errors['password']) : $errors['password']) ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- CONFIRM PASSWORD -->
            <div class="form-group">
                <input type="password"
                       name="confirm_password"
                       placeholder="Confirm Password"
                       class="<?= !empty($errors['confirm_password']) ? 'input-error' : '' ?>">

                <?php if (!empty($errors['confirm_password'])): ?>
                    <div class="error-text">
                        <?= htmlspecialchars(is_array($errors['confirm_password']) ? implode(', ', $errors['confirm_password']) : $errors['confirm_password']) ?>
                    </div>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn-primary">
                Create Account
            </button>

        </form>

        <p class="auth-footer">
            Already have an account?
            <a href="<?= BASE_URL ?>/Public/index.php?page=login">Login</a>
        </p>

    </div>

</div>

<?php require BASE_PATH . '/resources/views/layout/footer.php'; ?>
<style>

  /* =========================
   AUTH WRAPPER
========================= */
.auth-wrapper {
    min-height: calc(100vh - 80px);
    display: flex;
    justify-content: center;
    align-items: flex-start;
    padding-top: 40px;
    background: #f5f6fa;
    font-family: 'Segoe UI', sans-serif;
}

/* =========================
   CARD
========================= */
.auth-card {
    width: 100%;
    max-width: 420px;
    background: #fff;
    padding: 35px;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    border: 1px solid #eee;
    text-align: center;
}

/* =========================
   TITLE
========================= */
.auth-card h1 {
    font-size: 26px;
    margin-bottom: 6px;
    color: #222;
}

/* =========================
   SUBTITLE
========================= */
.subtitle {
    font-size: 14px;
    color: #666;
    margin-bottom: 22px;
}

/* =========================
   FORM GROUP
========================= */
.form-group {
    margin-bottom: 16px;
    text-align: left;
}

/* =========================
   INPUTS
========================= */
.form-group input {
    width: 100%;
    padding: 12px 14px;
    border: 1px solid #ddd;
    border-radius: 10px;
    font-size: 14px;
    outline: none;
    transition: all 0.3s ease;
    box-sizing: border-box;
    background: #fff;
}

/* INPUT FOCUS */
.form-group input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102,126,234,0.2);
}

/* =========================
   ERROR INPUT
========================= */
.input-error {
    border: 1px solid red !important;
    background: #fff5f5;
}

/* =========================
   ERROR TEXT
========================= */
.error-text {
    color: red;
    font-size: 13px;
    margin-top: 5px;
}

/* =========================
   BUTTON
========================= */
.btn-primary {
    width: 100%;
    padding: 12px;
    background: brown;
    color: white;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 15px;
    cursor: pointer;
    transition: 0.3s ease;
}

/* BUTTON HOVER */
.btn-primary:hover {
    background: #5a67d8;
    transform: translateY(-1px);
}

/* =========================
   FOOTER
========================= */
.auth-footer {
    margin-top: 18px;
    font-size: 14px;
    color:brown;
}

.auth-footer a {
    color:brown;
    text-decoration: none;
    font-weight: 500;
}

.auth-footer a:hover {
    text-decoration: underline;
}

/* =========================
   RESPONSIVE
========================= */
@media (max-width: 480px) {

    .auth-wrapper {
        padding: 20px;
        padding-top: 30px;
    }

    .auth-card {
        padding: 25px;
    }

    .auth-card h1 {
        font-size: 22px;
    }
}
</style>