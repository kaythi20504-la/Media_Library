<?php $title = "500 - Server Error"; ?>

<style>
    body {
        background: #0f172a;
        color: white;
        font-family: Arial;
        padding: 40px;
    }

    .box {
        max-width: 900px;
        margin: auto;
        background: #111827;
        padding: 20px;
        border-radius: 10px;
    }

    .error {
        color: #ef4444;
        font-size: 22px;
    }

    .meta {
        color: #94a3b8;
        margin-top: 10px;
        font-size: 14px;
    }

    pre {
        background: black;
        padding: 15px;
        overflow: auto;
        margin-top: 20px;
        color: #10b981;
    }
</style>

<div class="box">
    <h1 class="error">500 - Server Error</h1>

    <p><?= htmlspecialchars($response->message ?? 'Unknown error') ?></p>

    <div class="meta">
        <p><strong>File:</strong> <?= $response->file ?? 'N/A' ?></p>
        <p><strong>Line:</strong> <?= $response->line ?? 'N/A' ?></p>
    </div>

    <?php if (!empty($response->trace)): ?>
        <pre><?= htmlspecialchars($response->trace) ?></pre>
    <?php endif; ?>
</div>