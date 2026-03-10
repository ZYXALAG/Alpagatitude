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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    admin_csrf_check();
    $post_action = input('post_action');

    if ($post_action === 'save') {
        $id          = (int)input('id');
        $name        = input('name');
        $name_en     = input('name_en');
        $name_es     = input('name_es');
        $description = input('description');
        $desc_en     = input('description_en');
        $desc_es     = input('description_es');
        $price       = (float)str_replace(',', '.', input('price'));
        $duration    = input('duration');
        $max_p       = max(1, (int)input('max_participants', '10'));
        $active      = isset($_POST['active']) ? 1 : 0;

        // Upload image
        $new_image = null;
        try {
            $new_image = upload_image('image', __DIR__ . '/../assets/activities/');
        } catch (RuntimeException $ex) {
            $error = $ex->getMessage();
        }

        if (!$error && (!$name || $price <= 0)) {
            $error = 'Le nom et le prix sont obligatoires.';
        }

        if (!$error) {
            if ($id) {
                $old = $db->prepare('SELECT image FROM activities WHERE id=?');
                $old->execute([$id]);
                $old_row = $old->fetch();
                $img_to_save = $new_image ?? $old_row['image'];
                $stmt = $db->prepare(
                    'UPDATE activities SET name=?, name_en=?, name_es=?,
                     description=?, description_en=?, description_es=?,
                     price=?, duration=?, max_participants=?, active=?, image=? WHERE id=?'
                );
                $stmt->execute([$name, $name_en, $name_es, $description, $desc_en, $desc_es,
                                $price, $duration, $max_p, $active, $img_to_save, $id]);
                $message = 'Activité mise à jour.';
            } else {
                $stmt = $db->prepare(
                    'INSERT INTO activities (name, name_en, name_es, description, description_en, description_es,
                     price, duration, max_participants, active, image) VALUES (?,?,?,?,?,?,?,?,?,?,?)'
                );
                $stmt->execute([$name, $name_en, $name_es, $description, $desc_en, $desc_es,
                                $price, $duration, $max_p, $active, $new_image]);
                $message = 'Activité créée.';
            }
            redirect(SITE_URL . '/admin/activites.php?msg=' . urlencode($message));
        }
    }

    if ($post_action === 'delete') {
        $id = (int)input('id');
        $db->prepare('DELETE FROM activities WHERE id=?')->execute([$id]);
        redirect(SITE_URL . '/admin/activites.php?msg=Activité+supprimée');
    }

    if ($post_action === 'toggle') {
        $id = (int)input('id');
        $db->prepare('UPDATE activities SET active = 1 - active WHERE id=?')->execute([$id]);
        redirect(SITE_URL . '/admin/activites.php');
    }
}

if (!empty($_GET['msg'])) {
    $message = htmlspecialchars(urldecode($_GET['msg']), ENT_QUOTES, 'UTF-8');
}

if ($action === 'edit' && $edit_id) {
    $stmt = $db->prepare('SELECT * FROM activities WHERE id=?');
    $stmt->execute([$edit_id]);
    $editing = $stmt->fetch();
}

$activities = $db->query('SELECT * FROM activities ORDER BY name ASC')->fetchAll();
$page_title = 'Activités';
$active_nav = 'activities';
require_once __DIR__ . '/includes/header.php';
?>

<?php if ($message): ?><div class="admin-alert admin-alert-success"><?= e($message) ?></div><?php endif; ?>
<?php if ($error):   ?><div class="admin-alert admin-alert-error"><?= e($error) ?></div><?php endif; ?>

<?php if ($action === 'new' || $action === 'edit'): ?>
<div class="admin-card" style="max-width:820px;">
    <div class="admin-card-header">
        <h2><?= $editing ? 'Modifier' : 'Ajouter' ?> une activité</h2>
        <a href="activites.php" class="btn-admin btn-admin-outline btn-admin-sm">← Retour</a>
    </div>
    <div class="admin-card-body">
        <form method="POST" action="activites.php" enctype="multipart/form-data" novalidate>
            <input type="hidden" name="csrf_token" value="<?= e(admin_csrf_token()) ?>">
            <input type="hidden" name="post_action" value="save">
            <input type="hidden" name="id" value="<?= (int)($editing['id'] ?? 0) ?>">

            <div class="form-row-3 form-mb">
                <div>
                    <label class="form-label">Nom (FR) *</label>
                    <input type="text" class="form-control" name="name" required
                           value="<?= e($editing['name'] ?? '') ?>">
                </div>
                <div>
                    <label class="form-label">Nom (EN)</label>
                    <input type="text" class="form-control" name="name_en"
                           value="<?= e($editing['name_en'] ?? '') ?>">
                </div>
                <div>
                    <label class="form-label">Nom (ES)</label>
                    <input type="text" class="form-control" name="name_es"
                           value="<?= e($editing['name_es'] ?? '') ?>">
                </div>
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
            <div class="form-row-3 form-mb">
                <div>
                    <label class="form-label">Prix / pers. (€) *</label>
                    <input type="number" class="form-control" name="price" step="0.01" min="0"
                           value="<?= number_format((float)($editing['price'] ?? 0), 2, '.', '') ?>">
                </div>
                <div>
                    <label class="form-label">Durée (ex: 1h30)</label>
                    <input type="text" class="form-control" name="duration"
                           value="<?= e($editing['duration'] ?? '') ?>">
                </div>
                <div>
                    <label class="form-label">Participants max.</label>
                    <input type="number" class="form-control" name="max_participants" min="1"
                           value="<?= (int)($editing['max_participants'] ?? 10) ?>">
                </div>
            </div>
            <div class="form-mb">
                <label class="form-label">Photo</label>
                <?php if (!empty($editing['image'])): ?>
                <div style="margin-bottom:.6rem;">
                    <img src="<?= SITE_URL ?>/assets/activities/<?= e($editing['image']) ?>"
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
                    Activité active (visible sur le site)
                </label>
            </div>
            <div style="display:flex;gap:.75rem;">
                <button type="submit" class="btn-admin btn-admin-primary">
                    <?= $editing ? '💾 Enregistrer' : '➕ Créer' ?>
                </button>
                <a href="activites.php" class="btn-admin btn-admin-outline">Annuler</a>
            </div>
        </form>
    </div>
</div>

<?php else: ?>
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;flex-wrap:wrap;gap:.5rem;">
    <p style="color:#6b7280;font-size:.9rem;"><?= count($activities) ?> activité(s)</p>
    <a href="activites.php?action=new" class="btn-admin btn-admin-primary">+ Ajouter une activité</a>
</div>

<div class="admin-card">
    <?php if (empty($activities)): ?>
        <div class="empty-state">
            <div class="empty-icon">🦙</div>
            <p>Aucune activité. <a href="activites.php?action=new">Créer la première</a></p>
        </div>
    <?php else: ?>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Photo</th>
                    <th>Nom</th>
                    <th>Durée</th>
                    <th>Prix / pers.</th>
                    <th>Max. pers.</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($activities as $a): ?>
                <tr>
                    <td><?= (int)$a['id'] ?></td>
                    <td>
                        <?php if (!empty($a['image'])): ?>
                        <img src="<?= SITE_URL ?>/assets/activities/<?= e($a['image']) ?>"
                             alt="" style="width:56px;height:42px;object-fit:cover;border-radius:4px;">
                        <?php else: ?>🦙<?php endif; ?>
                    </td>
                    <td><?= e($a['name']) ?></td>
                    <td><?= e($a['duration'] ?: '—') ?></td>
                    <td><?= number_format((float)$a['price'], 2, ',', ' ') ?> €</td>
                    <td><?= (int)$a['max_participants'] ?></td>
                    <td><?= $a['active'] ? '<span class="badge badge-success">Actif</span>' : '<span class="badge badge-secondary">Inactif</span>' ?></td>
                    <td class="actions">
                        <a href="activites.php?action=edit&id=<?= (int)$a['id'] ?>" class="btn-admin btn-admin-sm btn-admin-outline">✏️</a>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="csrf_token" value="<?= e(admin_csrf_token()) ?>">
                            <input type="hidden" name="post_action" value="toggle">
                            <input type="hidden" name="id" value="<?= (int)$a['id'] ?>">
                            <button type="submit" class="btn-admin btn-admin-sm btn-admin-warning">
                                <?= $a['active'] ? '🚫' : '✅' ?>
                            </button>
                        </form>
                        <form method="POST" style="display:inline;" onsubmit="return confirm('Supprimer ?')">
                            <input type="hidden" name="csrf_token" value="<?= e(admin_csrf_token()) ?>">
                            <input type="hidden" name="post_action" value="delete">
                            <input type="hidden" name="id" value="<?= (int)$a['id'] ?>">
                            <button type="submit" class="btn-admin btn-admin-sm btn-admin-danger">🗑️</button>
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
