<?php
$page_title = 'Alpagatitude — Domaine des Alpagas';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/lang.php';

// Récupérer rooms + activities pour les aperçus
try {
    $db = getDB();
    $rooms      = $db->query('SELECT * FROM rooms WHERE active = 1 ORDER BY price_per_night ASC LIMIT 3')->fetchAll();
    $activities = $db->query('SELECT * FROM activities WHERE active = 1 ORDER BY price ASC LIMIT 3')->fetchAll();
    $site_desc  = get_setting('site_description', t_raw('hero_subtitle'));
    $address    = get_setting('address', '123 Route des Alpagas');
} catch (Exception $e) {
    $rooms = $activities = [];
    $site_desc = t_raw('hero_subtitle');
    $address   = '';
}

require_once __DIR__ . '/includes/header.php';
?>

<!-- ═══════════ HERO ══════════════════════════════════════════════════════ -->
<section class="hero" id="accueil">
    <div class="hero-alpaca">🦙</div>
    <div class="container">
        <div class="hero-content fade-in-up">
            <span class="hero-eyebrow">🌿 Domaine des Alpagas</span>
            <h1><?= t('hero_title') ?></h1>
            <p><?= e($site_desc) ?></p>
            <div class="hero-ctas">
                <a href="<?= SITE_URL ?>/chambres.php" class="btn btn-primary"><?= t('hero_cta_rooms') ?></a>
                <a href="<?= SITE_URL ?>/reservation.php" class="btn btn-outline"><?= t('hero_cta_book') ?></a>
            </div>
        </div>
    </div>
    <div class="hero-scroll">
        Découvrir
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
    </div>
</section>

<!-- ═══════════ À PROPOS ═════════════════════════════════════════════════ -->
<section class="section" id="apropos">
    <div class="container">
        <div class="about-grid">
            <div class="about-text">
                <div class="section-header" style="text-align:left;">
                    <h2><?= t('about_title') ?></h2>
                    <span class="section-divider" style="margin:0;"></span>
                </div>
                <p style="margin-top:1rem;"><?= t('about_text') ?></p>
                <a href="<?= SITE_URL ?>/activites.php" class="btn btn-primary mt-3"><?= t('nav_activities') ?></a>
            </div>
            <div class="about-features">
                <div class="feature-card">
                    <div class="feature-icon">🦙</div>
                    <h3><?= t('about_alpacas') ?></h3>
                    <p><?= t('about_alpacas_text') ?></p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🌲</div>
                    <h3><?= t('about_nature') ?></h3>
                    <p><?= t('about_nature_text') ?></p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🛏️</div>
                    <h3><?= t('about_relaxation') ?></h3>
                    <p><?= t('about_relaxation_text') ?></p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🌄</div>
                    <h3><?php global $lang; echo e($lang['about_nature'] ?? ''); ?></h3>
                    <p><?= t('about_nature_text') ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════ STATS ════════════════════════════════════════════════════ -->
<div class="stats-band">
    <div class="container">
        <div class="stats-grid">
            <div>
                <span class="stat-number">20+</span>
                <span class="stat-label">Alpagas</span>
            </div>
            <div>
                <span class="stat-number">500+</span>
                <span class="stat-label">Visiteurs / an</span>
            </div>
            <div>
                <span class="stat-number"><?= max(1, count($rooms)) ?></span>
                <span class="stat-label"><?= t('nav_rooms') ?></span>
            </div>
            <div>
                <span class="stat-number"><?= max(1, count($activities)) ?></span>
                <span class="stat-label"><?= t('nav_activities') ?></span>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════ HÉBERGEMENTS ════════════════════════════════════════════ -->
<section class="section section-alt" id="hebergements">
    <div class="container">
        <div class="section-header">
            <h2><?= t('rooms_title') ?></h2>
            <p><?= t('rooms_subtitle') ?></p>
            <span class="section-divider"></span>
        </div>
        <?php if (empty($rooms)): ?>
            <div class="alert alert-info text-center"><?= t('rooms_no_rooms') ?></div>
        <?php else: ?>
        <div class="cards-grid">
            <?php foreach ($rooms as $room):
                $desc = $current_lang === 'en' ? ($room['description_en'] ?: $room['description'])
                      : ($current_lang === 'es' ? ($room['description_es'] ?: $room['description']) : $room['description']);
            ?>
            <div class="card">
                <div class="card-img">
                    <?php if (!empty($room['image'])): ?>
                        <img src="<?= SITE_URL ?>/assets/rooms/<?= e($room['image']) ?>" alt="<?= e($room['name']) ?>" loading="lazy">
                    <?php else: ?>
                        <div class="card-img-placeholder">🏡</div>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <h3><?= e($room['name']) ?></h3>
                    <p><?= e(mb_substr($desc ?? '', 0, 120)) ?>…</p>
                    <div class="card-meta">
                        <span class="card-meta-item">👥 <?= e((string)$room['capacity']) ?> <?= t('rooms_person') ?></span>
                        <span class="card-price"><?= format_price((float)$room['price_per_night']) ?> <small><?= t('rooms_card_price') ?></small></span>
                    </div>
                    <a href="<?= SITE_URL ?>/chambres.php?id=<?= (int)$room['id'] ?>" class="btn btn-primary btn-sm"><?= t('rooms_card_btn') ?></a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="<?= SITE_URL ?>/chambres.php" class="btn btn-outline" style="color:var(--green);border-color:var(--green);"><?= t('rooms_title') ?> →</a>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- ═══════════ ACTIVITÉS ════════════════════════════════════════════════ -->
<section class="section section-green" id="activites">
    <div class="container">
        <div class="section-header">
            <h2><?= t('activities_title') ?></h2>
            <p><?= t('activities_subtitle') ?></p>
            <span class="section-divider"></span>
        </div>
        <?php if (empty($activities)): ?>
            <div class="alert alert-info text-center"><?= t('activities_no_activities') ?></div>
        <?php else: ?>
        <div class="cards-grid">
            <?php
            $act_icons = ['🦙', '🌾', '📸', '🧶', '🥾'];
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
                    <p><?= e(mb_substr($desc ?? '', 0, 110)) ?>…</p>
                    <div class="card-meta">
                        <?php if (!empty($act['duration'])): ?>
                        <span class="card-meta-item">⏱ <?= e($act['duration']) ?></span>
                        <?php endif; ?>
                        <span class="card-price"><?= format_price((float)$act['price']) ?> <small><?= t('activities_per_person') ?></small></span>
                    </div>
                    <a href="<?= SITE_URL ?>/reservation.php?type=activity&id=<?= (int)$act['id'] ?>" class="btn btn-primary btn-sm"><?= t('activities_card_btn') ?></a>
                </div>
            </div>
            <?php $i++; endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="<?= SITE_URL ?>/activites.php" class="btn btn-outline" style="color:var(--green);border-color:var(--green);"><?= t('activities_title') ?> →</a>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- ═══════════ CTA ══════════════════════════════════════════════════════ -->
<section class="cta-section">
    <div class="container">
        <h2><?= t('hero_cta_book') ?></h2>
        <p><?= t('hero_subtitle') ?></p>
        <a href="<?= SITE_URL ?>/reservation.php" class="btn btn-outline"><?= t('hero_cta_book') ?></a>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
