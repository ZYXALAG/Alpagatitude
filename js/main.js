// ── Navigation mobile ────────────────────────────────────────────────────────
const navToggle = document.getElementById('nav-toggle');
const mainNav   = document.getElementById('main-nav');

if (navToggle && mainNav) {
    navToggle.addEventListener('click', () => {
        const open = mainNav.classList.toggle('open');
        navToggle.setAttribute('aria-expanded', open);
        document.body.style.overflow = open ? 'hidden' : '';
    });

    // Fermer menu si click en dehors
    document.addEventListener('click', (e) => {
        if (!navToggle.contains(e.target) && !mainNav.contains(e.target)) {
            mainNav.classList.remove('open');
            navToggle.setAttribute('aria-expanded', 'false');
            document.body.style.overflow = '';
        }
    });
}

// ── Header scroll effect ─────────────────────────────────────────────────────
const header = document.getElementById('site-header');
if (header) {
    const onScroll = () => header.classList.toggle('scrolled', window.scrollY > 20);
    window.addEventListener('scroll', onScroll, { passive: true });
}

// ── Active nav link detection ────────────────────────────────────────────────
(function() {
    const links = document.querySelectorAll('.main-nav a');
    const path  = window.location.pathname;
    links.forEach(l => {
        const href = l.getAttribute('href') || '';
        if (href && path.includes(href.replace(/^.*\//, '')) && href !== '/') {
            l.classList.add('active');
        }
    });
    // Mark home
    if (path === '/' || path.endsWith('index.php')) {
        const home = document.querySelector('.main-nav a[href$="/"]');
        if (home) home.classList.add('active');
    }
})();

// ── Réservation : calcul du total en temps réel ──────────────────────────────
(function() {
    const form      = document.getElementById('reservation-form');
    if (!form) return;

    const typeRoom  = document.getElementById('type-room');
    const typeAct   = document.getElementById('type-activity');
    const sectionRm = document.getElementById('section-room');
    const sectionAc = document.getElementById('section-activity');
    const totalBox  = document.getElementById('total-amount');
    const selectRm  = document.getElementById('room_id');
    const selectAc  = document.getElementById('activity_id');
    const checkin   = document.getElementById('date_start');
    const checkout  = document.getElementById('date_end');
    const actDate   = document.getElementById('activity_date');
    const parts     = document.getElementById('participants');
    const dateEndGroup = document.getElementById('date-end-group');
    const actDateGrp   = document.getElementById('activity-date-group');

    function switchType(isRoom) {
        if (!sectionRm || !sectionAc) return;
        sectionRm.style.display = isRoom ? 'block' : 'none';
        sectionAc.style.display = isRoom ? 'none'  : 'block';
        if (dateEndGroup)  dateEndGroup.style.display  = isRoom ? 'block' : 'none';
        if (actDateGrp)    actDateGrp.style.display    = isRoom ? 'none'  : 'block';
        if (checkin)  checkin.name = isRoom ? 'date_start' : 'date_start';
        updateTotal();
    }

    if (typeRoom) typeRoom.addEventListener('click', () => switchType(true));
    if (typeAct)  typeAct.addEventListener('click',  () => switchType(false));

    function nightsBetween(d1, d2) {
        if (!d1 || !d2) return 0;
        const t1 = new Date(d1), t2 = new Date(d2);
        return Math.max(0, (t2 - t1) / 86400000 | 0);
    }

    function updateTotal() {
        if (!totalBox) return;
        const isRoom = typeRoom && typeRoom.classList.contains('active');
        let total = 0;
        if (isRoom && selectRm) {
            const opt = selectRm.options[selectRm.selectedIndex];
            const price = parseFloat(opt ? opt.dataset.price || 0 : 0);
            const nights = nightsBetween(checkin?.value, checkout?.value);
            total = price * nights;
            totalBox.dataset.suffix = ` (${nights} nuit${nights > 1 ? 's' : ''} × ${price.toFixed(2).replace('.', ',')} €)`;
        } else if (selectAc) {
            const opt = selectAc.options[selectAc.selectedIndex];
            const price = parseFloat(opt ? opt.dataset.price || 0 : 0);
            const nb = parseInt(parts?.value || 1);
            total = price * nb;
            totalBox.dataset.suffix = ` (${nb} pers. × ${price.toFixed(2).replace('.', ',')} €)`;
        }
        const suffix = totalBox.dataset.suffix || '';
        totalBox.textContent = total.toFixed(2).replace('.', ',') + ' €' + suffix;
    }

    [selectRm, selectAc, checkin, checkout, parts, actDate].forEach(el => {
        if (el) el.addEventListener('change', updateTotal);
    });

    // Date minimum = aujourd'hui
    const today = new Date().toISOString().split('T')[0];
    if (checkin)  checkin.min  = today;
    if (actDate)  actDate.min  = today;
    if (checkin) {
        checkin.addEventListener('change', () => {
            if (checkout) {
                checkout.min = checkin.value;
                if (checkout.value && checkout.value <= checkin.value) {
                    const d = new Date(checkin.value);
                    d.setDate(d.getDate() + 1);
                    checkout.value = d.toISOString().split('T')[0];
                }
                updateTotal();
            }
        });
    }

    // Init
    switchType(true);

    // Tabs visuels
    document.querySelectorAll('.type-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.type-tab').forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
        });
    });
})();

// ── Animation intersection observer ─────────────────────────────────────────
(function() {
    if (!('IntersectionObserver' in window)) return;
    const items = document.querySelectorAll('.card, .feature-card, .stat-card, .contact-info-item');
    const obs = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.classList.add('fade-in-up');
                obs.unobserve(e.target);
            }
        });
    }, { threshold: 0.1 });
    items.forEach(el => obs.observe(el));
})();

// ── Auto-dismiss alerts ───────────────────────────────────────────────────────
document.querySelectorAll('.alert[data-dismiss]').forEach(el => {
    setTimeout(() => { el.style.opacity = '0'; el.style.transition = 'opacity .5s'; }, 4000);
});
