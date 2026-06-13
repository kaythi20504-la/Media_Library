<?php require BASE_PATH . '/resources/views/layout/header.php'; ?>

<div class="section page">
<div class="wrapper">

    <h2>Admin Reservations</h2>

    <?php if (empty($reservations)): ?>
        <p>No reservations found.</p>
    <?php else: ?>

    <table border="1" cellpadding="10" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Media</th>
                <th>Status</th>
                <th>Notes</th>
                <th>Reserved At</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($reservations as $r): ?>
                <tr>
                    <td><?= htmlspecialchars($r['id']) ?></td>
                    <td><?= htmlspecialchars($r['user_name'] ?? $r['user_id']) ?></td>
                    <td><?= htmlspecialchars($r['media_title'] ?? $r['media_id']) ?></td>

                    <td>
                        <span style="
                            padding:4px 8px;
                            border-radius:5px;
                            color:white;
                            background:
                                <?= $r['status'] === 'approved' ? 'green' :
                                   ($r['status'] === 'rejected' ? 'red' : 'orange') ?>;
                        ">
                            <?= htmlspecialchars($r['status']) ?>
                        </span>
                    </td>

                    <td><?= htmlspecialchars($r['notes'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($r['reserved_at']) ?></td>

                    <td>

                        <form method="post"
                              action="<?= BASE_URL ?>/Public/index.php?page=admin/reservation/action">

                            <input type="hidden" name="id" value="<?= htmlspecialchars($r['id']) ?>">

                            <button name="action" value="approve"
                                <?= $r['status'] === 'approved' ? 'disabled' : '' ?>>
                                Approve
                            </button>

                            <button type="button"
                                    onclick="openRejectModal(<?= $r['id'] ?>)">
                                Reject
                            </button>

                        </form>

                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php endif; ?>

</div>
</div>

<!-- =======================
     REJECT MODAL
======================= -->
<div id="rejectModal"
     style="display:none; position:fixed; top:0; left:0;
     width:100%; height:100%; background:rgba(0,0,0,0.5);">

    <div style="
        background:#fff;
        width:400px;
        margin:10% auto;
        padding:20px;
        border-radius:10px;
    ">

        <h3>Reject Reservation</h3>

        <form method="post"
              action="<?= BASE_URL ?>/Public/index.php?page=admin/reservation/action">

            <input type="hidden" name="id" id="rejectId">

            <textarea name="notes"
                      placeholder="Enter rejection reason..."
                      style="width:100%; height:100px;"></textarea>

            <div style="margin-top:15px; text-align:right;">

                <button type="button" onclick="closeRejectModal()">
                    Cancel
                </button>

                <button name="action" value="reject"
                        style="background:red; color:white;">
                    Confirm Reject
                </button>

            </div>

        </form>

    </div>
</div>

<!-- =======================
     JS
======================= -->
<script>
function openRejectModal(id) {
    document.getElementById('rejectId').value = id;
    document.getElementById('rejectModal').style.display = 'block';
}

function closeRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
}
</script>

<?php require BASE_PATH . '/resources/views/layout/footer.php'; ?>