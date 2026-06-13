<?php require BASE_PATH . '/resources/views/layout/header.php'; ?>

<div class="section page">
    <div class="wrapper">

        <h2>My Reservations</h2>

        <?php if (empty($reservations)): ?>
            <p>You have no reservations yet.</p>
        <?php else: ?>

            <table style="width:100%; border-collapse:collapse; margin-top:20px;">
                <thead>
                    <tr style="background:#f5f5f5;">
                        <th style="padding:10px;">ID</th>
                        <th style="padding:10px;">Media</th>
                        <th style="padding:10px;">Status</th>
                        <th style="padding:10px;">Notes</th>
                        <th style="padding:10px;">Reserved At</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($reservations as $r): ?>
                        <tr style="border-bottom:1px solid #eee;">

                            <!-- ID -->
                            <td style="padding:10px;">
                                <?= htmlspecialchars($r['id']) ?>
                            </td>

                            <!-- MEDIA -->
                            <td style="padding:10px;">
                                <div style="display:flex; align-items:center; gap:10px;">

                                    <img
                                        src="<?= BASE_URL . '/' . htmlspecialchars($r['img'] ?? 'img/default.png') ?>"
                                        width="50"
                                        height="50"
                                        style="border-radius:6px; object-fit:cover;"
                                    >

                                    <div>
                                        <strong>
                                            <?= htmlspecialchars($r['title'] ?? 'Unknown Title') ?>
                                        </strong>
                                    </div>

                                </div>
                            </td>

                            <!-- STATUS -->
                            <td style="padding:10px;">
                                <span style="
                                    padding:5px 10px;
                                    border-radius:6px;
                                    font-size:12px;
                                    color:white;
                                    background:
                                        <?= 
                                            $r['status'] === 'approved' ? 'green' :
                                            ($r['status'] === 'rejected' ? 'red' : 'orange')
                                        ?>;
                                ">
                                    <?= htmlspecialchars($r['status']) ?>
                                </span>
                            </td>

                            <!-- NOTES -->
                            <td style="padding:10px;">
                                <?= htmlspecialchars($r['notes'] ?: '-') ?>
                            </td>

                            <!-- DATE -->
                            <td style="padding:10px;">
                                <?= htmlspecialchars($r['reserved_at']) ?>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <?php endif; ?>

    </div>
</div>

<?php require BASE_PATH . '/resources/views/layout/footer.php'; ?>