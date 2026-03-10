<?php
require_once __DIR__ . '/config.php';

$available_langs = ['fr', 'en', 'es'];
$default_lang    = 'fr';

// Changer de langue via GET ?lang=xx
if (isset($_GET['lang']) && in_array($_GET['lang'], $available_langs, true)) {
    $_SESSION['lang'] = $_GET['lang'];
}

$current_lang = $_SESSION['lang'] ?? $default_lang;

$lang_file = __DIR__ . '/../lang/' . $current_lang . '.php';
$lang = file_exists($lang_file)
    ? require $lang_file
    : require __DIR__ . '/../lang/' . $default_lang . '.php';

/**
 * Retourne la traduction d'une clé.
 */
function t(string $key): string {
    global $lang;
    return htmlspecialchars($lang[$key] ?? $key, ENT_QUOTES, 'UTF-8');
}

/**
 * Retourne la traduction sans échappement HTML (pour attributs JS, etc.).
 */
function t_raw(string $key): string {
    global $lang;
    return $lang[$key] ?? $key;
}
