<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageTitle ?? 'Media Library') ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
</head>
<body>

<div class="page-container">
<div class="content">

<header class="header">
    <div class="wrapper">

        <!-- LOGO -->
        <h1 class="logo">
            <a href="<?= BASE_URL ?>/Public/index.php?page=home">
                <img src="<?= BASE_URL ?>/img/Brand-title.png" alt="Media Library">
            </a>
        </h1>

        <!-- NAVIGATION -->
        <ul class="nav">

            <!-- PUBLIC MENU -->
            <li class="<?= (isset($section) && $section === 'books') ? 'on' : '' ?>">
                <a href="<?= BASE_URL ?>/Public/index.php?page=catalog&cat=books">
                    <img src="<?= BASE_URL ?>/img/book.png">
                    Books
                </a>
            </li>

            <li class="<?= (isset($section) && $section === 'movies') ? 'on' : '' ?>">
                <a href="<?= BASE_URL ?>/Public/index.php?page=catalog&cat=movies">
                    <img src="<?= BASE_URL ?>/img/movie.png">
                    Movies
                </a>
            </li>

            <li class="<?= (isset($section) && $section === 'music') ? 'on' : '' ?>">
                <a href="<?= BASE_URL ?>/Public/index.php?page=catalog&cat=music">
                    <img src="<?= BASE_URL ?>/img/music.png">
                    Music
                </a>
            </li>

            <li class="<?= (isset($section) && $section === 'suggest') ? 'on' : '' ?>">
                <a href="<?= BASE_URL ?>/Public/index.php?page=suggest">
                    <img src="<?= BASE_URL ?>/img/suggestion.png">
                    Suggest
                </a>
            </li>

            <!-- =========================
                 AUTH SECTION
            ========================== -->

            <?php if (!empty($_SESSION['user_id'])): ?>

                <?php $role = $_SESSION['role'] ?? 'user'; ?>
                <?php $userName = $_SESSION['username'] ?? 'User'; ?>

                <!-- USER ONLY MENU -->
                <?php if ($role === 'user'): ?>
                    <li>
                        <a href="<?= BASE_URL ?>/Public/index.php?page=my">
                            My Reservations
                        </a>
                    </li>
                <?php endif; ?>

                <!-- ADMIN ONLY MENU -->
                <?php if ($role === 'admin'): ?>
                    <li>
                        <a href="<?= BASE_URL ?>/Public/index.php?page=admin/reservations">
                            Admin Panel
                        </a>
                    </li>
                <?php endif; ?>

                <!-- USER INFO -->
                <li>
                    <span style="color:#fff; font-weight:500; padding:0 10px;">
                        👤 <?= htmlspecialchars($userName) ?>
                    </span>
                </li>

                <!-- LOGOUT -->
                <li>
                    <a href="<?= BASE_URL ?>/Public/index.php?page=logout">
                        Logout
                    </a>
                </li>

            <?php else: ?>

                <!-- LOGIN -->
                <li>
                    <a href="<?= BASE_URL ?>/Public/index.php?page=login">
                        Login
                    </a>
                </li>

                <!-- REGISTER -->
                <li>
                    <a href="<?= BASE_URL ?>/Public/index.php?page=register">
                        Register
                    </a>
                </li>

            <?php endif; ?>

        </ul>

    </div>
</header>

<!-- SEARCH BAR -->
<?php if (empty($hideSearch)): ?>

<div class="search">
    <div class="wrapper">

        <form method="get" action="<?= BASE_URL ?>/Public/index.php">

            <input type="hidden" name="page" value="catalog">

            <?php if (!empty($section)): ?>
                <input type="hidden"
                       name="cat"
                       value="<?= htmlspecialchars($section) ?>">
            <?php endif; ?>

            <label for="s">Search:</label>

            <input type="text" name="s" id="s">

            <input type="submit" value="Go">

        </form>

    </div>
</div>

<?php endif; ?>

<main id="content">