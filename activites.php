<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/lang.php';

try {
    $db = getDB();
    $activities = $db->query('SELECT * FROM activities WHERE active = 1 ORDER BY price ASC')->fetchAll();
} catch (Exception $e) {
    $activities = [];
}

$page_title = t_raw('activities_title');
require_once __DIR__ . '/includes/header.php';

$act_icons = ['🦙', '🌾', '📸', '🧶', '🥾', '🌿', '🎠', '🐾'];
?>

<section class="page-hero">
    <div class="container">
        <h1><?= t('activities_title') ?></h1>
        <p><?= t('activities_subtitle') ?></p>
        <nav class="breadcrumb" aria-label="breadcrumb">
            <a href="<?= SITE_URL ?>/"><?= t('nav_home') ?></a>
            <span class="breadcrumb-sep">›</span>
            <span><?= t('nav_activities') ?></span>
        </nav>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if (empty($activities)): ?>
            <div class="alert alert-info text-center"><?= t('activities_no_activities') ?></div>
        <?php else: ?>
        <div class="cards-grid">
            <?php
            $i = 0;
            foreach ($activities as $act):
                $name = $current_lang === 'en' ? ($act['name_en'] ?: $act['name'])
                      : ($current_lang === 'es' ? ($act['name_es'] ?: $act['name']) : $act['name']);
                $desc = $current_lang === 'en' ? ($act['description_en'] ?: $act['description'])
                      : ($current_lang === 'es' ? ($act['description_es'] ?: $act['description']) : $act['description']);
            ?>
            <div class="card">
                <div class="card-img">
                    <?php if (!empty($act['image'])): ?>
                        <img src="<?= SITE_URL ?>/assets/activities/<?= e($act['image']) ?>" alt="<?= e($name) ?>" loading="lazy">
                    <?php else: ?>
                        <div class="card-img-placeholder"><?= $act_icons[$i % count($act_icons)] ?></div>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <h3><?= e($name) ?></h3>
                    <p><?= e($desc ?? '') ?></p>
                    <div class="card-meta" style="flex-direction:column;align-items:flex-start;gap:.4rem;">
                        <?php if (!empty($act['duration'])): ?>
                        <span class="card-meta-item">⏱ <?= t('activities_duration') ?> : <strong><?= e($act['duration']) ?></strong></span>
                        <?php endif; ?>
                        <?php if (!empty($act['max_participants'])): ?>
                        <span class="card-meta-item">👥 <?= t('activities_max') ?> : <strong><?= (int)$act['max_participants'] ?></strong></span>
                        <?php endif; ?>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-top:1rem;flex-wrap:wrap;gap:.5rem;">
                        <span class="card-price"><?= format_price((float)$act['price']) ?> <small><?= t('activities_per_person') ?></small></span>
                        <a href="<?= SITE_URL ?>/reservation.php?type=activity&id=<?= (int)$act['id'] ?>" class="btn btn-primary btn-sm"><?= t('activities_card_btn') ?></a>
                    </div>
                </div>
            </div>
            <?php $i++; endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<section class="cta-section">
    <div class="container">
        <h2><?= t('hero_cta_book') ?></h2>
        <p><?= t('about_alpacas_text') ?></p>
        <a href="<?= SITE_URL ?>/reservation.php" class="btn btn-outline"><?= t('hero_cta_book') ?></a>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
