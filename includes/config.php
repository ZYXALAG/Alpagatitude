<?php
// ─── Configuration base de données ──────────────────────────────────────────
define('DB_HOST', 'localhost');
define('DB_NAME', 'alpagatitude');
define('DB_USER', 'root');
define('DB_PASS', 'root');

// ─── Configuration site ──────────────────────────────────────────────────────
define('SITE_NAME', 'Alpagatitude');
define('SITE_URL', '/alpagatitude');  // Chemin depuis la racine du serveur, sans slash final
define('ADMIN_EMAIL', 'admin@alpagatitude.com');

// ─── Paramètres d'affichage des erreurs (mettre à 0 en production) ───────────
ini_set('display_errors', 1);
error_reporting(E_ALL);

// ─── Session sécurisée ───────────────────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/', '', false, true);
    session_start();
}
