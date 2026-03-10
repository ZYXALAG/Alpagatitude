<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/includes/auth.php';
require_admin();

$message = '';
$error   = '';

// ── Traitement POST ──────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    admin_csrf_check();

    $keys = [
        'site_name', 'site_description',
        'address', 'phone', 'contact_email',
        'hours', 'facebook_url', 'instagram_url',
    ];
    foreach ($keys as $key) {
        $val = trim((string)($_POST[$key] ?? ''));
        set_setting($key, $val);
    }

    // Changement de mot de passe admin
    $new_pass = (string)($_POST['new_password'] ?? '');
    $confirm  = (string)($_POST['confirm_password'] ?? '');
    if ($new_pass) {
        if (strlen($new_pass) < 8) {
            $error = 'Le mot de passe doit faire au moins 8 caractères.';
        } elseif ($new_pass !== $confirm) {
            $error = 'Les mots de passe ne correspondent pas.';
        } else {
            $hashed = password_hash($new_pass, PASSWORD_BCRYPT);
            $db = getDB();
            $db->prepare('UPDATE admins SET password=? WHERE id=?')
               ->execute([$hashed, $_SESSION['admin_id']]);
        }
    }

    if (!$error) {
        $message = 'Paramètres enregistrés.';
    }
}

// ── Valeurs actuelles ────────────────────────────────────────────────────────
$settings_keys = ['site_name', 'site_description', 'address', 'phone', 'contact_email', 'hours', 'facebook_url', 'instagram_url'];
$settings = [];
foreach ($settings_keys as $k) {
    $settings[$k] = get_setting($k, '');
}

$page_title = 'Paramètres';
$active_nav = 'settings';
require_once __DIR__ . '/includes/header.php';
?>

<?php if ($message): ?><div class="admin-alert admin-alert-success">✅ <?= e($message) ?></div><?php endif; ?>
<?php if ($error):   ?><div class="admin-alert admin-alert-error">⚠️ <?= e($error) ?></div><?php endif; ?>

<form method="POST" action="parametres.php" novalidate>
    <input type="hidden" name="csrf_token" value="<?= e(admin_csrf_token()) ?>">

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;align-items:start;">

        <!-- Infos du site -->
        <div class="admin-card">
            <div class="admin-card-header"><h2>🌐 Informations du site</h2></div>
            <div class="admin-card-body">
                <div class="form-mb">
                    <label class="form-label">Nom du site</label>
                    <input type="text" class="form-control" name="site_name"
                           value="<?= e($settings['site_name'] ?: SITE_NAME) ?>">
                </div>
                <div class="form-mb">
                    <label class="form-label">Description / Slogan</label>
                    <textarea class="form-control" name="site_description"><?= e($settings['site_description']) ?></textarea>
                </div>
            </div>
        </div>

        <!-- Contact -->
        <div class="admin-card">
            <div class="admin-card-header"><h2>📞 Coordonnées</h2></div>
            <div class="admin-card-body">
                <div class="form-mb">
                    <label class="form-label">Adresse postale</label>
                    <textarea class="form-control" name="address" rows="2"><?= e($settings['address']) ?></textarea>
                </div>
                <div class="form-row-2 form-mb">
                    <div>
                        <label class="form-label">Téléphone</label>
                        <input type="text" class="form-control" name="phone" value="<?= e($settings['phone']) ?>">
                    </div>
                    <div>
                        <label class="form-label">Email de contact</label>
                        <input type="email" class="form-control" name="contact_email" value="<?= e($settings['contact_email']) ?>">
                    </div>
                </div>
                <div class="form-mb">
                    <label class="form-label">Horaires d'ouverture</label>
                    <input type="text" class="form-control" name="hours" value="<?= e($settings['hours']) ?>"
                           placeholder="Ex: Lun–Dim : 9h–18h">
                </div>
            </div>
        </div>

        <!-- Réseaux sociaux -->
        <div class="admin-card">
            <div class="admin-card-header"><h2>📱 Réseaux sociaux</h2></div>
            <div class="admin-card-body">
                <div class="form-mb">
                    <label class="form-label">URL Facebook</label>
                    <input type="url" class="form-control" name="facebook_url"
                           value="<?= e($settings['facebook_url']) ?>" placeholder="https://facebook.com/...">
                </div>
                <div class="form-mb">
                    <label class="form-label">URL Instagram</label>
                    <input type="url" class="form-control" name="instagram_url"
                           value="<?= e($settings['instagram_url']) ?>" placeholder="https://instagram.com/...">
                </div>
            </div>
        </div>

        <!-- Sécurité -->
        <div class="admin-card">
            <div class="admin-card-header"><h2>🔐 Changer le mot de passe</h2></div>
            <div class="admin-card-body">
                <p style="font-size:.85rem;color:#6b7280;margin-bottom:1rem;">
                    Laissez vide pour conserver le mot de passe actuel.
                </p>
                <div class="form-mb">
                    <label class="form-label">Nouveau mot de passe</label>
                    <input type="password" class="form-control" name="new_password" autocomplete="new-password">
                    <p class="form-hint">Minimum 8 caractères</p>
                </div>
                <div class="form-mb">
                    <label class="form-label">Confirmer le mot de passe</label>
                    <input type="password" class="form-control" name="confirm_password" autocomplete="new-password">
                </div>
            </div>
        </div>

    </div>

    <div style="margin-top:1.25rem;">
        <button type="submit" class="btn-admin btn-admin-primary">💾 Enregistrer les paramètres</button>
    </div>
</form>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
