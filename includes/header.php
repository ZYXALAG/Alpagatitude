<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/lang.php';

global $current_lang;
$page_title = $page_title ?? SITE_NAME;
?>
<!DOCTYPE html>
<html lang="<?= e($current_lang) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($page_title) ?> — <?= e(SITE_NAME) ?></title>
    <meta name="description" content="<?= t('hero_subtitle') ?>">
    <link rel="stylesheet" href="<?= SITE_URL ?>/css/style.css">
    <link rel="icon" type="image/svg+xml" href="<?= SITE_URL ?>/assets/favicon.svg">
</head>
<body>

<!-- ══ HEADER ══════════════════════════════════════════════════════════════ -->
<header class="site-header" id="site-header">
    <div class="container header-inner">

        <a href="<?= SITE_URL ?>/" class="logo">
            <span class="logo-icon">🦙</span>
            <span class="logo-text"><?= e(SITE_NAME) ?></span>
        </a>

        <button class="nav-toggle" id="nav-toggle" aria-label="Menu" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>

        <nav class="main-nav" id="main-nav" role="navigation">
            <ul>
                <li><a href="<?= SITE_URL ?>/"><?= t('nav_home') ?></a></li>
                <li><a href="<?= SITE_URL ?>/chambres.php"><?= t('nav_rooms') ?></a></li>
                <li><a href="<?= SITE_URL ?>/activites.php"><?= t('nav_activities') ?></a></li>
                <li><a href="<?= SITE_URL ?>/reservation.php" class="btn-nav"><?= t('nav_reservation') ?></a></li>
                <li><a href="<?= SITE_URL ?>/contact.php"><?= t('nav_contact') ?></a></li>
            </ul>

            <!-- Sélecteur de langue -->
            <div class="lang-switcher">
                <?php foreach (['fr', 'en', 'es'] as $l):
                    $flags = ['fr' => '🇫🇷', 'en' => '🇬🇧', 'es' => '🇪🇸'];
                    $active = ($l === $current_lang) ? ' active' : '';
                    $current_url = strtok($_SERVER['REQUEST_URI'] ?? '/', '?');
                    // Reconstruct query string without lang
                    $params = $_GET;
                    $params['lang'] = $l;
                    $qs = http_build_query($params);
                ?>
                    <a href="<?= e($current_url . '?' . $qs) ?>" class="lang-btn<?= $active ?>"
                       title="<?= $l === 'fr' ? 'Français' : ($l === 'en' ? 'English' : 'Español') ?>">
                        <?= $flags[$l] ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </nav>

    </div>
</header>
<!-- ══ END HEADER ═══════════════════════════════════════════════════════════ -->

<main id="main-content">
