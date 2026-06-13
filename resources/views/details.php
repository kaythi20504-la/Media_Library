<?php require BASE_PATH . '/resources/views/layout/header.php'; ?>

<?php
$_SESSION['csrf_token'] = $_SESSION['csrf_token'] ?? bin2hex(random_bytes(32));
?>

<div class="section page">
    <div class="wrapper">

        <?php require BASE_PATH . '/resources/views/partials/breadcrumbs.php'; ?>

        <div class="media-container">

            <!-- IMAGE -->
            <div class="media-picture">
                <img
                    src="<?= BASE_URL . '/' . htmlspecialchars($item['img']); ?>"
                    alt="<?= htmlspecialchars($item['title']); ?>"
                >
            </div>

            <!-- DETAILS -->
            <div class="media-details">

                <h1><?= htmlspecialchars($item['title']); ?></h1>

                <table>
                    <tr>
                        <th>Category</th>
                        <td><?= htmlspecialchars($item['category']); ?></td>
                    </tr>

                    <tr>
                        <th>Genre</th>
                        <td><?= htmlspecialchars($item['genre']); ?></td>
                    </tr>

                    <tr>
                        <th>Format</th>
                        <td><?= htmlspecialchars($item['format']); ?></td>
                    </tr>

                    <tr>
                        <th>Year</th>
                        <td><?= htmlspecialchars($item['year']); ?></td>
                    </tr>
                </table>

                <!-- RESERVATION SECTION -->
             <?php if (!empty($_SESSION['user_id']) && ($_SESSION['role'] ?? 'user') === 'user'): ?>

                    <?php if (empty($reservation)): ?>

                        <form method="post"
                              action="<?= BASE_URL ?>/Public/index.php?page=reserve"
                              class="reservation-form">

                            <input
                                type="hidden"
                                name="media_id"
                                value="<?= (int)$item['media_id']; ?>"
                            >

                            <button type="submit" class="reserve-btn">
                                Reserve This Item
                            </button>
                        </form>

                    <?php elseif ($reservation['status'] === 'pending'): ?>

                        <button disabled class="reserve-btn pending">
                            ⏳ Pending Reservation
                        </button>

                    <?php elseif ($reservation['status'] === 'approved'): ?>

                        <button disabled class="reserve-btn approved">
                            ✅ Approved
                        </button>

                    <?php elseif ($reservation['status'] === 'rejected'): ?>

                        <button disabled class="reserve-btn rejected">
                            ❌ Rejected
                        </button>

                    <?php endif; ?>

                <?php else: ?>

                    <p style="margin-top:20px;color:brown;">
                        Please login to reserve this item.
                    </p>

                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<style>
.media-container{
    display:flex;
    gap:30px;
    align-items:flex-start;
    margin-top:20px;
}

.media-picture img{
    max-width:300px;
    border-radius:10px;
}

.media-details{
    flex:1;
}

.media-details table{
    width:100%;
    border-collapse:collapse;
    margin-top:15px;
}

.media-details th{
    text-align:left;
    width:140px;
    padding:10px;
    background:#f5f5f5;
}

.media-details td{
    padding:10px;
    border-bottom:1px solid #eee;
}

.reservation-form{
    margin-top:25px;
}

.reserve-btn{
    background:brown;
    color:white;
    border:none;
    padding:12px 24px;
    border-radius:8px;
    cursor:pointer;
    font-size:15px;
    font-weight:600;
}

.reserve-btn:hover{
    opacity:0.9;
}

.reserve-btn.pending{
    background:orange;
    cursor:not-allowed;
}

.reserve-btn.approved{
    background:green;
    cursor:not-allowed;
}

.reserve-btn.rejected{
    background:red;
    cursor:not-allowed;
}
</style>

<?php require BASE_PATH . '/resources/views/layout/footer.php'; ?>