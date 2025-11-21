<?php
require_once __DIR__ . '/../config/config.php';

session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? null) !== 'admin') {
    header('Location: ../../frontend/window/вход.html');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Проверка базы данных - Travel Hub</title>
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
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center">
                <a href="/index.php" class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-cyan-500 to-blue-600 flex items-center justify-center mr-3">
                        <i class="fas fa-plane text-white text-lg"></i>
                    </div>
                    <span class="heading-font text-2xl font-bold bg-gradient-to-r from-cyan-500 to-blue-600 bg-clip-text text-transparent">Travel Hub</span>
                </a>
            </div>

            <?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                <a href="admin.php" class="hidden lg:block bg-gradient-to-r from-red-500 to-red-600 text-white px-5 py-2 rounded-full font-medium hover:shadow-lg transition">Админ панель</a>
            <?php else: ?>
                <!-- Tours Bar -->
                <div class="hidden lg:flex bg-cyan-50 px-4 py-1 rounded-full text-sm">
                    <span class="text-cyan-600 font-medium mr-4">Туры:</span>
                    <a href="/frontend/window/tours.php" class="text-gray-600 hover:text-cyan-600 mr-3">Горящие</a>
                    <a href="/frontend/window/tours.php" class="text-gray-600 hover:text-cyan-600 mr-3">Турция</a>
                    <a href="/frontend/window/tours.php" class="text-gray-600 hover:text-cyan-600 mr-3">ОАЭ</a>
                    <a href="/frontend/window/tours.php" class="text-gray-600 hover:text-cyan-600">Египет</a>
                </div>
            <?php endif; ?>

            <nav class="hidden md:flex space-x-8">
                <a href="sss.php" class="nav-link text-gray-700 font-medium hover:text-cyan-500 transition">Главная</a>
                <a href="/frontend/window/tours.php" class="nav-link text-gray-700 font-medium hover:text-cyan-500 transition">Туры</a>
                <a href="#" class="nav-link text-gray-700 font-medium hover:text-cyan-500 transition">Услуги</a>
                <a href="#" class="nav-link text-gray-700 font-medium hover:text-cyan-500 transition">Акции</a>
                <a href="#" class="nav-link text-gray-700 font-medium hover:text-cyan-500 transition">О нас</a>
                <a href="#" class="nav-link text-gray-700 font-medium hover:text-cyan-500 transition">Контакты</a>
            </nav>

            <div class="flex items-center space-x-4">
                <div class="hidden md:flex space-x-3">
                    <a href="#" class="w-9 h-9 rounded-full border border-gray-300 flex items-center justify-center text-gray-600 hover:bg-cyan-50 hover:text-cyan-500 transition">
                        <i class="fab fa-telegram"></i>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-full border border-gray-300 flex items-center justify-center text-gray-600 hover:bg-cyan-50 hover:text-cyan-500 transition">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </div>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <div class="relative">
                        <button id="user-menu-button" class="hidden md:flex items-center bg-gradient-to-r from-cyan-500 to-blue-600 text-white px-5 py-2 rounded-full font-medium">
                            <i class="fas fa-user mr-2"></i><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Пользователь'); ?>
                            <i class="fas fa-chevron-down ml-2"></i>
                        </button>
                        <div id="user-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50">
                            <a href="/frontend/window/dashboard.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Личный кабинет</a>
                            <?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                            <a href="admin.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Админ панель</a>
                            <?php endif; ?>
                            <a href="../scripts/logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Выход</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="/frontend/window/login.html" class="hidden md:block bg-gradient-to-r from-cyan-500 to-blue-600 text-white px-5 py-2 rounded-full font-medium">Войти</a>
                <?php endif; ?>
                <button id="mobile-menu-button" class="md:hidden text-gray-600">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t py-3 px-4">
            <div class="flex flex-col space-y-3">
                <a href="sss.php" class="nav-link text-gray-700 font-medium hover:text-cyan-500 transition">Главная</a>
                <a href="/frontend/window/tours.php" class="nav-link text-gray-700 font-medium hover:text-cyan-500 transition">Туры</a>
                <a href="#" class="nav-link text-gray-700 font-medium hover:text-cyan-500 transition">Услуги</a>
                <a href="#" class="nav-link text-gray-700 font-medium hover:text-cyan-500 transition">Акции</a>
                <a href="#" class="nav-link text-gray-700 font-medium hover:text-cyan-500 transition">О нас</a>
                <a href="#" class="nav-link text-gray-700 font-medium hover:text-cyan-500 transition">Контакты</a>
                <div class="flex space-x-3 pt-2">
                    <a href="#" class="w-9 h-9 rounded-full border border-gray-300 flex items-center justify-center text-gray-600 hover:bg-cyan-50 hover:text-cyan-500 transition">
                        <i class="fab fa-telegram"></i>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-full border border-gray-300 flex items-center justify-center text-gray-600 hover:bg-cyan-50 hover:text-cyan-500 transition">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </div>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="/frontend/window/dashboard.php" class="bg-gradient-to-r from-cyan-500 to-blue-600 text-white px-5 py-2 rounded-full font-medium text-center">Личный кабинет</a>
                    <?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                    <a href="admin.php" class="bg-gradient-to-r from-cyan-500 to-blue-600 text-white px-5 py-2 rounded-full font-medium text-center">Админ панель</a>
                    <?php endif; ?>
                    <a href="../scripts/logout.php" class="bg-gray-500 text-white px-5 py-2 rounded-full font-medium text-center">Выход</a>
                <?php else: ?>
                    <a href="/frontend/window/login.html" class="bg-gradient-to-r from-cyan-500 to-blue-600 text-white px-5 py-2 rounded-full font-medium text-center">Войти</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-12">
                    <h1 class="heading-font text-4xl font-bold text-gray-800 mb-4">Проверка базы данных</h1>
                    <a href="admin.php" class="inline-block bg-gradient-to-r from-cyan-500 to-blue-600 text-white px-6 py-3 rounded-full font-medium hover:shadow-lg transition">Назад в админ панель</a>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                    <p class="text-gray-700 mb-4"><strong>Файл config.php существует:</strong> <?php echo (file_exists(__DIR__ . '/../config/config.php') ? '<span class="text-green-600">Да</span>' : '<span class="text-red-600">Нет</span>'); ?></p>

                    <?php
                    if ($pdo) {
                        echo "<div class='bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-6'>Подключение к базе данных успешно!</div>";

                        // Проверяем таблицы
                        $tables = ['users', 'user_logs', 'bookings', 'newsletter_subscriptions'];
                        echo "<h2 class='heading-font text-2xl font-bold text-gray-800 mb-4'>Проверка таблиц</h2>";
                        echo "<div class='space-y-2 mb-6'>";
                        foreach ($tables as $table) {
                            try {
                                $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
                                $count = $stmt->fetchColumn();
                                echo "<p class='text-gray-700'><strong>$table:</strong> $count записей</p>";
                            } catch (PDOException $e) {
                                echo "<p class='text-red-600'><strong>$table:</strong> Ошибка - " . $e->getMessage() . "</p>";
                            }
                        }
                        echo "</div>";

                        // Показать последние пользователи
                        try {
                            $stmt = $pdo->query("SELECT id, name, email, reg_date FROM users ORDER BY id DESC LIMIT 5");
                            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            if ($users) {
                                echo "<h2 class='heading-font text-2xl font-bold text-gray-800 mb-4'>Последние пользователи</h2>";
                                echo "<div class='overflow-x-auto'>";
                                echo "<table class='w-full table-auto'>";
                                echo "<thead><tr class='bg-gray-50'><th class='px-4 py-2 text-left text-gray-600 font-medium'>ID</th><th class='px-4 py-2 text-left text-gray-600 font-medium'>Имя</th><th class='px-4 py-2 text-left text-gray-600 font-medium'>Email</th><th class='px-4 py-2 text-left text-gray-600 font-medium'>Дата регистрации</th></tr></thead>";
                                echo "<tbody>";
                                foreach ($users as $user) {
                                    echo "<tr class='border-t'><td class='px-4 py-2'>{$user['id']}</td><td class='px-4 py-2'>{$user['name']}</td><td class='px-4 py-2'>{$user['email']}</td><td class='px-4 py-2'>{$user['reg_date']}</td></tr>";
                                }
                                echo "</tbody></table></div>";
                            }
                        } catch (PDOException $e) {
                            echo "<div class='bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded'>Ошибка получения пользователей: " . $e->getMessage() . "</div>";
                        }

                    } else {
                        $dbPath = __DIR__ . '/../config/user_management.db';
                        echo "<div class='bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4'>Подключение к базе данных не удалось.</div>";
                        echo "<p class='text-gray-700 mb-2'><strong>Файл БД:</strong> " . $dbPath . "</p>";
                        echo "<p class='text-gray-700'><strong>Существует:</strong> " . (file_exists($dbPath) ? '<span class="text-green-600">Да</span>' : '<span class="text-red-600">Нет</span>') . "</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white pt-16 pb-8">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                <div>
                    <a href="sss.php" class="flex items-center mb-6">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-cyan-500 to-blue-600 flex items-center justify-center mr-3">
                            <i class="fas fa-plane text-white text-lg"></i>
                        </div>
                        <span class="heading-font text-2xl font-bold bg-gradient-to-r from-cyan-500 to-blue-600 bg-clip-text text-transparent">Travel Hub</span>
                    </a>
                    <p class="text-gray-400 mb-4">Мы создаем незабываемые путешествия с 2010 года. Более 50 000 довольных клиентов.</p>
                </div>

                <div>
                    <h4 class="heading-font font-bold text-lg mb-4">Туры</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Горящие туры</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Популярные направления</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Экскурсионные туры</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Пляжный отдых</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="heading-font font-bold text-lg mb-4">Услуги</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Авиабилеты</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Отели</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Трансферы</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Страхование</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="heading-font font-bold text-lg mb-4">Информация</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition">О компании</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Отзывы</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Блог</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Контакты</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-gray-500 text-sm mb-4 md:mb-0">© 2023 Travel Hub. Все права защищены.</p>
                    <div class="flex space-x-6">
                        <a href="#" class="text-gray-500 hover:text-white text-sm transition">Политика конфиденциальности</a>
                        <a href="#" class="text-gray-500 hover:text-white text-sm transition">Условия использования</a>
                    </div>
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
        document.getElementById('user-menu-button').addEventListener('click', function() {
            const menu = document.getElementById('user-menu');
            menu.classList.toggle('hidden');
        });
        <?php endif; ?>
    </script>
</body>
</html>