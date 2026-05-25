<?php 
/** * Data comes from CatalogService::getCatalogPage 
 * Accessing variables via the 'pagination' and 'filters' keys 
 */
$current = $pagination['currentPage'] ?? 1;
$total   = $pagination['totalPages'] ?? 1;
$section = $filters['category'] ?? null;
$search  = $filters['search'] ?? null;
?>

<?php if ($total > 1): ?>
<div class="pagination">
    Pages:
    <?php for ($i = 1; $i <= $total; $i++): ?>
        <?php if ($i == $current): ?>
            <span class="current-page"><?= $i ?></span>
        <?php else: ?>
            <?php
                $query = [
                    'page' => 'catalog',
                    'pg'   => $i
                ];
                if (!empty($section)) $query['cat'] = $section;
                if (!empty($search))  $query['s']   = $search;
            ?>
            <a href="index.php?<?= http_build_query($query) ?>">
                <?= $i ?>
            </a>
        <?php endif; ?>
    <?php endfor; ?>
</div>
<?php endif; ?>