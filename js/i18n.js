/**
 * Alpagatitude — Internationalisation (FR / EN / ES)
 * Gère le changement de langue sur les pages statiques GitHub Pages.
 */
const TRANSLATIONS = {
  fr: {
    // Navigation
    nav_home: 'Accueil',
    nav_rooms: 'Hébergements',
    nav_activities: 'Activités',
    nav_reservation: 'Réserver',
    nav_contact: 'Contact',
    // Hero
    hero_eyebrow: '🌿 Domaine des Alpagas',
    hero_title: 'Bienvenue à Alpagatitude',
    hero_subtitle: 'Un domaine unique au cœur de la nature, où vous rencontrerez nos adorables alpagas.',
    hero_cta_rooms: 'Voir les hébergements',
    hero_cta_book: 'Réserver maintenant',
    hero_scroll: 'Découvrir',
    // À propos
    about_title: 'Notre Domaine',
    about_text: "Niché au cœur d'une magnifique région verdoyante, le Domaine Alpagatitude vous accueille pour des expériences inoubliables avec nos alpagas. Venez vous ressourcer, découvrir ces animaux fascinants et profiter d'activités en pleine nature.",
    about_alpacas: 'Nos Alpagas',
    about_alpacas_text: 'Nos alpagas sont élevés avec amour et passion. Doux, curieux et attachants, ils seront ravis de faire votre connaissance.',
    about_nature: 'La Nature',
    about_nature_text: 'Le domaine s\'étend sur plusieurs hectares de prairies et de chemins boisés, offrant un cadre idéal pour se reconnecter avec la nature.',
    about_relaxation: 'La Détente',
    about_relaxation_text: 'Profitez d\'un séjour reposant dans nos hébergements confortables, loin du stress quotidien.',
    about_cta: 'Nos activités',
    // Stats
    stat_alpacas: 'Alpagas',
    stat_visitors: 'Visiteurs / an',
    stat_rooms: 'Hébergements',
    stat_activities: 'Activités',
    // Hébergements
    rooms_section_title: 'Nos Hébergements',
    rooms_section_subtitle: 'Des logements confortables au cœur du domaine',
    rooms_see_all: 'Tous les hébergements →',
    rooms_title: 'Nos Hébergements',
    rooms_subtitle: 'Des logements confortables au cœur du domaine',
    rooms_per_night: '/ nuit',
    rooms_person: 'personne(s)',
    rooms_card_btn: 'Voir & Réserver',
    rooms_book_btn: 'Réserver',
    rooms_back: '← Hébergements',
    rooms_availability: 'Disponible',
    // Activités
    activities_section_title: 'Nos Activités',
    activities_section_subtitle: 'Des expériences uniques avec les alpagas',
    activities_title: 'Nos Activités',
    activities_subtitle: 'Des expériences uniques avec les alpagas',
    activities_see_all: 'Toutes les activités →',
    activities_duration: 'Durée',
    activities_price: 'Prix',
    activities_max: 'Max. participants',
    activities_per_person: '/ personne',
    activities_card_btn: 'Réserver',
    // CTA section
    cta_title: 'Prêt à vivre l\'expérience Alpagatitude ?',
    cta_text: 'Réservez dès maintenant votre séjour ou activité. Nos alpagas vous attendent !',
    cta_btn: 'Réserver maintenant',
    // Réservation
    book_title: 'Réservation',
    book_subtitle: 'Remplissez le formulaire pour réserver',
    book_type_room: 'Hébergement',
    book_type_activity: 'Activité',
    book_select_room: '-- Choisir un hébergement --',
    book_select_activity: '-- Choisir une activité --',
    book_checkin: 'Date d\'arrivée *',
    book_checkout: 'Date de départ *',
    book_activity_date: 'Date de l\'activité *',
    book_participants: 'Nombre de participants',
    book_firstname: 'Prénom *',
    book_lastname: 'Nom *',
    book_email: 'Email *',
    book_phone: 'Téléphone',
    book_message: 'Message (optionnel)',
    book_total: 'Total estimé',
    book_submit: 'Envoyer la demande de réservation',
    book_required: '* Champs obligatoires',
    book_note: 'Votre demande sera traitée et confirmée par email dans les 24h.',
    // Contact
    contact_title: 'Contact',
    contact_subtitle: 'Posez-nous vos questions',
    contact_name: 'Nom complet *',
    contact_email_label: 'Email *',
    contact_subject: 'Sujet *',
    contact_message: 'Message *',
    contact_submit: 'Envoyer',
    contact_address: 'Adresse',
    contact_phone_label: 'Téléphone',
    contact_hours: 'Horaires',
    contact_find_us: 'Nous trouver',
    contact_address_val: '123 Route des Alpagas, 00000 La Montagne',
    contact_phone_val: '+33 (0)6 00 00 00 00',
    contact_email_val: 'contact@alpagatitude.com',
    contact_hours_val: 'Lun–Dim : 9h–18h (saison) · 10h–16h (hiver)',
    // Footer
    footer_tagline: 'Un domaine d\'exception avec des alpagas',
    footer_links: 'Liens rapides',
    footer_contact_title: 'Contact',
    footer_rights: 'Tous droits réservés',
    footer_made_with: 'Fait avec ❤️ pour les alpagas',
    // Chambres data
    room1_name: 'Le Chalet Alpagas',
    room1_desc: "Un chalet charmant et chaleureux au cœur du domaine, avec une vue imprenable sur les prairies des alpagas. Équipé d'une cuisine, d'un salon cosy et de 2 chambres.",
    room2_name: 'La Lodge du Pré',
    room2_desc: 'Une lodge spacieuse et confortable bordant les pâturages. Parfaite pour les familles ou les groupes, avec terrasse et accès direct aux alpagas.',
    room3_name: 'Le Tipi Étoilé',
    room3_desc: 'Vivez une expérience insolite dans notre tipi confortable, idéal pour les couples. Dormez à la belle étoile tout en restant au chaud, et réveillez-vous avec les alpagas.',
    // Activités data
    act1_name: 'Balade avec les alpagas',
    act1_desc: 'Partez en balade dans les chemins bucoliques du domaine accompagnés de nos alpagas. Une expérience unique et inoubliable pour petits et grands.',
    act2_name: 'Nourrissage & câlins',
    act2_desc: 'Approchez les alpagas, donnez-leur à manger et profitez de leurs caresses. Idéal pour les enfants et les amoureux des animaux.',
    act3_name: 'Séance photo avec les alpagas',
    act3_desc: 'Immortalisez votre visite avec une séance photo aux côtés de nos magnifiques alpagas. Des souvenirs uniques garantis !',
    act4_name: 'Atelier laine & tonte',
    act4_desc: "Découvrez les secrets de la laine d'alpaga : tonte, cardage, filage... Un atelier artisanal passionnant pour toute la famille.",
    act5_name: 'Randonnée accompagnée',
    act5_desc: 'Explorez les sentiers naturels entourant le domaine en compagnie de nos guides et de quelques alpagas. Paysages grandioses garantis !',
  },
  en: {
    nav_home: 'Home',
    nav_rooms: 'Accommodations',
    nav_activities: 'Activities',
    nav_reservation: 'Book Now',
    nav_contact: 'Contact',
    hero_eyebrow: '🌿 Alpaca Domain',
    hero_title: 'Welcome to Alpagatitude',
    hero_subtitle: 'A unique domain in the heart of nature, where you will meet our adorable alpacas.',
    hero_cta_rooms: 'View accommodations',
    hero_cta_book: 'Book now',
    hero_scroll: 'Discover',
    about_title: 'Our Domain',
    about_text: "Nestled in the heart of a beautiful green region, the Alpagatitude Domain welcomes you for unforgettable experiences with our alpacas. Come and unwind, discover these fascinating animals, and enjoy outdoor activities.",
    about_alpacas: 'Our Alpacas',
    about_alpacas_text: 'Our alpacas are raised with love and passion. Gentle, curious and endearing, they will be delighted to meet you.',
    about_nature: 'Nature',
    about_nature_text: 'The domain spans several hectares of meadows and wooded paths, providing the ideal setting to reconnect with nature.',
    about_relaxation: 'Relaxation',
    about_relaxation_text: 'Enjoy a restful stay in our comfortable accommodations, far from everyday stress.',
    about_cta: 'Our activities',
    stat_alpacas: 'Alpacas',
    stat_visitors: 'Visitors / year',
    stat_rooms: 'Accommodations',
    stat_activities: 'Activities',
    rooms_section_title: 'Our Accommodations',
    rooms_section_subtitle: 'Comfortable lodgings in the heart of the domain',
    rooms_see_all: 'All accommodations →',
    rooms_title: 'Our Accommodations',
    rooms_subtitle: 'Comfortable lodgings in the heart of the domain',
    rooms_per_night: '/ night',
    rooms_person: 'person(s)',
    rooms_card_btn: 'View & Book',
    rooms_book_btn: 'Book',
    rooms_back: '← Accommodations',
    rooms_availability: 'Available',
    activities_section_title: 'Our Activities',
    activities_section_subtitle: 'Unique experiences with the alpacas',
    activities_title: 'Our Activities',
    activities_subtitle: 'Unique experiences with the alpacas',
    activities_see_all: 'All activities →',
    activities_duration: 'Duration',
    activities_price: 'Price',
    activities_max: 'Max. participants',
    activities_per_person: '/ person',
    activities_card_btn: 'Book',
    cta_title: 'Ready for the Alpagatitude experience?',
    cta_text: 'Book your stay or activity now. Our alpacas are waiting for you!',
    cta_btn: 'Book now',
    book_title: 'Reservation',
    book_subtitle: 'Fill in the form to book',
    book_type_room: 'Accommodation',
    book_type_activity: 'Activity',
    book_select_room: '-- Choose an accommodation --',
    book_select_activity: '-- Choose an activity --',
    book_checkin: 'Check-in date *',
    book_checkout: 'Check-out date *',
    book_activity_date: 'Activity date *',
    book_participants: 'Number of participants',
    book_firstname: 'First name *',
    book_lastname: 'Last name *',
    book_email: 'Email *',
    book_phone: 'Phone',
    book_message: 'Message (optional)',
    book_total: 'Estimated total',
    book_submit: 'Send booking request',
    book_required: '* Required fields',
    book_note: 'Your request will be processed and confirmed by email within 24h.',
    contact_title: 'Contact',
    contact_subtitle: 'Ask us your questions',
    contact_name: 'Full name *',
    contact_email_label: 'Email *',
    contact_subject: 'Subject *',
    contact_message: 'Message *',
    contact_submit: 'Send',
    contact_address: 'Address',
    contact_phone_label: 'Phone',
    contact_hours: 'Opening hours',
    contact_find_us: 'Find us',
    contact_address_val: '123 Route des Alpagas, 00000 La Montagne',
    contact_phone_val: '+33 (0)6 00 00 00 00',
    contact_email_val: 'contact@alpagatitude.com',
    contact_hours_val: 'Mon–Sun: 9am–6pm (season) · 10am–4pm (winter)',
    footer_tagline: 'An exceptional domain with alpacas',
    footer_links: 'Quick links',
    footer_contact_title: 'Contact',
    footer_rights: 'All rights reserved',
    footer_made_with: 'Made with ❤️ for the alpacas',
    room1_name: 'The Alpaca Chalet',
    room1_desc: "A charming and cozy chalet in the heart of the domain, with a breathtaking view of the alpaca meadows. Equipped with a kitchen, a cozy living room and 2 bedrooms.",
    room2_name: 'The Meadow Lodge',
    room2_desc: 'A spacious and comfortable lodge bordering the pastures. Perfect for families or groups, with a terrace and direct access to the alpacas.',
    room3_name: 'The Starry Tipi',
    room3_desc: 'Live an unusual experience in our comfortable tipi, ideal for couples. Sleep under the stars while staying warm, and wake up with the alpacas.',
    act1_name: 'Walk with the alpacas',
    act1_desc: 'Take a walk along the bucolic paths of the domain accompanied by our alpacas. A unique and unforgettable experience for young and old.',
    act2_name: 'Feeding & cuddles',
    act2_desc: 'Approach the alpacas, feed them and enjoy their cuddles. Ideal for children and animal lovers.',
    act3_name: 'Photo session with alpacas',
    act3_desc: 'Immortalize your visit with a photo session alongside our beautiful alpacas. Unique memories guaranteed!',
    act4_name: 'Wool & shearing workshop',
    act4_desc: 'Discover the secrets of alpaca wool: shearing, carding, spinning... A fascinating craft workshop for the whole family.',
    act5_name: 'Guided hike',
    act5_desc: 'Explore the natural trails surrounding the domain with our guides and some alpacas. Breathtaking landscapes guaranteed!',
  },
  es: {
    nav_home: 'Inicio',
    nav_rooms: 'Alojamientos',
    nav_activities: 'Actividades',
    nav_reservation: 'Reservar',
    nav_contact: 'Contacto',
    hero_eyebrow: '🌿 Dominio de las Alpacas',
    hero_title: 'Bienvenido a Alpagatitude',
    hero_subtitle: 'Un dominio único en el corazón de la naturaleza, donde conocerás nuestras adorables alpacas.',
    hero_cta_rooms: 'Ver alojamientos',
    hero_cta_book: 'Reservar ahora',
    hero_scroll: 'Descubrir',
    about_title: 'Nuestro Dominio',
    about_text: "Enclavado en el corazón de una hermosa región verde, el Dominio Alpagatitude te acoge para experiencias inolvidables con nuestras alpacas. Ven a descansar, descubrir estos fascinantes animales y disfrutar de actividades en la naturaleza.",
    about_alpacas: 'Nuestras Alpacas',
    about_alpacas_text: 'Nuestras alpacas se crían con amor y pasión. Suaves, curiosas y entrañables, estarán encantadas de conocerte.',
    about_nature: 'La Naturaleza',
    about_nature_text: 'El dominio se extiende por varios hectáreas de praderas y caminos arbolados, ofreciendo el marco ideal para reconectarse con la naturaleza.',
    about_relaxation: 'El Descanso',
    about_relaxation_text: 'Disfruta de una estancia relajante en nuestros confortables alojamientos, lejos del estrés cotidiano.',
    about_cta: 'Nuestras actividades',
    stat_alpacas: 'Alpacas',
    stat_visitors: 'Visitantes / año',
    stat_rooms: 'Alojamientos',
    stat_activities: 'Actividades',
    rooms_section_title: 'Nuestros Alojamientos',
    rooms_section_subtitle: 'Alojamientos confortables en el corazón del dominio',
    rooms_see_all: 'Todos los alojamientos →',
    rooms_title: 'Nuestros Alojamientos',
    rooms_subtitle: 'Alojamientos confortables en el corazón del dominio',
    rooms_per_night: '/ noche',
    rooms_person: 'persona(s)',
    rooms_card_btn: 'Ver y Reservar',
    rooms_book_btn: 'Reservar',
    rooms_back: '← Alojamientos',
    rooms_availability: 'Disponible',
    activities_section_title: 'Nuestras Actividades',
    activities_section_subtitle: 'Experiencias únicas con las alpacas',
    activities_title: 'Nuestras Actividades',
    activities_subtitle: 'Experiencias únicas con las alpacas',
    activities_see_all: 'Todas las actividades →',
    activities_duration: 'Duración',
    activities_price: 'Precio',
    activities_max: 'Participantes máx.',
    activities_per_person: '/ persona',
    activities_card_btn: 'Reservar',
    cta_title: '¿Listo para vivir la experiencia Alpagatitude?',
    cta_text: '¡Reserva ya tu estancia o actividad. Nuestras alpacas te esperan!',
    cta_btn: 'Reservar ahora',
    book_title: 'Reserva',
    book_subtitle: 'Rellena el formulario para reservar',
    book_type_room: 'Alojamiento',
    book_type_activity: 'Actividad',
    book_select_room: '-- Elige un alojamiento --',
    book_select_activity: '-- Elige una actividad --',
    book_checkin: 'Fecha de llegada *',
    book_checkout: 'Fecha de salida *',
    book_activity_date: 'Fecha de la actividad *',
    book_participants: 'Número de participantes',
    book_firstname: 'Nombre *',
    book_lastname: 'Apellido *',
    book_email: 'Email *',
    book_phone: 'Teléfono',
    book_message: 'Mensaje (opcional)',
    book_total: 'Total estimado',
    book_submit: 'Enviar solicitud de reserva',
    book_required: '* Campos obligatorios',
    book_note: 'Tu solicitud será procesada y confirmada por email en 24h.',
    contact_title: 'Contacto',
    contact_subtitle: 'Haznos tus preguntas',
    contact_name: 'Nombre completo *',
    contact_email_label: 'Email *',
    contact_subject: 'Asunto *',
    contact_message: 'Mensaje *',
    contact_submit: 'Enviar',
    contact_address: 'Dirección',
    contact_phone_label: 'Teléfono',
    contact_hours: 'Horario',
    contact_find_us: 'Encuéntranos',
    contact_address_val: '123 Route des Alpagas, 00000 La Montagne',
    contact_phone_val: '+33 (0)6 00 00 00 00',
    contact_email_val: 'contact@alpagatitude.com',
    contact_hours_val: 'Lun–Dom: 9h–18h (temporada) · 10h–16h (invierno)',
    footer_tagline: 'Un dominio excepcional con alpacas',
    footer_links: 'Enlaces rápidos',
    footer_contact_title: 'Contacto',
    footer_rights: 'Todos los derechos reservados',
    footer_made_with: 'Hecho con ❤️ para las alpacas',
    room1_name: 'El Chalet de las Alpacas',
    room1_desc: "Un acogedor chalet en el corazón del dominio, con vistas impresionantes a los prados de las alpacas. Equipado con cocina, salón acogedor y 2 dormitorios.",
    room2_name: 'La Lodge del Prado',
    room2_desc: 'Una lodge espaciosa y cómoda junto a los pastizales. Perfecta para familias o grupos, con terraza y acceso directo a las alpacas.',
    room3_name: 'El Tipi Estrellado',
    room3_desc: 'Vive una experiencia insólita en nuestro cómodo tipi, ideal para parejas. Duerme bajo las estrellas manteniéndote caliente, y despierta con las alpacas.',
    act1_name: 'Paseo con las alpacas',
    act1_desc: 'Pasea por los caminos bucólicos del dominio acompañado de nuestras alpacas. ¡Una experiencia única e inolvidable para grandes y pequeños!',
    act2_name: 'Alimentación y caricias',
    act2_desc: 'Acércate a las alpacas, dales de comer y disfruta de sus caricias. ¡Ideal para niños y amantes de los animales!',
    act3_name: 'Sesión de fotos con alpacas',
    act3_desc: '¡Inmortaliza tu visita con una sesión de fotos junto a nuestras hermosas alpacas. Recuerdos únicos garantizados!',
    act4_name: 'Taller de lana y esquila',
    act4_desc: 'Descubre los secretos de la lana de alpaca: esquila, cardado, hilado... ¡Un fascinante taller artesanal para toda la familia!',
    act5_name: 'Senderismo acompañado',
    act5_desc: '¡Explora los senderos naturales alrededor del dominio con nuestros guías y algunas alpacas. Paisajes impresionantes garantizados!',
  }
};

// ── Appliquer la langue ───────────────────────────────────────────────────────
function applyLang(lang) {
  if (!TRANSLATIONS[lang]) lang = 'fr';
  const t = TRANSLATIONS[lang];
  document.documentElement.lang = lang;

  // Texte simple
  document.querySelectorAll('[data-i18n]').forEach(el => {
    const key = el.dataset.i18n;
    if (t[key] !== undefined) el.textContent = t[key];
  });

  // Attribut HTML (innerHTML pour les éléments avec balises)
  document.querySelectorAll('[data-i18n-html]').forEach(el => {
    const key = el.dataset.i18nHtml;
    if (t[key] !== undefined) el.innerHTML = t[key];
  });

  // Placeholder
  document.querySelectorAll('[data-i18n-ph]').forEach(el => {
    const key = el.dataset.i18nPh;
    if (t[key] !== undefined) el.placeholder = t[key];
  });

  // Boutons lang actif
  document.querySelectorAll('.lang-btn').forEach(btn => {
    btn.classList.toggle('active', btn.dataset.lang === lang);
  });

  // Titre de page
  const titleKey = document.documentElement.dataset.titleKey;
  if (titleKey && t[titleKey]) {
    document.title = t[titleKey] + ' — Alpagatitude';
  }

  // Conserver le choix
  localStorage.setItem('alpagatitude_lang', lang);
  window._lang = lang;
}

// ── Initialisation ────────────────────────────────────────────────────────────
(function () {
  const saved = localStorage.getItem('alpagatitude_lang') || 'fr';
  // Attendre le DOM
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
      applyLang(saved);
      bindLangButtons();
    });
  } else {
    applyLang(saved);
    bindLangButtons();
  }
})();

function bindLangButtons() {
  document.querySelectorAll('.lang-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      applyLang(btn.dataset.lang);
    });
  });
}

// Raccourci global
function t(key) {
  const lang = window._lang || localStorage.getItem('alpagatitude_lang') || 'fr';
  return (TRANSLATIONS[lang] && TRANSLATIONS[lang][key]) || (TRANSLATIONS['fr'][key]) || key;
}
