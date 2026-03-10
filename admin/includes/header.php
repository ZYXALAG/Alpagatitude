<?php
// Ce fichier est inclus par toutes les pages admin
// Variables attendues : $page_title, $active_nav
$admin_username = $_SESSION['admin_username'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title ?? 'Admin', ENT_QUOTES, 'UTF-8') ?> — Alpagatitude Admin</title>
    <link rel="stylesheet" href="<?= SITE_URL ?>/admin/css/admin.css">
</head>
<body>
<div class="admin-wrap">

    <!-- ── Sidebar ─────────────────────────────────────────────────────── -->
    <aside class="admin-sidebar" id="admin-sidebar">
        <a href="<?= SITE_URL ?>/admin/index.php" class="sidebar-brand">
            <span>🦙</span> Alpagatitude
        </a>
        <nav class="sidebar-nav">
            <a href="<?= SITE_URL ?>/admin/index.php" class="<?= ($active_nav ?? '') === 'dashboard' ? 'active' : '' ?>">
                <span class="nav-icon">📊</span> Tableau de bord
            </a>
            <a href="<?= SITE_URL ?>/admin/reservations.php" class="<?= ($active_nav ?? '') === 'reservations' ? 'active' : '' ?>">
                <span class="nav-icon">📅</span> Réservations
            </a>
            <a href="<?= SITE_URL ?>/admin/chambres.php" class="<?= ($active_nav ?? '') === 'rooms' ? 'active' : '' ?>">
                <span class="nav-icon">🏡</span> Hébergements
            </a>
            <a href="<?= SITE_URL ?>/admin/activites.php" class="<?= ($active_nav ?? '') === 'activities' ? 'active' : '' ?>">
                <span class="nav-icon">🦙</span> Activités
            </a>
            <a href="<?= SITE_URL ?>/admin/traductions.php" class="<?= ($active_nav ?? '') === 'translations' ? 'active' : '' ?>">
                <span class="nav-icon">🌐</span> Traductions
            </a>
            <a href="<?= SITE_URL ?>/admin/parametres.php" class="<?= ($active_nav ?? '') === 'settings' ? 'active' : '' ?>">
                <span class="nav-icon">⚙️</span> Paramètres
            </a>
        </nav>
        <div class="sidebar-footer">
            v1.0 · <?= date('Y') ?>
        </div>
    </aside>

    <!-- ── Main ───────────────────────────────────────────────────────── -->
    <div class="admin-main">
        <header class="admin-topbar">
            <h1><?= htmlspecialchars($page_title ?? '', ENT_QUOTES, 'UTF-8') ?></h1>
            <div class="topbar-right">
                <span style="font-size:.85rem;color:#6b7280;">👤 <?= htmlspecialchars($admin_username, ENT_QUOTES, 'UTF-8') ?></span>
                <a href="<?= SITE_URL ?>/index.php" target="_blank">🌐 Voir le site</a>
                <a href="<?= SITE_URL ?>/admin/logout.php" style="color:#c0392b;">Déconnexion →</a>
            </div>
        </header>
        <div class="admin-content">
