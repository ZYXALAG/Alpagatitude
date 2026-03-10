<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/lang.php';

$booking_id = (int)input('id', '0', 'GET');
$booking    = null;
$item_name  = '';

if ($booking_id) {
    try {
        $db   = getDB();
        $stmt = $db->prepare('SELECT * FROM reservations WHERE id = ? LIMIT 1');
        $stmt->execute([$booking_id]);
        $booking = $stmt->fetch();

        if ($booking) {
            if ($booking['type'] === 'room') {
                $s = $db->prepare('SELECT name FROM rooms WHERE id = ?');
                $s->execute([$booking['item_id']]);
                $row = $s->fetch();
                $item_name = $row ? $row['name'] : '';
            } else {
                $s = $db->prepare('SELECT name FROM activities WHERE id = ?');
                $s->execute([$booking['item_id']]);
                $row = $s->fetch();
                $item_name = $row ? $row['name'] : '';
            }
        }
    } catch (Exception $e) {
        $booking = null;
    }
}

$page_title = t_raw('confirm_title');
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
    <div class="container">
        <?php if ($booking): ?>
        <h1>✅ <?= t('confirm_title') ?></h1>
        <?php else: ?>
        <h1><?= t('confirm_title') ?></h1>
        <?php endif; ?>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if (!$booking): ?>
            <div class="alert alert-error text-center">Réservation introuvable.</div>
            <div class="text-center mt-3">
                <a href="<?= SITE_URL ?>/" class="btn btn-primary"><?= t('confirm_back') ?></a>
            </div>
        <?php else: ?>
        <!-- Étapes -->
        <div class="steps text-center mb-4">
            <div class="step done"><span class="step-num">1</span><span><?= t('book_type') ?></span></div>
            <span style="color:var(--border);">────</span>
            <div class="step done"><span class="step-num">2</span><span><?= t('book_submit') ?></span></div>
            <span style="color:var(--border);">────</span>
            <div class="step done"><span class="step-num">3</span><span><?= t('confirm_title') ?></span></div>
        </div>

        <div class="confirm-card">
            <div class="confirm-icon">🎉</div>
            <h2 style="color:var(--green-dark);margin-bottom:.5rem;"><?= t('confirm_title') ?></h2>
            <p><?= t('confirm_text') ?></p>

            <div class="confirm-details">
                <p style="font-weight:700;margin-bottom:.75rem;"><?= t('confirm_details') ?></p>
                <dl>
                    <dt><?= t('confirm_ref') ?></dt>
                    <dd><strong>#<?= str_pad((string)$booking['id'], 6, '0', STR_PAD_LEFT) ?></strong></dd>

                    <dt><?= t('book_type') ?></dt>
                    <dd><?= $booking['type'] === 'room' ? t('book_type_room') : t('book_type_activity') ?></dd>

                    <dt><?= $booking['type'] === 'room' ? t('book_select_room') : t('book_select_activity') ?></dt>
                    <dd><?= e($item_name) ?></dd>

                    <dt><?= $booking['type'] === 'room' ? t('book_checkin') : t('book_activity_date') ?></dt>
                    <dd><?= e(format_date($booking['date_start'], $current_lang)) ?></dd>

                    <?php if ($booking['type'] === 'room' && $booking['date_end'] && $booking['date_end'] !== $booking['date_start']): ?>
                    <dt><?= t('book_checkout') ?></dt>
                    <dd><?= e(format_date($booking['date_end'], $current_lang)) ?>
                        (<?= nights_between($booking['date_start'], $booking['date_end']) ?> <?= t('book_nights') ?>)
                    </dd>
                    <?php endif; ?>

                    <?php if ($booking['type'] === 'activity'): ?>
                    <dt><?= t('book_participants') ?></dt>
                    <dd><?= (int)$booking['participants'] ?> <?= t('book_persons') ?></dd>
                    <?php endif; ?>

                    <dt><?= t('book_firstname') ?> / <?= t('book_lastname') ?></dt>
                    <dd><?= e($booking['firstname'] . ' ' . $booking['lastname']) ?></dd>

                    <dt><?= t('book_email') ?></dt>
                    <dd><?= e($booking['email']) ?></dd>

                    <?php if ($booking['total_price']): ?>
                    <dt><?= t('book_total') ?></dt>
                    <dd><strong><?= format_price((float)$booking['total_price']) ?></strong></dd>
                    <?php endif; ?>

                    <dt>Statut</dt>
                    <dd><?= status_badge($booking['status']) ?></dd>
                </dl>
            </div>

            <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;margin-top:1.5rem;">
                <a href="<?= SITE_URL ?>/" class="btn btn-primary"><?= t('confirm_back') ?></a>
                <a href="<?= SITE_URL ?>/activites.php" class="btn" style="border:2px solid var(--green);color:var(--green);"><?= t('nav_activities') ?></a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
