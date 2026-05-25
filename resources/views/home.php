<?php require BASE_PATH . '/resources/views/layout/header.php'; ?>

<main class="wrapper">
    <h2 class="title">May we suggest something?</h2>

    <ul class="catalog">
        <?php foreach ($random as $item): ?>
            <?= \App\Core\ItemView::render($item); ?>
        <?php endforeach; ?>
    </ul>
</main>

<?php require BASE_PATH . '/resources/views/layout/footer.php'; ?>