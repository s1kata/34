<?php
include __DIR__ . '/../../backend/config/config.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /frontend/window/login.html');
    exit;
}

// Проверяем, заполнены ли паспортные данные
$user_id = $_SESSION['user_id'];
try {
    $stmt = $pdo->prepare("SELECT passport_series, passport_number FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (empty($user['passport_series']) || empty($user['passport_number'])) {
        header('Location: profile.php');
        exit;
    }
} catch (PDOException $e) {
    // Игнорируем ошибку, продолжаем
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет - Travel Hub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
        }
        .heading-font {
            font-family: 'Montserrat', sans-serif;
        }
    </style>
</head>
<body class="bg-gradient-to-b from-sky-50/50 to-white min-h-screen">
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

        <!-- Mobile Menu -->
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

    <section class="py-16 bg-gradient-to-b from-sky-50/50 to-white">
        <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto">
                <div class="text-center mb-12">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-r from-sky-300 via-sky-400 to-sky-500 mb-6 shadow-lg shadow-sky-200/60">
                        <i class="fas fa-home text-white text-3xl"></i>
                    </div>
                    <h1 class="heading-font text-4xl font-bold text-slate-900 mb-4">Личный кабинет</h1>
                    <p class="text-xl text-slate-600">Добро пожаловать в ваш личный кабинет Travel Hub!</p>
                </div>

                <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-xl border border-sky-100 p-8">
                    <h2 class="heading-font text-2xl font-bold text-slate-900 mb-6">Информация о пользователе</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-gradient-to-br from-sky-50 to-blue-50 p-6 rounded-xl border border-sky-100">
                            <h3 class="heading-font text-lg font-semibold text-slate-800 mb-4 flex items-center">
                                <i class="fas fa-info-circle mr-2 text-sky-500"></i>Основная информация
                            </h3>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center py-2 border-b border-sky-100">
                                    <span class="text-slate-600">Имя:</span>
                                    <span class="font-semibold text-slate-900"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Не указано'); ?></span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-sky-100">
                                    <span class="text-slate-600">Email:</span>
                                    <span class="font-semibold text-slate-900"><?php echo htmlspecialchars($_SESSION['user_email'] ?? 'Не указано'); ?></span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-sky-100">
                                    <span class="text-slate-600">Статус:</span>
                                    <span class="font-semibold text-green-600">Авторизован</span>
                                </div>
                                <div class="flex justify-between items-center py-2">
                                    <span class="text-slate-600">Роль:</span>
                                    <span class="font-semibold text-slate-900"><?php echo htmlspecialchars($_SESSION['user_role'] ?? 'user'); ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-sky-50 to-blue-50 p-6 rounded-xl border border-sky-100">
                            <h3 class="heading-font text-lg font-semibold text-slate-800 mb-4 flex items-center">
                                <i class="fas fa-bolt mr-2 text-sky-500"></i>Быстрые действия
                            </h3>
                            <div class="space-y-3">
                                <a href="/frontend/window/profile.php" class="block w-full bg-gradient-to-r from-sky-300 via-sky-400 to-sky-500 text-white text-center py-3 px-4 rounded-xl font-semibold hover:shadow-lg shadow-md transition">
                                    <i class="fas fa-user mr-2"></i>Управление профилем
                                </a>
                                <a href="/frontend/window/tours.php" class="block w-full bg-gradient-to-r from-green-400 to-green-500 text-white text-center py-3 px-4 rounded-xl font-semibold hover:shadow-lg shadow-md transition">
                                    <i class="fas fa-search mr-2"></i>Найти туры
                                </a>
                                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                                <a href="/backend/admin/admin.php" class="block w-full bg-gradient-to-r from-rose-300 via-rose-400 to-rose-500 text-white text-center py-3 px-4 rounded-xl font-semibold hover:shadow-lg shadow-md transition">
                                    <i class="fas fa-cog mr-2"></i>Админ-панель
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <a href="/backend/scripts/logout.php" class="inline-block bg-gradient-to-r from-red-500 to-red-600 text-white px-8 py-3 rounded-xl font-semibold hover:shadow-lg shadow-md transition mr-4">
                            <i class="fas fa-sign-out-alt mr-2"></i>Выйти из аккаунта
                        </a>
                        <a href="/index.php" class="inline-block bg-gradient-to-r from-slate-400 to-slate-500 text-white px-8 py-3 rounded-xl font-semibold hover:shadow-lg shadow-md transition">
                            <i class="fas fa-home mr-2"></i>Вернуться на главную
                        </a>
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
                            <li><a href="#" class="hover:text-sky-500">О нас</a></li>
                            <li><a href="#" class="hover:text-sky-500">Команда</a></li>
                            <li><a href="#" class="hover:text-sky-500">Партнёры</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-800 mb-3">Туры</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="hover:text-sky-500">Премиум</a></li>
                            <li><a href="#" class="hover:text-sky-500">Семейные</a></li>
                            <li><a href="#" class="hover:text-sky-500">Медовый месяц</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-800 mb-3">Консьерж</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="hover:text-sky-500">Привилегии</a></li>
                            <li><a href="#" class="hover:text-sky-500">Кейсы</a></li>
                            <li><a href="#" class="hover:text-sky-500">FAQ</a></li>
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
                    <a href="#" class="hover:text-sky-500">Условия использования</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });

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
    </script>
</body>
</html>