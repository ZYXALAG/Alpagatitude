<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/includes/auth.php';
require_admin();

$lang_dir = __DIR__ . '/../lang/';
$langs    = ['fr' => '🇫🇷 Français', 'en' => '🇬🇧 English', 'es' => '🇪🇸 Español'];

// ── Groupes de clés (pour l'affichage en sections) ────────────────────────────
$GROUPS = [
    '🧭 Navigation'       => ['nav_home','nav_rooms','nav_activities','nav_reservation','nav_contact'],
    '🦸 Héros (accueil)'  => ['hero_title','hero_subtitle','hero_cta_rooms','hero_cta_book'],
    '🏡 À propos'         => ['about_title','about_text','about_alpacas','about_alpacas_text','about_nature','about_nature_text','about_relaxation','about_relaxation_text'],
    '🛏 Hébergements'     => ['rooms_title','rooms_subtitle','rooms_card_capacity','rooms_card_price','rooms_card_btn','rooms_person','rooms_no_rooms','rooms_gallery','rooms_details','rooms_availability'],
    '🦙 Activités'        => ['activities_title','activities_subtitle','activities_duration','activities_price','activities_max','activities_card_btn','activities_no_activities','activities_per_person'],
    '📅 Formulaire de réservation' => ['book_title','book_subtitle','book_type','book_type_room','book_type_activity','book_select_room','book_select_activity','book_checkin','book_checkout','book_activity_date','book_participants','book_firstname','book_lastname','book_email','book_phone','book_message','book_total','book_submit','book_required','book_nights','book_persons'],
    '✅ Confirmation'     => ['confirm_title','confirm_text','confirm_ref','confirm_back','confirm_details'],
    '✉️ Contact'          => ['contact_title','contact_subtitle','contact_name','contact_email','contact_subject','contact_message','contact_submit','contact_success','contact_error','contact_address','contact_email_label','contact_phone_label','contact_hours'],
    '🔻 Pied de page'     => ['footer_tagline','footer_links','footer_legal','footer_privacy','footer_rights','footer_made_with'],
    '⚠️ Erreurs'          => ['error_required','error_email','error_date','error_date_order','error_generic'],
    '⚙️ Admin'            => ['admin_dashboard','admin_rooms','admin_activities','admin_reservations','admin_settings','admin_logout','admin_login','admin_username','admin_password','admin_welcome'],
    '🔖 Statuts'          => ['status_pending','status_confirmed','status_cancelled'],
    '🦙 Noms activités'   => ['act_walk_name','act_feed_name','act_photo_name','act_wool_name','act_hike_name'],
];

// ── Charge les tableaux de traduction actuels ─────────────────────────────────
function load_lang(string $lang_dir, string $code): array {
    $file = $lang_dir . $code . '.php';
    if (!file_exists($file)) return [];
    $data = include $file;
    return is_array($data) ? $data : [];
}

// ── Écrit un fichier de langue de manière sécurisée ──────────────────────────
function save_lang(string $lang_dir, string $code, array $translations): bool {
    $file = $lang_dir . $code . '.php';
    // Recalcule toutes les clés connues dans la langue en cours, fusionne
    $existing = load_lang($lang_dir, $code);
    // Écrase les clés soumises, conserve les autres (lang_name, lang_flag, etc.)
    foreach ($translations as $k => $v) {
        $existing[$k] = $v;
    }
    // Génère le contenu PHP
    $lines = ["<?php", "return ["];
    foreach ($existing as $key => $val) {
        $escaped = str_replace("\\", "\\\\", $val);
        $escaped = str_replace("'",  "\\'",  $escaped);
        $lines[] = "    '" . $key . "' => '" . $escaped . "',";
    }
    $lines[] = "];";
    $content = implode("\n", $lines) . "\n";
    return file_put_contents($file, $content) !== false;
}

$message = '';
$error   = '';

// ── Traitement POST ───────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    admin_csrf_check();

    $ok = true;
    foreach (array_keys($langs) as $code) {
        $submitted = [];
        foreach ($_POST as $field => $val) {
            // Champs nommés : lang_fr[key], lang_en[key], lang_es[key]
            if (strpos($field, 'lang_' . $code) === 0) {
                // récupéré via $_POST['lang_fr'] qui est un tableau
            }
        }
        $submitted = $_POST['lang_' . $code] ?? [];
        if (!is_array($submitted)) continue;
        // Sanitise chaque valeur (text only, pas de balise HTML)
        $clean = [];
        foreach ($submitted as $k => $v) {
            $k = preg_replace('/[^a-z0-9_]/', '', (string)$k);
            $clean[$k] = trim((string)$v);
        }
        if (!save_lang($lang_dir, $code, $clean)) {
            $ok    = false;
            $error = "Impossible d'écrire le fichier lang/$code.php — vérifiez les permissions.";
        }
    }
    if ($ok) $message = 'Traductions enregistrées avec succès.';
}

// ── Charge les valeurs à afficher ─────────────────────────────────────────────
$data = [];
foreach (array_keys($langs) as $code) {
    $data[$code] = load_lang($lang_dir, $code);
}

// ── Collecte toutes les clés définies dans au moins une langue ───────────────
$all_keys = array_unique(array_merge(...array_map('array_keys', $data)));
// Clés déjà couvertes par les groupes
$covered = array_merge(...array_values($GROUPS));
// Clés "autres" non regroupées
$others  = array_diff($all_keys, $covered, ['lang_name','lang_flag','room_cottage_name','room_lodge_name','room_tipi_name']);

$page_title = 'Traductions';
$active_nav = 'translations';
require_once __DIR__ . '/includes/header.php';
?>

<style>
/* ── Styles spécifiques à cette page ── */
.trad-tabs { display:flex; gap:.5rem; margin-bottom:1.5rem; flex-wrap:wrap; }
.trad-tab  { padding:.45rem 1.2rem; border-radius:6px; background:#f3f4f6; border:2px solid transparent;
             cursor:pointer; font-weight:600; font-size:.9rem; transition:.2s; }
.trad-tab.active { background:#2e5a3e; color:#fff; border-color:#2e5a3e; }
.trad-tab:hover:not(.active) { background:#e5e7eb; }

.trad-section { margin-bottom:2rem; }
.trad-section-title {
    font-size:.8rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em;
    color:#6b7280; padding:.5rem 0; border-bottom:2px solid #e5e7eb; margin-bottom:1rem;
}
.trad-row {
    display:grid; grid-template-columns: 200px 1fr 1fr 1fr; gap:.75rem;
    align-items:start; padding:.5rem 0; border-bottom:1px solid #f3f4f6;
}
.trad-key { font-size:.8rem; font-family:monospace; color:#9ca3af; padding-top:.4rem; word-break:break-all; }
.trad-input { width:100%; padding:.4rem .65rem; border:1px solid #d1d5db; border-radius:5px;
              font-size:.88rem; resize:vertical; background:#fff; transition:border-color .2s; }
.trad-input:focus { outline:none; border-color:#2e5a3e; }
.trad-header { display:grid; grid-template-columns:200px 1fr 1fr 1fr; gap:.75rem;
               font-size:.78rem; font-weight:700; color:#374151; padding:.5rem 0;
               position:sticky; top:0; background:var(--admin-bg,#f9fafb); z-index:5;
               border-bottom:2px solid #d1d5db; margin-bottom:.5rem; }
.flag { font-size:1.2rem; vertical-align:middle; margin-right:.25rem; }
.lang-label { display:flex; align-items:center; gap:.4rem; }
@media(max-width:800px){
    .trad-row,.trad-header{ grid-template-columns:1fr; }
    .trad-key { font-weight:700; color:#374151; }
}
</style>

<?php if ($message): ?>
<div class="admin-alert admin-alert-success">✅ <?= e($message) ?></div>
<?php endif; ?>
<?php if ($error): ?>
<div class="admin-alert admin-alert-error">⚠️ <?= e($error) ?></div>
<?php endif; ?>

<form method="POST" action="traductions.php" id="trad-form">
<input type="hidden" name="csrf_token" value="<?= e(admin_csrf_token()) ?>">

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <p style="color:#6b7280;font-size:.95rem;margin:0;">
        Modifiez les textes de l'interface pour chaque langue. <strong>Les chambres et activités</strong> ont leurs propres noms dans <a href="chambres.php">Hébergements</a> et <a href="activites.php">Activités</a>.
    </p>
    <button type="submit" class="admin-btn admin-btn-primary">💾 Enregistrer toutes les traductions</button>
</div>

<!-- Saut rapide vers section -->
<div class="trad-tabs" id="section-tabs">
<?php $i=0; foreach ($GROUPS as $label => $keys): ?>
    <button type="button" class="trad-tab <?= $i===0?'active':'' ?>"
            onclick="scrollToSection('section-<?= $i ?>', this)"><?= htmlspecialchars($label) ?></button>
<?php $i++; endforeach; ?>
</div>

<!-- En-tête colonnes -->
<div class="trad-header">
    <div>Clé</div>
    <?php foreach ($langs as $code => $label): ?>
    <div class="lang-label"><?= $label ?></div>
    <?php endforeach; ?>
</div>

<?php $i = 0; foreach ($GROUPS as $section_label => $keys): ?>
<div class="trad-section" id="section-<?= $i ?>">
    <div class="trad-section-title"><?= htmlspecialchars($section_label) ?></div>
    <?php foreach ($keys as $key): ?>
    <div class="trad-row">
        <div class="trad-key" title="<?= e($key) ?>"><?= e($key) ?></div>
        <?php foreach (array_keys($langs) as $code):
            $val = $data[$code][$key] ?? '';
            $is_long = strlen($val) > 80;
        ?>
        <?php if ($is_long): ?>
        <textarea class="trad-input" name="lang_<?= $code ?>[<?= e($key) ?>]"
                  rows="2"><?= e($val) ?></textarea>
        <?php else: ?>
        <input type="text" class="trad-input" name="lang_<?= $code ?>[<?= e($key) ?>]"
               value="<?= e($val) ?>">
        <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <?php endforeach; ?>
</div>
<?php $i++; endforeach; ?>

<?php if (!empty($others)): ?>
<div class="trad-section" id="section-other">
    <div class="trad-section-title">🔑 Autres clés</div>
    <?php foreach ($others as $key): ?>
    <div class="trad-row">
        <div class="trad-key"><?= e($key) ?></div>
        <?php foreach (array_keys($langs) as $code): $val = $data[$code][$key] ?? ''; ?>
        <input type="text" class="trad-input" name="lang_<?= $code ?>[<?= e($key) ?>]"
               value="<?= e($val) ?>">
        <?php endforeach; ?>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<div style="margin-top:2rem;text-align:right;">
    <button type="submit" class="admin-btn admin-btn-primary">💾 Enregistrer toutes les traductions</button>
</div>
</form>

<script>
function scrollToSection(id, btn) {
    document.querySelectorAll('.trad-tab').forEach(t => t.classList.remove('active'));
    btn.classList.add('active');
    const el = document.getElementById(id);
    if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// Warn on unsaved changes
let changed = false;
document.getElementById('trad-form').addEventListener('input', () => changed = true);
document.getElementById('trad-form').addEventListener('submit', () => changed = false);
window.addEventListener('beforeunload', e => {
    if (changed) { e.preventDefault(); e.returnValue = ''; }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
