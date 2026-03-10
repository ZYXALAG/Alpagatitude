<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/lang.php';

// Détail d'une chambre ?
$selected_room = null;
$selected_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

try {
    $db = getDB();
    $rooms = $db->query('SELECT * FROM rooms WHERE active = 1 ORDER BY price_per_night ASC')->fetchAll();
    if ($selected_id) {
        $stmt = $db->prepare('SELECT * FROM rooms WHERE id = ? AND active = 1');
        $stmt->execute([$selected_id]);
        $selected_room = $stmt->fetch();
    }
} catch (Exception $e) {
    $rooms = [];
}

$page_title = t_raw('rooms_title');
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
    <div class="container">
        <h1><?= t('rooms_title') ?></h1>
        <p><?= t('rooms_subtitle') ?></p>
        <nav class="breadcrumb" aria-label="breadcrumb">
            <a href="<?= SITE_URL ?>/"><?= t('nav_home') ?></a>
            <span class="breadcrumb-sep">›</span>
            <span><?= t('nav_rooms') ?></span>
        </nav>
    </div>
</section>

<?php if ($selected_room):
    $desc = $current_lang === 'en' ? ($selected_room['description_en'] ?: $selected_room['description'])
          : ($current_lang === 'es' ? ($selected_room['description_es'] ?: $selected_room['description']) : $selected_room['description']);
?>
<!-- Détail chambre sélectionnée -->
<section class="section">
    <div class="container">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:2.5rem;align-items:start;">
            <div>
                <div style="border-radius:var(--radius);overflow:hidden;background:var(--green-pale);aspect-ratio:4/3;display:flex;align-items:center;justify-content:center;font-size:8rem;">
                    <?php if (!empty($selected_room['image'])): ?>
                        <img src="<?= SITE_URL ?>/assets/rooms/<?= e($selected_room['image']) ?>" alt="<?= e($selected_room['name']) ?>" style="width:100%;height:100%;object-fit:cover;">
                    <?php else: ?>
                        🏡
                    <?php endif; ?>
                </div>
            </div>
            <div>
                <span class="badge badge-success mb-2"><?= t('rooms_availability') ?></span>
                <h2><?= e($selected_room['name']) ?></h2>
                <p style="color:var(--text-light);margin:1rem 0;"><?= e($desc) ?></p>
                <div style="display:flex;gap:1.5rem;align-items:center;flex-wrap:wrap;margin:1.5rem 0;">
                    <div>
                        <strong style="font-size:2rem;color:var(--green-dark);"><?= format_price((float)$selected_room['price_per_night']) ?></strong>
                        <span style="color:var(--text-light);"> / <?= t('rooms_card_price') ?></span>
                    </div>
                    <span>👥 <?= e((string)$selected_room['capacity']) ?> <?= t('rooms_person') ?></span>
                </div>
                <a href="<?= SITE_URL ?>/reservation.php?type=room&id=<?= (int)$selected_room['id'] ?>" class="btn btn-primary"><?= t('rooms_card_btn') ?></a>
                <a href="<?= SITE_URL ?>/chambres.php" class="btn" style="color:var(--text-light);margin-left:.5rem;">← <?= t('rooms_title') ?></a>
            </div>
        </div>
    </div>
</section>
<hr style="border:0;border-top:1px solid var(--border);">
<?php endif; ?>

<!-- Liste des chambres -->
<section class="section <?= $selected_room ? 'section-alt' : '' ?>">
    <div class="container">
        <?php if (!$selected_room): ?>
        <div class="section-header">
            <h2><?= t('rooms_title') ?></h2>
            <span class="section-divider"></span>
        </div>
        <?php endif; ?>
        <?php if (empty($rooms)): ?>
            <div class="alert alert-info text-center"><?= t('rooms_no_rooms') ?></div>
        <?php else: ?>
        <div class="cards-grid">
            <?php foreach ($rooms as $room):
                $desc = $current_lang === 'en' ? ($room['description_en'] ?: $room['description'])
                      : ($current_lang === 'es' ? ($room['description_es'] ?: $room['description']) : $room['description']);
            ?>
            <div class="card<?= ($selected_room && $selected_room['id'] == $room['id']) ? ' card-highlighted' : '' ?>">
                <div class="card-img">
                    <?php if (!empty($room['image'])): ?>
                        <img src="<?= SITE_URL ?>/assets/rooms/<?= e($room['image']) ?>" alt="<?= e($room['name']) ?>" loading="lazy">
                    <?php else: ?>
                        <div class="card-img-placeholder">🏡</div>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <h3><?= e($room['name']) ?></h3>
                    <p><?= e(mb_substr($desc ?? '', 0, 130)) ?>…</p>
                    <div class="card-meta">
                        <span class="card-meta-item">👥 <?= e((string)$room['capacity']) ?> <?= t('rooms_person') ?></span>
                        <span class="card-price"><?= format_price((float)$room['price_per_night']) ?> <small><?= t('rooms_card_price') ?></small></span>
                    </div>
                    <div style="display:flex;gap:.5rem;flex-wrap:wrap;">
                        <a href="<?= SITE_URL ?>/chambres.php?id=<?= (int)$room['id'] ?>" class="btn btn-sm" style="border:2px solid var(--green);color:var(--green);"><?= t('rooms_details') ?></a>
                        <a href="<?= SITE_URL ?>/reservation.php?type=room&id=<?= (int)$room['id'] ?>" class="btn btn-primary btn-sm"><?= t('rooms_card_btn') ?></a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
