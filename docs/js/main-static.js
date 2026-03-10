/* =====================================================================
   main-static.js — GitHub Pages static behaviour for Alpagatitude
   ===================================================================== */

/* ── Mobile nav toggle ──────────────────────────────────────────────── */
(function () {
  const toggle = document.getElementById('nav-toggle');
  const nav    = document.getElementById('main-nav');
  if (!toggle || !nav) return;
  toggle.addEventListener('click', function () {
    const open = nav.classList.toggle('open');
    toggle.classList.toggle('open', open);
    toggle.setAttribute('aria-expanded', open);
  });
  document.addEventListener('click', function (e) {
    if (!nav.contains(e.target) && !toggle.contains(e.target)) {
      nav.classList.remove('open');
      toggle.classList.remove('open');
      toggle.setAttribute('aria-expanded', false);
    }
  });
})();

/* ── Sticky header shadow on scroll ─────────────────────────────────── */
(function () {
  const header = document.getElementById('site-header');
  if (!header) return;
  function onScroll() {
    header.classList.toggle('scrolled', window.scrollY > 20);
  }
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();
})();

/* ── Fade-in-up observer ─────────────────────────────────────────────── */
(function () {
  if (!window.IntersectionObserver) return;
  const obs = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        obs.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1 });
  document.querySelectorAll('.card, .feature-card, .fade-in-up').forEach(function (el) {
    obs.observe(el);
  });
})();

/* ── Render helpers (called from page <script> blocks) ──────────────── */

/**
 * Build an array of room card HTML strings.
 * @param {boolean} preview – if true, limit to first 3 items
 */
function renderRoomsInto(containerId, preview) {
  const el = document.getElementById(containerId);
  if (!el) return;
  const items = preview ? ROOMS.slice(0, 3) : ROOMS;
  el.innerHTML = items.map(function (r) {
    return '<div class="card">' +
      '<div class="card-img"><img src="' + r.image + '" alt="' + getItemName(r) + '" loading="lazy"></div>' +
      '<div class="card-body">' +
        '<h3>' + getItemName(r) + '</h3>' +
        '<p>' + getItemDesc(r) + '</p>' +
        '<div class="card-meta">' +
          '<span class="card-meta-item">👥 ' + r.capacity + ' ' + t('rooms_person') + '</span>' +
          '<span class="card-price">' + formatPrice(r.price) + ' <small>' + t('rooms_card_price') + '</small></span>' +
        '</div>' +
        '<a href="reservation.html?type=room&id=' + r.id + '" class="btn btn-primary btn-full">' + t('rooms_card_btn') + '</a>' +
      '</div></div>';
  }).join('');
}

/**
 * Build activity cards.
 * @param {boolean} preview – if true, limit to first 3 items
 */
function renderActivitiesInto(containerId, preview) {
  const el = document.getElementById(containerId);
  if (!el) return;
  const items = preview ? ACTIVITIES.slice(0, 3) : ACTIVITIES;
  el.innerHTML = items.map(function (a) {
    return '<div class="card">' +
      '<div class="card-img"><img src="' + a.image + '" alt="' + getItemName(a) + '" loading="lazy"></div>' +
      '<div class="card-body">' +
        '<h3>' + getItemName(a) + '</h3>' +
        '<p>' + getItemDesc(a) + '</p>' +
        '<div class="card-meta">' +
          '<span class="card-meta-item">⏱ ' + a.duration + '</span>' +
          '<span class="card-meta-item">👥 ' + a.max + ' max</span>' +
        '</div>' +
        '<div class="card-meta">' +
          '<span class="card-price">' + formatPrice(a.price) + ' <small>' + t('activities_per_person') + '</small></span>' +
          '<a href="reservation.html?type=activity&id=' + a.id + '" class="btn btn-primary btn-sm">' + t('activities_card_btn') + '</a>' +
        '</div>' +
      '</div></div>';
  }).join('');
}

/**
 * Populate the <select> dropdowns on reservation.html.
 */
function renderBookingSelects() {
  var roomSel = document.getElementById('select-room');
  var actSel  = document.getElementById('select-activity');
  if (roomSel) {
    roomSel.innerHTML = '<option value="">' + t('reservation_room_default') + '</option>' +
      ROOMS.map(function (r) {
        return '<option value="' + r.id + '">' + getItemName(r) + ' — ' + formatPrice(r.price) + ' ' + t('rooms_card_price') + '</option>';
      }).join('');
  }
  if (actSel) {
    actSel.innerHTML = '<option value="">' + t('reservation_activity_default') + '</option>' +
      ACTIVITIES.map(function (a) {
        return '<option value="' + a.id + '">' + getItemName(a) + ' — ' + formatPrice(a.price) + ' ' + t('activities_per_person') + '</option>';
      }).join('');
  }
}

/* ── URL parameter pre-selection ─────────────────────────────────────── */
function preselectFromURL() {
  var params = new URLSearchParams(window.location.search);
  var type   = params.get('type');
  var id     = params.get('id');
  if (!type || !id) return;
  if (type === 'room') {
    var sel = document.getElementById('select-room');
    if (sel) { sel.value = id; sel.dispatchEvent(new Event('change')); }
  } else if (type === 'activity') {
    var sel = document.getElementById('select-activity');
    if (sel) { sel.value = id; sel.dispatchEvent(new Event('change')); }
  }
}

/* ── Reservation price calculator ────────────────────────────────────── */
(function () {
  function recalc() {
    var roomId    = (document.getElementById('select-room')    || {}).value;
    var actId     = (document.getElementById('select-activity') || {}).value;
    var arrivalEl = document.getElementById('arrival');
    var departEl  = document.getElementById('depart');
    var guestsEl  = document.getElementById('guests');
    var sumEl     = document.getElementById('price-summary');
    if (!sumEl) return;

    var nights   = 0;
    var roomCost = 0;
    var actCost  = 0;
    if (arrivalEl && departEl && arrivalEl.value && departEl.value) {
      var diff = (new Date(departEl.value) - new Date(arrivalEl.value)) / 86400000;
      nights = Math.max(0, diff);
    }
    var guests = (guestsEl && parseInt(guestsEl.value)) || 1;
    if (roomId) {
      var room = ROOMS.find(function (r) { return r.id === parseInt(roomId); });
      if (room) roomCost = room.price * nights;
    }
    if (actId) {
      var act = ACTIVITIES.find(function (a) { return a.id === parseInt(actId); });
      if (act) actCost = act.price * guests;
    }
    var total = roomCost + actCost;
    sumEl.innerHTML = '';
    if (roomCost > 0) {
      sumEl.innerHTML += '<div>' + t('reservation_room') + ': ' + nights + ' ' + t('reservation_nights') + ' × ' + formatPrice(ROOMS.find(function(r){ return r.id === parseInt(roomId); }).price) + ' = <strong>' + formatPrice(roomCost) + '</strong></div>';
    }
    if (actCost > 0) {
      sumEl.innerHTML += '<div>' + t('reservation_activity') + ': ' + guests + ' × ' + formatPrice(ACTIVITIES.find(function(a){ return a.id === parseInt(actId); }).price) + ' = <strong>' + formatPrice(actCost) + '</strong></div>';
    }
    if (total > 0) {
      sumEl.innerHTML += '<div class="price-total">' + t('reservation_total') + ': <strong>' + formatPrice(total) + '</strong></div>';
    }
  }

  document.addEventListener('DOMContentLoaded', function () {
    ['select-room','select-activity','arrival','depart','guests'].forEach(function (id) {
      var el = document.getElementById(id);
      if (el) el.addEventListener('change', recalc);
    });
    recalc();
  });
})();

/* ── Active nav link ─────────────────────────────────────────────────── */
(function () {
  var page = window.location.pathname.split('/').pop() || 'index.html';
  document.querySelectorAll('.main-nav a[href]').forEach(function (a) {
    a.classList.toggle('active', a.getAttribute('href') === page);
  });
})();
