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

        <h1 class="logo">
            <a href="<?= BASE_URL ?>/Public/index.php?page=home">
                <img src="<?= BASE_URL ?>/img/Brand-title.png" alt="Media Library">
            </a>
        </h1>

        <ul class="nav">

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

            <?php if (!empty($_SESSION['user_id'])): ?>

                <?php $userName = $_SESSION['username'] ?? 'User'; ?>

                <li>
                    <span style="color:#fff; font-weight: 500; padding: 0 10px;">
                        👤 <?= htmlspecialchars($userName) ?>
                    </span>
                </li>

                <li>
                    <a href="<?= BASE_URL ?>/Public/index.php?page=logout">
                        Logout
                    </a>
                </li>

            <?php else: ?>

                <li>
                    <a href="<?= BASE_URL ?>/Public/index.php?page=login">
                        Login
                    </a>
                </li>

                <li>
                    <a href="<?= BASE_URL ?>/Public/index.php?page=register">
                        Register
                    </a>
                </li>

            <?php endif; ?>

        </ul>

    </div>
</header>

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