<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/includes/auth.php';
require_admin();

$db      = getDB();
$action  = input('action', '', 'GET');
$edit_id = (int)input('id', '0', 'GET');
$message = '';
$error   = '';
$editing = null;

// ── Traitement POST ──────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    admin_csrf_check();
    $post_action = input('post_action');

    if ($post_action === 'save') {
        $id          = (int)input('id');
        $name        = input('name');
        $description = input('description');
        $desc_en     = input('description_en');
        $desc_es     = input('description_es');
        $capacity    = max(1, (int)input('capacity', '2'));
        $price       = (float)str_replace(',', '.', input('price'));
        $active      = isset($_POST['active']) ? 1 : 0;

        // Upload image
        $new_image = null;
        try {
            $new_image = upload_image('image', __DIR__ . '/../assets/rooms/');
        } catch (RuntimeException $ex) {
            $error = $ex->getMessage();
        }

        if (!$error && (!$name || $price <= 0)) {
            $error = 'Le nom et le prix sont obligatoires.';
        }

        if (!$error) {
            if ($id) {
                $old = $db->prepare('SELECT image FROM rooms WHERE id=?');
                $old->execute([$id]);
                $old_row = $old->fetch();
                $img_to_save = $new_image ?? $old_row['image'];
                $stmt = $db->prepare(
                    'UPDATE rooms SET name=?, description=?, description_en=?, description_es=?,
                     capacity=?, price_per_night=?, active=?, image=? WHERE id=?'
                );
                $stmt->execute([$name, $description, $desc_en, $desc_es, $capacity, $price, $active, $img_to_save, $id]);
                $message = 'Hébergement mis à jour.';
            } else {
                $stmt = $db->prepare(
                    'INSERT INTO rooms (name, description, description_en, description_es, capacity, price_per_night, active, image)
                     VALUES (?,?,?,?,?,?,?,?)'
                );
                $stmt->execute([$name, $description, $desc_en, $desc_es, $capacity, $price, $active, $new_image]);
                $message = 'Hébergement créé.';
            }
            redirect(SITE_URL . '/admin/chambres.php?msg=' . urlencode($message));
        }
    }

    if ($post_action === 'delete') {
        $id = (int)input('id');
        $db->prepare('DELETE FROM rooms WHERE id=?')->execute([$id]);
        redirect(SITE_URL . '/admin/chambres.php?msg=Hébergement+supprimé');
    }

    if ($post_action === 'toggle') {
        $id = (int)input('id');
        $db->prepare('UPDATE rooms SET active = 1 - active WHERE id=?')->execute([$id]);
        redirect(SITE_URL . '/admin/chambres.php');
    }
}

// ── Message GET ──────────────────────────────────────────────────────────────
if (!empty($_GET['msg'])) {
    $message = htmlspecialchars(urldecode($_GET['msg']), ENT_QUOTES, 'UTF-8');
}

// ── Chargement si édition ────────────────────────────────────────────────────
if ($action === 'edit' && $edit_id) {
    $stmt = $db->prepare('SELECT * FROM rooms WHERE id=?');
    $stmt->execute([$edit_id]);
    $editing = $stmt->fetch();
}

$rooms      = $db->query('SELECT * FROM rooms ORDER BY name ASC')->fetchAll();
$page_title = 'Hébergements';
$active_nav = 'rooms';
require_once __DIR__ . '/includes/header.php';
?>

<?php if ($message): ?><div class="admin-alert admin-alert-success"><?= e($message) ?></div><?php endif; ?>
<?php if ($error):   ?><div class="admin-alert admin-alert-error"><?= e($error) ?></div><?php endif; ?>

<?php if ($action === 'new' || $action === 'edit'): ?>
<!-- ── Formulaire ──────────────────────────────────────────────────────── -->
<div class="admin-card" style="max-width:760px;">
    <div class="admin-card-header">
        <h2><?= $editing ? 'Modifier' : 'Ajouter' ?> un hébergement</h2>
        <a href="chambres.php" class="btn-admin btn-admin-outline btn-admin-sm">← Retour</a>
    </div>
    <div class="admin-card-body">
        <form method="POST" action="chambres.php" enctype="multipart/form-data" novalidate>
            <input type="hidden" name="csrf_token" value="<?= e(admin_csrf_token()) ?>">
            <input type="hidden" name="post_action" value="save">
            <input type="hidden" name="id" value="<?= (int)($editing['id'] ?? 0) ?>">

            <div class="form-mb">
                <label class="form-label">Nom *</label>
                <input type="text" class="form-control" name="name" required
                       value="<?= e($editing['name'] ?? '') ?>">
            </div>
            <div class="form-mb">
                <label class="form-label">Description (FR)</label>
                <textarea class="form-control" name="description"><?= e($editing['description'] ?? '') ?></textarea>
            </div>
            <div class="form-row-2 form-mb">
                <div>
                    <label class="form-label">Description (EN)</label>
                    <textarea class="form-control" name="description_en"><?= e($editing['description_en'] ?? '') ?></textarea>
                </div>
                <div>
                    <label class="form-label">Description (ES)</label>
                    <textarea class="form-control" name="description_es"><?= e($editing['description_es'] ?? '') ?></textarea>
                </div>
            </div>
            <div class="form-row-2 form-mb">
                <div>
                    <label class="form-label">Capacité (personnes)</label>
                    <input type="number" class="form-control" name="capacity" min="1" max="20"
                           value="<?= (int)($editing['capacity'] ?? 2) ?>">
                </div>
                <div>
                    <label class="form-label">Prix / nuit (€) *</label>
                    <input type="number" class="form-control" name="price" step="0.01" min="0"
                           value="<?= number_format((float)($editing['price_per_night'] ?? 0), 2, '.', '') ?>">
                </div>
            </div>
            <div class="form-mb">
                <label class="form-label">Photo</label>
                <?php if (!empty($editing['image'])): ?>
                <div style="margin-bottom:.6rem;">
                    <img src="<?= SITE_URL ?>/assets/rooms/<?= e($editing['image']) ?>"
                         alt="Photo actuelle" style="max-width:200px;border-radius:6px;border:1px solid #e5e7eb;">
                    <p style="font-size:.8rem;color:#6b7280;margin-top:.3rem;">Photo actuelle · laisser vide pour conserver</p>
                </div>
                <?php endif; ?>
                <input type="file" class="form-control" name="image" accept="image/jpeg,image/png,image/webp">
                <small style="color:#6b7280;">JPG, PNG ou WebP · max 5 Mo</small>
            </div>
            <div class="form-mb">
                <label class="form-check">
                    <input type="checkbox" name="active" value="1"
                           <?= !isset($editing) || $editing['active'] ? 'checked' : '' ?>>
                    Hébergement actif (visible sur le site)
                </label>
            </div>
            <div style="display:flex;gap:.75rem;">
                <button type="submit" class="btn-admin btn-admin-primary">
                    <?= $editing ? '💾 Enregistrer' : '➕ Créer' ?>
                </button>
                <a href="chambres.php" class="btn-admin btn-admin-outline">Annuler</a>
            </div>
        </form>
    </div>
</div>

<?php else: ?>
<!-- ── Liste ───────────────────────────────────────────────────────────── -->
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;flex-wrap:wrap;gap:.5rem;">
    <p style="color:#6b7280;font-size:.9rem;"><?= count($rooms) ?> hébergement(s)</p>
    <a href="chambres.php?action=new" class="btn-admin btn-admin-primary">+ Ajouter un hébergement</a>
</div>

<div class="admin-card">
    <?php if (empty($rooms)): ?>
        <div class="empty-state">
            <div class="empty-icon">🏡</div>
            <p>Aucun hébergement. <a href="chambres.php?action=new">Créer le premier</a></p>
        </div>
    <?php else: ?>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Photo</th>
                    <th>Nom</th>
                    <th>Capacité</th>
                    <th>Prix / nuit</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($rooms as $r): ?>
                <tr>
                    <td><?= (int)$r['id'] ?></td>
                    <td>
                        <?php if (!empty($r['image'])): ?>
                        <img src="<?= SITE_URL ?>/assets/rooms/<?= e($r['image']) ?>"
                             alt="" style="width:56px;height:42px;object-fit:cover;border-radius:4px;">
                        <?php else: ?>🏡<?php endif; ?>
                    </td>
                    <td><?= e($r['name']) ?></td>
                    <td>👥 <?= (int)$r['capacity'] ?></td>
                    <td><?= number_format((float)$r['price_per_night'], 2, ',', ' ') ?> €</td>
                    <td>
                        <?php if ($r['active']): ?>
                            <span class="badge badge-success">Actif</span>
                        <?php else: ?>
                            <span class="badge badge-secondary">Inactif</span>
                        <?php endif; ?>
                    </td>
                    <td class="actions">
                        <a href="chambres.php?action=edit&id=<?= (int)$r['id'] ?>" class="btn-admin btn-admin-sm btn-admin-outline">✏️ Modifier</a>
                        <form method="POST" style="display:inline;" onsubmit="return confirm('Désactiver / activer ?')">
                            <input type="hidden" name="csrf_token" value="<?= e(admin_csrf_token()) ?>">
                            <input type="hidden" name="post_action" value="toggle">
                            <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                            <button type="submit" class="btn-admin btn-admin-sm btn-admin-warning">
                                <?= $r['active'] ? '🚫 Désactiver' : '✅ Activer' ?>
                            </button>
                        </form>
                        <form method="POST" style="display:inline;" onsubmit="return confirm('Supprimer définitivement ?')">
                            <input type="hidden" name="csrf_token" value="<?= e(admin_csrf_token()) ?>">
                            <input type="hidden" name="post_action" value="delete">
                            <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                            <button type="submit" class="btn-admin btn-admin-sm btn-admin-danger">🗑️ Suppr.</button>
                        </form>
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
