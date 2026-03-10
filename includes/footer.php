<?php global $current_lang; ?>
</main>

<!-- ══ FOOTER ═══════════════════════════════════════════════════════════════ -->
<footer class="site-footer">
    <div class="container footer-grid">

        <div class="footer-brand">
            <div class="footer-logo">🦙 <?= e(SITE_NAME) ?></div>
            <p><?= t('footer_tagline') ?></p>
            <div class="footer-social">
                <a href="#" aria-label="Facebook" title="Facebook">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                </a>
                <a href="#" aria-label="Instagram" title="Instagram">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" fill="none" stroke="white" stroke-width="2"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5" stroke="white" stroke-width="2" stroke-linecap="round"/></svg>
                </a>
            </div>
        </div>

        <div class="footer-links">
            <h4><?= t('footer_links') ?></h4>
            <ul>
                <li><a href="<?= SITE_URL ?>/"><?= t('nav_home') ?></a></li>
                <li><a href="<?= SITE_URL ?>/chambres.php"><?= t('nav_rooms') ?></a></li>
                <li><a href="<?= SITE_URL ?>/activites.php"><?= t('nav_activities') ?></a></li>
                <li><a href="<?= SITE_URL ?>/reservation.php"><?= t('nav_reservation') ?></a></li>
                <li><a href="<?= SITE_URL ?>/contact.php"><?= t('nav_contact') ?></a></li>
            </ul>
        </div>

        <div class="footer-contact">
            <h4><?= t('contact_title') ?></h4>
            <?php
            $address = get_setting('address', '123 Route des Alpagas, 00000 La Montagne');
            $phone   = get_setting('phone',   '+33 (0)6 00 00 00 00');
            $email   = get_setting('contact_email', ADMIN_EMAIL);
            ?>
            <p>📍 <?= e($address) ?></p>
            <p>📞 <?= e($phone) ?></p>
            <p>✉️ <a href="mailto:<?= e($email) ?>"><?= e($email) ?></a></p>
        </div>

    </div>
    <div class="footer-bottom">
        <div class="container">
            <span>&copy; <?= date('Y') ?> <?= e(SITE_NAME) ?> — <?= t('footer_rights') ?></span>
            <span><?= t('footer_made_with') ?></span>
        </div>
    </div>
</footer>
<!-- ══ END FOOTER ════════════════════════════════════════════════════════════ -->

<script src="<?= SITE_URL ?>/js/main.js"></script>
</body>
</html>
