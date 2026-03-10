<?php
require_once __DIR__ . '/config.php';

/**
 * Échappe une valeur pour l'affichage HTML.
 */
function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

/**
 * Génère ou récupère le token CSRF enregistré en session.
 */
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Vérifie le token CSRF soumis via POST.
 */
function csrf_check(): void {
    if (
        empty($_POST['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])
    ) {
        http_response_code(403);
        die('Requête invalide (CSRF).');
    }
}

/**
 * Redirige vers l'URL donnée.
 */
function redirect(string $url): void {
    header('Location: ' . $url);
    exit;
}

/**
 * Récupère un paramètre GET/POST nettoyé.
 */
function input(string $key, string $default = '', string $method = 'POST'): string {
    $source = $method === 'GET' ? $_GET : $_POST;
    return trim((string)($source[$key] ?? $default));
}

/**
 * Formate un prix en euros.
 */
function format_price(float $price): string {
    return number_format($price, 2, ',', ' ') . ' €';
}

/**
 * Formate une date au format local.
 */
function format_date(string $date, string $lang = 'fr'): string {
    $ts = strtotime($date);
    if (!$ts) return $date;
    $months_fr = ['', 'janvier', 'février', 'mars', 'avril', 'mai', 'juin',
                  'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];
    $months_en = ['', 'January', 'February', 'March', 'April', 'May', 'June',
                  'July', 'August', 'September', 'October', 'November', 'December'];
    $months_es = ['', 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
                  'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
    $d = (int)date('j', $ts);
    $m = (int)date('n', $ts);
    $y = date('Y', $ts);
    if ($lang === 'en') {
        return $months_en[$m] . ' ' . $d . ', ' . $y;
    } elseif ($lang === 'es') {
        return $d . ' de ' . $months_es[$m] . ' de ' . $y;
    } else {
        return $d . ' ' . $months_fr[$m] . ' ' . $y;
    }
}

/**
 * Calcule le nombre de nuits entre deux dates.
 */
function nights_between(string $checkin, string $checkout): int {
    $d1 = new DateTime($checkin);
    $d2 = new DateTime($checkout);
    return max(0, (int)$d1->diff($d2)->days);
}

/**
 * Récupère un paramètre de configuration depuis la table settings.
 */
function get_setting(string $key, string $default = ''): string {
    try {
        $db  = getDB();
        $stmt = $db->prepare('SELECT setting_value FROM settings WHERE setting_key = ? LIMIT 1');
        $stmt->execute([$key]);
        $row = $stmt->fetch();
        return $row ? (string)$row['setting_value'] : $default;
    } catch (Exception $e) {
        return $default;
    }
}

/**
 * Met à jour ou insère un paramètre de configuration.
 */
function set_setting(string $key, string $value): void {
    $db = getDB();
    $stmt = $db->prepare('INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)
                          ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)');
    $stmt->execute([$key, $value]);
}

/**
 * Sanitise et valide une adresse email.
 */
function valid_email(string $email): bool {
    return (bool)filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Retourne le statut d'une réservation avec badge HTML coloré.
 */
function status_badge(string $status): string {
    $map = [
        'pending'   => ['label' => 'En attente', 'class' => 'badge-warning'],
        'confirmed' => ['label' => 'Confirmé',   'class' => 'badge-success'],
        'cancelled' => ['label' => 'Annulé',      'class' => 'badge-danger'],
    ];
    $info = $map[$status] ?? ['label' => $status, 'class' => 'badge-secondary'];
    return '<span class="badge ' . $info['class'] . '">' . e($info['label']) . '</span>';
}

/**
 * Gère l'upload sécurisé d'une image.
 * Retourne le nom de fichier généré, ou null si aucun fichier soumis.
 * Lance une RuntimeException en cas d'erreur.
 *
 * @param string $file_key  Clé dans $_FILES
 * @param string $dest_dir  Chemin absolu du dossier de destination
 * @return string|null
 */
function upload_image(string $file_key, string $dest_dir): ?string {
    if (!isset($_FILES[$file_key]) || $_FILES[$file_key]['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    $file = $_FILES[$file_key];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $codes = [
            UPLOAD_ERR_INI_SIZE   => 'Fichier trop volumineux (limite serveur).',
            UPLOAD_ERR_FORM_SIZE  => 'Fichier trop volumineux.',
            UPLOAD_ERR_PARTIAL    => 'Upload incomplet.',
            UPLOAD_ERR_NO_TMP_DIR => 'Dossier temporaire introuvable.',
            UPLOAD_ERR_CANT_WRITE => 'Impossible d\'écrire sur le disque.',
        ];
        throw new RuntimeException($codes[$file['error']] ?? 'Erreur upload (code ' . $file['error'] . ').');
    }
    if ($file['size'] > 5 * 1024 * 1024) {
        throw new RuntimeException('Fichier trop lourd (max 5 Mo).');
    }
    // Validation du type MIME réel (pas du champ client)
    if (function_exists('finfo_open')) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
    } else {
        $mime = mime_content_type($file['tmp_name']);
    }
    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif',
    ];
    if (!isset($allowed[$mime])) {
        throw new RuntimeException('Format non supporté. Utilisez JPG, PNG ou WebP.');
    }
    $ext      = $allowed[$mime];
    $filename = bin2hex(random_bytes(8)) . '.' . $ext;
    if (!is_dir($dest_dir)) {
        mkdir($dest_dir, 0755, true);
    }
    if (!move_uploaded_file($file['tmp_name'], rtrim($dest_dir, '/') . '/' . $filename)) {
        throw new RuntimeException('Impossible de sauvegarder le fichier uploadé.');
    }
    return $filename;
}

/**
 * Vérifie si une chambre est disponible pour les dates données.
 */
function is_room_available(int $room_id, string $checkin, string $checkout, int $exclude_id = 0): bool {
    $db   = getDB();
    $sql  = 'SELECT COUNT(*) FROM reservations
             WHERE type = "room" AND item_id = ? AND status != "cancelled"
               AND date_start < ? AND date_end > ?
               AND id != ?';
    $stmt = $db->prepare($sql);
    $stmt->execute([$room_id, $checkout, $checkin, $exclude_id]);
    return (int)$stmt->fetchColumn() === 0;
}
