<?php require BASE_PATH . '/resources/views/layout/header.php'; ?>

<div class="section catalog page">
  <div class="wrapper">

    <h1>
    <?php
    // Get values from the $filters array passed by the Service
    $category = $filters['category'] ?? null;
    $search = $filters['search'] ?? null;
    
    $title = $pageTitle;

    if (!empty($search)) {
      $title = 'Search results for "' . htmlspecialchars($search) . '"';
    }

    if (!empty($category)) {
      $title .= ' in ' . ucfirst($category);
    }

    echo $title;
    ?>
    </h1>

    <?php if (empty($catalog)): ?>

      <p>No items were found matching that search term.</p>

      <p>
        Search again or
        <a href="<?= BASE_URL ?>/index.php?page=catalog">Browse the Full Catalog.</a>
      </p>

    <?php else: ?>

      <?php require BASE_PATH . '/resources/views/partials/pagination.php'; ?>
      
      <ul class="catalog">
        <?php foreach ($catalog as $item): ?>
          <?php // Updated to use the correct Namespace for ItemView ?>
          <?= \App\Core\ItemView::render($item); ?>
        <?php endforeach; ?>
      </ul>

      <?php require BASE_PATH . '/resources/views/partials/pagination.php'; ?>

    <?php endif; ?>

  </div>
</div>

<?php require BASE_PATH . '/resources/views/layout/footer.php'; ?>