<?php
require_once 'backend/config/config.php';
session_start();

// Language support
$lang = $_GET['lang'] ?? 'ru';
$translations = [
    'ru' => [
        'home' => 'Главная',
        'tours' => 'Туры',
        'services' => 'Услуги',
        'offers' => 'Акции',
        'about' => 'О нас',
        'contacts' => 'Контакты',
        'login' => 'Войти',
        'menu_home' => 'Главная',
        'menu_collection' => 'Коллекция туров',
        'menu_hotels' => 'Все туры',
        'menu_private' => 'Все туры',
        'menu_contacts' => 'Контакты',
        'hero_title' => 'Сейшелы класса люкс вместе с Travel Hub',
        'hero_subtitle' => 'Персональный консьерж, лучшие отели и приватные перелёты. Ответим за 15 минут и соберём идеальное путешествие.',
        'find_tour' => 'Получить предложение',
        'special_offers' => 'Коллекция направлений',
        'search_tours' => 'Поиск туров',
        'popular_destinations' => 'Популярные направления',
        'why_choose_us' => 'Почему выбирают Travel Hub',
        'testimonials' => 'Отзывы наших клиентов',
        'newsletter' => 'Подпишитесь на рассылку',
        'newsletter_text' => 'Получайте первыми информацию о горящих турах, специальных предложениях и акциях',
        'subscribe' => 'Подписаться',
        'privacy' => 'Мы не рассылаем спам и не передаем ваши данные третьим лицам',
        'footer_tours' => 'Туры',
        'footer_services' => 'Услуги',
        'footer_info' => 'Информация',
        'copyright' => '© 2023 Travel Hub. Все права защищены.',
        'privacy_policy' => 'Политика конфиденциальности',
        'terms' => 'Условия использования'
    ],
    'en' => [
        'home' => 'Home',
        'tours' => 'Tours',
        'services' => 'Services',
        'offers' => 'Offers',
        'about' => 'About Us',
        'contacts' => 'Contacts',
        'login' => 'Login',
        'menu_home' => 'Home',
        'menu_collection' => 'Curated Collection',
        'menu_hotels' => 'All Tours',
        'menu_private' => 'All Tours',
        'menu_contacts' => 'Contacts',
        'hero_title' => 'Seychelles luxury curated by Travel Hub',
        'hero_subtitle' => 'Personal concierge, iconic resorts and private flights. We reply within 15 minutes and craft your perfect escape.',
        'find_tour' => 'Request an offer',
        'special_offers' => 'Destination collection',
        'search_tours' => 'Search tours',
        'popular_destinations' => 'Popular destinations',
        'why_choose_us' => 'Why choose Travel Hub',
        'testimonials' => 'Customer testimonials',
        'newsletter' => 'Subscribe to newsletter',
        'newsletter_text' => 'Get the first information about hot tours, special offers and promotions',
        'subscribe' => 'Subscribe',
        'privacy' => 'We do not send spam and do not share your data with third parties',
        'footer_tours' => 'Tours',
        'footer_services' => 'Services',
        'footer_info' => 'Information',
        'copyright' => '© 2023 Travel Hub. All rights reserved.',
        'privacy_policy' => 'Privacy Policy',
        'terms' => 'Terms of Use'
    ]
];
$heroKicker = $lang === 'ru'
    ? 'Сейшельские острова · отдых вне времени'
    : 'Seychelles · timeless escape';
$mobileHighlightTitle = $lang === 'ru'
    ? 'Travel Hub рекомендует'
    : 'Travel Hub recommends';
$mobileHighlightSubtitle = $lang === 'ru'
    ? 'Подборка локаций на Сейшелах: острова, лагуны и фотогеничные пляжи для вашего отпуска.'
    : 'Curated Seychelles spots: islands, lagoons and photogenic beaches for your getaway.';
$highlightCards = [
    [
        'image' => 'https://images.unsplash.com/photo-1520357456833-4e5a6c10cb19?auto=format&fit=crop&w=900&q=80',
        'badge' => [
            'ru' => 'Маэ · Сент-Анн',
            'en' => 'Mahé · Sainte Anne'
        ],
        'title' => [
            'ru' => 'Морской парк и черепахи в лагуне',
            'en' => 'Marine park & turtles in the lagoon'
        ],
        'description' => [
            'ru' => 'Лодка с прозрачным дном, снорклинг и гигантские черепахи рядом с вами.',
            'en' => 'Glass-bottom boats, reef snorkelling and gentle giant tortoises nearby.'
        ]
    ],
    [
        'image' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=900&q=80',
        'badge' => [
            'ru' => 'Ла-Диг',
            'en' => 'La Digue'
        ],
        'title' => [
            'ru' => "Анс Сурс д'Аржан — пляж мечты",
            'en' => "Anse Source d'Argent — iconic beach"
        ],
        'description' => [
            'ru' => 'Гранитные валуны, белый песок и закаты, которые невозможно забыть.',
            'en' => 'Granite boulders, white sand and sunsets you will never forget.'
        ]
    ],
    [
        'image' => 'https://images.unsplash.com/photo-1500375592092-40eb2168fd21?auto=format&fit=crop&w=900&q=80',
        'badge' => [
            'ru' => 'Праслин',
            'en' => 'Praslin'
        ],
        'title' => [
            'ru' => 'Долина Валле-де-Мэ',
            'en' => 'Vallée de Mai rainforest'
        ],
        'description' => [
            'ru' => 'ЮНЕСКО лес с пальмами коко-де-мер и редкими черными попугаями.',
            'en' => 'UNESCO-listed palms of coco de mer and rare black parrots.'
        ]
    ]
];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Hub - Путешествия вашей мечты</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-body: #f4f9ff;
            --bg-surface: #ffffff;
            --bg-muted: #edf5ff;
            --bg-accent-soft: #dff0ff;
            --accent-primary: #3ba3ff;
            --accent-secondary: #7bc4ff;
            --accent-highlight: #ffe8a3;
            --text-primary: #1f2a44;
            --text-secondary: #4f5f78;
            --text-muted: rgba(79, 95, 120, 0.68);
            --border-soft: rgba(59, 163, 255, 0.18);
            --shadow-soft: 0 22px 48px rgba(59, 163, 255, 0.18);
        }
        body {
            font-family: 'Open Sans', sans-serif;
            background: linear-gradient(180deg, #f8fbff 0%, #eef6ff 45%, #fdfdff 100%);
            color: var(--text-primary);
            position: relative;
            overflow-x: hidden;
        }
        /* Переливающийся фон-коллаж */
        .animated-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }
        .background-collage {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-template-rows: repeat(3, 1fr);
            gap: 0;
        }
        .background-cell {
            position: relative;
            overflow: hidden;
        }
        .background-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0;
            transition: opacity 1.5s ease-in-out;
            filter: blur(3px);
            transform: scale(1.1);
        }
        .background-image.active {
            opacity: 0.6;
        }
        .background-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(180deg, rgba(248, 251, 255, 0.7) 0%, rgba(238, 245, 255, 0.6) 45%, rgba(253, 253, 255, 0.7) 100%);
            z-index: 1;
            pointer-events: none;
        }
        .heading-font {
            font-family: 'Montserrat', sans-serif;
        }
        .pastel-card {
            background: var(--bg-surface);
            border-radius: 26px;
            border: 1px solid var(--border-soft);
            box-shadow: var(--shadow-soft);
        }
        .surface-card {
            background: var(--bg-surface);
            border-radius: 20px;
            border: 1px solid rgba(59, 163, 255, 0.12);
            box-shadow: 0 16px 32px rgba(59, 163, 255, 0.12);
        }
        .tour-card {
            background: var(--bg-surface);
            border-radius: 22px;
            border: 1px solid rgba(59, 163, 255, 0.12);
            box-shadow: 0 22px 50px rgba(59, 163, 255, 0.14);
            transition: transform 0.35s ease, box-shadow 0.35s ease;
        }
        .tour-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 30px 70px rgba(59, 163, 255, 0.22);
        }
        .tour-card:hover .tour-image {
            transform: scale(1.05);
        }
        .tour-image {
            transition: transform 0.4s ease;
        }
        .nav-link {
            font-weight: 500;
            color: var(--text-secondary);
            transition: color 0.3s ease;
        }
        .nav-link:hover {
            color: var(--accent-primary);
        }
        .nav-container {
            position: relative;
            padding-bottom: 6px;
        }
        #menu-indicator {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--accent-secondary), var(--accent-primary));
            border-radius: 999px;
            transition: transform 0.3s ease, width 0.3s ease;
            opacity: 0;
        }
        .fade-in {
            opacity: 0;
            transition: opacity 0.8s ease;
        }
        .search-box {
            background: var(--bg-surface);
            border-radius: 24px;
            box-shadow: var(--shadow-soft);
            border: 1px solid rgba(59, 163, 255, 0.14);
        }
        .animated-button {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .animated-button:hover {
            transform: translateY(-4px);
            box-shadow: 0 24px 45px rgba(59, 163, 255, 0.22);
        }
        .testimonial-card {
            background: var(--bg-surface);
            border-radius: 22px;
            border: 1px solid rgba(59, 163, 255, 0.14);
            box-shadow: 0 16px 36px rgba(59, 163, 255, 0.14);
            transition: transform 0.35s ease, box-shadow 0.35s ease;
        }
        .testimonial-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 28px 58px rgba(59, 163, 255, 0.22);
        }
        .eyebrow-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 18px;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: 0.32em;
            font-size: 0.65rem;
            background: rgba(59, 163, 255, 0.12);
            border: 1px solid rgba(59, 163, 255, 0.28);
            color: var(--text-primary);
        }
        .pill-badge {
            display: inline-flex;
            align-items: center;
            padding: 9px 18px;
            border-radius: 999px;
            background: rgba(59, 163, 255, 0.12);
            border: 1px solid rgba(59, 163, 255, 0.22);
            font-size: 0.7rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--text-secondary);
        }
        .hero-metric-card {
            background: var(--bg-surface);
            border-radius: 18px;
            border: 1px solid rgba(59, 163, 255, 0.16);
            box-shadow: 0 18px 40px rgba(59, 163, 255, 0.16);
            padding: 18px 20px;
        }
        .hero-metrics {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
            max-width: 500px;
        }
        @media (min-width: 768px) {
            .hero-metrics {
                grid-template-columns: repeat(4, minmax(0, 1fr));
                max-width: 720px;
            }
        }
        .hero-metric-value {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.9rem;
            font-weight: 700;
            color: var(--accent-primary);
        }
        .hero-metric-label {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.22em;
            color: var(--text-muted);
        }
        .mobile-highlight-card {
            position: relative;
            min-width: 280px;
            border-radius: 26px;
            overflow: hidden;
            background-size: cover;
            background-position: center;
            box-shadow: 0 22px 48px rgba(59, 163, 255, 0.18);
        }
        .mobile-highlight-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.05) 0%, rgba(15, 23, 42, 0.4) 100%);
        }
        .mobile-highlight-content {
            position: relative;
            color: var(--bg-surface);
            padding: 24px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            height: 100%;
            gap: 12px;
        }
        .floating-contact-bar {
            backdrop-filter: blur(18px);
            background: rgba(255, 255, 255, 0.94);
            border: 1px solid rgba(59, 163, 255, 0.2);
            box-shadow: 0 26px 50px rgba(59, 163, 255, 0.18);
            padding: 4px;
            opacity: 0;
            transform: translateY(24px);
            pointer-events: none;
            transition: opacity 0.4s ease, transform 0.4s ease;
        }
        .floating-contact-bar.is-visible {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }
        .floating-contact-cta {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 8px 16px;
            border-radius: 999px;
            background: linear-gradient(90deg, #7bc4ff, #3ba3ff, #2f7fe6);
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            font-size: 0.68rem;
            box-shadow: 0 18px 32px rgba(59, 163, 255, 0.28);
        }
        .floating-contact-cta-question {
            font-family: 'Montserrat', sans-serif;
            font-size: 0.8rem;
            text-transform: none;
            letter-spacing: normal;
        }
        .floating-contact-cta-action {
            padding: 6px 14px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.35);
            letter-spacing: 0.2em;
            font-size: 0.62rem;
        }
        #mobile-menu {
            max-height: 0;
            opacity: 0;
            transform: translateY(-12px);
            overflow: hidden;
            pointer-events: none;
            transition: max-height 0.45s ease, opacity 0.3s ease, transform 0.3s ease;
        }
        #mobile-menu.mobile-menu--open {
            max-height: 600px;
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }
        @media (max-width: 767px) {
            .mobile-hero-kicker {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                font-size: 12px;
                letter-spacing: 0.12em;
                text-transform: uppercase;
                padding: 8px 14px;
                border-radius: 9999px;
                background: rgba(59, 163, 255, 0.14);
                color: var(--text-secondary);
            }
            .mobile-hero-title {
                font-size: 34px;
                line-height: 1.2;
                letter-spacing: -0.01em;
            }
            .mobile-hero-subtitle {
                font-size: 16px;
                line-height: 1.5;
                color: var(--text-secondary);
            }
            .mobile-section-heading {
                font-size: 24px;
                line-height: 1.3;
                color: var(--text-primary);
            }
            .mobile-section-subtitle {
                font-size: 14px;
                color: var(--text-secondary);
            }
        }
        .nav-link--primary[data-active="true"] {
            color: var(--accent-primary);
        }
    </style>
</head>
<body id="page-top" class="text-slate-900">
    <!-- Переливающийся фон-коллаж -->
    <div class="animated-background">
        <div class="background-collage" id="backgroundCollage"></div>
        <div class="background-overlay"></div>
    </div>
    <div id="page-top"></div>
    <!-- Header with Navigation -->
    <header class="bg-white/90 backdrop-blur-md border-b border-sky-100 sticky top-0 z-50">
        <div class="mx-auto w-full max-w-[95vw] px-4 md:px-8 lg:px-16 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <a href="sss.php" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-sky-300 via-sky-400 to-sky-500 flex items-center justify-center shadow-lg shadow-sky-200/60">
                        <i class="fas fa-plane text-white text-base"></i>
                    </div>
                    <span class="heading-font text-2xl font-bold text-sky-600 tracking-wide">Travel Hub</span>
                </a>
            </div>

            <?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                <a href="/backend/admin/admin.php" class="hidden lg:block bg-gradient-to-r from-rose-300 via-rose-400 to-rose-500 text-white px-5 py-2 rounded-full font-medium shadow-md hover:shadow-lg transition">Админ панель</a>
            <?php else: ?>
                <div class="hidden lg:flex bg-sky-50 px-6 py-2 rounded-full text-xs uppercase tracking-[0.32em] text-sky-500/80">
                    <span class="font-semibold mr-5">Коллекция</span>
                    <a href="/frontend/window/tours.php" class="text-slate-500 hover:text-sky-500 mr-4">Горящие</a>
                    <a href="/frontend/window/tours.php" class="text-slate-500 hover:text-sky-500 mr-4">Турция</a>
                    <a href="/frontend/window/tours.php" class="text-slate-500 hover:text-sky-500 mr-4">ОАЭ</a>
                    <a href="/frontend/window/tours.php" class="text-slate-500 hover:text-sky-500">Египет</a>
                </div>
            <?php endif; ?>

            <nav id="desktop-nav" class="hidden md:flex items-center space-x-8 nav-container">
                <a href="#page-top" class="nav-link nav-link--primary" data-active="true"><?php echo $translations[$lang]['menu_home']; ?></a>
                <a href="#tours-section" class="nav-link nav-link--primary"><?php echo $translations[$lang]['menu_collection']; ?></a>
                <a href="/frontend/window/tours.php" class="nav-link nav-link--primary"><?php echo $translations[$lang]['menu_hotels']; ?></a>
                <a href="#contact" class="nav-link nav-link--primary"><?php echo $translations[$lang]['menu_contacts']; ?></a>
                <div id="menu-indicator" class="menu-indicator"></div>
            </nav>

            <div class="flex items-center space-x-4">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <div class="relative">
                        <button id="user-menu-button" class="hidden md:flex items-center bg-gradient-to-r from-sky-300 via-sky-400 to-sky-500 text-white px-5 py-2 rounded-full font-medium shadow-md">
                            <i class="fas fa-user mr-2"></i><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Пользователь'); ?>
                            <i class="fas fa-chevron-down ml-2"></i>
                        </button>
                        <div id="user-menu" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-sky-100 z-50">
                            <a href="/frontend/window/dashboard.php" class="block px-4 py-2 text-sm text-slate-600 hover:bg-sky-50">Личный кабинет</a>
                            <?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                            <a href="/backend/admin/admin.php" class="block px-4 py-2 text-sm text-slate-600 hover:bg-sky-50">Админ панель</a>
                            <?php endif; ?>
                            <a href="/backend/scripts/logout.php" class="block px-4 py-2 text-sm text-slate-600 hover:bg-sky-50">Выход</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="/frontend/window/login.html" class="hidden md:block bg-gradient-to-r from-sky-300 via-sky-400 to-sky-500 text-white px-5 py-2 rounded-full font-medium animated-button">Войти</a>
                <?php endif; ?>
                <button id="mobile-menu-button" class="md:hidden text-slate-500">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>
        
        <div id="mobile-menu" class="md:hidden bg-white/95 border-t border-sky-100 py-4 px-4">
            <div class="flex flex-col space-y-3">
                <a href="#page-top" class="nav-link">Главная</a>
                <a href="#tours-section" class="nav-link">Туры</a>
                <a href="/frontend/window/tours.php" class="nav-link">Все туры</a>
                <a href="#contact" class="nav-link"><?php echo $translations[$lang]['menu_contacts']; ?></a>
                <div class="flex space-x-3 pt-2">
                    <a href="#" class="w-9 h-9 rounded-full border border-sky-200 flex items-center justify-center text-slate-500 hover:bg-sky-100 hover:text-sky-500 transition">
                        <i class="fab fa-telegram"></i>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-full border border-sky-200 flex items-center justify-center text-slate-500 hover:bg-sky-100 hover:text-sky-500 transition">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </div>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="/frontend/window/dashboard.php" class="bg-gradient-to-r from-sky-300 via-sky-400 to-sky-500 text-white px-5 py-2 rounded-full font-medium text-center animated-button">Личный кабинет</a>
                    <?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                    <a href="/backend/admin/admin.php" class="bg-gradient-to-r from-sky-300 via-sky-400 to-sky-500 text-white px-5 py-2 rounded-full font-medium text-center animated-button">Админ панель</a>
                    <?php endif; ?>
                    <a href="/backend/scripts/logout.php" class="bg-slate-200 text-slate-600 px-5 py-2 rounded-full font-medium text-center">Выход</a>
                <?php else: ?>
                    <a href="/frontend/window/login.html" class="bg-gradient-to-r from-sky-300 via-sky-400 to-sky-500 text-white px-5 py-2 rounded-full font-medium text-center animated-button">Войти</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-sky-100 via-white to-sky-50 text-slate-800 py-16 md:py-24 overflow-hidden">
        <div class="container mx-auto px-6 relative z-10">
            <div class="max-w-5xl xl:max-w-6xl fade-in space-y-8">
                <div class="md:hidden mobile-hero-kicker text-sky-600">
                    <i class="fas fa-location-dot text-sm"></i>
                    <?php echo $heroKicker; ?>
                </div>
                <h1 class="heading-font text-4xl md:text-5xl font-bold mobile-hero-title text-slate-900"><?php echo $translations[$lang]['hero_title']; ?></h1>
                <p class="text-lg md:text-xl mobile-hero-subtitle text-slate-700"><?php echo $translations[$lang]['hero_subtitle']; ?></p>
                <p class="text-xs uppercase tracking-[0.35em] text-sky-500">Среднее время ответа — 15 минут</p>
                <div class="md:hidden flex flex-wrap gap-2 text-sm text-sky-700">
                    <span class="px-3 py-2 rounded-full bg-sky-100 border border-sky-200">Приватные виллы</span>
                    <span class="px-3 py-2 rounded-full bg-sky-100 border border-sky-200">Премиальный сервис</span>
                    <span class="px-3 py-2 rounded-full bg-sky-100 border border-sky-200">365 дней солнца</span>
                </div>
                <div class="flex flex-wrap gap-3 pt-2">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="#consultation" class="bg-gradient-to-r from-sky-300 via-sky-400 to-sky-500 text-white px-6 py-3 rounded-full font-semibold animated-button shadow-lg tracking-[0.3em] text-xs uppercase"><?php echo $translations[$lang]['find_tour']; ?></a>
                        <a href="#tours-section" class="border border-sky-200 text-sky-600 px-6 py-3 rounded-full font-semibold animated-button tracking-[0.3em] text-xs uppercase bg-white/70"><?php echo $translations[$lang]['special_offers']; ?></a>
                    <?php else: ?>
                        <a href="/frontend/window/login.html" class="bg-gradient-to-r from-sky-300 via-sky-400 to-sky-500 text-white px-6 py-3 rounded-full font-semibold animated-button shadow-lg tracking-[0.3em] text-xs uppercase" onclick="alert('Войдите, чтобы получить персональное предложение')"><?php echo $translations[$lang]['find_tour']; ?></a>
                        <a href="#tours-section" class="border border-sky-200 text-sky-600 px-6 py-3 rounded-full font-semibold animated-button tracking-[0.3em] text-xs uppercase bg-white/70"><?php echo $translations[$lang]['special_offers']; ?></a>
                    <?php endif; ?>
                </div>
                <div class="hero-metrics">
                    <div class="hero-metric-card">
                        <div class="hero-metric-value">15 мин</div>
                        <div class="hero-metric-label">Ответ менеджера</div>
                    </div>
                    <div class="hero-metric-card">
                        <div class="hero-metric-value">50+</div>
                        <div class="hero-metric-label">Направлений</div>
                    </div>
                    <div class="hero-metric-card">
                        <div class="hero-metric-value">24/7</div>
                        <div class="hero-metric-label">Консьерж поддержка</div>
                    </div>
                    <div class="hero-metric-card">
                        <div class="hero-metric-value">99%</div>
                        <div class="hero-metric-label">Гостей возвращаются</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="absolute inset-0 overflow-hidden z-0">
            <div class="absolute inset-0 bg-gradient-to-t from-white via-transparent to-transparent opacity-80"></div>
            <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1920&q=80"
                 alt="Travel" class="w-full h-full object-cover" style="mix-blend-mode: lighten; opacity: 0.55;">
        </div>
    </section>

    <!-- Private Jet & Concierge -->
    <section id="premium-concierge" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-14 space-y-3">
                <span class="pill-badge">Concierge 24/7</span>
                <h2 class="heading-font text-3xl font-bold text-slate-900">Персональный менеджер и команда на связи в любой точке мира</h2>
                <p class="text-slate-600 max-w-3xl mx-auto">Мы берём ответственность за путешествие от момента заявки до возвращения домой: бронирование, контроль перелётов, досуг и безопасность.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                <div class="surface-card p-6 space-y-4">
                    <div class="w-12 h-12 rounded-full bg-sky-100 text-sky-500 flex items-center justify-center text-xl"><i class="fas fa-headset"></i></div>
                    <h3 class="heading-font text-xl font-semibold text-slate-800">24/7 куратор</h3>
                    <p class="text-sm text-slate-600">Персональный менеджер, который лично отвечает за качество сервиса на протяжении всего путешествия.</p>
                </div>
                <div class="surface-card p-6 space-y-4">
                    <div class="w-12 h-12 rounded-full bg-sky-100 text-sky-500 flex items-center justify-center text-xl"><i class="fas fa-shield-alt"></i></div>
                    <h3 class="heading-font text-xl font-semibold text-slate-800">Безопасность</h3>
                    <p class="text-sm text-slate-600">Страхование, круглосуточная горячая линия, проверенные трансферы и сопровождение в непредвиденных ситуациях.</p>
                </div>
                <div class="surface-card p-6 space-y-4">
                    <div class="w-12 h-12 rounded-full bg-sky-100 text-sky-500 flex items-center justify-center text-xl"><i class="fas fa-gift"></i></div>
                    <h3 class="heading-font text-xl font-semibold text-slate-800">Привилегии</h3>
                    <p class="text-sm text-slate-600">Эксклюзивные тарифы, апгрейды номеров, VIP-залы в аэропортах и приватные экскурсии.</p>
                </div>
                <div class="surface-card p-6 space-y-4">
                    <div class="w-12 h-12 rounded-full bg-sky-100 text-sky-500 flex items-center justify-center text-xl"><i class="fas fa-map-marked-alt"></i></div>
                    <h3 class="heading-font text-xl font-semibold text-slate-800">Индивидуальные маршруты</h3>
                    <p class="text-sm text-slate-600">Комбинируем несколько направлений, частные перелёты и тематические события под ваши предпочтения.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Search Box -->
    <div class="container mx-auto px-6 -mt-12 relative z-20">
        <div class="pastel-card p-6 search-box">
            <h2 class="heading-font text-xl font-semibold mb-4 text-slate-800"><?php echo $translations[$lang]['search_tours']; ?></h2>
            <form class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2">Направление</label>
                    <select class="w-full px-4 py-3 border border-sky-100 rounded-xl bg-white focus:ring-2 focus:ring-sky-300 focus:border-sky-300 text-slate-700">
                        <option>Все направления</option>
                        <option>Турция</option>
                        <option>Тайланд</option>
                        <option>ОАЭ</option>
                        <option>Мальдивы</option>
                        <option>Испания</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2">Дата вылета</label>
                    <input type="date" class="w-full px-4 py-3 border border-sky-100 rounded-xl bg-white focus:ring-2 focus:ring-sky-300 focus:border-sky-300 text-slate-700">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2">Ночей</label>
                    <select class="w-full px-4 py-3 border border-sky-100 rounded-xl bg-white focus:ring-2 focus:ring-sky-300 focus:border-sky-300 text-slate-700">
                        <option>Любое количество</option>
                        <option>7 ночей</option>
                        <option>10 ночей</option>
                        <option>14 ночей</option>
                        <option>21 ночь</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-gradient-to-r from-sky-300 via-sky-400 to-sky-500 text-white px-4 py-3 rounded-xl font-semibold animated-button tracking-[0.3em] text-xs uppercase">
                        Найти туры <i class="fas fa-search ml-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Mobile Highlight Stories -->
    <section class="md:hidden bg-white pt-16 pb-6 px-4">
        <div class="space-y-3">
            <h2 class="heading-font font-bold text-slate-800 mobile-section-heading"><?php echo $mobileHighlightTitle; ?></h2>
            <p class="text-slate-500 mobile-section-subtitle"><?php echo $mobileHighlightSubtitle; ?></p>
            </div>
        <div class="flex overflow-x-auto gap-4 snap-x snap-mandatory pt-6 pb-2">
            <?php foreach ($highlightCards as $card): ?>
                <article class="mobile-highlight-card snap-center" style="background-image: url('<?php echo $card['image']; ?>');">
                    <div class="mobile-highlight-content">
                        <span class="text-xs uppercase tracking-[0.2em] text-white/85"><?php echo $card['badge'][$lang]; ?></span>
                        <h3 class="heading-font text-xl font-semibold leading-snug text-white"><?php echo $card['title'][$lang]; ?></h3>
                        <p class="text-sm text-white/85"><?php echo $card['description'][$lang]; ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
                        </div>
    </section>

    <!-- Popular Tours -->
    <section id="tours-section" class="py-16 bg-sky-50">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12 space-y-3">
                <span class="eyebrow-badge inline-flex justify-center">
                    <i class="fas fa-globe-europe"></i>
                    <?php echo $translations[$lang]['popular_destinations']; ?>
                </span>
                <h2 class="heading-font text-3xl font-bold text-slate-800 tracking-wide">Лучшие предложения сезона Travel Hub</h2>
                <p class="text-slate-500 max-w-3xl mx-auto">Автогенерация подборки на основе актуальной базы: рейтинги, теги и цены обновляются автоматически.</p>
                    </div>

            <div id="featured-tours-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"></div>

            <div class="text-center mt-10">
                <a href="/frontend/window/tours.php" class="inline-block bg-gradient-to-r from-sky-300 via-sky-400 to-sky-500 text-white px-6 py-3 rounded-full font-semibold animated-button shadow-lg tracking-[0.3em] text-xs uppercase">
                    Смотреть все туры <i class="fas fa-arrow-right ml-2"></i>
                </a>
                        </div>
                    </div>
    </section>


    <section class="py-20 bg-sky-50">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                <div class="space-y-5">
                    <span class="pill-badge">Почему Travel Hub</span>
                    <h2 class="heading-font text-3xl font-bold text-slate-900">Команда, которой доверяют VIP-гости и корпорации</h2>
                    <p class="text-slate-600">Наши менеджеры работают более 8 лет в премиальном сегменте туризма и сопровождают частных и корпоративных клиентов: от семейных каникул до деловых саммитов.</p>
                    <div class="pastel-card p-6 space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-sky-100 text-sky-500 flex items-center justify-center"><i class="fas fa-user-check"></i></div>
                            <div>
                                <h3 class="heading-font text-lg font-semibold text-slate-800">Менеджер уровня Director</h3>
                                <p class="text-sm text-slate-600">Персональный куратор Travel Hub сопровождает любое направление — от сиднейских яхт до альпийских шале.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="surface-card p-6 space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-sky-100 text-sky-500 flex items-center justify-center"><i class="fas fa-crown"></i></div>
                            <h3 class="heading-font text-lg font-semibold text-slate-800">Приватный lifestyle</h3>
            </div>
                        <p class="text-sm text-slate-600">Подбор вилл с вилла-менеджерами, личные шефы, шопинг-сопровождение и закрытые ивенты.</p>
            </div>
                    <div class="surface-card p-6 space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-sky-100 text-sky-500 flex items-center justify-center"><i class="fas fa-star"></i></div>
                            <h3 class="heading-font text-lg font-semibold text-slate-800">5* гарантии</h3>
        </div>
                        <p class="text-sm text-slate-600">Работаем напрямую с сетями Six Senses, One&Only, The Ritz-Carlton, Mandarin Oriental, Aman и др.</p>
            </div>
                    <div class="surface-card p-6 space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-sky-100 text-sky-500 flex items-center justify-center"><i class="fas fa-plane-departure"></i></div>
                            <h3 class="heading-font text-lg font-semibold text-slate-800">Полный цикл</h3>
                        </div>
                        <p class="text-sm text-slate-600">Премиальные перелёты, сопровождение на маршруте, визовая поддержка и организация событий.</p>
                    </div>
                    <div class="surface-card p-6 space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-sky-100 text-sky-500 flex items-center justify-center"><i class="fas fa-shield-check"></i></div>
                            <h3 class="heading-font text-lg font-semibold text-slate-800">Финансовая безопасность</h3>
                </div>
                        <p class="text-sm text-slate-600">Защищённые платежи, юридическое сопровождение и персональный договор.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12 space-y-3">
                <span class="eyebrow-badge inline-flex justify-center">
                    <i class="fas fa-bolt"></i>
                    Специальные предложения
                </span>
                <h2 class="heading-font text-3xl font-bold text-slate-900 mb-3 tracking-wide">Эксклюзивы Travel Hub с ограниченным доступом</h2>
                <p class="text-slate-600 max-w-3xl mx-auto">Этот блок автоматически обновляется: актуальная цена, доступность и легенда подтягиваются из SQL базы и отображаются в реальном времени.</p>
            </div>
            <div id="spotlight-tours" class="grid grid-cols-1 lg:grid-cols-2 gap-6"></div>
                    </div>
    </section>

    <!-- Testimonials -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="flex flex-col lg:flex-row justify-between items-center gap-10 mb-12">
                <div class="space-y-4 max-w-xl">
                    <span class="pill-badge">Отзывы гостей</span>
                    <h2 class="heading-font text-3xl font-bold text-slate-900">"Travel Hub — это команда, которая предугадывает желания"</h2>
                    <p class="text-slate-600">Ниже — живые отзывы гостей, которые доверили нам организацию путешествий. Мы берём ограниченное число проектов, чтобы сохранить качество сервиса.</p>
                    </div>
                <div class="pastel-card px-6 py-5 max-w-lg">
                    <p class="text-sm text-slate-600">"Наш контакт-менеджер помог организовать family-trip для 12 человек в Дубай. Бронирование виллы, перелёты бизнес-класса и празднование дня рождения на яхте — всё было безупречно". <span class="block mt-3 font-semibold text-slate-800">— Ирина Власова, Москва</span></p>
                </div>
                    </div>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                <?php foreach ($testimonials as $testimonial): ?>
                    <article class="testimonial-card p-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 bg-sky-100 text-sky-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-user"></i>
                </div>
                            <div>
                                <h3 class="heading-font text-lg font-semibold text-slate-800"><?php echo $testimonial['name']; ?></h3>
                                <p class="text-sm text-slate-500"><?php echo $testimonial['location']; ?></p>
                    </div>
                </div>
                        <p class="text-slate-600 leading-relaxed"><?php echo $testimonial['review']; ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="consultation" class="py-20 bg-sky-100/70">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                <div class="space-y-6">
                    <span class="pill-badge">Индивидуальная заявка</span>
                    <h2 class="heading-font text-3xl font-bold text-slate-900">Расскажите о планах — Travel Hub подготовит персональное предложение</h2>
                    <p class="text-slate-600">Оставьте контакты, и мы свяжемся с вами в течение 15 минут. Подготовим 2-3 концепции путешествия с расчётом бюджета.</p>
                    <div class="flex flex-wrap gap-3">
                        <span class="inline-flex items-center px-4 py-2 rounded-full bg-white border border-sky-200 text-sm text-slate-600"><i class="fas fa-clock text-sky-400 mr-2"></i> Быстрый ответ</span>
                        <span class="inline-flex items-center px-4 py-2 rounded-full bg-white border border-sky-200 text-sm text-slate-600"><i class="fas fa-lock text-sky-400 mr-2"></i> Конфиденциальность</span>
            </div>
                </div>
                <form class="pastel-card p-8 space-y-5 bg-white/90">
                        <div>
                        <label class="block text-sm font-medium text-slate-600 mb-2">Имя*</label>
                        <input type="text" class="w-full px-4 py-3 border border-sky-100 rounded-xl focus:ring-2 focus:ring-sky-300 focus:border-sky-300" placeholder="Как к вам обращаться?">
                            </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-2">Телефон*</label>
                        <input type="tel" class="w-full px-4 py-3 border border-sky-100 rounded-xl focus:ring-2 focus:ring-sky-300 focus:border-sky-300" placeholder="+7 (___) ___-__-__">
                        </div>
                        <div>
                        <label class="block text-sm font-medium text-slate-600 mb-2">Сообщение</label>
                        <textarea rows="4" class="w-full px-4 py-3 border border-sky-100 rounded-xl focus:ring-2 focus:ring-sky-300 focus:border-sky-300" placeholder="Расскажите детали поездки"></textarea>
                            </div>
                    <button type="submit" class="w-full bg-gradient-to-r from-sky-300 via-sky-400 to-sky-500 text-white px-6 py-3 rounded-xl font-semibold animated-button tracking-[0.3em] text-xs uppercase">Отправить заявку</button>
                    <p class="text-xs text-slate-500 text-center">Нажимая на кнопку, вы соглашаетесь с политикой конфиденциальности</p>
                </form>
                        </div>
                    </div>
    </section>

    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="flex flex-col lg:flex-row justify-between items-center gap-8 mb-12">
                <div class="space-y-4 max-w-xl">
                    <span class="pill-badge">Блог Travel Hub</span>
                    <h2 class="heading-font text-3xl font-bold text-slate-900">Актуальные идеи, чтобы вдохновиться поездкой</h2>
                    <p class="text-slate-600">Мы собираем инсайды, подборки и чек-листы по странам, которые помогут спланировать путешествие и избежать ошибок.</p>
                            </div>
                <a href="#" class="inline-flex items-center bg-gradient-to-r from-sky-100 to-white border border-sky-200 text-sky-600 px-5 py-3 rounded-full font-semibold hover:border-sky-300 transition">Читать все статьи <i class="fas fa-arrow-right ml-3"></i></a>
                        </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php foreach ($blogPosts as $post): ?>
                    <article class="surface-card overflow-hidden">
                        <img src="<?php echo $post['image']; ?>" alt="<?php echo $post['title']; ?>" class="w-full h-48 object-cover">
                        <div class="p-6 space-y-3">
                            <span class="text-xs uppercase tracking-[0.2em] text-slate-500"><?php echo $post['category']; ?></span>
                            <h3 class="heading-font text-lg font-semibold text-slate-800 leading-snug"><?php echo $post['title']; ?></h3>
                            <p class="text-sm text-slate-600"><?php echo $post['excerpt']; ?></p>
                            <a href="#" class="inline-flex items-center text-sky-500 font-medium">Читать дальше <i class="fas fa-arrow-right ml-2"></i></a>
                    </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Newsletter -->
    <section class="relative py-16 text-white overflow-hidden" style="background-image: url('https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=1920&q=80');">
        <div class="absolute inset-0 bg-gradient-to-r from-cyan-600/90 to-blue-700/80"></div>
        <div class="container mx-auto px-4 text-center relative z-10">
            <h2 class="heading-font text-3xl font-bold mb-4"><?php echo $translations[$lang]['newsletter']; ?></h2>
            <p class="max-w-2xl mx-auto mb-8"><?php echo $translations[$lang]['newsletter_text']; ?></p>
            
            <form action="subscribe.php" method="POST" class="max-w-md mx-auto flex">
                <input type="email" name="email" placeholder="Ваш email" required class="flex-grow px-4 py-3 rounded-l-lg focus:outline-none bg-white/95 text-slate-900">
                <button type="submit" class="bg-gradient-to-r from-cyan-400 via-blue-500 to-purple-500 hover:brightness-110 px-6 py-3 rounded-r-lg font-semibold transition">
                    <?php echo $translations[$lang]['subscribe']; ?> <i class="fas fa-paper-plane ml-2"></i>
                </button>
            </form>
            
            <p class="text-sm opacity-80 mt-3"><?php echo $translations[$lang]['privacy']; ?></p>
        </div>
    </section>

    <!-- Contact Info -->
    <section id="contact" class="relative bg-sky-100/60 py-20">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 items-start">
                <div class="space-y-4">
                    <span class="pill-badge">Travel Hub Office</span>
                    <h2 class="heading-font text-3xl font-bold text-slate-900">Свяжитесь с нами любым удобным способом</h2>
                    <p class="text-slate-600">Вы можете приехать в офис Travel Hub или запросить встречу онлайн. Мы подготовим презентацию и варианты путешествий заранее.</p>
                    <div class="surface-card p-6 space-y-3">
                        <h3 class="heading-font text-lg font-semibold text-slate-800">Контакты</h3>
                        <p class="text-slate-600">Телефон: +7 (495) 123-45-67</p>
                        <p class="text-slate-600">Email: concierge@travelhub.ru</p>
                        <p class="text-slate-600">Адрес: Москва, Краснопресненская наб., 12</p>
                        <div class="flex gap-3 pt-2">
                            <a href="https://t.me/TravelHub" class="w-9 h-9 rounded-full border border-sky-200 flex items-center justify-center text-slate-500 hover:bg-sky-100 hover:text-sky-500 transition" aria-label="Telegram">
                                <i class="fab fa-telegram"></i>
                            </a>
                            <a href="https://wa.me/70000000000" class="w-9 h-9 rounded-full border border-sky-200 flex items-center justify-center text-slate-500 hover:bg-sky-100 hover:text-sky-500 transition" aria-label="WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </div>
            </div>
                    </div>
                <div class="lg:col-span-2 pastel-card overflow-hidden">
                    <iframe class="w-full h-96" src="https://yandex.ru/map-widget/v1/?um=constructor%3A68baa4f498b6c1a43d0b2de2ac4ac2740d37afb0db1f2fd56edb4b54883b81cd&amp;source=constructor" frameborder="0"></iframe>
                    </div>
                </div>
                    </div>
        <div class="floating-contact-bar fixed bottom-6 right-6 hidden md:flex rounded-full shadow-xl">
            <a href="#consultation" class="floating-contact-cta">
                <span class="floating-contact-cta-question">Нужна консультация?</span>
                <span class="floating-contact-cta-action">Оставить заявку</span>
            </a>
        </div>
    </section>

    <footer class="bg-white border-t border-sky-100 py-10">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between gap-8">
                <div class="space-y-3 max-w-sm">
                    <span class="heading-font text-2xl font-bold text-sky-600">Travel Hub</span>
                    <p class="text-slate-600">Мы создаём путешествия класса люкс и обеспечиваем сервис, который остаётся в памяти надолго.</p>
                    <div class="flex gap-3">
                        <a href="#" class="w-9 h-9 rounded-full border border-sky-200 flex items-center justify-center text-slate-500 hover:bg-sky-100 hover:text-sky-500 transition"><i class="fab fa-telegram"></i></a>
                        <a href="#" class="w-9 h-9 rounded-full border border-sky-200 flex items-center justify-center text-slate-500 hover:bg-sky-100 hover:text-sky-500 transition"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="w-9 h-9 rounded-full border border-sky-200 flex items-center justify-center text-slate-500 hover:bg-sky-100 hover:text-sky-500 transition"><i class="fab fa-whatsapp"></i></a>
                            </div>
                            </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-sm text-slate-600">
                            <div>
                        <h3 class="font-semibold text-slate-800 mb-3">Компания</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="hover:text-sky-500">О нас</a></li>
                            <li><a href="#" class="hover:text-sky-500">Команда</a></li>
                            <li><a href="#" class="hover:text-sky-500">Партнёры</a></li>
                            <li><a href="#" class="hover:text-sky-500">Карьера</a></li>
                        </ul>
                            </div>
                            <div>
                        <h3 class="font-semibold text-slate-800 mb-3">Туры</h3>
                    <ul class="space-y-2">
                            <li><a href="#" class="hover:text-sky-500">Премиум</a></li>
                            <li><a href="#" class="hover:text-sky-500">Семейные</a></li>
                            <li><a href="#" class="hover:text-sky-500">Медовый месяц</a></li>
                            <li><a href="#" class="hover:text-sky-500">Корпоративные</a></li>
                    </ul>
                </div>
                <div>
                        <h3 class="font-semibold text-slate-800 mb-3">Консьерж</h3>
                    <ul class="space-y-2">
                            <li><a href="#" class="hover:text-sky-500">Привилегии</a></li>
                            <li><a href="#" class="hover:text-sky-500">Кейсы</a></li>
                            <li><a href="#" class="hover:text-sky-500">Часто задаваемые</a></li>
                    </ul>
                </div>
                <div>
                        <h3 class="font-semibold text-slate-800 mb-3">Контакты</h3>
                    <ul class="space-y-2">
                            <li><a href="#" class="hover:text-sky-500">Заявка</a></li>
                            <li><a href="#" class="hover:text-sky-500">Telegram</a></li>
                            <li><a href="#" class="hover:text-sky-500">WhatsApp</a></li>
                    </ul>
                </div>
            </div>
                    </div>
            <div class="mt-10 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-slate-500">
                <p>© <?php echo date('Y'); ?> Travel Hub. Все права защищены.</p>
                <div class="flex gap-4">
                    <a href="#" class="hover:text-sky-500">Политика конфиденциальности</a>
                    <a href="#" class="hover:text-sky-500">Пользовательское соглашение</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Mobile Bottom Bar -->
    <div class="md:hidden fixed inset-x-0 bottom-0 z-40 px-4 pb-4">
        <div class="floating-contact-bar rounded-3xl flex items-center justify-between gap-3 px-5 py-4">
            <div>
                <p class="heading-font text-sm font-semibold text-slate-800 uppercase tracking-[0.32em]">Готовы спланировать отдых?</p>
                <p class="text-xs text-slate-500">Перезвоним в течение 15 минут</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="tel:+74951234567" class="w-11 h-11 rounded-full bg-gradient-to-r from-sky-300 via-sky-400 to-sky-500 text-white flex items-center justify-center shadow-lg">
                    <i class="fas fa-phone"></i>
                </a>
                <a href="https://t.me/TravelHub" class="w-11 h-11 rounded-full border border-sky-200 text-sky-500 flex items-center justify-center bg-white">
                    <i class="fab fa-telegram"></i>
                </a>
            </div>
        </div>
    </div>

    <script>
        // Mobile menu toggle
        (function() {
            const menuButton = document.getElementById('mobile-menu-button');
            const menu = document.getElementById('mobile-menu');
            if (!menuButton || !menu) return;

            menuButton.addEventListener('click', () => {
                menu.classList.toggle('mobile-menu--open');
            });

            document.querySelectorAll('#mobile-menu .nav-link').forEach(link => {
                link.addEventListener('click', () => {
                    menu.classList.remove('mobile-menu--open');
                });
            });
        })();

        document.addEventListener('DOMContentLoaded', () => {
            const floatingCta = document.querySelector('.floating-contact-bar.fixed');
            if (!floatingCta) return;
            setTimeout(() => {
                floatingCta.classList.add('is-visible');
            }, 60000);
        });

        // User menu toggle
        <?php if(isset($_SESSION['user_id'])): ?>
        document.getElementById('user-menu-button').addEventListener('click', function() {
            const menu = document.getElementById('user-menu');
            menu.classList.toggle('hidden');
        });
        <?php endif; ?>

        // Desktop navigation indicator
        const desktopNav = document.getElementById('desktop-nav');
        if (desktopNav) {
            const indicator = document.getElementById('menu-indicator');
            const navLinks = desktopNav.querySelectorAll('.nav-link--primary');
            let activeLink = Array.from(navLinks).find(link => link.dataset.active === 'true') || navLinks[0];

            const moveIndicator = (link) => {
                if (!indicator || !link) return;
                const offsetLeft = link.offsetLeft;
                const width = link.offsetWidth;
                indicator.style.width = `${width}px`;
                indicator.style.transform = `translateX(${offsetLeft}px)`;
                indicator.style.opacity = '1';
            };

            const setActiveLink = (link) => {
                if (!link) return;
                navLinks.forEach(item => item.removeAttribute('data-active'));
                link.setAttribute('data-active', 'true');
                activeLink = link;
                moveIndicator(link);
            };

            navLinks.forEach(link => {
                link.addEventListener('mouseenter', () => moveIndicator(link));
                link.addEventListener('focus', () => moveIndicator(link));
                link.addEventListener('click', () => setActiveLink(link));
                link.addEventListener('touchstart', () => moveIndicator(link), { passive: true });
            });

            desktopNav.addEventListener('mouseleave', () => moveIndicator(activeLink));
            window.addEventListener('resize', () => moveIndicator(activeLink));
            moveIndicator(activeLink);

            const observedSections = Array.from(navLinks)
                .map(link => {
                    const href = link.getAttribute('href');
                    if (!href || !href.startsWith('#')) return null;
                    const section = document.querySelector(href);
                    return section ? { link, section } : null;
                })
                .filter(Boolean);

            if (observedSections.length) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const matched = observedSections.find(item => item.section === entry.target);
                            if (matched) {
                                setActiveLink(matched.link);
                            }
                        }
                    });
                }, {
                    rootMargin: '-40% 0px -40% 0px',
                    threshold: 0.2
                });

                observedSections.forEach(item => observer.observe(item.section));
            }
        }

        // Language switch
        function switchLanguage() {
            const currentLang = localStorage.getItem('lang') || 'ru';
            const newLang = currentLang === 'ru' ? 'en' : 'ru';
            localStorage.setItem('lang', newLang);
            window.location.href = '?lang=' + newLang;
        }

        // Animate elements on scroll
        const animateOnScroll = function() {
            const elements = document.querySelectorAll('.fade-in');

            elements.forEach(element => {
                const elementPosition = element.getBoundingClientRect().top;
                const screenPosition = window.innerHeight / 1.3;

                if(elementPosition < screenPosition) {
                    element.style.opacity = '1';
                }
            });
        };

        window.addEventListener('scroll', animateOnScroll);
        window.addEventListener('load', animateOnScroll);

        const toursApiBase = 'api/tours.php';
        const featuredGridEl = document.getElementById('featured-tours-grid');
        const spotlightContainerEl = document.getElementById('spotlight-tours');
        const currencyFormatter = new Intl.NumberFormat('ru-RU');

        const tourCache = {
            featured: [],
            spotlight: []
        };

        const createTag = (text) => {
            const span = document.createElement('span');
            span.className = 'px-3 py-1 rounded-full border border-sky-200 bg-sky-50 text-sky-600 text-xs uppercase tracking-[0.18em]';
            span.textContent = text;
            return span;
        };

        const renderEmptyState = (container, message) => {
            if (!container) return;
            container.innerHTML = `<div class="surface-card border border-sky-100 rounded-2xl p-8 text-center text-slate-500">${message}</div>`;
        };

        const formatPrice = (value) => {
            if (value === null || typeof value === 'undefined' || Number.isNaN(value)) {
                return 'По запросу';
            }
            return `${currencyFormatter.format(value)} ₽`;
        };

        const fetchTours = async (params = {}) => {
            const query = new URLSearchParams(params).toString();
            const url = `${toursApiBase}?${query}`;
            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error(`Tours API responded with status ${response.status}`);
            }

            return response.json();
        };

        const renderFeaturedTours = (tours) => {
            if (!featuredGridEl) return;
            if (!Array.isArray(tours) || tours.length === 0) {
                renderEmptyState(featuredGridEl, 'Актуальные туры временно недоступны. Попробуйте обновить страницу позже.');
                return;
            }

            featuredGridEl.innerHTML = '';
            tours.slice(0, 4).forEach((tour) => {
                const card = document.createElement('article');
                card.className = 'surface-card flex flex-col overflow-hidden h-full';

                const badgeHtml = tour.badge
                    ? `<div class="absolute top-3 right-3 bg-gradient-to-r from-sky-300 to-sky-500 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-[0.3em]">${tour.badge}</div>`
                    : '';

                card.innerHTML = `
                    <div class="relative overflow-hidden h-48">
                        <img src="${tour.image || 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1600&q=80'}" alt="${tour.title}" class="w-full h-full object-cover tour-image transition duration-500">
                        ${badgeHtml}
                    </div>
                    <div class="p-6 flex flex-col h-full">
                        <div class="flex items-center justify-between mb-3 text-xs uppercase tracking-[0.2em] text-slate-400">
                            <span>${tour.destination || ''}</span>
                            <span>${tour.duration || ''}</span>
                        </div>
                        <h3 class="heading-font font-semibold text-lg text-slate-900 mb-2">${tour.title}</h3>
                        <p class="text-slate-600 text-sm mb-4">${tour.subtitle || tour.description || ''}</p>
                        <div class="flex flex-wrap gap-2 mb-4">
                            ${(tour.tags || []).map((tag) => `<span class="px-3 py-1 bg-sky-50 border border-sky-100 rounded-full text-xs text-sky-600">${tag}</span>`).join('')}
                        </div>
                        <div class="mt-auto flex justify-between items-center">
                            <div>
                                <p class="text-slate-400 text-xs uppercase tracking-[0.3em]">от</p>
                                <p class="heading-font text-2xl font-bold text-slate-900">${formatPrice(tour.price)}</p>
                            </div>
                            <a href="/frontend/window/tours.php" class="inline-flex items-center text-sky-500 font-semibold">Подробнее <i class="fas fa-arrow-right ml-2"></i></a>
                        </div>
                    </div>
                `;

                featuredGridEl.appendChild(card);
            });
        };


        const renderSpotlightTours = (tours) => {
            if (!spotlightContainerEl) return;
            if (!Array.isArray(tours) || tours.length === 0) {
                renderEmptyState(spotlightContainerEl, 'Специальные предложения появятся после загрузки данных.');
                return;
            }

            spotlightContainerEl.innerHTML = '';
            tours.slice(0, 2).forEach((tour) => {
                const spotlight = tour.spotlight || {};
                const card = document.createElement('article');
                card.className = 'relative rounded-3xl overflow-hidden shadow-xl h-96 border border-sky-100';
                card.style.backgroundImage = `url(${tour.image || 'https://images.unsplash.com/photo-1519046904884-53103b34b206?auto=format&fit=crop&w=1600&q=80'})`;
                card.style.backgroundSize = 'cover';
                card.style.backgroundPosition = 'center';

                card.innerHTML = `
                    <div class="absolute inset-0 bg-gradient-to-t from-black/55 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-8 text-white">
                        ${tour.badge ? `<span class="bg-white/95 text-slate-900 text-xs font-bold px-4 py-1 rounded-full mb-3 inline-block uppercase tracking-[0.35em]">${tour.badge}</span>` : ''}
                        <h3 class="heading-font text-2xl font-semibold mb-2">${spotlight.headline || tour.title}</h3>
                        <p class="mb-5 text-white/85 max-w-xl">${spotlight.text || tour.description || ''}</p>
                        <div class="flex items-center gap-3">
                            <p class="text-2xl font-bold text-white">${spotlight.priceLabel || formatPrice(tour.price)}</p>
                            ${spotlight.priceOld ? `<p class="text-sm line-through text-white/70">${currencyFormatter.format(spotlight.priceOld)} ₽</p>` : ''}
                        </div>
                        <a href="#consultation" class="absolute top-4 right-4 bg-white text-slate-800 w-12 h-12 rounded-full flex items-center justify-center hover:bg-sky-50 transition shadow-lg">
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                `;

                spotlightContainerEl.appendChild(card);
            });
        };

        const hydrateHomeTours = async () => {
            try {
                const [featuredResponse, spotlightResponse] = await Promise.all([
                    fetchTours({ context: 'featured', per_page: 8 }),
                    fetchTours({ context: 'spotlight', per_page: 4 })
                ]);

                tourCache.featured = featuredResponse.tours || [];
                tourCache.spotlight = spotlightResponse.tours || [];

                renderFeaturedTours(tourCache.featured);
                renderSpotlightTours(tourCache.spotlight);
            } catch (error) {
                console.error('Не удалось загрузить туры:', error);
                renderFeaturedTours([]);
                renderSpotlightTours([]);
            }
        };

        document.addEventListener('DOMContentLoaded', hydrateHomeTours);

        // Переливающийся фон-коллаж
        document.addEventListener('DOMContentLoaded', function() {
            const backgroundImages = [
                'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?auto=format&fit=crop&w=1920&q=80', // Горный пейзаж
                'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?auto=format&fit=crop&w=1920&q=80', // Лес с лучами света
                'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1920&q=80', // Пляж на закате
                'https://images.unsplash.com/photo-1513326738677-b964603b136d?auto=format&fit=crop&w=1920&q=80', // Москва, Кремль
                'https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=1920&q=80', // Природа
                'https://images.unsplash.com/photo-1519046904884-53103b34b206?auto=format&fit=crop&w=1920&q=80', // Пляж
                'https://images.unsplash.com/photo-1501594907352-04cda38ebc29?auto=format&fit=crop&w=1920&q=80', // Горы
                'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?auto=format&fit=crop&w=1920&q=80', // Пейзаж
                'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?auto=format&fit=crop&w=1920&q=80', // Лес
                'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1920&q=80', // Океан
                'https://images.unsplash.com/photo-1513326738677-b964603b136d?auto=format&fit=crop&w=1920&q=80', // Город
                'https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=1920&q=80'  // Природа
            ];

            const collage = document.getElementById('backgroundCollage');
            if (!collage) {
                console.error('Background collage element not found!');
                return;
            }

            // Создаем сетку изображений (4x3 = 12 изображений)
            const totalCells = 12;
            const cells = [];

            // Создаем ячейки коллажа
            for (let i = 0; i < totalCells; i++) {
                const cell = document.createElement('div');
                cell.className = 'background-cell';
                cell.style.position = 'relative';
                cell.style.overflow = 'hidden';
                
                const img = document.createElement('img');
                img.className = 'background-image';
                img.src = backgroundImages[i % backgroundImages.length];
                img.alt = '';
                
                // Предзагрузка изображений
                img.onload = function() {
                    this.classList.add('active');
                };
                
                cell.appendChild(img);
                collage.appendChild(cell);
                cells.push({ cell, img, currentIndex: i % backgroundImages.length });
            }

            // Функция для смены изображений
            function rotateImages() {
                cells.forEach((item) => {
                    // Случайная задержка для каждого элемента (1-2 секунды)
                    const delay = Math.random() * 1000 + 1000;
                    
                    setTimeout(() => {
                        // Убираем активный класс
                        item.img.classList.remove('active');
                        
                        // Через небольшую паузу меняем изображение
                        setTimeout(() => {
                            // Выбираем следующее изображение из массива
                            item.currentIndex = (item.currentIndex + 1) % backgroundImages.length;
                            item.img.src = backgroundImages[item.currentIndex];
                            
                            // Добавляем активный класс для плавного появления
                            setTimeout(() => {
                                item.img.classList.add('active');
                            }, 50);
                        }, 300);
                    }, delay);
                });
            }

            // Инициализация: показываем первые изображения после загрузки
            setTimeout(() => {
                cells.forEach(item => {
                    if (item.img.complete) {
                        item.img.classList.add('active');
                    }
                });
            }, 500);

            // Запускаем ротацию каждые 2 секунды
            setInterval(rotateImages, 2000);
        });
    </script>
</body>
</html>