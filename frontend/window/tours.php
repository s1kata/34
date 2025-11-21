<?php
require_once __DIR__ . '/../../backend/config/config.php';
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Туры - Travel Hub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-body: #f4f9ff;
            --bg-surface: #ffffff;
            --bg-muted: #eaf3ff;
            --accent-primary: #3ba3ff;
            --accent-secondary: #7bc4ff;
            --text-primary: #1f2a44;
            --text-secondary: #4f5f78;
            --text-muted: rgba(79, 95, 120, 0.7);
            --border-soft: rgba(59, 163, 255, 0.18);
            --shadow-soft: 0 18px 42px rgba(59, 163, 255, 0.18);
        }
        body {
            font-family: 'Open Sans', sans-serif;
            background: linear-gradient(180deg, #f8fbff 0%, #eff5ff 45%, #fdfdff 100%);
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
            z-index: 0;
            overflow: hidden;
            pointer-events: none;
        }
        @media (min-width: 768px) {
            .animated-background {
                display: block !important;
            }
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
            background: linear-gradient(180deg, rgba(248, 251, 255, 0.75) 0%, rgba(238, 245, 255, 0.65) 45%, rgba(253, 253, 255, 0.75) 100%);
            z-index: 1;
            pointer-events: none;
        }
        /* Убеждаемся, что контент поверх фона */
        body > *:not(.animated-background) {
            position: relative;
            z-index: 1;
        }
        .heading-font {
            font-family: 'Montserrat', sans-serif;
        }
        .nav-link {
            color: var(--text-secondary);
            transition: color 0.3s ease;
        }
        .nav-link:hover {
            color: var(--accent-primary);
        }
        .tour-card {
            background: var(--bg-surface);
            border-radius: 20px;
            border: 1px solid var(--border-soft);
            box-shadow: var(--shadow-soft);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .tour-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 26px 54px rgba(59, 163, 255, 0.22);
        }
        .tour-card:hover .tour-image {
            transform: scale(1.05);
        }
        .search-box {
            background: var(--bg-surface);
            border-radius: 24px;
            border: 1px solid var(--border-soft);
            box-shadow: var(--shadow-soft);
        }
        .animated-button {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .animated-button:hover {
            transform: translateY(-4px);
            box-shadow: 0 24px 45px rgba(59, 163, 255, 0.22);
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
        .testimonial-card {
            background: var(--bg-surface) !important;
            border: 1px solid var(--border-soft) !important;
            box-shadow: 0 18px 36px rgba(59, 163, 255, 0.16) !important;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .testimonial-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 28px 56px rgba(59, 163, 255, 0.22) !important;
        }
        .loading {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 220px;
        }
        .spinner {
            border: 4px solid rgba(59, 163, 255, 0.15);
            border-top: 4px solid var(--accent-primary);
            border-radius: 50%;
            width: 44px;
            height: 44px;
            animation: spin 1s linear infinite;
        }
        .filter-btn {
            border-radius: 999px;
            transition: all 0.3s ease;
        }
        .filter-btn.active {
            background: linear-gradient(90deg, var(--accent-secondary), var(--accent-primary));
            color: #ffffff;
            box-shadow: 0 18px 40px rgba(59, 163, 255, 0.24);
        }
        .filter-btn:not(.active) {
            background: var(--bg-muted);
            color: var(--text-secondary);
        }
        .filter-btn:not(.active):hover {
            background: #dbeafe;
            color: var(--accent-primary);
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="text-slate-900" style="background: linear-gradient(180deg, #f8fbff 0%, #eff5ff 45%, #fdfdff 100%); min-height: 100vh;">
    <!-- Переливающийся фон-коллаж -->
    <div class="animated-background">
        <div class="background-collage" id="backgroundCollage"></div>
        <div class="background-overlay"></div>
    </div>
    <!-- Header with Navigation -->
    <header class="bg-white/95 backdrop-blur-lg border-b border-sky-100 sticky top-0 z-50 shadow-sm">
        <div class="mx-auto w-full max-w-[95vw] px-4 md:px-8 lg:px-16 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <a href="/index.php" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-sky-300 via-sky-400 to-sky-500 flex items-center justify-center shadow-lg shadow-sky-200/60">
                        <i class="fas fa-plane text-white text-base"></i>
                    </div>
                    <span class="heading-font text-2xl font-bold text-sky-600 tracking-wide">Travel Hub</span>
                </a>
            </div>

            <?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                <a href="/backend/admin/admin.php" class="hidden lg:block bg-gradient-to-r from-rose-300 via-rose-400 to-rose-500 text-white px-5 py-2 rounded-full font-medium shadow-md hover:shadow-lg transition">Админ панель</a>
            <?php else: ?>
                <div class="hidden lg:flex items-center gap-4">
                    <div class="bg-gradient-to-r from-sky-50 to-blue-50 px-6 py-2.5 rounded-full text-xs uppercase tracking-[0.32em] text-sky-600 font-semibold border border-sky-200 shadow-sm">
                        <a href="/frontend/window/tours.php?filter=hot" class="text-slate-600 hover:text-sky-600 mr-4 transition font-medium">🔥 Горящие</a>
                        <a href="/frontend/window/tours.php?filter=turkey" class="text-slate-600 hover:text-sky-600 mr-4 transition font-medium">🇹🇷 Турция</a>
                        <a href="/frontend/window/tours.php?filter=uae" class="text-slate-600 hover:text-sky-600 mr-4 transition font-medium">🇦🇪 ОАЭ</a>
                        <a href="/frontend/window/tours.php?filter=egypt" class="text-slate-600 hover:text-sky-600 transition font-medium">🇪🇬 Египет</a>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="tel:+74951234567" class="w-10 h-10 rounded-full bg-sky-100 text-sky-600 flex items-center justify-center hover:bg-sky-200 transition">
                            <i class="fas fa-phone text-sm"></i>
                        </a>
                        <a href="https://t.me/TravelHub" class="w-10 h-10 rounded-full bg-sky-100 text-sky-600 flex items-center justify-center hover:bg-sky-200 transition">
                            <i class="fab fa-telegram"></i>
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <nav class="hidden md:flex items-center space-x-8">
                <a href="/index.php" class="text-slate-700 font-medium hover:text-sky-500 transition">Главная</a>
                <a href="/frontend/window/tours.php" class="text-slate-700 font-medium hover:text-sky-500 transition">Туры</a>
                <a href="/frontend/window/services.php" class="text-slate-700 font-medium hover:text-sky-500 transition">Услуги</a>
                <a href="/frontend/window/about.php" class="text-slate-700 font-medium hover:text-sky-500 transition">О нас</a>
                <a href="/frontend/window/contacts.php" class="text-slate-700 font-medium hover:text-sky-500 transition">Контакты</a>
            </nav>

            <div class="flex items-center space-x-4">
                <div class="hidden md:flex items-center gap-2">
                    <a href="tel:+74951234567" class="w-10 h-10 rounded-full bg-sky-100 text-sky-600 flex items-center justify-center hover:bg-sky-200 transition">
                        <i class="fas fa-phone text-sm"></i>
                    </a>
                    <a href="https://t.me/TravelHub" class="w-10 h-10 rounded-full bg-sky-100 text-sky-600 flex items-center justify-center hover:bg-sky-200 transition">
                        <i class="fab fa-telegram"></i>
                    </a>
                </div>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <div class="relative z-50">
                        <button id="user-menu-button" class="hidden md:flex items-center bg-gradient-to-r from-sky-300 via-sky-400 to-sky-500 text-white px-5 py-2 rounded-full font-medium shadow-md hover:shadow-lg transition">
                            <i class="fas fa-user mr-2"></i><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Пользователь'); ?>
                            <i class="fas fa-chevron-down ml-2"></i>
                        </button>
                        <div id="user-menu" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-2xl border border-sky-100 z-[60] min-w-[200px]">
                            <a href="/frontend/window/profile.php" class="block px-4 py-3 text-sm text-slate-700 hover:bg-sky-50 transition rounded-t-xl">
                                <i class="fas fa-user-circle mr-2 text-sky-500"></i>Профиль
                            </a>
                            <a href="/frontend/window/dashboard.php" class="block px-4 py-3 text-sm text-slate-700 hover:bg-sky-50 transition">
                                <i class="fas fa-tachometer-alt mr-2 text-sky-500"></i>Личный кабинет
                            </a>
                            <?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                            <a href="/backend/admin/admin.php" class="block px-4 py-3 text-sm text-slate-700 hover:bg-sky-50 transition">
                                <i class="fas fa-cog mr-2 text-rose-500"></i>Админ панель
                            </a>
                            <?php endif; ?>
                            <div class="border-t border-sky-100 my-1"></div>
                            <a href="/backend/scripts/logout.php" class="block px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition rounded-b-xl">
                                <i class="fas fa-sign-out-alt mr-2"></i>Выход
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="/frontend/window/login.html" class="hidden md:block bg-gradient-to-r from-sky-300 via-sky-400 to-sky-500 text-white px-5 py-2 rounded-full font-medium shadow-md">Войти</a>
                <?php endif; ?>
                <button id="mobile-menu-button" class="md:hidden text-slate-500">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>

        <div id="mobile-menu" class="hidden md:hidden bg-white/95 border-t border-sky-100 py-4 px-4">
            <div class="flex flex-col space-y-3">
                <a href="/index.php" class="text-slate-700 font-medium hover:text-sky-500 transition">Главная</a>
                <a href="/frontend/window/tours.php" class="text-slate-700 font-medium hover:text-sky-500 transition">Туры</a>
                <a href="/frontend/window/services.php" class="text-slate-700 font-medium hover:text-sky-500 transition">Услуги</a>
                <a href="/frontend/window/about.php" class="text-slate-700 font-medium hover:text-sky-500 transition">О нас</a>
                <a href="/frontend/window/contacts.php" class="text-slate-700 font-medium hover:text-sky-500 transition">Контакты</a>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="/frontend/window/profile.php" class="bg-gradient-to-r from-sky-300 via-sky-400 to-sky-500 text-white px-5 py-2 rounded-full font-medium text-center">Профиль</a>
                    <a href="/frontend/window/dashboard.php" class="bg-gradient-to-r from-sky-300 via-sky-400 to-sky-500 text-white px-5 py-2 rounded-full font-medium text-center">Личный кабинет</a>
                    <?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                    <a href="/backend/admin/admin.php" class="bg-gradient-to-r from-sky-300 via-sky-400 to-sky-500 text-white px-5 py-2 rounded-full font-medium text-center">Админ панель</a>
                    <?php endif; ?>
                    <a href="/backend/scripts/logout.php" class="bg-gray-500 text-white px-5 py-2 rounded-full font-medium text-center">Выход</a>
                <?php else: ?>
                    <a href="/frontend/window/login.html" class="bg-gradient-to-r from-sky-300 via-sky-400 to-sky-500 text-white px-5 py-2 rounded-full font-medium text-center">Войти</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-sky-100 via-white to-sky-50 text-slate-800 py-20 md:py-28 overflow-hidden">
        <div class="container mx-auto px-6 relative z-10">
            <div class="max-w-6xl mx-auto text-center space-y-8">
                <div class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-gradient-to-r from-sky-50 to-blue-50 border border-sky-200 text-xs uppercase tracking-[0.32em] text-sky-600 font-semibold shadow-sm">
                    <i class="fas fa-compass text-sky-500"></i>
                    Travel Hub — Каталог туров
                </div>
                <h1 class="heading-font text-5xl md:text-6xl lg:text-7xl font-bold text-slate-900 leading-tight">
                    Откройте мир <span class="bg-gradient-to-r from-sky-500 to-blue-600 bg-clip-text text-transparent">мечты</span>
                </h1>
                <p class="text-xl md:text-2xl text-slate-600 max-w-3xl mx-auto leading-relaxed">
                    Более <strong class="text-sky-600">1500 эксклюзивных туров</strong> по всему миру. Персональный подход, прозрачные цены и команда, которая сопровождает вас 24/7.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4">
                    <div class="surface-card p-6 text-center transform hover:scale-105 transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-br from-sky-400 to-blue-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                            <i class="fas fa-globe text-2xl text-white"></i>
                        </div>
                        <h3 class="heading-font font-bold text-xl text-slate-900 mb-2">1500+ туров</h3>
                        <p class="text-sm text-slate-600">по всему миру</p>
                    </div>
                    <div class="surface-card p-6 text-center transform hover:scale-105 transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-br from-sky-400 to-blue-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                            <i class="fas fa-star text-2xl text-white"></i>
                        </div>
                        <h3 class="heading-font font-bold text-xl text-slate-900 mb-2">Лучшие тарифы</h3>
                        <p class="text-sm text-slate-600">благодаря прямым контрактам</p>
                    </div>
                    <div class="surface-card p-6 text-center transform hover:scale-105 transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-br from-sky-400 to-blue-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                            <i class="fas fa-shield-alt text-2xl text-white"></i>
                        </div>
                        <h3 class="heading-font font-bold text-xl text-slate-900 mb-2">Безопасность</h3>
                        <p class="text-sm text-slate-600">страховка и поддержка 24/7</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Search Box -->
    <div class="container mx-auto px-4 -mt-16 relative z-20">
        <div class="bg-white rounded-3xl shadow-2xl p-8 search-box border border-sky-100">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-sky-400 to-blue-500 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-search text-white text-lg"></i>
                </div>
                <h2 class="heading-font text-2xl font-bold text-slate-900">Поиск туров</h2>
            </div>
            <form id="search-form" class="grid grid-cols-1 md:grid-cols-5 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fas fa-map-marker-alt text-sky-500 mr-2"></i>Направление
                    </label>
                    <select id="destination" class="w-full px-5 py-3 border-2 border-sky-100 rounded-xl focus:ring-2 focus:ring-sky-400 focus:border-sky-400 bg-white text-slate-700 font-medium transition-all hover:border-sky-300">
                        <option value="">Все направления</option>
                        <option value="hot">🔥 Горящие туры</option>
                        <option value="turkey">🇹🇷 Турция</option>
                        <option value="uae">🇦🇪 ОАЭ</option>
                        <option value="egypt">🇪🇬 Египет</option>
                        <option value="thailand">🇹🇭 Таиланд</option>
                        <option value="maldives">🇲🇻 Мальдивы</option>
                        <option value="spain">🇪🇸 Испания</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fas fa-calendar-alt text-sky-500 mr-2"></i>Дата вылета
                    </label>
                    <input id="departure-date" type="date" class="w-full px-5 py-3 border-2 border-sky-100 rounded-xl focus:ring-2 focus:ring-sky-400 focus:border-sky-400 bg-white text-slate-700 font-medium transition-all hover:border-sky-300">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fas fa-moon text-sky-500 mr-2"></i>Ночей
                    </label>
                    <select id="nights" class="w-full px-5 py-3 border-2 border-sky-100 rounded-xl focus:ring-2 focus:ring-sky-400 focus:border-sky-400 bg-white text-slate-700 font-medium transition-all hover:border-sky-300">
                        <option value="">Любое количество</option>
                        <option value="7">7 ночей</option>
                        <option value="10">10 ночей</option>
                        <option value="14">14 ночей</option>
                        <option value="21">21 ночь</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fas fa-ruble-sign text-sky-500 mr-2"></i>Цена до
                    </label>
                    <input id="max-price" type="number" placeholder="Максимальная цена" class="w-full px-5 py-3 border-2 border-sky-100 rounded-xl focus:ring-2 focus:ring-sky-400 focus:border-sky-400 bg-white text-slate-700 font-medium transition-all hover:border-sky-300">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-gradient-to-r from-sky-400 via-sky-500 to-blue-500 text-white px-6 py-3.5 rounded-xl font-bold animated-button shadow-lg hover:shadow-xl transition-all text-base">
                        <i class="fas fa-search mr-2"></i>Найти туры
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Special Offers -->
    <section id="special-offers" class="py-16 bg-sky-50">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12 space-y-3">
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-sky-200 text-xs uppercase tracking-[0.28em] text-sky-500">Limited Deals</span>
                <h2 class="heading-font text-3xl font-bold text-slate-900">Эксклюзивные предложения</h2>
                <p class="text-slate-600 max-w-2xl mx-auto">Ограниченные по времени акции и специальные цены на популярные направления. Блок обновляется автоматически.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Big Offer 1 -->
                <div class="relative rounded-3xl overflow-hidden shadow-xl border border-sky-100 h-80">
                    <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1470&q=80"
                         alt="Special Offer" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/55 via-black/10 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                        <span class="bg-white/95 text-slate-900 text-sm font-semibold px-4 py-1 rounded-full mb-3 inline-block uppercase tracking-[0.28em]">Раннее бронирование</span>
                        <h3 class="heading-font text-2xl font-bold mb-2">Турция. Лучшие отели 5*</h3>
                        <p class="mb-4 text-white/85">Забронируйте тур до конца месяца и получите до 35% выгоды + трансфер бизнес-класса в подарок.</p>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.28em] text-white/75">от</p>
                                <p class="heading-font text-3xl font-bold">42 500 ₽</p>
                                <p class="text-sm line-through text-white/70">65 000 ₽</p>
                            </div>
                            <a href="#consultation" class="bg-white text-slate-800 px-6 py-3 rounded-full font-semibold hover:bg-sky-50 transition">
                                Забронировать
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Big Offer 2 -->
                <div class="relative rounded-3xl overflow-hidden shadow-xl border border-sky-100 h-80">
                    <img src="https://images.unsplash.com/photo-1519046904884-53103b34b206?auto=format&fit=crop&w=1470&q=80"
                         alt="Special Offer" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/55 via-black/10 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                        <span class="bg-white/95 text-slate-900 text-sm font-semibold px-4 py-1 rounded-full mb-3 inline-block uppercase tracking-[0.28em]">Горящий тур</span>
                        <h3 class="heading-font text-2xl font-bold mb-2">Мальдивы. Вилла над водой</h3>
                        <p class="mb-4 text-white/85">Один из последних номеров в отеле класса люкс. Приватный батлер и трансфер на гидросамолёте включены.</p>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.28em] text-white/75">от</p>
                                <p class="heading-font text-3xl font-bold">165 000 ₽</p>
                                <p class="text-sm line-through text-white/70">210 000 ₽</p>
                            </div>
                            <a href="#consultation" class="bg-white text-slate-800 px-6 py-3 rounded-full font-semibold hover:bg-sky-50 transition">
                                Забронировать
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section id="why-us" class="py-16 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12 space-y-3">
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-sky-50 border border-sky-200 text-xs uppercase tracking-[0.28em] text-sky-500">Почему Travel Hub</span>
                <h2 class="heading-font text-3xl font-bold text-slate-900">Премиум-сервис в каждом путешествии</h2>
                <p class="text-slate-600 max-w-2xl mx-auto">Мы делаем ваши поездки комфортными, безопасными и незабываемыми. Каждый проект ведёт персональный менеджер Travel Hub.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="surface-card p-6 text-center">
                    <div class="w-16 h-16 bg-sky-100 rounded-full flex items-center justify-center mx-auto mb-4 text-sky-500 text-2xl">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="heading-font font-semibold text-lg text-slate-800 mb-2">Полная безопасность</h3>
                    <p class="text-slate-600 text-sm">Туры застрахованы, отели проверены, вы защищены на каждом этапе.</p>
                </div>

                <div class="surface-card p-6 text-center">
                    <div class="w-16 h-16 bg-sky-100 rounded-full flex items-center justify-center mx-auto mb-4 text-sky-500 text-2xl">
                        <i class="fas fa-percent"></i>
                    </div>
                    <h3 class="heading-font font-semibold text-lg text-slate-800 mb-2">Лучшие условия</h3>
                    <p class="text-slate-600 text-sm">Прямые контракты с сетями отелей и авиаперевозчиками дают эксклюзивные тарифы.</p>
                </div>

                <div class="surface-card p-6 text-center">
                    <div class="w-16 h-16 bg-sky-100 rounded-full flex items-center justify-center mx-auto mb-4 text-sky-500 text-2xl">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3 class="heading-font font-semibold text-lg text-slate-800 mb-2">24/7 поддержка</h3>
                    <p class="text-slate-600 text-sm">Менеджер Travel Hub всегда на связи и моментально решает вопросы.</p>
                </div>

                <div class="surface-card p-6 text-center">
                    <div class="w-16 h-16 bg-sky-100 rounded-full flex items-center justify-center mx-auto mb-4 text-sky-500 text-2xl">
                        <i class="fas fa-passport"></i>
                    </div>
                    <h3 class="heading-font font-semibold text-lg text-slate-800 mb-2">Документы и визы</h3>
                    <p class="text-slate-600 text-sm">Подготовим полный пакет документов и сопроводим процесс оформления.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Tours Section -->
    <section id="tours-section" class="py-16 bg-sky-50">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12 space-y-3">
                <span class="eyebrow-badge inline-flex justify-center">
                    <i class="fas fa-globe-europe"></i>
                    Популярные направления
                </span>
                <h2 class="heading-font text-3xl font-bold text-slate-800 tracking-wide">Лучшие предложения сезона Travel Hub</h2>
                <p class="text-slate-500 max-w-3xl mx-auto">Автогенерация подборки на основе актуальной базы: рейтинги, теги и цены обновляются автоматически.</p>
            </div>

            <!-- Tour Tabs -->
            <div class="flex justify-center mb-8">
                <div class="bg-white rounded-full shadow-md p-1.5 border border-sky-100 overflow-x-auto">
                    <div class="flex space-x-1 min-w-max">
                        <button class="filter-btn tab-button px-4 md:px-6 py-3 text-sm md:text-base font-medium active" data-filter="all">Все туры</button>
                        <button class="filter-btn tab-button px-4 md:px-6 py-3 text-sm md:text-base font-medium" data-filter="hot">Горящие</button>
                        <button class="filter-btn tab-button px-4 md:px-6 py-3 text-sm md:text-base font-medium" data-filter="turkey">Турция</button>
                        <button class="filter-btn tab-button px-4 md:px-6 py-3 text-sm md:text-base font-medium" data-filter="uae">ОАЭ</button>
                        <button class="filter-btn tab-button px-4 md:px-6 py-3 text-sm md:text-base font-medium" data-filter="egypt">Египет</button>
                        <button class="filter-btn tab-button px-4 md:px-6 py-3 text-sm md:text-base font-medium" data-filter="thailand">Таиланд</button>
                    </div>
                </div>
            </div>

            <!-- Loading -->
            <div id="loading" class="loading">
                <div class="spinner"></div>
            </div>

            <!-- Tours Grid -->
            <div id="tours-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <!-- Tours will be loaded here -->
            </div>

            <!-- Load More -->
            <div class="text-center mt-10">
                <button id="load-more" class="bg-gradient-to-r from-sky-300 via-sky-400 to-sky-500 text-white px-6 py-3 rounded-full font-semibold animated-button hidden">
                    Загрузить еще <i class="fas fa-arrow-down ml-2"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-16 bg-sky-50">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12 space-y-3">
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-sky-200 text-xs uppercase tracking-[0.28em] text-sky-500">Отзывы гостей</span>
                <h2 class="heading-font text-3xl font-bold text-slate-900">Опыт путешественников Travel Hub</h2>
                <p class="text-slate-600 max-w-2xl mx-auto">Более 10 000 клиентов доверили нам организацию путешествий. Делимся несколькими историями.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Testimonial 1 -->
                <div class="testimonial-card p-6 rounded-2xl">
                    <div class="flex items-center mb-4">
                        <img src="https://randomuser.me/api/portraits/women/43.jpg" alt="User" class="w-12 h-12 rounded-full object-cover mr-4">
                        <div>
                            <h4 class="heading-font font-semibold text-slate-900">Анна Смирнова</h4>
                            <div class="flex text-yellow-400 text-sm">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-slate-600 mb-4">«Отдыхали в Турции через Travel Hub. Всё было организовано на высшем уровне! Особенно понравился подобранный отель с отличным сервисом. Обязательно обратимся ещё.»</p>
                    <div class="text-sm text-slate-500">Турция, июль 2023</div>
                </div>

                <!-- Testimonial 2 -->
                <div class="testimonial-card p-6 rounded-2xl">
                    <div class="flex items-center mb-4">
                        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="User" class="w-12 h-12 rounded-full object-cover mr-4">
                        <div>
                            <h4 class="heading-font font-semibold text-slate-900">Иван Петров</h4>
                            <div class="flex text-yellow-400 text-sm">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-slate-600 mb-4">«Брали экскурсионный тур по Италии. Гид был потрясающий, знал все тонкости и интересные места. Отель в центре Рима — мечта! Спасибо за прекрасный отдых.»</p>
                    <div class="text-sm text-slate-500">Италия, май 2023</div>
                </div>

                <!-- Testimonial 3 -->
                <div class="testimonial-card p-6 rounded-2xl">
                    <div class="flex items-center mb-4">
                        <img src="https://randomuser.me/api/portraits/women/65.jpg" alt="User" class="w-12 h-12 rounded-full object-cover mr-4">
                        <div>
                            <h4 class="heading-font font-semibold text-slate-900">Елена Козлова</h4>
                            <div class="flex text-yellow-400 text-sm">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-slate-600 mb-4">«Поездка на Мальдивы была сказочной! Travel Hub подобрали идеальный отель с виллой над водой. Отдельное спасибо за помощь с трансферами и организацию активностей.»</p>
                    <div class="text-sm text-slate-500">Мальдивы, март 2023</div>
                </div>
            </div>

            <div class="text-center mt-10">
                <a href="#" class="inline-block bg-gradient-to-r from-sky-300 via-sky-400 to-sky-500 text-white px-6 py-3 rounded-full font-semibold animated-button tracking-[0.28em] text-xs uppercase">
                    Читать все отзывы <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Newsletter -->
    <section id="newsletter" class="py-16 bg-white">
        <div class="container mx-auto px-6 text-center">
            <div class="max-w-3xl mx-auto space-y-4">
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-sky-50 border border-sky-200 text-xs uppercase tracking-[0.28em] text-sky-500">Будьте в курсе</span>
                <h2 class="heading-font text-3xl font-bold text-slate-900">Подпишитесь на подборку Travel Hub</h2>
                <p class="text-slate-600">Получайте только полезные письма: горящие туры, закрытые тарифы и советы по путешествиям раз в неделю.</p>
            </div>

        <form action="/backend/scripts/subscribe.php" method="POST" class="max-w-2xl mx-auto mt-8 flex flex-col sm:flex-row gap-3 sm:gap-0">
                <input type="email" name="email" placeholder="Ваш email" required class="flex-grow px-4 py-3 rounded-full border border-sky-100 focus:ring-2 focus:ring-sky-300 focus:border-sky-300 text-slate-700">
                <button type="submit" class="bg-gradient-to-r from-sky-300 via-sky-400 to-sky-500 text-white px-6 py-3 rounded-full font-semibold animated-button tracking-[0.28em] text-xs uppercase">
                    Подписаться <i class="fas fa-paper-plane ml-2"></i>
                </button>
            </form>

            <p class="text-sm text-slate-500 mt-4">Обещаем конфиденциальность и возможность отписаться в любой момент.</p>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-sky-100 pt-16 pb-8">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                <div>
                    <a href="/index.php" class="flex items-center mb-6">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-sky-300 via-sky-400 to-sky-500 flex items-center justify-center mr-3">
                            <i class="fas fa-plane text-white text-lg"></i>
                        </div>
                        <span class="heading-font text-2xl font-bold text-sky-600">Travel Hub</span>
                    </a>
                    <p class="text-slate-600 mb-4">Мы создаём незабываемые путешествия с 2010 года. Более 50 000 довольных клиентов.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-slate-500 hover:text-sky-500 transition">
                            <i class="fab fa-vk"></i>
                        </a>
                        <a href="#" class="text-slate-500 hover:text-sky-500 transition">
                            <i class="fab fa-telegram"></i>
                        </a>
                        <a href="#" class="text-slate-500 hover:text-sky-500 transition">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-slate-500 hover:text-sky-500 transition">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>

                <div>
                    <h4 class="heading-font font-semibold text-lg text-slate-800 mb-4">Туры</h4>
                    <ul class="space-y-2 text-slate-600">
                        <li><a href="#" class="hover:text-sky-500 transition">Горящие туры</a></li>
                        <li><a href="#" class="hover:text-sky-500 transition">Популярные направления</a></li>
                        <li><a href="#" class="hover:text-sky-500 transition">Экскурсионные туры</a></li>
                        <li><a href="#" class="hover:text-sky-500 transition">Пляжный отдых</a></li>
                        <li><a href="#" class="hover:text-sky-500 transition">Горнолыжные туры</a></li>
                        <li><a href="#" class="hover:text-sky-500 transition">Круизы</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="heading-font font-semibold text-lg text-slate-800 mb-4">Услуги</h4>
                    <ul class="space-y-2 text-slate-600">
                        <li><a href="#" class="hover:text-sky-500 transition">Авиабилеты</a></li>
                        <li><a href="#" class="hover:text-sky-500 transition">Отели</a></li>
                        <li><a href="#" class="hover:text-sky-500 transition">Трансферы</a></li>
                        <li><a href="#" class="hover:text-sky-500 transition">Страхование</a></li>
                        <li><a href="#" class="hover:text-sky-500 transition">Визовая поддержка</a></li>
                        <li><a href="#" class="hover:text-sky-500 transition">Экскурсии</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="heading-font font-semibold text-lg text-slate-800 mb-4">Информация</h4>
                    <ul class="space-y-2 text-slate-600">
                        <li><a href="#" class="hover:text-sky-500 transition">О компании</a></li>
                        <li><a href="#" class="hover:text-sky-500 transition">Отзывы</a></li>
                        <li><a href="#" class="hover:text-sky-500 transition">Блог</a></li>
                        <li><a href="#" class="hover:text-sky-500 transition">Акции</a></li>
                        <li><a href="#" class="hover:text-sky-500 transition">Вакансии</a></li>
                        <li><a href="#" class="hover:text-sky-500 transition">Контакты</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-sky-100 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-slate-500 text-sm">© <?php echo date('Y'); ?> Travel Hub. Все права защищены.</p>
                    <div class="flex space-x-6 text-sm text-slate-500">
                        <a href="#" class="hover:text-sky-500 transition">Политика конфиденциальности</a>
                        <a href="#" class="hover:text-sky-500 transition">Условия использования</a>
                        <a href="#" class="hover:text-sky-500 transition">Публичная оферта</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Floating Action Button -->
    <div class="fixed bottom-6 right-6 z-50">
        <div class="relative">
            

            <div id="fab-options" class="hidden absolute bottom-16 right-0 space-y-2">
                <a href="#" class="w-12 h-12 bg-white text-sky-500 rounded-full shadow-md flex items-center justify-center hover:bg-sky-50 transition">
                    <i class="fab fa-telegram"></i>
                </a>
                <a href="#" class="w-12 h-12 bg-white text-sky-500 rounded-full shadow-md flex items-center justify-center hover:bg-sky-50 transition">
                    <i class="fab fa-whatsapp"></i>
                </a>
                <a href="#" class="w-12 h-12 bg-white text-sky-500 rounded-full shadow-md flex items-center justify-center hover:bg-sky-50 transition">
                    <i class="fas fa-phone-alt"></i>
                </a>
            </div>
        </div>
    </div>

    <script>
        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        if (mobileMenuButton) {
            mobileMenuButton.addEventListener('click', function() {
                const menu = document.getElementById('mobile-menu');
                if (menu) {
                    menu.classList.toggle('hidden');
                }
            });
        }

        // User menu toggle
        <?php if(isset($_SESSION['user_id'])): ?>
        (function() {
            const userMenuButton = document.getElementById('user-menu-button');
            const userMenu = document.getElementById('user-menu');
            
            if (userMenuButton && userMenu) {
                let isMenuOpen = false;
                
                userMenuButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    isMenuOpen = !isMenuOpen;
                    
                    if (isMenuOpen) {
                        userMenu.classList.remove('hidden');
                    } else {
                        userMenu.classList.add('hidden');
                    }
                });
                
                // Закрытие меню при клике вне его
                document.addEventListener('click', function(e) {
                    if (isMenuOpen && userMenu && userMenuButton) {
                        if (!userMenu.contains(e.target) && !userMenuButton.contains(e.target)) {
                            userMenu.classList.add('hidden');
                            isMenuOpen = false;
                        }
                    }
                });
            }
        })();
        <?php endif; ?>

        // FAB toggle
        const fabMain = document.getElementById('fab-main');
        if (fabMain) {
            fabMain.addEventListener('click', function() {
                const options = document.getElementById('fab-options');
                if (options) {
                    options.classList.toggle('hidden');
                }
            });
        }

        // Tours data and functionality
        let currentPage = 1;
        let currentFilter = 'all';
        let allTours = [];
        const toursPerPage = 12;
        const priceFormatter = new Intl.NumberFormat('ru-RU');

        const formatPrice = (value) => {
            if (value === null || typeof value === 'undefined' || Number.isNaN(value)) {
                return 'По запросу';
            }
            return `${priceFormatter.format(value)} ₽`;
        };

        async function loadTours(page = 1, filter = 'all') {
            const loading = document.getElementById('loading');
            const toursGrid = document.getElementById('tours-grid');
            const loadMoreBtn = document.getElementById('load-more');

            loading.style.display = 'flex';
            loadMoreBtn.classList.add('hidden');

            try {
                const response = await fetch(`../api/tours.php?page=${page}&filter=${filter}&per_page=${toursPerPage}&context=list`);
                const data = await response.json();

                if (page === 1) {
                    allTours = data.tours || [];
                    toursGrid.innerHTML = '';
                } else {
                    allTours = [...allTours, ...(data.tours || [])];
                }

                renderTours(data.tours || []);

                if (data.hasMore) {
                    loadMoreBtn.classList.remove('hidden');
                }

                currentPage = page;
            } catch (error) {
                console.error('Error loading tours:', error);
                loadMockTours();
            } finally {
                loading.style.display = 'none';
            }
        }

        function loadMockTours() {
            const mockTours = [
                {
                    id: 1,
                    title: 'Hilton Seychelles Labriz',
                    description: 'Вилла с личным батлером и приватным пляжем',
                    image: 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1600&q=80',
                    price: 365000,
                    rating: 4.9,
                    reviews: 186,
                    location: 'seychelles',
                    duration: '7-14 ночей',
                    badge: 'Signature'
                },
                {
                    id: 2,
                    title: 'Cheval Blanc Seychelles',
                    description: 'Signature Maison от Louis Vuitton',
                    image: 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1600&q=80',
                    price: 540000,
                    rating: 5.0,
                    reviews: 94,
                    location: 'seychelles',
                    duration: '5-12 ночей',
                    badge: 'New'
                },
                {
                    id: 3,
                    title: 'Мальдивы. Private Atoll',
                    description: 'Overwater Villa + Private Yacht',
                    image: 'https://images.unsplash.com/photo-1527631746610-bca00a040d60?auto=format&fit=crop&w=1600&q=80',
                    price: 470000,
                    rating: 4.9,
                    reviews: 187,
                    location: 'maldives',
                    duration: '7-10 ночей',
                    badge: 'Hot'
                },
                {
                    id: 4,
                    title: 'Дубай. Opera District',
                    description: 'Город будущего и desert retreat',
                    image: 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=1600&q=80',
                    price: 310000,
                    rating: 4.6,
                    reviews: 203,
                    location: 'uae',
                    duration: '5-9 ночей',
                    badge: 'Combo'
                }
            ];

            allTours = mockTours;
            const toursGrid = document.getElementById('tours-grid');
            toursGrid.innerHTML = '';
            renderTours(mockTours);
        }

        function renderTours(tours) {
            const toursGrid = document.getElementById('tours-grid');
            tours.forEach(tour => {
                const tourCard = createTourCard(tour);
                toursGrid.appendChild(tourCard);
            });
        }

        function createTourCard(tour) {
            const card = document.createElement('div');
            card.className = 'tour-card overflow-hidden transition duration-300';

            const priceLabel = formatPrice(tour.price);

            card.innerHTML = `
                <div class="relative overflow-hidden h-48">
                    <img src="${tour.image || 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=80'}"
                         alt="${tour.title}" class="w-full h-full object-cover tour-image transition duration-500">
                    ${tour.badge ? `<div class="absolute top-3 right-3 bg-gradient-to-r from-sky-300 to-sky-500 text-white text-xs font-semibold px-3 py-1 rounded-full uppercase tracking-[0.26em]">${tour.badge}</div>` : ''}
                </div>
                <div class="p-5">
                    <h3 class="heading-font font-semibold text-lg text-slate-900 mb-1">${tour.title}</h3>
                    <p class="text-slate-600 text-sm mb-3">${tour.description || ''}</p>
                    <div class="flex items-center text-yellow-400 mb-3">
                        ${renderStars(tour.rating)}
                        <span class="text-slate-500 text-sm ml-2">${tour.rating || '—'} (${tour.reviews || 0})</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-slate-400 text-xs uppercase tracking-[0.26em]">от</p>
                            <p class="heading-font text-xl font-bold text-slate-900">${priceLabel}</p>
                        </div>
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <a href="#" class="text-sky-500 hover:text-sky-600 font-semibold flex items-center" onclick="viewTourDetails(${tour.id})">
                                Подробнее <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        <?php else: ?>
                            <a href="login.html" class="text-sky-500 hover:text-sky-600 font-semibold flex items-center" onclick="alert('Для просмотра деталей тура необходимо войти в аккаунт')">
                                Подробнее <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            `;

            return card;
        }

        function renderStars(rating = 0) {
            const stars = Math.floor(rating);
            const hasHalfStar = rating % 1 !== 0;
            let starsHtml = '';
            for (let i = 0; i < stars; i++) starsHtml += '<i class="fas fa-star"></i>';
            if (hasHalfStar) starsHtml += '<i class="fas fa-star-half-alt"></i>';
            for (let i = stars + (hasHalfStar ? 1 : 0); i < 5; i++) starsHtml += '<i class="far fa-star"></i>';
            return starsHtml;
        }

        // Filter tours
        function filterTours(filter) {
            currentFilter = filter;
            currentPage = 1;

            const filterButtons = document.querySelectorAll('.filter-btn');
            filterButtons.forEach(btn => {
                btn.classList.remove('active');
            });

            const activeButton = document.querySelector(`[data-filter="${filter}"]`);
            if (activeButton) {
                activeButton.classList.add('active');
            }

            loadTours(1, filter);
        }

        // Search tours
        const searchForm = document.getElementById('search-form');
        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const destination = document.getElementById('destination');
                const departureDate = document.getElementById('departure-date');
                const nights = document.getElementById('nights');
                const maxPrice = document.getElementById('max-price');

                // Apply filters
                let filteredTours = allTours;

                if (destination && destination.value) {
                    filteredTours = filteredTours.filter(tour => tour.location === destination.value);
                }

                if (maxPrice && maxPrice.value) {
                    filteredTours = filteredTours.filter(tour => tour.price <= parseInt(maxPrice.value));
                }

                if (nights && nights.value) {
                    filteredTours = filteredTours.filter(tour => tour.duration.includes(nights.value));
                }

                const toursGrid = document.getElementById('tours-grid');
                if (toursGrid) {
                    toursGrid.innerHTML = '';
                    renderTours(filteredTours);
                }
            });
        }

        // Load more tours
        const loadMoreButton = document.getElementById('load-more');
        if (loadMoreButton) {
            loadMoreButton.addEventListener('click', function() {
                loadTours(currentPage + 1, currentFilter);
            });
        }

        // Filter button clicks
        document.querySelectorAll('.filter-btn').forEach(button => {
            button.addEventListener('click', function() {
                const filter = this.getAttribute('data-filter');
                filterTours(filter);
            });
        });

        // View tour details
        function viewTourDetails(tourId) {
            // Implement tour details modal or redirect
            alert(`Просмотр деталей тура ${tourId}`);
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadTours();
        });

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