<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/includes/auth.php';
require_admin();

$page_title = 'Tableau de bord';
$active_nav = 'dashboard';

try {
    $db = getDB();
    $total_reservations   = (int)$db->query('SELECT COUNT(*) FROM reservations')->fetchColumn();
    $pending_reservations = (int)$db->query("SELECT COUNT(*) FROM reservations WHERE status='pending'")->fetchColumn();
    $confirmed            = (int)$db->query("SELECT COUNT(*) FROM reservations WHERE status='confirmed'")->fetchColumn();
    $total_rooms          = (int)$db->query('SELECT COUNT(*) FROM rooms WHERE active=1')->fetchColumn();
    $total_activities     = (int)$db->query('SELECT COUNT(*) FROM activities WHERE active=1')->fetchColumn();
    $revenue = (float)$db->query("SELECT COALESCE(SUM(total_price),0) FROM reservations WHERE status='confirmed'")->fetchColumn();

    // Dernières réservations
    $latest = $db->query('SELECT * FROM reservations ORDER BY created_at DESC LIMIT 8')->fetchAll();

    // Réservations par mois (6 derniers mois)
    $monthly = $db->query(
        "SELECT DATE_FORMAT(created_at,'%Y-%m') as month, COUNT(*) as cnt
         FROM reservations
         WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
         GROUP BY month ORDER BY month ASC"
    )->fetchAll();

} catch (Exception $e) {
    $total_reservations = $pending_reservations = $confirmed = $total_rooms = $total_activities = 0;
    $revenue = 0;
    $latest  = [];
    $monthly = [];
}

require_once __DIR__ . '/includes/header.php';
?>

<!-- Stats -->
<div class="stats-row">
    <div class="stat-card">
        <div class="stat-card-icon icon-blue">📅</div>
        <div class="stat-card-info">
            <div class="number"><?= $total_reservations ?></div>
            <div class="label">Réservations totales</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon icon-gold">⏳</div>
        <div class="stat-card-info">
            <div class="number"><?= $pending_reservations ?></div>
            <div class="label">En attente</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon icon-green">✅</div>
        <div class="stat-card-info">
            <div class="number"><?= $confirmed ?></div>
            <div class="label">Confirmées</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon icon-green">🏡</div>
        <div class="stat-card-info">
            <div class="number"><?= $total_rooms ?></div>
            <div class="label">Hébergements</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon icon-purple">🦙</div>
        <div class="stat-card-info">
            <div class="number"><?= $total_activities ?></div>
            <div class="label">Activités</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon icon-gold">💶</div>
        <div class="stat-card-info">
            <div class="number"><?= number_format($revenue, 0, ',', ' ') ?> €</div>
            <div class="label">Revenus confirmés</div>
        </div>
    </div>
</div>

<!-- Raccourcis -->
<div style="display:flex;gap:.75rem;flex-wrap:wrap;margin-bottom:1.75rem;">
    <a href="reservations.php?status=pending" class="btn-admin btn-admin-warning">
        ⏳ <?= $pending_reservations ?> en attente
    </a>
    <a href="chambres.php?action=new" class="btn-admin btn-admin-outline">+ Ajouter un hébergement</a>
    <a href="activites.php?action=new" class="btn-admin btn-admin-outline">+ Ajouter une activité</a>
</div>

<!-- Dernières réservations -->
<div class="admin-card">
    <div class="admin-card-header">
        <h2>📋 Dernières réservations</h2>
        <a href="reservations.php" class="btn-admin btn-admin-outline btn-admin-sm">Voir tout</a>
    </div>
    <?php if (empty($latest)): ?>
        <div class="empty-state"><div class="empty-icon">📭</div><p>Aucune réservation pour le moment.</p></div>
    <?php else: ?>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Client</th>
                    <th>Type</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($latest as $r): ?>
                <tr>
                    <td><strong>#<?= str_pad((string)$r['id'], 5, '0', STR_PAD_LEFT) ?></strong></td>
                    <td><?= htmlspecialchars($r['firstname'] . ' ' . $r['lastname'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><span class="badge badge-info"><?= $r['type'] === 'room' ? '🏡 Héberg.' : '🦙 Activité' ?></span></td>
                    <td><?= htmlspecialchars($r['date_start'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= $r['total_price'] ? number_format((float)$r['total_price'], 2, ',', ' ') . ' €' : '—' ?></td>
                    <td><?= status_badge($r['status']) ?></td>
                    <td class="actions">
                        <a href="reservations.php?id=<?= (int)$r['id'] ?>" class="btn-admin btn-admin-sm btn-admin-outline">Voir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
