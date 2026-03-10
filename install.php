<?php
/**
 * ═══════════════════════════════════════════════════════════════
 *  ALPAGATITUDE — Script d'installation
 *  Accès : http://localhost/alpagatitude/install.php
 *  ⚠️ SUPPRIMER CE FICHIER APRÈS L'INSTALLATION !
 * ═══════════════════════════════════════════════════════════════
 */

// Sécurité : désactiver si fichier lock existe
if (file_exists(__DIR__ . '/install.lock')) {
    die('<p style="color:red;font-family:sans-serif;padding:2rem;">
         ⛔ Installation déjà effectuée. Supprimez install.lock pour recommencer.
         </p>');
}

$step    = (int)($_GET['step'] ?? 1);
$errors  = [];
$success = false;

// ── Traitement ──────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $step === 2) {
    $db_host  = trim($_POST['db_host']  ?? 'localhost');
    $db_name  = trim($_POST['db_name']  ?? 'alpagatitude');
    $db_user  = trim($_POST['db_user']  ?? 'root');
    $db_pass  = $_POST['db_pass'] ?? '';
    $admin_user  = trim($_POST['admin_user']  ?? '');
    $admin_pass  = $_POST['admin_pass'] ?? '';
    $admin_email = trim($_POST['admin_email'] ?? '');

    if (!$admin_user)  $errors[] = 'Le nom d\'utilisateur admin est requis.';
    if (strlen($admin_pass) < 8) $errors[] = 'Le mot de passe admin doit faire au moins 8 caractères.';
    if ($admin_pass !== ($_POST['admin_pass2'] ?? '')) $errors[] = 'Les mots de passe ne correspondent pas.';

    if (empty($errors)) {
        try {
            // Connexion PDO
            $dsn = "mysql:host=$db_host;charset=utf8mb4";
            $pdo = new PDO($dsn, $db_user, $db_pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);

            // Créer la BDD
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $pdo->exec("USE `$db_name`");

            // ── Schéma SQL ─────────────────────────────────────────────────
            $sql = "
            CREATE TABLE IF NOT EXISTS rooms (
                id               INT AUTO_INCREMENT PRIMARY KEY,
                name             VARCHAR(150) NOT NULL,
                description      TEXT,
                description_en   TEXT,
                description_es   TEXT,
                capacity         INT DEFAULT 2,
                price_per_night  DECIMAL(10,2) NOT NULL DEFAULT 0,
                image            VARCHAR(255),
                active           TINYINT(1) DEFAULT 1,
                created_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB;

            CREATE TABLE IF NOT EXISTS activities (
                id               INT AUTO_INCREMENT PRIMARY KEY,
                name             VARCHAR(150) NOT NULL,
                name_en          VARCHAR(150),
                name_es          VARCHAR(150),
                description      TEXT,
                description_en   TEXT,
                description_es   TEXT,
                price            DECIMAL(10,2) NOT NULL DEFAULT 0,
                duration         VARCHAR(50),
                max_participants INT DEFAULT 10,
                image            VARCHAR(255),
                active           TINYINT(1) DEFAULT 1,
                created_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB;

            CREATE TABLE IF NOT EXISTS reservations (
                id           INT AUTO_INCREMENT PRIMARY KEY,
                type         ENUM('room','activity') NOT NULL,
                item_id      INT NOT NULL,
                firstname    VARCHAR(100) NOT NULL,
                lastname     VARCHAR(100) NOT NULL,
                email        VARCHAR(255) NOT NULL,
                phone        VARCHAR(30),
                date_start   DATE NOT NULL,
                date_end     DATE,
                participants INT DEFAULT 1,
                total_price  DECIMAL(10,2),
                message      TEXT,
                status       ENUM('pending','confirmed','cancelled') DEFAULT 'pending',
                created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_type_item   (type, item_id),
                INDEX idx_status      (status),
                INDEX idx_date_start  (date_start)
            ) ENGINE=InnoDB;

            CREATE TABLE IF NOT EXISTS admins (
                id         INT AUTO_INCREMENT PRIMARY KEY,
                username   VARCHAR(80)  NOT NULL UNIQUE,
                password   VARCHAR(255) NOT NULL,
                email      VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB;

            CREATE TABLE IF NOT EXISTS settings (
                id            INT AUTO_INCREMENT PRIMARY KEY,
                setting_key   VARCHAR(100) NOT NULL UNIQUE,
                setting_value TEXT
            ) ENGINE=InnoDB;
            ";

            foreach (array_filter(explode(';', $sql)) as $query) {
                $q = trim($query);
                if ($q) $pdo->exec($q);
            }

            // ── Données de démonstration ────────────────────────────────
            // Hébergements
            $pdo->exec("INSERT IGNORE INTO rooms (name, description, description_en, description_es, capacity, price_per_night, image, active) VALUES
                ('Le Chalet Alpagas',
                 'Un chalet charmant et chaleureux au cœur du domaine, avec une vue imprenable sur les prairies des alpagas. Équipé d\\'une cuisine, d\\'un salon cosy et de 2 chambres.',
                 'A charming and cozy chalet in the heart of the domain, with a breathtaking view of the alpaca meadows. Equipped with a kitchen, a cozy living room and 2 bedrooms.',
                 'Un acogedor chalet en el corazón del dominio, con vistas impresionantes a los prados de las alpacas.',
                 4, 120.00, 'chalet-alpagas.svg', 1),
                ('La Lodge du Pré',
                 'Une lodge spacieuse et confortable bordant les pâturages. Parfaite pour les familles ou les groupes, avec terrasse et accès direct aux alpagas.',
                 'A spacious and comfortable lodge bordering the pastures. Perfect for families or groups, with a terrace and direct access to the alpacas.',
                 'Una lodge espaciosa y cómoda junto a los pastizales. Perfecta para familias o grupos.',
                 6, 180.00, 'lodge-pre.svg', 1),
                ('Le Tipi Étoilé',
                 'Vivez une expérience insolite dans notre tipi confortable, idéal pour les couples. Dormez à la belle étoile tout en restant au chaud, et réveillez-vous avec les alpagas.',
                 'Live an unusual experience in our comfortable tipi, ideal for couples. Sleep under the stars while staying warm, and wake up with the alpacas.',
                 'Vive una experiencia insólita en nuestro cómodo tipi, ideal para parejas.',
                 2, 90.00, 'tipi-etoile.svg', 1)
            ");

            // Activités
            $pdo->exec("INSERT IGNORE INTO activities (name, name_en, name_es, description, description_en, description_es, price, duration, max_participants, image, active) VALUES
                ('Balade avec les alpagas', 'Walk with the alpacas', 'Paseo con las alpacas',
                 'Partez en balade dans les chemins bucoliques du domaine accompagnés de nos alpagas. Une expérience unique et inoubliable pour petits et grands.',
                 'Take a walk along the bucolic paths of the domain accompanied by our alpacas. A unique and unforgettable experience for young and old.',
                 'Pasee por los caminos bucólicos del dominio acompañado de nuestras alpacas.',
                 25.00, '1h30', 8, 'balade-alpagas.svg', 1),
                ('Nourrissage & câlins', 'Feeding & cuddles', 'Alimentación y caricias',
                 'Approchez les alpagas, donnez-leur à manger et profitez de leurs caresses. Idéal pour les enfants et les amoureux des animaux.',
                 'Approach the alpacas, feed them and enjoy their cuddles. Ideal for children and animal lovers.',
                 'Acércate a las alpacas, dales de comer y disfruta de sus caricias.',
                 15.00, '45min', 12, 'nourrissage.svg', 1),
                ('Séance photo avec les alpagas', 'Photo session with alpacas', 'Sesión de fotos con alpacas',
                 'Immortalisez votre visite avec une séance photo professionnelle aux côtés de nos magnifiques alpagas. Des souvenirs uniques garantis !',
                 'Immortalize your visit with a professional photo session alongside our beautiful alpacas. Unique memories guaranteed!',
                 'Inmortaliza tu visita con una sesión de fotos junto a nuestras hermosas alpacas.',
                 35.00, '1h', 6, 'seance-photo.svg', 1),
                ('Atelier laine & tonte', 'Wool & shearing workshop', 'Taller de lana y esquila',
                 'Découvrez les secrets de la laine d\\'alpaga : tonte, cardage, filage... Un atelier artisanal passionnant pour toute la famille.',
                 'Discover the secrets of alpaca wool: shearing, carding, spinning... A fascinating craft workshop for the whole family.',
                 'Descubra los secretos de la lana de alpaca: esquila, cardado, hilado...',
                 40.00, '2h', 10, 'atelier-laine.svg', 1),
                ('Randonnée accompagnée', 'Guided hike', 'Senderismo acompañado',
                 'Explorez les sentiers naturels entourant le domaine en compagnie de nos guides et de quelques alpagas. Paysages grandioses garantis !',
                 'Explore the natural trails surrounding the domain with our guides and some alpacas. Breathtaking landscapes guaranteed!',
                 'Explora los senderos naturales alrededor del dominio con nuestros guías y algunas alpacas.',
                 45.00, '3h', 10, 'randonnee.svg', 1)
            ");

            // Paramètres par défaut
            $defaults = [
                'site_name'        => 'Alpagatitude',
                'site_description' => 'Un domaine unique au cœur de la nature, où vous rencontrerez nos adorables alpagas.',
                'address'          => '123 Route des Alpagas, 00000 La Montagne',
                'phone'            => '+33 (0)6 00 00 00 00',
                'contact_email'    => $admin_email ?: 'admin@alpagatitude.com',
                'hours'            => 'Lun–Dim : 9h–18h (saison) / 10h–16h (hiver)',
                'facebook_url'     => '',
                'instagram_url'    => '',
            ];
            $settingStmt = $pdo->prepare(
                'INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)
                 ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)'
            );
            foreach ($defaults as $k => $v) {
                $settingStmt->execute([$k, $v]);
            }

            // Admin
            $hashed = password_hash($admin_pass, PASSWORD_BCRYPT);
            $pdo->prepare(
                'INSERT INTO admins (username, password, email) VALUES (?,?,?)
                 ON DUPLICATE KEY UPDATE password=VALUES(password)'
            )->execute([$admin_user, $hashed, $admin_email]);

            // Mettre à jour config.php avec les paramètres DB
            $config_content = file_get_contents(__DIR__ . '/includes/config.php');
            $config_content = preg_replace("/define\('DB_HOST',\s*'[^']*'\)/",  "define('DB_HOST', '$db_host')",  $config_content);
            $config_content = preg_replace("/define\('DB_NAME',\s*'[^']*'\)/",  "define('DB_NAME', '$db_name')",  $config_content);
            $config_content = preg_replace("/define\('DB_USER',\s*'[^']*'\)/",  "define('DB_USER', '$db_user')",  $config_content);
            $config_content = preg_replace("/define\('DB_PASS',\s*'[^']*'\)/",  "define('DB_PASS', '$db_pass')",  $config_content);
            file_put_contents(__DIR__ . '/includes/config.php', $config_content);

            // Créer le fichier lock
            file_put_contents(__DIR__ . '/install.lock', date('Y-m-d H:i:s'));
            $success = true;

        } catch (PDOException $e) {
            $errors[] = 'Erreur MySQL : ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        } catch (Exception $e) {
            $errors[] = 'Erreur : ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installation — Alpagatitude</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', system-ui, sans-serif; background: #f0ebe0; color: #2a2a2a; line-height: 1.6; }
        .wrap { max-width: 640px; margin: 3rem auto; padding: 0 1rem; }
        .card { background: #fff; border-radius: 12px; padding: 2.5rem; box-shadow: 0 4px 24px rgba(0,0,0,.12); }
        .logo { text-align: center; font-size: 3.5rem; margin-bottom: .5rem; }
        h1 { text-align: center; color: #2e5a3e; font-size: 1.6rem; margin-bottom: .25rem; }
        .subtitle { text-align: center; color: #888; font-size: .9rem; margin-bottom: 2rem; }
        .steps { display: flex; justify-content: center; gap: 1.5rem; margin-bottom: 2rem; }
        .step { display: flex; align-items: center; gap: .4rem; font-size: .85rem; color: #888; }
        .step-n { width: 26px; height: 26px; border-radius: 50%; background: #e5e7eb; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: .8rem; }
        .step.active .step-n { background: #2e5a3e; color: #fff; }
        label { display: block; font-weight: 600; font-size: .88rem; margin-bottom: .35rem; color: #374151; }
        input { width: 100%; padding: .6rem .9rem; border: 1.5px solid #d1d5db; border-radius: 6px; font-size: .9rem; margin-bottom: 1rem; outline: none; }
        input:focus { border-color: #2e5a3e; box-shadow: 0 0 0 3px rgba(46,90,62,.14); }
        .row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .btn { width: 100%; padding: .8rem; background: #2e5a3e; color: #fff; border: none; border-radius: 6px; font-size: 1rem; font-weight: 700; cursor: pointer; margin-top: .5rem; }
        .btn:hover { background: #1e3d2a; }
        .alert { padding: .85rem 1rem; border-radius: 6px; margin-bottom: 1.25rem; font-size: .9rem; border-left: 3px solid; }
        .alert-error { background: #fdf0f0; border-color: #c0392b; color: #7b1d1d; }
        .alert-success { background: #f0faf4; border-color: #2e7d32; color: #1e5631; }
        hr { border: 0; border-top: 1px solid #e5e7eb; margin: 1.5rem 0; }
        .warning-box { background: #fffbf0; border: 1px solid #d4a843; border-radius: 6px; padding: 1rem; font-size: .88rem; margin-bottom: 1.5rem; }
        a.link-btn { display: inline-block; padding: .7rem 1.8rem; background: #2e5a3e; color: #fff; border-radius: 6px; font-weight: 700; text-decoration: none; }
        a.link-btn:hover { background: #1e3d2a; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <div class="logo">🦙</div>
        <h1>Alpagatitude</h1>
        <p class="subtitle">Assistant d'installation</p>

        <div class="steps">
            <div class="step <?= $step <= 1 ? 'active' : '' ?>">
                <span class="step-n">1</span><span>Bienvenue</span>
            </div>
            <div class="step <?= $step >= 2 && !$success ? 'active' : '' ?>">
                <span class="step-n">2</span><span>Configuration</span>
            </div>
            <div class="step <?= $success ? 'active' : '' ?>">
                <span class="step-n">3</span><span>Terminé</span>
            </div>
        </div>

        <?php if ($success): ?>
        <!-- ── Succès ─────────────────────────────────────────────────── -->
        <div class="alert alert-success">
            ✅ Installation réussie ! La base de données a été créée et les données de démonstration ont été insérées.
        </div>
        <div class="warning-box">
            <strong>⚠️ Important :</strong> Supprimez le fichier <code>install.php</code> ou le fichier
            <code>install.lock</code> de votre serveur pour des raisons de sécurité.
        </div>
        <div style="text-align:center;display:flex;flex-direction:column;gap:1rem;align-items:center;">
            <a href="index.php" class="link-btn">🌿 Voir le site</a>
            <a href="admin/login.php" class="link-btn" style="background:#b8860b;">🔐 Accéder à l'administration</a>
        </div>

        <?php elseif ($step === 1): ?>
        <!-- ── Étape 1 ────────────────────────────────────────────────── -->
        <p>Bienvenue dans l'assistant d'installation d'<strong>Alpagatitude</strong>. Ce script va :</p>
        <ul style="margin:1rem 0 1rem 1.5rem;font-size:.9rem;color:#555;">
            <li>Créer la base de données MySQL</li>
            <li>Créer les tables nécessaires</li>
            <li>Insérer des données de démonstration</li>
            <li>Créer votre compte administrateur</li>
            <li>Mettre à jour includes/config.php</li>
        </ul>
        <div class="warning-box">
            <strong>Prérequis :</strong> PHP 8.0+, MySQL 5.7+ ou MariaDB 10.3+, extension PDO_MySQL activée.
        </div>
        <a href="install.php?step=2" class="link-btn" style="display:block;text-align:center;">Continuer →</a>

        <?php else: ?>
        <!-- ── Étape 2 ────────────────────────────────────────────────── -->
        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <ul style="margin:0 0 0 1rem;">
                    <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="install.php?step=2" novalidate>
            <p style="font-weight:700;margin-bottom:1rem;color:#2e5a3e;">🗄️ Base de données</p>
            <div class="row">
                <div>
                    <label for="db_host">Hôte MySQL</label>
                    <input type="text" id="db_host" name="db_host" value="<?= htmlspecialchars($_POST['db_host'] ?? 'localhost', ENT_QUOTES) ?>">
                </div>
                <div>
                    <label for="db_name">Nom de la base</label>
                    <input type="text" id="db_name" name="db_name" value="<?= htmlspecialchars($_POST['db_name'] ?? 'alpagatitude', ENT_QUOTES) ?>">
                </div>
            </div>
            <div class="row">
                <div>
                    <label for="db_user">Utilisateur MySQL</label>
                    <input type="text" id="db_user" name="db_user" value="<?= htmlspecialchars($_POST['db_user'] ?? 'root', ENT_QUOTES) ?>">
                </div>
                <div>
                    <label for="db_pass">Mot de passe MySQL</label>
                    <input type="password" id="db_pass" name="db_pass">
                </div>
            </div>
            <hr>
            <p style="font-weight:700;margin-bottom:1rem;color:#2e5a3e;">🔐 Compte administrateur</p>
            <label for="admin_user">Identifiant admin *</label>
            <input type="text" id="admin_user" name="admin_user" value="<?= htmlspecialchars($_POST['admin_user'] ?? 'admin', ENT_QUOTES) ?>" required>
            <label for="admin_email">Email admin</label>
            <input type="email" id="admin_email" name="admin_email" value="<?= htmlspecialchars($_POST['admin_email'] ?? '', ENT_QUOTES) ?>">
            <div class="row">
                <div>
                    <label for="admin_pass">Mot de passe * <small>(min. 8 car.)</small></label>
                    <input type="password" id="admin_pass" name="admin_pass" required>
                </div>
                <div>
                    <label for="admin_pass2">Confirmer *</label>
                    <input type="password" id="admin_pass2" name="admin_pass2" required>
                </div>
            </div>
            <button type="submit" class="btn">🚀 Lancer l'installation</button>
        </form>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
