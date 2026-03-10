<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/lang.php';

$errors   = [];
$success  = false;
$booking  = null;

// Pré-sélection depuis l'URL
$preselect_type  = in_array(input('type', '', 'GET'), ['room', 'activity']) ? input('type', '', 'GET') : 'room';
$preselect_id    = (int)input('id', '0', 'GET');

// Chargement des données
try {
    $db         = getDB();
    $rooms      = $db->query('SELECT * FROM rooms WHERE active = 1 ORDER BY name ASC')->fetchAll();
    $activities = $db->query('SELECT * FROM activities WHERE active = 1 ORDER BY name ASC')->fetchAll();
} catch (Exception $e) {
    $rooms = $activities = [];
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    $type         = in_array(input('type'), ['room', 'activity']) ? input('type') : '';
    $item_id      = (int)input('item_id');
    $firstname    = input('firstname');
    $lastname     = input('lastname');
    $email        = input('email');
    $phone        = input('phone');
    $date_start   = input('date_start');
    $date_end     = input('date_end');
    $participants = max(1, (int)input('participants', '1'));
    $message      = input('message');

    // Validations
    if (!$type)      $errors['type']      = t_raw('error_required');
    if (!$item_id)   $errors['item_id']   = t_raw('error_required');
    if (!$firstname) $errors['firstname'] = t_raw('error_required');
    if (!$lastname)  $errors['lastname']  = t_raw('error_required');
    if (!$email)     $errors['email']     = t_raw('error_required');
    elseif (!valid_email($email)) $errors['email'] = t_raw('error_email');
    if (!$date_start) $errors['date_start'] = t_raw('error_required');

    if ($type === 'room') {
        if (!$date_end)   $errors['date_end'] = t_raw('error_required');
        elseif ($date_end <= $date_start) $errors['date_end'] = t_raw('error_date_order');
    } else {
        $date_end = $date_start; // Pour une activité, même jour
    }

    // Vérification dispo chambre
    if (empty($errors) && $type === 'room') {
        if (!is_room_available($item_id, $date_start, $date_end)) {
            $errors['date_start'] = 'Cette chambre n\'est pas disponible pour ces dates.';
        }
    }

    // Calcul du prix total
    $total_price = 0;
    if (empty($errors)) {
        try {
            if ($type === 'room') {
                $stmt = $db->prepare('SELECT price_per_night FROM rooms WHERE id = ? AND active = 1');
                $stmt->execute([$item_id]);
                $room_data = $stmt->fetch();
                if ($room_data) {
                    $nights = nights_between($date_start, $date_end);
                    $total_price = $room_data['price_per_night'] * $nights;
                } else {
                    $errors['item_id'] = 'Hébergement introuvable.';
                }
            } else {
                $stmt = $db->prepare('SELECT price FROM activities WHERE id = ? AND active = 1');
                $stmt->execute([$item_id]);
                $act_data = $stmt->fetch();
                if ($act_data) {
                    $total_price = $act_data['price'] * $participants;
                } else {
                    $errors['item_id'] = 'Activité introuvable.';
                }
            }
        } catch (Exception $e) {
            $errors['general'] = t_raw('error_generic');
        }
    }

    // Insertion
    if (empty($errors)) {
        try {
            $stmt = $db->prepare(
                'INSERT INTO reservations
                 (type, item_id, firstname, lastname, email, phone,
                  date_start, date_end, participants, total_price, message, status)
                 VALUES (?,?,?,?,?,?,?,?,?,?,?,?)'
            );
            $stmt->execute([
                $type, $item_id, $firstname, $lastname, $email, $phone,
                $date_start, $date_end, $participants, $total_price, $message, 'pending'
            ]);
            $booking_id = $db->lastInsertId();
            redirect(SITE_URL . '/confirmation.php?id=' . $booking_id);
        } catch (Exception $e) {
            $errors['general'] = t_raw('error_generic');
        }
    }
}

$page_title = t_raw('book_title');
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
    <div class="container">
        <h1><?= t('book_title') ?></h1>
        <p><?= t('book_subtitle') ?></p>
        <nav class="breadcrumb" aria-label="breadcrumb">
            <a href="<?= SITE_URL ?>/"><?= t('nav_home') ?></a>
            <span class="breadcrumb-sep">›</span>
            <span><?= t('nav_reservation') ?></span>
        </nav>
    </div>
</section>

<section class="section">
    <div class="container">

        <!-- Étapes -->
        <div class="steps text-center">
            <div class="step done">
                <span class="step-num">1</span>
                <span><?= t('book_type') ?></span>
            </div>
            <span style="color:var(--border);">────</span>
            <div class="step">
                <span class="step-num">2</span>
                <span><?= t('book_submit') ?></span>
            </div>
            <span style="color:var(--border);">────</span>
            <div class="step">
                <span class="step-num">3</span>
                <span><?= t('confirm_title') ?></span>
            </div>
        </div>

        <?php if (!empty($errors['general'])): ?>
            <div class="alert alert-error" data-dismiss="1"><?= e($errors['general']) ?></div>
        <?php endif; ?>

        <form class="form-card" id="reservation-form" method="POST" action="reservation.php" novalidate>
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="type" id="form-type" value="<?= e($preselect_type) ?>">

            <!-- Type de réservation -->
            <p class="form-section-title"><?= t('book_type') ?></p>
            <div class="type-tabs">
                <button type="button" class="type-tab<?= $preselect_type === 'room' ? ' active' : '' ?>"
                        id="type-room"
                        onclick="document.getElementById('form-type').value='room'">
                    🏡 <?= t('book_type_room') ?>
                </button>
                <button type="button" class="type-tab<?= $preselect_type === 'activity' ? ' active' : '' ?>"
                        id="type-activity"
                        onclick="document.getElementById('form-type').value='activity'">
                    🦙 <?= t('book_type_activity') ?>
                </button>
            </div>

            <!-- Section chambre -->
            <div id="section-room">
                <div class="form-group">
                    <label for="room_id"><?= t('book_select_room') ?> *</label>
                    <select class="form-control" id="room_id" name="item_id_room">
                        <option value="">— <?= t('book_select_room') ?> —</option>
                        <?php foreach ($rooms as $r): ?>
                            <option value="<?= (int)$r['id'] ?>"
                                    data-price="<?= (float)$r['price_per_night'] ?>"
                                    <?= ($preselect_type === 'room' && $preselect_id === (int)$r['id']) ? 'selected' : '' ?>>
                                <?= e($r['name']) ?> — <?= format_price((float)$r['price_per_night']) ?> / nuit
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!empty($errors['item_id']) && $preselect_type === 'room'): ?>
                        <span class="form-error"><?= e($errors['item_id']) ?></span>
                    <?php endif; ?>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="date_start"><?= t('book_checkin') ?> *</label>
                        <input type="date" class="form-control" id="date_start" name="date_start_room"
                               value="<?= e($_POST['date_start'] ?? '') ?>">
                        <?php if (!empty($errors['date_start'])): ?>
                            <span class="form-error"><?= e($errors['date_start']) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group" id="date-end-group">
                        <label for="date_end"><?= t('book_checkout') ?> *</label>
                        <input type="date" class="form-control" id="date_end" name="date_end_room"
                               value="<?= e($_POST['date_end'] ?? '') ?>">
                        <?php if (!empty($errors['date_end'])): ?>
                            <span class="form-error"><?= e($errors['date_end']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Section activité -->
            <div id="section-activity" style="display:none;">
                <div class="form-group">
                    <label for="activity_id"><?= t('book_select_activity') ?> *</label>
                    <select class="form-control" id="activity_id" name="item_id_activity">
                        <option value="">— <?= t('book_select_activity') ?> —</option>
                        <?php foreach ($activities as $a):
                            $aname = $current_lang === 'en' ? ($a['name_en'] ?: $a['name'])
                                   : ($current_lang === 'es' ? ($a['name_es'] ?: $a['name']) : $a['name']);
                        ?>
                            <option value="<?= (int)$a['id'] ?>"
                                    data-price="<?= (float)$a['price'] ?>"
                                    <?= ($preselect_type === 'activity' && $preselect_id === (int)$a['id']) ? 'selected' : '' ?>>
                                <?= e($aname) ?> — <?= format_price((float)$a['price']) ?> / pers.
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group" id="activity-date-group">
                        <label for="activity_date"><?= t('book_activity_date') ?> *</label>
                        <input type="date" class="form-control" id="activity_date" name="date_start_activity"
                               value="<?= e($_POST['date_start'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="participants"><?= t('book_participants') ?></label>
                        <input type="number" class="form-control" id="participants" name="participants"
                               min="1" max="50" value="<?= (int)($_POST['participants'] ?? 1) ?>">
                    </div>
                </div>
            </div>

            <!-- Coordonnées -->
            <p class="form-section-title">👤 Vos coordonnées</p>
            <div class="form-row">
                <div class="form-group">
                    <label for="firstname"><?= t('book_firstname') ?> *</label>
                    <input type="text" class="form-control" id="firstname" name="firstname"
                           value="<?= e($_POST['firstname'] ?? '') ?>" autocomplete="given-name">
                    <?php if (!empty($errors['firstname'])): ?>
                        <span class="form-error"><?= e($errors['firstname']) ?></span>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="lastname"><?= t('book_lastname') ?> *</label>
                    <input type="text" class="form-control" id="lastname" name="lastname"
                           value="<?= e($_POST['lastname'] ?? '') ?>" autocomplete="family-name">
                    <?php if (!empty($errors['lastname'])): ?>
                        <span class="form-error"><?= e($errors['lastname']) ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="email"><?= t('book_email') ?> *</label>
                    <input type="email" class="form-control" id="email" name="email"
                           value="<?= e($_POST['email'] ?? '') ?>" autocomplete="email">
                    <?php if (!empty($errors['email'])): ?>
                        <span class="form-error"><?= e($errors['email']) ?></span>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="phone"><?= t('book_phone') ?></label>
                    <input type="tel" class="form-control" id="phone" name="phone"
                           value="<?= e($_POST['phone'] ?? '') ?>" autocomplete="tel">
                </div>
            </div>
            <div class="form-group">
                <label for="message"><?= t('book_message') ?></label>
                <textarea class="form-control" id="message" name="message" rows="3"><?= e($_POST['message'] ?? '') ?></textarea>
            </div>

            <!-- Total estimé -->
            <div class="total-box">
                <span><?= t('book_total') ?></span>
                <span class="total-amount" id="total-amount">—</span>
            </div>

            <p style="font-size:.82rem;color:var(--text-light);margin-bottom:1.25rem;"><?= t('book_required') ?></p>
            <button type="submit" class="btn btn-primary btn-full"><?= t('book_submit') ?></button>
        </form>
    </div>
</section>

<script>
// Sync les champs "hidden" avant submit pour envoyer les bons item_id / dates
document.getElementById('reservation-form').addEventListener('submit', function(e) {
    var isRoom = document.getElementById('form-type').value === 'room';
    // item_id
    var itemField = document.createElement('input');
    itemField.type = 'hidden';
    itemField.name = 'item_id';
    itemField.value = isRoom
        ? (document.getElementById('room_id')?.value || '')
        : (document.getElementById('activity_id')?.value || '');
    this.appendChild(itemField);
    // date_start
    var ds = document.createElement('input');
    ds.type = 'hidden'; ds.name = 'date_start';
    ds.value = isRoom
        ? (document.getElementById('date_start')?.value || '')
        : (document.getElementById('activity_date')?.value || '');
    this.appendChild(ds);
    // date_end
    var de = document.createElement('input');
    de.type = 'hidden'; de.name = 'date_end';
    de.value = isRoom
        ? (document.getElementById('date_end')?.value || '')
        : (document.getElementById('activity_date')?.value || '');
    this.appendChild(de);
});
// Initialiser les onglets au chargement
window.addEventListener('DOMContentLoaded', function() {
    var pre = '<?= e($preselect_type) ?>';
    var isRoom = pre === 'room';
    if (document.getElementById('section-room'))    document.getElementById('section-room').style.display    = isRoom ? 'block' : 'none';
    if (document.getElementById('section-activity'))document.getElementById('section-activity').style.display = isRoom ? 'none' : 'block';
    if (document.getElementById('date-end-group'))  document.getElementById('date-end-group').style.display  = isRoom ? 'block' : 'none';
    if (document.getElementById('activity-date-group')) document.getElementById('activity-date-group').style.display = isRoom ? 'none' : 'block';
    document.querySelectorAll('.type-tab').forEach(function(t) { t.classList.remove('active'); });
    var activeBtny = document.getElementById(isRoom ? 'type-room' : 'type-activity');
    if (activeBtny) activeBtny.classList.add('active');
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
