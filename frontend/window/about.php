<?php
require_once __DIR__ . '/../../backend/config/config.php';
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>О нас - Travel Hub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-body: #f4f9ff;
            --accent-primary: #3ba3ff;
            --accent-secondary: #7bc4ff;
            --text-primary: #1f2a44;
            --text-secondary: #4f5f78;
            --border-soft: rgba(59, 163, 255, 0.18);
            --shadow-soft: 0 20px 48px rgba(59, 163, 255, 0.18);
        }
        body {
            font-family: 'Open Sans', sans-serif;
            background: linear-gradient(180deg, #f8fbff 0%, #eff5ff 45%, #fdfdff 100%);
            color: var(--text-primary);
            min-height: 100vh;
        }
        .heading-font { font-family: 'Montserrat', sans-serif; }
        .surface-card {
            background: #ffffff;
            border-radius: 22px;
            border: 1px solid rgba(59, 163, 255, 0.12);
            box-shadow: 0 16px 32px rgba(59, 163, 255, 0.12);
        }
    </style>
</head>
<body class="text-slate-900">
    <!-- Header -->
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
                <a href="/frontend/window/about.php" class="text-slate-700 font-medium hover:text-sky-500 transition text-sky-500">О нас</a>
                <a href="/frontend/window/contacts.php" class="text-slate-700 font-medium hover:text-sky-500 transition">Контакты</a>
            </nav>

            <div class="flex items-center space-x-4">
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
    <section class="relative py-20 md:py-28 bg-gradient-to-br from-sky-50 via-white to-sky-50">
        <div class="container mx-auto px-6">
            <div class="text-center max-w-4xl mx-auto">
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-sky-50 border border-sky-200 text-xs uppercase tracking-[0.28em] text-sky-500 mb-6">О компании</span>
                <h1 class="heading-font text-4xl md:text-5xl font-bold text-slate-900 mb-6">Travel Hub — ваш надежный партнер в путешествиях</h1>
                <p class="text-xl text-slate-600 mb-8">Мы создаем незабываемые путешествия с 2010 года. Более 50 000 довольных клиентов доверили нам организацию своих поездок.</p>
            </div>
        </div>
    </section>

    <!-- About Content -->
    <section class="py-16">
        <div class="container mx-auto px-6">
            <div class="max-w-6xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">
                    <div class="surface-card p-8">
                        <div class="w-16 h-16 rounded-full bg-gradient-to-r from-sky-300 via-sky-400 to-sky-500 flex items-center justify-center mb-6">
                            <i class="fas fa-history text-white text-2xl"></i>
                        </div>
                        <h2 class="heading-font text-2xl font-bold text-slate-900 mb-4">Наша история</h2>
                        <p class="text-slate-600 mb-4">Travel Hub была основана в 2010 году группой энтузиастов, которые верили, что путешествия должны быть доступными, безопасными и незабываемыми.</p>
                        <p class="text-slate-600">За годы работы мы выросли из небольшого агентства в одну из ведущих туристических компаний, специализирующихся на премиальном сервисе и индивидуальном подходе к каждому клиенту.</p>
                    </div>

                    <div class="surface-card p-8">
                        <div class="w-16 h-16 rounded-full bg-gradient-to-r from-sky-300 via-sky-400 to-sky-500 flex items-center justify-center mb-6">
                            <i class="fas fa-bullseye text-white text-2xl"></i>
                        </div>
                        <h2 class="heading-font text-2xl font-bold text-slate-900 mb-4">Наша миссия</h2>
                        <p class="text-slate-600 mb-4">Мы стремимся сделать путешествия доступными для каждого, предоставляя высококачественный сервис, персональный подход и гарантию безопасности.</p>
                        <p class="text-slate-600">Наша цель — создать незабываемые воспоминания, которые останутся с вами на всю жизнь.</p>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-16">
                    <div class="surface-card p-6 text-center">
                        <div class="text-4xl font-bold text-sky-600 mb-2">50K+</div>
                        <div class="text-slate-600">Довольных клиентов</div>
                    </div>
                    <div class="surface-card p-6 text-center">
                        <div class="text-4xl font-bold text-sky-600 mb-2">14+</div>
                        <div class="text-slate-600">Лет опыта</div>
                    </div>
                    <div class="surface-card p-6 text-center">
                        <div class="text-4xl font-bold text-sky-600 mb-2">100+</div>
                        <div class="text-slate-600">Направлений</div>
                    </div>
                    <div class="surface-card p-6 text-center">
                        <div class="text-4xl font-bold text-sky-600 mb-2">24/7</div>
                        <div class="text-slate-600">Поддержка</div>
                    </div>
                </div>

                <!-- Values -->
                <div class="mb-16">
                    <h2 class="heading-font text-3xl font-bold text-slate-900 text-center mb-12">Наши ценности</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="surface-card p-6 text-center">
                            <div class="w-14 h-14 rounded-full bg-sky-100 text-sky-600 flex items-center justify-center mx-auto mb-4 text-2xl">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <h3 class="heading-font text-xl font-semibold text-slate-900 mb-3">Безопасность</h3>
                            <p class="text-slate-600">Все туры застрахованы, отели проверены, вы защищены на каждом этапе путешествия.</p>
                        </div>
                        <div class="surface-card p-6 text-center">
                            <div class="w-14 h-14 rounded-full bg-sky-100 text-sky-600 flex items-center justify-center mx-auto mb-4 text-2xl">
                                <i class="fas fa-heart"></i>
                            </div>
                            <h3 class="heading-font text-xl font-semibold text-slate-900 mb-3">Забота</h3>
                            <p class="text-slate-600">Персональный менеджер сопровождает вас от момента бронирования до возвращения домой.</p>
                        </div>
                        <div class="surface-card p-6 text-center">
                            <div class="w-14 h-14 rounded-full bg-sky-100 text-sky-600 flex items-center justify-center mx-auto mb-4 text-2xl">
                                <i class="fas fa-star"></i>
                            </div>
                            <h3 class="heading-font text-xl font-semibold text-slate-900 mb-3">Качество</h3>
                            <p class="text-slate-600">Работаем только с проверенными партнерами и гарантируем высокий уровень сервиса.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-sky-100 py-10 mt-20">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between gap-8">
                <div class="space-y-3 max-w-sm">
                    <span class="heading-font text-2xl font-bold text-sky-600">Travel Hub</span>
                    <p class="text-slate-600">Мы создаём путешествия класса люкс и обеспечиваем сервис, который остаётся в памяти надолго.</p>
                    <div class="flex gap-3">
                        <a href="https://t.me/TravelHub" class="w-9 h-9 rounded-full border border-sky-200 flex items-center justify-center text-slate-500 hover:bg-sky-100 hover:text-sky-500 transition"><i class="fab fa-telegram"></i></a>
                        <a href="#" class="w-9 h-9 rounded-full border border-sky-200 flex items-center justify-center text-slate-500 hover:bg-sky-100 hover:text-sky-500 transition"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="w-9 h-9 rounded-full border border-sky-200 flex items-center justify-center text-slate-500 hover:bg-sky-100 hover:text-sky-500 transition"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-sm text-slate-600">
                    <div>
                        <h3 class="font-semibold text-slate-800 mb-3">Компания</h3>
                        <ul class="space-y-2">
                            <li><a href="/frontend/window/about.php" class="hover:text-sky-500">О нас</a></li>
                            <li><a href="#" class="hover:text-sky-500">Команда</a></li>
                            <li><a href="#" class="hover:text-sky-500">Партнёры</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-800 mb-3">Туры</h3>
                        <ul class="space-y-2">
                            <li><a href="/frontend/window/tours.php" class="hover:text-sky-500">Все туры</a></li>
                            <li><a href="#" class="hover:text-sky-500">Семейные</a></li>
                            <li><a href="#" class="hover:text-sky-500">Медовый месяц</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-800 mb-3">Услуги</h3>
                        <ul class="space-y-2">
                            <li><a href="/frontend/window/services.php" class="hover:text-sky-500">Все услуги</a></li>
                            <li><a href="#" class="hover:text-sky-500">Визы</a></li>
                            <li><a href="#" class="hover:text-sky-500">Страхование</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-800 mb-3">Контакты</h3>
                        <ul class="space-y-2">
                            <li><a href="/frontend/window/contacts.php" class="hover:text-sky-500">Связаться</a></li>
                            <li><a href="tel:+74951234567" class="hover:text-sky-500">+7 (495) 123-45-67</a></li>
                            <li><a href="mailto:concierge@travelhub.ru" class="hover:text-sky-500">Email</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="mt-10 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-slate-500">
                <p>© <?php echo date('Y'); ?> Travel Hub. Все права защищены.</p>
                <div class="flex gap-4">
                    <a href="#" class="hover:text-sky-500">Политика конфиденциальности</a>
                    <a href="#" class="hover:text-sky-500">Условия использования</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });

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
    </script>
</body>
</html>


