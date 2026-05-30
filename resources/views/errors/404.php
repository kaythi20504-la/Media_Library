<?php $title = "404 - Page Not Found"; ?>

<style>
    body {
        margin: 0;
        font-family: 'Segoe UI', sans-serif;
        background: linear-gradient(135deg, #0f172a, #1e293b);
        color: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .error-container {
        text-align: center;
        max-width: 520px;
        padding: 40px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 16px;
        backdrop-filter: blur(10px);
        box-shadow: 0 10px 40px rgba(0,0,0,0.5);
        animation: fadeIn 0.6s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .error-icon {
        font-size: 50px;
        margin-bottom: 10px;
    }

    .error-code {
        font-size: 100px;
        font-weight: bold;
        background: linear-gradient(90deg, #3b82f6, #06b6d4);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
    }

    .error-title {
        font-size: 26px;
        margin: 10px 0;
    }

    .error-message {
        color: #cbd5f5;
        margin-bottom: 30px;
        line-height: 1.5;
    }

    .btn {
        display: inline-block;
        padding: 12px 22px;
        background: linear-gradient(90deg, #3b82f6, #06b6d4);
        color: white;
        text-decoration: none;
        border-radius: 10px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(59,130,246,0.5);
    }

    .small-link {
        display: block;
        margin-top: 15px;
        color: #94a3b8;
        font-size: 14px;
        text-decoration: none;
    }

    .small-link:hover {
        color: #fff;
    }
</style>

<div class="error-container">
    <div class="error-icon">🚫</div>

    <div class="error-code">404</div>

    <div class="error-title">Page Not Found</div>

    <div class="error-message">
        Sorry, the page you’re looking for doesn’t exist <br>
        or may have been moved.
    </div>

    <a href="<?= BASE_URL ?>/Public/index.php?page=home" class="btn">
        ⬅️ Back to Home
    </a>

    <a href="javascript:history.back()" class="small-link">
        or go back to previous page
    </a>
</div>