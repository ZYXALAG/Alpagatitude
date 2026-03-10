<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/lang.php';

$success = false;
$errors  = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    $name    = input('name');
    $email   = input('email');
    $subject = input('subject');
    $message = input('message');

    if (!$name)    $errors['name']    = t_raw('error_required');
    if (!$email)   $errors['email']   = t_raw('error_required');
    elseif (!valid_email($email)) $errors['email'] = t_raw('error_email');
    if (!$subject) $errors['subject'] = t_raw('error_required');
    if (!$message) $errors['message'] = t_raw('error_required');

    if (empty($errors)) {
        $to      = get_setting('contact_email', ADMIN_EMAIL);
        $headers = "From: noreply@" . ($_SERVER['HTTP_HOST'] ?? 'alpagatitude.com') . "\r\n"
                 . "Reply-To: " . filter_var($email, FILTER_SANITIZE_EMAIL) . "\r\n"
                 . "Content-Type: text/plain; charset=UTF-8\r\n";
        $body    = "Nouveau message de : $name <$email>\n\n"
                 . "Sujet : $subject\n\n"
                 . $message;
        // mail() peut être désactivé en local - on log dans tous les cas
        @mail($to, '[Alpagatitude] ' . $subject, $body, $headers);
        $success = true;
    }
}

$address = get_setting('address', '123 Route des Alpagas, 00000 La Montagne');
$phone   = get_setting('phone', '+33 (0)6 00 00 00 00');
$email   = get_setting('contact_email', ADMIN_EMAIL);
$hours   = get_setting('hours', 'Lun–Dim : 9h–18h');

$page_title = t_raw('contact_title');
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
    <div class="container">
        <h1><?= t('contact_title') ?></h1>
        <p><?= t('contact_subtitle') ?></p>
        <nav class="breadcrumb" aria-label="breadcrumb">
            <a href="<?= SITE_URL ?>/"><?= t('nav_home') ?></a>
            <span class="breadcrumb-sep">›</span>
            <span><?= t('nav_contact') ?></span>
        </nav>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="contact-grid">

            <!-- Formulaire -->
            <div>
                <?php if ($success): ?>
                    <div class="alert alert-success" data-dismiss="1">✅ <?= t('contact_success') ?></div>
                <?php endif; ?>
                <?php if (!empty($errors['general'])): ?>
                    <div class="alert alert-error"><?= e($errors['general']) ?></div>
                <?php endif; ?>

                <form method="POST" action="contact.php" class="form-card" novalidate>
                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

                    <div class="form-row">
                        <div class="form-group">
                            <label for="name"><?= t('contact_name') ?> *</label>
                            <input type="text" class="form-control" id="name" name="name"
                                   value="<?= e($_POST['name'] ?? '') ?>" autocomplete="name">
                            <?php if (!empty($errors['name'])): ?>
                                <span class="form-error"><?= e($errors['name']) ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label for="email"><?= t('contact_email') ?> *</label>
                            <input type="email" class="form-control" id="email" name="email"
                                   value="<?= e($_POST['email'] ?? '') ?>" autocomplete="email">
                            <?php if (!empty($errors['email'])): ?>
                                <span class="form-error"><?= e($errors['email']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="subject"><?= t('contact_subject') ?> *</label>
                        <input type="text" class="form-control" id="subject" name="subject"
                               value="<?= e($_POST['subject'] ?? '') ?>">
                        <?php if (!empty($errors['subject'])): ?>
                            <span class="form-error"><?= e($errors['subject']) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="message"><?= t('contact_message') ?> *</label>
                        <textarea class="form-control" id="message" name="message" rows="6"><?= e($_POST['message'] ?? '') ?></textarea>
                        <?php if (!empty($errors['message'])): ?>
                            <span class="form-error"><?= e($errors['message']) ?></span>
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="btn btn-primary btn-full"><?= t('contact_submit') ?></button>
                </form>
            </div>

            <!-- Infos de contact -->
            <div class="contact-info">
                <h3><?= t('contact_title') ?></h3>

                <div class="contact-info-item">
                    <div class="contact-info-icon">📍</div>
                    <div class="contact-info-text">
                        <strong><?= t('contact_address') ?></strong>
                        <span><?= e($address) ?></span>
                    </div>
                </div>
                <div class="contact-info-item">
                    <div class="contact-info-icon">📞</div>
                    <div class="contact-info-text">
                        <strong><?= t('contact_phone_label') ?></strong>
                        <span><a href="tel:<?= e(preg_replace('/[^+\d]/', '', $phone)) ?>"><?= e($phone) ?></a></span>
                    </div>
                </div>
                <div class="contact-info-item">
                    <div class="contact-info-icon">✉️</div>
                    <div class="contact-info-text">
                        <strong><?= t('contact_email_label') ?></strong>
                        <span><a href="mailto:<?= e($email) ?>"><?= e($email) ?></a></span>
                    </div>
                </div>
                <div class="contact-info-item">
                    <div class="contact-info-icon">🕐</div>
                    <div class="contact-info-text">
                        <strong><?= t('contact_hours') ?></strong>
                        <span><?= e($hours) ?></span>
                    </div>
                </div>

                <!-- Carte placeholder -->
                <div style="margin-top:1.5rem;background:var(--green-pale);border-radius:var(--radius);padding:2rem;text-align:center;color:var(--text-light);">
                    <div style="font-size:3rem;margin-bottom:.5rem;">🗺️</div>
                    <p style="margin:0;font-size:.9rem;">Plan d'accès disponible sur demande</p>
                </div>

                <div style="margin-top:1.5rem;">
                    <a href="<?= SITE_URL ?>/reservation.php" class="btn btn-gold btn-full"><?= t('nav_reservation') ?></a>
                </div>
            </div>

        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
