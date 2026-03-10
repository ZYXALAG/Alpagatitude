<?php
require_once __DIR__ . '/../../includes/config.php';

/**
 * Vérifie que l'administrateur est connecté.
 * Redirige vers la page de connexion sinon.
 */
function require_admin(): void {
    if (empty($_SESSION['admin_id'])) {
        header('Location: ' . SITE_URL . '/admin/login.php');
        exit;
    }
}

/**
 * Vérifie le token CSRF pour les formulaires admin.
 */
function admin_csrf_check(): void {
    if (
        empty($_POST['csrf_token']) ||
        !hash_equals($_SESSION['admin_csrf'] ?? '', $_POST['csrf_token'])
    ) {
        http_response_code(403);
        die('Requête invalide.');
    }
}

/**
 * Génère le token CSRF admin.
 */
function admin_csrf_token(): string {
    if (empty($_SESSION['admin_csrf'])) {
        $_SESSION['admin_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['admin_csrf'];
}
