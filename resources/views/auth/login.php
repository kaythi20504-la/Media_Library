<?php require BASE_PATH . '/resources/views/layout/header.php'; ?>

<?php
$errors = $errors ?? [];
$old = $old ?? [];
?>

<div class="auth-wrapper">

    <div class="auth-card">

        <h1>Welcome Back ✨</h1>
        <p class="subtitle">Login to continue to your Media Library</p>

        <!-- GENERAL ERROR -->
        <?php if (!empty($errors['general'])): ?>
            <div class="alert alert-error">
                <?= htmlspecialchars(is_array($errors['general']) ? implode(', ', $errors['general']) : $errors['general']) ?>
            </div>
        <?php endif; ?>

        <form method="post" action="?page=login-submit">

            <!-- EMAIL -->
            <div class="form-group">
                <input type="email"
                       name="email"
                       placeholder="Email Address"
                       value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                       class="<?= !empty($errors['email']) ? 'input-error' : '' ?>">

                <?php if (!empty($errors['email'])): ?>
                    <div class="error-message">
                        ⚠ <?= htmlspecialchars(is_array($errors['email']) ? implode(', ', $errors['email']) : $errors['email']) ?>
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
                    <div class="error-message">
                        ⚠ <?= htmlspecialchars(is_array($errors['password']) ? implode(', ', $errors['password']) : $errors['password']) ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- BUTTON -->
            <button type="submit" class="btn-primary">
                Login
            </button>

        </form>

        <p class="auth-footer">
            Don't have an account?
            <a href="<?= BASE_URL ?>/Public/index.php?page=register">Register</a>
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
    padding-top: 60px;
    background: #f5f6fa;
    font-family: 'Segoe UI', sans-serif;
}

/* =========================
   AUTH CARD
========================= */
.auth-card {
    width: 100%;
    max-width: 420px;
    background: #ffffff;
    padding: 35px;
    border-radius: 18px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.12);
    text-align: center;
    border: 1px solid #eee;
}

/* =========================
   TITLE
========================= */
.auth-card h1 {
    margin-bottom: 8px;
    font-size: 28px;
    color: #222;
}

/* =========================
   SUBTITLE
========================= */
.subtitle {
    color: #666;
    font-size: 14px;
    margin-bottom: 25px;
}

/* =========================
   FORM GROUP
========================= */
.form-group {
    margin-bottom: 18px;
    text-align: left;
}

/* =========================
   INPUT FIELDS
========================= */
.form-group input {
    width: 100%;
    padding: 12px 14px;
    border: 1px solid #ddd;
    border-radius: 10px;
    outline: none;
    transition: all 0.3s ease;
    font-size: 14px;
    background: #fff;
    box-sizing: border-box;
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
    border: 1px solid #ff4d4f !important;
    background: #fff5f5 !important;
    animation: shake 0.3s;
}

/* =========================
   ERROR MESSAGE
========================= */
.error-text,
.error-message {
    margin-top: 6px;
    font-size: 13px;
    color: #ff4d4f;
    animation: fadeIn 0.3s ease-in;
}

/* =========================
   ALERT ERROR
========================= */
.alert-error {
    background: #fff1f0;
    border-left: 4px solid #ff4d4f;
    color: #a8071a;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 18px;
    font-size: 14px;
    text-align: left;
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
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
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
    color: brown;
}

.auth-footer a {
    color: brown;
    text-decoration: none;
    font-weight: 500;
}

.auth-footer a:hover {
    text-decoration: underline;
}

/* =========================
   ANIMATIONS
========================= */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-4px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes shake {
    0% { transform: translateX(0); }
    25% { transform: translateX(-3px); }
    50% { transform: translateX(3px); }
    75% { transform: translateX(-3px); }
    100% { transform: translateX(0); }
}

/* =========================
   RESPONSIVE
========================= */
@media (max-width: 480px) {

    .auth-wrapper {
        padding: 20px;
        padding-top: 40px;
    }

    .auth-card {
        padding: 25px;
    }

    .auth-card h1 {
        font-size: 24px;
    }
}

</style>