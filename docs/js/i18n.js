/**
 * Alpagatitude — Internationalisation (FR / EN / ES)
 * Utilisé sur le site statique GitHub Pages.
 */
const TRANSLATIONS = {
  fr: {
    nav_home: 'Accueil',
    nav_rooms: 'Hébergements',
    nav_activities: 'Activités',
    nav_reservation: 'Réserver',
    nav_contact: 'Contact',
    lang_name: 'Français',

    hero_eyebrow: '🌿 Domaine des Alpagas',
    hero_title: 'Bienvenue à Alpagatitude',
    hero_subtitle: 'Un domaine unique au cœur de la nature, où vous rencontrerez nos adorables alpagas.',
    hero_cta_rooms: 'Voir les hébergements',
    hero_cta_book: 'Réserver maintenant',
    hero_scroll: 'Découvrir',

    about_title: 'Notre Domaine',
    about_text: "Niché au cœur d'une magnifique région verdoyante, le Domaine Alpagatitude vous accueille pour des expériences inoubliables avec nos alpagas. Venez vous ressourcer, découvrir ces animaux fascinants et profiter d'activités en pleine nature.",
    about_alpacas: 'Nos Alpagas',
    about_alpacas_text: "Nos alpagas sont élevés avec amour et passion. Doux, curieux et attachants, ils seront ravis de faire votre connaissance lors de visites guidées ou de balades.",
    about_nature: 'La Nature',
    about_nature_text: "Le domaine s'étend sur plusieurs hectares de prairies et de chemins boisés, offrant un cadre idéal pour se reconnecter avec la nature.",
    about_relaxation: 'La Détente',
    about_relaxation_text: "Profitez d'un séjour reposant dans nos hébergements confortables, loin du stress quotidien, au rythme tranquille des alpagas.",
    about_family: 'En Famille',
    about_family_text: "Le domaine est idéal pour les familles avec enfants. Nos alpagas adorent la compagnie des plus jeunes !",

    stat_alpacas: '20+ Alpagas',
    stat_visitors: '500+ Visiteurs/an',
    stat_rooms: 'Hébergements',
    stat_activities: 'Activités',

    rooms_title: 'Nos Hébergements',
    rooms_subtitle: 'Des logements confortables au cœur du domaine',
    rooms_card_price: 'par nuit',
    rooms_card_btn: 'Voir & Réserver',
    rooms_person: 'personne(s)',
    rooms_availability: 'Disponible',
    rooms_see_all: 'Tous les hébergements',
    rooms_capacity: 'Capacité',
    rooms_breadcrumb: 'Hébergements',

    activities_title: 'Nos Activités',
    activities_subtitle: 'Des expériences uniques avec les alpagas',
    activities_duration: 'Durée',
    activities_price: 'Prix',
    activities_max: 'Participants max.',
    activities_card_btn: 'Réserver',
    activities_per_person: '/ personne',
    activities_see_all: 'Toutes les activités',
    activities_breadcrumb: 'Activités',

    cta_title: 'Prêt à vivre une expérience unique ?',
    cta_text: 'Réservez votre séjour ou votre activité dès maintenant et venez à la rencontre de nos alpagas.',
    cta_btn: 'Réserver maintenant',

    book_title: 'Réservation',
    book_subtitle: 'Remplissez le formulaire ci-dessous',
    book_type_room: '🏡 Hébergement',
    book_type_activity: '🦙 Activité',
    book_select_room: 'Choisir un hébergement',
    book_select_activity: 'Choisir une activité',
    book_checkin: "Date d'arrivée",
    book_checkout: 'Date de départ',
    book_activity_date: "Date de l'activité",
    book_participants: 'Nombre de participants',
    book_firstname: 'Prénom',
    book_lastname: 'Nom',
    book_email: 'Email',
    book_phone: 'Téléphone (optionnel)',
    book_message: 'Message (optionnel)',
    book_total: 'Total estimé',
    book_submit: 'Envoyer la demande de réservation',
    book_required: '* Champs obligatoires',
    book_nights: 'nuit(s)',
    book_persons: 'pers.',
    book_success: '✅ Votre demande a été envoyée ! Nous vous contacterons pour confirmer.',
    book_error: "Une erreur est survenue. Veuillez réessayer ou nous contacter directement.",
    book_breadcrumb: 'Réservation',
    book_note: 'Réservation soumise par email — confirmation sous 24h.',

    contact_title: 'Contact',
    contact_subtitle: 'Posez-nous vos questions',
    contact_name: 'Nom complet',
    contact_email: 'Email',
    contact_subject: 'Sujet',
    contact_message: 'Message',
    contact_submit: 'Envoyer',
    contact_success: '✅ Votre message a été envoyé ! Nous vous répondrons rapidement.',
    contact_error: "Erreur d'envoi. Réessayez ou contactez-nous directement.",
    contact_address: 'Adresse',
    contact_phone_label: 'Téléphone',
    contact_hours: 'Horaires',
    contact_breadcrumb: 'Contact',
    contact_info_title: 'Informations',

    footer_tagline: "Un domaine d'exception avec des alpagas",
    footer_links: 'Liens rapides',
    footer_contact_title: 'Contact',
    footer_rights: 'Tous droits réservés',
    footer_made_with: 'Fait avec ❤️ pour les alpagas',

    // Page titles
    page_rooms_title: 'Hébergements — Alpagatitude',
    page_activities_title: 'Activités — Alpagatitude',
    page_reservation_title: 'Réservation — Alpagatitude',
    page_contact_title: 'Contact — Alpagatitude',

    // Stats extra
    stat_open: 'Ouvert 7j/7',
    stat_rating: 'Avis clients',

    // Reservation form
    reservation_title: 'Réservation',
    reservation_subtitle: 'Réservez votre hébergement ou activité',
    reservation_section_stay: '1. Votre séjour',
    reservation_section_contact: '2. Vos coordonnées',
    reservation_arrival: 'Date d\'arrivée *',
    reservation_depart: 'Date de départ *',
    reservation_guests: 'Nombre de personnes *',
    reservation_room: 'Hébergement',
    reservation_room_default: '— Choisir un hébergement —',
    reservation_activity: 'Activité',
    reservation_activity_default: '— Choisir une activité —',
    reservation_first_name: 'Prénom *',
    reservation_last_name: 'Nom *',
    reservation_email: 'Email *',
    reservation_phone: 'Téléphone',
    reservation_message: 'Message / demandes spéciales',
    reservation_submit: 'Envoyer la demande',
    reservation_success: 'Votre demande a été envoyée ! Nous vous contacterons sous 24h.',
    reservation_legal: 'Vos données sont utilisées uniquement pour traiter votre réservation.',
    reservation_nights: 'nuit(s)',
    reservation_total: 'Total estimé',

    // Contact form extra keys
    contact_address: 'Adresse',
    contact_phone: 'Téléphone',
    contact_email_label: 'Email',
    contact_hours_title: 'Horaires',
    contact_hours: 'Lun–Dim : 9h–18h',
    contact_first_name: 'Prénom *',
    contact_last_name: 'Nom *',
    contact_subject: 'Sujet',
    contact_success: 'Votre message a bien été envoyé !',
  },

  en: {
    nav_home: 'Home',
    nav_rooms: 'Accommodations',
    nav_activities: 'Activities',
    nav_reservation: 'Book Now',
    nav_contact: 'Contact',
    lang_name: 'English',

    hero_eyebrow: '🌿 Alpaca Domain',
    hero_title: 'Welcome to Alpagatitude',
    hero_subtitle: 'A unique domain in the heart of nature, where you will meet our adorable alpacas.',
    hero_cta_rooms: 'View accommodations',
    hero_cta_book: 'Book now',
    hero_scroll: 'Discover',

    about_title: 'Our Domain',
    about_text: 'Nestled in the heart of a beautiful green region, the Alpagatitude Domain welcomes you for unforgettable experiences with our alpacas. Come and unwind, discover these fascinating animals, and enjoy outdoor activities in nature.',
    about_alpacas: 'Our Alpacas',
    about_alpacas_text: 'Our alpacas are raised with love and passion. Gentle, curious and endearing, they will be delighted to meet you during guided visits or walks.',
    about_nature: 'Nature',
    about_nature_text: 'The domain spans several hectares of meadows and wooded paths, providing the ideal setting to reconnect with nature.',
    about_relaxation: 'Relaxation',
    about_relaxation_text: 'Enjoy a restful stay in our comfortable accommodations, far from everyday stress, at the tranquil pace of the alpacas.',
    about_family: 'Family',
    about_family_text: 'The domain is ideal for families with children. Our alpacas love the company of the youngest visitors!',

    stat_alpacas: '20+ Alpacas',
    stat_visitors: '500+ Visitors/year',
    stat_rooms: 'Accommodations',
    stat_activities: 'Activities',

    rooms_title: 'Our Accommodations',
    rooms_subtitle: 'Comfortable lodgings in the heart of the domain',
    rooms_card_price: 'per night',
    rooms_card_btn: 'View & Book',
    rooms_person: 'person(s)',
    rooms_availability: 'Available',
    rooms_see_all: 'All accommodations',
    rooms_capacity: 'Capacity',
    rooms_breadcrumb: 'Accommodations',

    activities_title: 'Our Activities',
    activities_subtitle: 'Unique experiences with the alpacas',
    activities_duration: 'Duration',
    activities_price: 'Price',
    activities_max: 'Max. participants',
    activities_card_btn: 'Book',
    activities_per_person: '/ person',
    activities_see_all: 'All activities',
    activities_breadcrumb: 'Activities',

    cta_title: 'Ready for a unique experience?',
    cta_text: 'Book your stay or activity now and come meet our alpacas.',
    cta_btn: 'Book now',

    book_title: 'Reservation',
    book_subtitle: 'Fill in the form below',
    book_type_room: '🏡 Accommodation',
    book_type_activity: '🦙 Activity',
    book_select_room: 'Choose an accommodation',
    book_select_activity: 'Choose an activity',
    book_checkin: 'Check-in date',
    book_checkout: 'Check-out date',
    book_activity_date: 'Activity date',
    book_participants: 'Number of participants',
    book_firstname: 'First name',
    book_lastname: 'Last name',
    book_email: 'Email',
    book_phone: 'Phone (optional)',
    book_message: 'Message (optional)',
    book_total: 'Estimated total',
    book_submit: 'Send booking request',
    book_required: '* Required fields',
    book_nights: 'night(s)',
    book_persons: 'pers.',
    book_success: '✅ Your request has been sent! We will contact you to confirm.',
    book_error: 'An error occurred. Please try again or contact us directly.',
    book_breadcrumb: 'Reservation',
    book_note: 'Reservation sent by email — confirmation within 24h.',

    contact_title: 'Contact',
    contact_subtitle: 'Ask us your questions',
    contact_name: 'Full name',
    contact_email: 'Email',
    contact_subject: 'Subject',
    contact_message: 'Message',
    contact_submit: 'Send',
    contact_success: '✅ Your message has been sent! We will reply shortly.',
    contact_error: 'Send error. Please try again or contact us directly.',
    contact_address: 'Address',
    contact_phone_label: 'Phone',
    contact_hours: 'Opening hours',
    contact_breadcrumb: 'Contact',
    contact_info_title: 'Information',

    footer_tagline: 'An exceptional domain with alpacas',
    footer_links: 'Quick links',
    footer_contact_title: 'Contact',
    footer_rights: 'All rights reserved',
    footer_made_with: 'Made with ❤️ for the alpacas',

    // Page titles
    page_rooms_title: 'Accommodations — Alpagatitude',
    page_activities_title: 'Activities — Alpagatitude',
    page_reservation_title: 'Reservation — Alpagatitude',
    page_contact_title: 'Contact — Alpagatitude',

    // Stats extra
    stat_open: 'Open 7 days/week',
    stat_rating: 'Guest reviews',

    // Reservation form
    reservation_title: 'Reservation',
    reservation_subtitle: 'Book your accommodation or activity',
    reservation_section_stay: '1. Your stay',
    reservation_section_contact: '2. Your details',
    reservation_arrival: 'Arrival date *',
    reservation_depart: 'Departure date *',
    reservation_guests: 'Number of guests *',
    reservation_room: 'Accommodation',
    reservation_room_default: '— Choose an accommodation —',
    reservation_activity: 'Activity',
    reservation_activity_default: '— Choose an activity —',
    reservation_first_name: 'First name *',
    reservation_last_name: 'Last name *',
    reservation_email: 'Email *',
    reservation_phone: 'Phone',
    reservation_message: 'Message / special requests',
    reservation_submit: 'Send request',
    reservation_success: 'Your request has been sent! We will contact you within 24h.',
    reservation_legal: 'Your data is used only to process your reservation.',
    reservation_nights: 'night(s)',
    reservation_total: 'Estimated total',

    // Contact form extra keys
    contact_address: 'Address',
    contact_phone: 'Phone',
    contact_email_label: 'Email',
    contact_hours_title: 'Opening hours',
    contact_hours: 'Mon–Sun: 9am–6pm',
    contact_first_name: 'First name *',
    contact_last_name: 'Last name *',
    contact_subject: 'Subject',
    contact_success: 'Your message has been sent!',
  },

  es: {
    nav_home: 'Inicio',
    nav_rooms: 'Alojamientos',
    nav_activities: 'Actividades',
    nav_reservation: 'Reservar',
    nav_contact: 'Contacto',
    lang_name: 'Español',

    hero_eyebrow: '🌿 Dominio de Alpacas',
    hero_title: 'Bienvenido a Alpagatitude',
    hero_subtitle: 'Un dominio único en el corazón de la naturaleza, donde conocerás a nuestras adorables alpacas.',
    hero_cta_rooms: 'Ver alojamientos',
    hero_cta_book: 'Reservar ahora',
    hero_scroll: 'Descubrir',

    about_title: 'Nuestro Dominio',
    about_text: 'Situado en el corazón de una hermosa región verde, el Dominio Alpagatitude le da la bienvenida para vivir experiencias inolvidables con nuestras alpacas. Venga a relajarse, descubrir estos fascinantes animales y disfrutar de actividades en plena naturaleza.',
    about_alpacas: 'Nuestras Alpacas',
    about_alpacas_text: 'Nuestras alpacas se crían con amor y pasión. Suaves, curiosas y encantadoras, estarán encantadas de conocerle durante visitas guiadas o paseos.',
    about_nature: 'La Naturaleza',
    about_nature_text: 'El dominio se extiende por varios hectáreas de praderas y caminos arbolados, ofreciendo un entorno ideal para reconectarse con la naturaleza.',
    about_relaxation: 'La Relajación',
    about_relaxation_text: 'Disfrute de una estancia tranquila en nuestros cómodos alojamientos, lejos del estrés cotidiano, al ritmo tranquilo de las alpacas.',
    about_family: 'En Familia',
    about_family_text: '¡El dominio es ideal para familias con niños. Nuestras alpacas adoran la compañía de los más pequeños!',

    stat_alpacas: '20+ Alpacas',
    stat_visitors: '500+ Visitantes/año',
    stat_rooms: 'Alojamientos',
    stat_activities: 'Actividades',

    rooms_title: 'Nuestros Alojamientos',
    rooms_subtitle: 'Alojamientos cómodos en el corazón del dominio',
    rooms_card_price: 'por noche',
    rooms_card_btn: 'Ver y Reservar',
    rooms_person: 'persona(s)',
    rooms_availability: 'Disponible',
    rooms_see_all: 'Todos los alojamientos',
    rooms_capacity: 'Capacidad',
    rooms_breadcrumb: 'Alojamientos',

    activities_title: 'Nuestras Actividades',
    activities_subtitle: 'Experiencias únicas con las alpacas',
    activities_duration: 'Duración',
    activities_price: 'Precio',
    activities_max: 'Participantes máx.',
    activities_card_btn: 'Reservar',
    activities_per_person: '/ persona',
    activities_see_all: 'Todas las actividades',
    activities_breadcrumb: 'Actividades',

    cta_title: '¿Listo para vivir una experiencia única?',
    cta_text: 'Reserve su estancia o actividad ahora y venga a conocer a nuestras alpacas.',
    cta_btn: 'Reservar ahora',

    book_title: 'Reserva',
    book_subtitle: 'Rellene el formulario a continuación',
    book_type_room: '🏡 Alojamiento',
    book_type_activity: '🦙 Actividad',
    book_select_room: 'Elegir un alojamiento',
    book_select_activity: 'Elegir una actividad',
    book_checkin: 'Fecha de entrada',
    book_checkout: 'Fecha de salida',
    book_activity_date: 'Fecha de la actividad',
    book_participants: 'Número de participantes',
    book_firstname: 'Nombre',
    book_lastname: 'Apellido',
    book_email: 'Correo electrónico',
    book_phone: 'Teléfono (opcional)',
    book_message: 'Mensaje (opcional)',
    book_total: 'Total estimado',
    book_submit: 'Enviar solicitud de reserva',
    book_required: '* Campos obligatorios',
    book_nights: 'noche(s)',
    book_persons: 'pers.',
    book_success: '✅ ¡Su solicitud ha sido enviada! Le contactaremos para confirmar.',
    book_error: 'Se produjo un error. Inténtelo de nuevo o contáctenos directamente.',
    book_breadcrumb: 'Reserva',
    book_note: 'Reserva enviada por correo — confirmación en 24h.',

    contact_title: 'Contacto',
    contact_subtitle: 'Háganos sus preguntas',
    contact_name: 'Nombre completo',
    contact_email: 'Correo electrónico',
    contact_subject: 'Asunto',
    contact_message: 'Mensaje',
    contact_submit: 'Enviar',
    contact_success: '✅ ¡Su mensaje ha sido enviado! Le responderemos pronto.',
    contact_error: 'Error de envío. Inténtelo de nuevo o contáctenos directamente.',
    contact_address: 'Dirección',
    contact_phone_label: 'Teléfono',
    contact_hours: 'Horario',
    contact_breadcrumb: 'Contacto',
    contact_info_title: 'Información',

    footer_tagline: 'Un dominio excepcional con alpacas',
    footer_links: 'Enlaces rápidos',
    footer_contact_title: 'Contacto',
    footer_rights: 'Todos los derechos reservados',
    footer_made_with: 'Hecho con ❤️ para las alpacas',

    // Page titles
    page_rooms_title: 'Alojamientos — Alpagatitude',
    page_activities_title: 'Actividades — Alpagatitude',
    page_reservation_title: 'Reserva — Alpagatitude',
    page_contact_title: 'Contacto — Alpagatitude',

    // Stats extra
    stat_open: 'Abierto 7 días/sem.',
    stat_rating: 'Reseñas',

    // Reservation form
    reservation_title: 'Reserva',
    reservation_subtitle: 'Reserve su alojamiento o actividad',
    reservation_section_stay: '1. Su estancia',
    reservation_section_contact: '2. Sus datos',
    reservation_arrival: 'Fecha de llegada *',
    reservation_depart: 'Fecha de salida *',
    reservation_guests: 'Número de personas *',
    reservation_room: 'Alojamiento',
    reservation_room_default: '— Elegir un alojamiento —',
    reservation_activity: 'Actividad',
    reservation_activity_default: '— Elegir una actividad —',
    reservation_first_name: 'Nombre *',
    reservation_last_name: 'Apellido *',
    reservation_email: 'Correo electrónico *',
    reservation_phone: 'Teléfono',
    reservation_message: 'Mensaje / solicitudes especiales',
    reservation_submit: 'Enviar solicitud',
    reservation_success: '¡Su solicitud ha sido enviada! Le contactaremos en 24h.',
    reservation_legal: 'Sus datos se utilizan únicamente para gestionar su reserva.',
    reservation_nights: 'noche(s)',
    reservation_total: 'Total estimado',

    // Contact form extra keys
    contact_address: 'Dirección',
    contact_phone: 'Teléfono',
    contact_email_label: 'Correo electrónico',
    contact_hours_title: 'Horario',
    contact_hours: 'Lun–Dom: 9h–18h',
    contact_first_name: 'Nombre *',
    contact_last_name: 'Apellido *',
    contact_subject: 'Asunto',
    contact_success: '¡Su mensaje ha sido enviado!',
  }
};

// ── Données statiques (remplacent la BD) ────────────────────────────────────
const ROOMS = [
  { id: 1, name: 'Le Chalet Alpagas',  name_en: 'The Alpaca Chalet',    name_es: 'El Chalet Alpacas',
    desc_fr: "Un chalet charmant et chaleureux au cœur du domaine, avec une vue imprenable sur les prairies des alpagas. Équipé d'une cuisine, d'un salon cosy et de 2 chambres.",
    desc_en: "A charming and cozy chalet in the heart of the domain, with a breathtaking view of the alpaca meadows. Equipped with a kitchen, a cozy living room and 2 bedrooms.",
    desc_es: "Un acogedor chalet en el corazón del dominio, con vistas impresionantes a los prados de las alpacas.",
    capacity: 4, price: 120, image: 'assets/rooms/chalet-alpagas.svg' },
  { id: 2, name: 'La Lodge du Pré',    name_en: 'The Meadow Lodge',     name_es: 'La Lodge del Prado',
    desc_fr: "Une lodge spacieuse et confortable bordant les pâturages. Parfaite pour les familles ou les groupes, avec terrasse et accès direct aux alpagas.",
    desc_en: "A spacious and comfortable lodge bordering the pastures. Perfect for families or groups, with a terrace and direct access to the alpacas.",
    desc_es: "Una lodge espaciosa y cómoda junto a los pastizales. Perfecta para familias o grupos.",
    capacity: 6, price: 180, image: 'assets/rooms/lodge-pre.svg' },
  { id: 3, name: 'Le Tipi Étoilé',     name_en: 'The Starry Tipi',      name_es: 'El Tipi Estrellado',
    desc_fr: "Vivez une expérience insolite dans notre tipi confortable, idéal pour les couples. Dormez à la belle étoile tout en restant au chaud, et réveillez-vous avec les alpagas.",
    desc_en: "Live an unusual experience in our comfortable tipi, ideal for couples. Sleep under the stars while staying warm, and wake up with the alpacas.",
    desc_es: "Vive una experiencia insólita en nuestro cómodo tipi, ideal para parejas.",
    capacity: 2, price: 90, image: 'assets/rooms/tipi-etoile.svg' },
];

const ACTIVITIES = [
  { id: 1, name: 'Balade avec les alpagas', name_en: 'Walk with the alpacas', name_es: 'Paseo con las alpacas',
    desc_fr: "Partez en balade dans les chemins bucoliques du domaine accompagnés de nos alpagas. Une expérience unique et inoubliable pour petits et grands.",
    desc_en: "Take a walk along the bucolic paths of the domain accompanied by our alpacas. A unique and unforgettable experience for young and old.",
    desc_es: "Pasee por los caminos bucólicos del dominio acompañado de nuestras alpacas.",
    price: 25, duration: '1h30', max: 8, image: 'assets/activities/balade-alpagas.svg' },
  { id: 2, name: 'Nourrissage & câlins', name_en: 'Feeding & cuddles', name_es: 'Alimentación y caricias',
    desc_fr: "Approchez les alpagas, donnez-leur à manger et profitez de leurs caresses. Idéal pour les enfants et les amoureux des animaux.",
    desc_en: "Approach the alpacas, feed them and enjoy their cuddles. Ideal for children and animal lovers.",
    desc_es: "Acércate a las alpacas, dales de comer y disfruta de sus caricias.",
    price: 15, duration: '45min', max: 12, image: 'assets/activities/nourrissage.svg' },
  { id: 3, name: 'Séance photo avec les alpagas', name_en: 'Photo session with alpacas', name_es: 'Sesión de fotos con alpacas',
    desc_fr: "Immortalisez votre visite avec une séance photo professionnelle aux côtés de nos magnifiques alpagas. Des souvenirs uniques garantis !",
    desc_en: "Immortalize your visit with a professional photo session alongside our beautiful alpacas. Unique memories guaranteed!",
    desc_es: "Inmortaliza tu visita con una sesión de fotos junto a nuestras hermosas alpacas.",
    price: 35, duration: '1h', max: 6, image: 'assets/activities/seance-photo.svg' },
  { id: 4, name: 'Atelier laine & tonte', name_en: 'Wool & shearing workshop', name_es: 'Taller de lana y esquila',
    desc_fr: "Découvrez les secrets de la laine d'alpaga : tonte, cardage, filage... Un atelier artisanal passionnant pour toute la famille.",
    desc_en: "Discover the secrets of alpaca wool: shearing, carding, spinning... A fascinating craft workshop for the whole family.",
    desc_es: "Descubra los secretos de la lana de alpaca: esquila, cardado, hilado...",
    price: 40, duration: '2h', max: 10, image: 'assets/activities/atelier-laine.svg' },
  { id: 5, name: 'Randonnée accompagnée', name_en: 'Guided hike', name_es: 'Senderismo acompañado',
    desc_fr: "Explorez les sentiers naturels entourant le domaine en compagnie de nos guides et de quelques alpagas. Paysages grandioses garantis !",
    desc_en: "Explore the natural trails surrounding the domain with our guides and some alpacas. Breathtaking landscapes guaranteed!",
    desc_es: "Explora los senderos naturales alrededor del dominio con nuestros guías y algunas alpacas.",
    price: 45, duration: '3h', max: 10, image: 'assets/activities/randonnee.svg' },
];

// ── Helpers ──────────────────────────────────────────────────────────────────
function getLang() {
  return localStorage.getItem('lang') || 'fr';
}
function setLang(l) {
  localStorage.setItem('lang', l);
  applyLang();
}
function t(key) {
  const lang = getLang();
  return (TRANSLATIONS[lang] && TRANSLATIONS[lang][key]) || (TRANSLATIONS['fr'][key]) || key;
}
function getItemName(item) {
  const lang = getLang();
  if (lang === 'en' && item.name_en) return item.name_en;
  if (lang === 'es' && item.name_es) return item.name_es;
  return item.name;
}
function getItemDesc(item) {
  const lang = getLang();
  if (lang === 'en' && item.desc_en) return item.desc_en;
  if (lang === 'es' && item.desc_es) return item.desc_es;
  return item.desc_fr;
}
function formatPrice(n) {
  return n.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' €';
}

// ── Apply translations to DOM (data-i18n attributes) ─────────────────────────
function applyLang() {
  const lang = getLang();
  document.querySelectorAll('[data-i18n]').forEach(el => {
    const key = el.getAttribute('data-i18n');
    const val = t(key);
    if (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') {
      el.placeholder = val;
    } else {
      el.textContent = val;
    }
  });
  document.querySelectorAll('[data-i18n-html]').forEach(el => {
    el.innerHTML = t(el.getAttribute('data-i18n-html'));
  });
  // Update lang buttons
  document.querySelectorAll('.lang-btn').forEach(btn => {
    btn.classList.toggle('active', btn.dataset.lang === lang);
  });
  document.documentElement.lang = lang;
  // Re-render dynamic sections if present
  if (typeof renderRooms === 'function')      renderRooms();
  if (typeof renderActivities === 'function') renderActivities();
  if (typeof renderBookingSelects === 'function') renderBookingSelects();
}
