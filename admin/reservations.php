<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/includes/auth.php';
require_admin();

$db      = getDB();
$message = '';
$detail  = null;

// ── Détail d'une réservation ────────────────────────────────────────────────
$view_id = (int)input('id', '0', 'GET');
if ($view_id) {
    $stmt = $db->prepare('SELECT * FROM reservations WHERE id=?');
    $stmt->execute([$view_id]);
    $detail = $stmt->fetch();
}

// ── Traitement POST ──────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    admin_csrf_check();
    $post_action = input('post_action');
    $id          = (int)input('id');

    if ($post_action === 'status') {
        $new_status = input('status');
        if (in_array($new_status, ['pending', 'confirmed', 'cancelled'], true)) {
            $db->prepare('UPDATE reservations SET status=? WHERE id=?')->execute([$new_status, $id]);
            redirect(SITE_URL . '/admin/reservations.php?id=' . $id . '&msg=Statut+mis+à+jour');
        }
    }

    if ($post_action === 'delete') {
        $db->prepare('DELETE FROM reservations WHERE id=?')->execute([$id]);
        redirect(SITE_URL . '/admin/reservations.php?msg=Réservation+supprimée');
    }
}

if (!empty($_GET['msg'])) {
    $message = htmlspecialchars(urldecode($_GET['msg']), ENT_QUOTES, 'UTF-8');
}

// ── Filtres ──────────────────────────────────────────────────────────────────
$filter_status = input('status', '', 'GET');
$filter_type   = input('type', '', 'GET');
$search        = input('search', '', 'GET');

$where = ['1=1'];
$params = [];
if ($filter_status && in_array($filter_status, ['pending', 'confirmed', 'cancelled'])) {
    $where[] = 'status = ?';
    $params[] = $filter_status;
}
if ($filter_type && in_array($filter_type, ['room', 'activity'])) {
    $where[] = 'type = ?';
    $params[] = $filter_type;
}
if ($search) {
    $where[] = '(firstname LIKE ? OR lastname LIKE ? OR email LIKE ?)';
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}
$sql  = 'SELECT * FROM reservations WHERE ' . implode(' AND ', $where) . ' ORDER BY created_at DESC';
$stmt = $db->prepare($sql);
$stmt->execute($params);
$reservations = $stmt->fetchAll();

// Récupérer noms des items
$rooms_map = [];
$acts_map  = [];
foreach ($db->query('SELECT id, name FROM rooms')->fetchAll() as $r) $rooms_map[$r['id']] = $r['name'];
foreach ($db->query('SELECT id, name FROM activities')->fetchAll() as $a) $acts_map[$a['id']] = $a['name'];

$page_title = 'Réservations';
$active_nav = 'reservations';
require_once __DIR__ . '/includes/header.php';
?>

<?php if ($message): ?><div class="admin-alert admin-alert-success"><?= e($message) ?></div><?php endif; ?>

<?php if ($detail): ?>
<!-- ── Détail d'une réservation ─────────────────────────────────────────── -->
<div style="margin-bottom:1rem;">
    <a href="reservations.php" class="btn-admin btn-admin-outline btn-admin-sm">← Retour à la liste</a>
</div>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;align-items:start;">
    <div class="admin-card">
        <div class="admin-card-header">
            <h2>Réservation #<?= str_pad((string)$detail['id'], 5, '0', STR_PAD_LEFT) ?></h2>
            <?= status_badge($detail['status']) ?>
        </div>
        <div class="admin-card-body">
            <dl style="display:grid;grid-template-columns:auto 1fr;gap:.5rem 1.25rem;font-size:.9rem;">
                <dt style="font-weight:600;color:#374151;">Type</dt>
                <dd><?= $detail['type'] === 'room' ? '🏡 Hébergement' : '🦙 Activité' ?></dd>
                <dt style="font-weight:600;color:#374151;">
                    <?= $detail['type'] === 'room' ? 'Chambre' : 'Activité' ?>
                </dt>
                <dd>
                    <?= $detail['type'] === 'room'
                        ? e($rooms_map[$detail['item_id']] ?? '—')
                        : e($acts_map[$detail['item_id']] ?? '—') ?>
                </dd>
                <dt style="font-weight:600;color:#374151;">Date début</dt>
                <dd><?= e($detail['date_start']) ?></dd>
                <?php if ($detail['date_end'] && $detail['date_end'] !== $detail['date_start']): ?>
                <dt style="font-weight:600;color:#374151;">Date fin</dt>
                <dd><?= e($detail['date_end']) ?> (<?= nights_between($detail['date_start'],$detail['date_end']) ?> nuit(s))</dd>
                <?php endif; ?>
                <dt style="font-weight:600;color:#374151;">Participants</dt>
                <dd><?= (int)$detail['participants'] ?></dd>
                <dt style="font-weight:600;color:#374151;">Total</dt>
                <dd><strong><?= $detail['total_price'] ? number_format((float)$detail['total_price'],2,',', ' ') . ' €' : '—' ?></strong></dd>
                <dt style="font-weight:600;color:#374151;">Message</dt>
                <dd><?= e($detail['message'] ?: '—') ?></dd>
                <dt style="font-weight:600;color:#374151;">Créée le</dt>
                <dd><?= e($detail['created_at']) ?></dd>
            </dl>
        </div>
    </div>

    <div>
        <div class="admin-card">
            <div class="admin-card-header"><h2>👤 Client</h2></div>
            <div class="admin-card-body" style="font-size:.9rem;">
                <p><strong><?= e($detail['firstname'] . ' ' . $detail['lastname']) ?></strong></p>
                <p>✉️ <a href="mailto:<?= e($detail['email']) ?>"><?= e($detail['email']) ?></a></p>
                <?php if ($detail['phone']): ?>
                    <p>📞 <?= e($detail['phone']) ?></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="admin-card" style="margin-top:1rem;">
            <div class="admin-card-header"><h2>⚙️ Actions</h2></div>
            <div class="admin-card-body">
                <form method="POST" action="reservations.php" style="margin-bottom:1rem;">
                    <input type="hidden" name="csrf_token" value="<?= e(admin_csrf_token()) ?>">
                    <input type="hidden" name="post_action" value="status">
                    <input type="hidden" name="id" value="<?= (int)$detail['id'] ?>">
                    <div class="form-mb">
                        <label class="form-label">Changer le statut</label>
                        <select class="form-control" name="status">
                            <option value="pending"   <?= $detail['status']==='pending'   ? 'selected':'' ?>>En attente</option>
                            <option value="confirmed" <?= $detail['status']==='confirmed' ? 'selected':'' ?>>Confirmé</option>
                            <option value="cancelled" <?= $detail['status']==='cancelled' ? 'selected':'' ?>>Annulé</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-admin btn-admin-primary btn-admin-sm">💾 Mettre à jour</button>
                </form>
                <form method="POST" action="reservations.php" onsubmit="return confirm('Supprimer définitivement ?')">
                    <input type="hidden" name="csrf_token" value="<?= e(admin_csrf_token()) ?>">
                    <input type="hidden" name="post_action" value="delete">
                    <input type="hidden" name="id" value="<?= (int)$detail['id'] ?>">
                    <button type="submit" class="btn-admin btn-admin-danger btn-admin-sm">🗑️ Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php else: ?>
<!-- ── Liste des réservations ───────────────────────────────────────────── -->
<!-- Filtres -->
<form method="GET" action="reservations.php" style="display:flex;gap:.5rem;flex-wrap:wrap;margin-bottom:1.25rem;align-items:flex-end;">
    <div>
        <label class="form-label" style="font-size:.8rem;">Recherche</label>
        <input type="text" class="form-control" name="search" placeholder="Nom, email…"
               value="<?= e($search) ?>" style="min-width:200px;">
    </div>
    <div>
        <label class="form-label" style="font-size:.8rem;">Statut</label>
        <select class="form-control" name="status">
            <option value="">Tous</option>
            <option value="pending"   <?= $filter_status==='pending'   ? 'selected':'' ?>>En attente</option>
            <option value="confirmed" <?= $filter_status==='confirmed' ? 'selected':'' ?>>Confirmés</option>
            <option value="cancelled" <?= $filter_status==='cancelled' ? 'selected':'' ?>>Annulés</option>
        </select>
    </div>
    <div>
        <label class="form-label" style="font-size:.8rem;">Type</label>
        <select class="form-control" name="type">
            <option value="">Tous</option>
            <option value="room"     <?= $filter_type==='room'     ? 'selected':'' ?>>Hébergement</option>
            <option value="activity" <?= $filter_type==='activity' ? 'selected':'' ?>>Activité</option>
        </select>
    </div>
    <button type="submit" class="btn-admin btn-admin-primary">🔍 Filtrer</button>
    <a href="reservations.php" class="btn-admin btn-admin-outline">Réinitialiser</a>
</form>

<div class="admin-card">
    <?php if (empty($reservations)): ?>
        <div class="empty-state"><div class="empty-icon">📭</div><p>Aucune réservation trouvée.</p></div>
    <?php else: ?>
    <div style="padding:.75rem 1rem;font-size:.85rem;color:#6b7280;border-bottom:1px solid #e5e7eb;">
        <?= count($reservations) ?> réservation(s)
    </div>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Client</th>
                    <th>Type</th>
                    <th>Item</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($reservations as $r): ?>
                <tr>
                    <td><strong>#<?= str_pad((string)$r['id'], 5, '0', STR_PAD_LEFT) ?></strong></td>
                    <td>
                        <?= e($r['firstname'] . ' ' . $r['lastname']) ?><br>
                        <small style="color:#6b7280;"><?= e($r['email']) ?></small>
                    </td>
                    <td><span class="badge badge-info"><?= $r['type'] === 'room' ? '🏡' : '🦙' ?></span></td>
                    <td><?= e($r['type'] === 'room' ? ($rooms_map[$r['item_id']] ?? '?') : ($acts_map[$r['item_id']] ?? '?')) ?></td>
                    <td><?= e($r['date_start']) ?></td>
                    <td><?= $r['total_price'] ? number_format((float)$r['total_price'],2,',',' ') . ' €' : '—' ?></td>
                    <td><?= status_badge($r['status']) ?></td>
                    <td class="actions">
                        <a href="reservations.php?id=<?= (int)$r['id'] ?>" class="btn-admin btn-admin-sm btn-admin-outline">👁 Voir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
