<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Token CSRF basique pour le login
    if (
        empty($_POST['csrf_token']) ||
        !hash_equals($_SESSION['login_csrf'] ?? '', $_POST['csrf_token'])
    ) {
        $error = 'Requête invalide.';
    } else {
        $username = trim((string)($_POST['username'] ?? ''));
        $password = (string)($_POST['password'] ?? '');

        if ($username && $password) {
            try {
                $db   = getDB();
                $stmt = $db->prepare('SELECT id, password FROM admins WHERE username = ? LIMIT 1');
                $stmt->execute([$username]);
                $admin = $stmt->fetch();

                if ($admin && password_verify($password, $admin['password'])) {
                    // Régénérer session pour prévenir fixation
                    session_regenerate_id(true);
                    $_SESSION['admin_id']       = $admin['id'];
                    $_SESSION['admin_username'] = $username;
                    unset($_SESSION['login_csrf']);
                    redirect(SITE_URL . '/admin/index.php');
                } else {
                    // Délai anti-brute force (500ms)
                    usleep(500000);
                    $error = 'Identifiant ou mot de passe incorrect.';
                }
            } catch (Exception $e) {
                $error = 'Erreur de connexion à la base de données.';
            }
        } else {
            $error = 'Tous les champs sont obligatoires.';
        }
    }
}

// Générer CSRF pour le formulaire
if (empty($_SESSION['login_csrf'])) {
    $_SESSION['login_csrf'] = bin2hex(random_bytes(32));
}
$login_csrf = $_SESSION['login_csrf'];

// Rediriger si déjà connecté
if (!empty($_SESSION['admin_id'])) {
    redirect(SITE_URL . '/admin/index.php');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration — Alpagatitude</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
<div class="admin-login-wrap">
    <div class="login-card">
        <div class="login-logo">🦙</div>
        <h1 class="login-title">Alpagatitude</h1>
        <p class="login-subtitle">Espace Administration</p>

        <?php if ($error): ?>
            <div class="admin-alert admin-alert-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php" novalidate>
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($login_csrf, ENT_QUOTES, 'UTF-8') ?>">
            <div class="form-mb">
                <label class="form-label" for="username">Identifiant</label>
                <input type="text" class="form-control" id="username" name="username"
                       autocomplete="username" required autofocus
                       value="<?= htmlspecialchars((string)($_POST['username'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="form-mb">
                <label class="form-label" for="password">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password"
                       autocomplete="current-password" required>
            </div>
            <button type="submit" class="btn-admin btn-admin-primary" style="width:100%;justify-content:center;padding:.75rem;">
                Se connecter
            </button>
        </form>

        <p style="text-align:center;margin-top:1.5rem;font-size:.82rem;color:#888;">
            <a href="../index.php" style="color:#888;">← Retour au site</a>
        </p>
    </div>
</div>
</body>
</html>
