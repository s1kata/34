<?php
require_once __DIR__ . '/../../backend/config/config.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /frontend/window/login.html');
    exit;
}

$user_id = $_SESSION['user_id'];

// Получаем данные пользователя
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p style='color: red;'>Ошибка загрузки данных: " . $e->getMessage() . "</p>";
    exit;
}

// Обработка обновления паспортных данных
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_passport'])) {
    $passport_series = trim($_POST['passport_series']);
    $passport_number = trim($_POST['passport_number']);
    $passport_issued_by = trim($_POST['passport_issued_by']);
    $passport_issue_date = trim($_POST['passport_issue_date']);
    $passport_expiry_date = trim($_POST['passport_expiry_date']);

    $errors = [];
    if (empty($passport_series)) $errors[] = 'Введите серию паспорта';
    if (empty($passport_number)) $errors[] = 'Введите номер паспорта';
    if (empty($passport_issued_by)) $errors[] = 'Введите кем выдан паспорт';
    if (empty($passport_issue_date)) $errors[] = 'Введите дату выдачи';
    if (empty($passport_expiry_date)) $errors[] = 'Введите дату окончания';

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("UPDATE users SET passport_series = ?, passport_number = ?, passport_issued_by = ?, passport_issue_date = ?, passport_expiry_date = ? WHERE id = ?");
            $stmt->execute([$passport_series, $passport_number, $passport_issued_by, $passport_issue_date, $passport_expiry_date, $user_id]);
            $success_message = 'Паспортные данные обновлены';
            // Перезагрузка данных пользователя
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $error_message = "Ошибка обновления: " . $e->getMessage();
        }
    } else {
        $error_message = implode(', ', $errors);
    }
}

// Обработка удаления профиля
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_profile'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        session_destroy();
        header('Location: /frontend/window/login.html');
        exit;
    } catch (PDOException $e) {
        $error_message = "Ошибка удаления профиля: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль - Travel Hub</title>
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

        <!-- Mobile Menu -->
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

    <!-- Profile Content -->
    <section class="py-8 md:py-16 bg-gradient-to-b from-sky-50/50 to-white">
        <div class="container mx-auto px-4 md:px-6">
            <div class="max-w-5xl mx-auto">
                <div class="text-center mb-8 md:mb-12">
                    <div class="inline-flex items-center justify-center w-16 h-16 md:w-20 md:h-20 rounded-full bg-gradient-to-r from-sky-300 via-sky-400 to-sky-500 mb-4 md:mb-6 shadow-lg shadow-sky-200/60">
                        <i class="fas fa-user text-white text-2xl md:text-3xl"></i>
                    </div>
                    <h1 class="heading-font text-3xl md:text-4xl font-bold text-slate-900 mb-2 md:mb-3">Мой профиль</h1>
                    <p class="text-slate-600 text-base md:text-lg px-4">Управляйте своей личной информацией и паспортными данными</p>
                </div>

                <?php if(isset($success_message)): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                        <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>

                <?php if(isset($error_message)): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

                <!-- Personal Information Section -->
                <div class="mb-8 md:mb-12">
                    <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-xl border border-sky-100 p-6 md:p-8 hover:shadow-2xl transition-shadow">
                        <h2 class="heading-font text-xl md:text-2xl font-bold text-slate-900 mb-6 flex items-center">
                            <div class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-gradient-to-r from-sky-300 via-sky-400 to-sky-500 flex items-center justify-center mr-3 md:mr-4 shadow-md">
                                <i class="fas fa-user text-white text-sm md:text-base"></i>
                            </div>
                            Личная информация
                        </h2>
                        <div class="space-y-3 md:space-y-4">
                            <div class="flex flex-col md:flex-row md:justify-between md:items-center py-3 border-b border-sky-100 hover:bg-sky-50/50 px-2 rounded-lg transition">
                                <span class="font-medium text-slate-600 flex items-center mb-1 md:mb-0"><i class="fas fa-user-circle mr-2 text-sky-500"></i>Имя:</span>
                                <span class="text-slate-900 font-semibold text-right"><?php echo htmlspecialchars($user['name']); ?></span>
                            </div>
                            <div class="flex flex-col md:flex-row md:justify-between md:items-center py-3 border-b border-sky-100 hover:bg-sky-50/50 px-2 rounded-lg transition">
                                <span class="font-medium text-slate-600 flex items-center mb-1 md:mb-0"><i class="fas fa-envelope mr-2 text-sky-500"></i>Email:</span>
                                <span class="text-slate-900 font-semibold text-right break-all"><?php echo htmlspecialchars($user['email']); ?></span>
                            </div>
                            <div class="flex flex-col md:flex-row md:justify-between md:items-center py-3 border-b border-sky-100 hover:bg-sky-50/50 px-2 rounded-lg transition">
                                <span class="font-medium text-slate-600 flex items-center mb-1 md:mb-0"><i class="fas fa-phone mr-2 text-sky-500"></i>Телефон:</span>
                                <span class="text-slate-900 font-semibold text-right"><?php echo htmlspecialchars($user['phone'] ?? 'Не указан'); ?></span>
                            </div>
                            <div class="flex flex-col md:flex-row md:justify-between md:items-center py-3 border-b border-sky-100 hover:bg-sky-50/50 px-2 rounded-lg transition">
                                <span class="font-medium text-slate-600 flex items-center mb-1 md:mb-0"><i class="fas fa-map-marker-alt mr-2 text-sky-500"></i>Город:</span>
                                <span class="text-slate-900 font-semibold text-right"><?php echo htmlspecialchars($user['city'] ?? 'Не указан'); ?></span>
                            </div>
                            <div class="flex flex-col md:flex-row md:justify-between md:items-center py-3 border-b border-sky-100 hover:bg-sky-50/50 px-2 rounded-lg transition">
                                <span class="font-medium text-slate-600 flex items-center mb-1 md:mb-0"><i class="fas fa-birthday-cake mr-2 text-sky-500"></i>Возраст:</span>
                                <span class="text-slate-900 font-semibold text-right"><?php echo htmlspecialchars($user['age'] ?? 'Не указан'); ?></span>
                            </div>
                            <div class="flex flex-col md:flex-row md:justify-between md:items-center py-3 hover:bg-sky-50/50 px-2 rounded-lg transition">
                                <span class="font-medium text-slate-600 flex items-center mb-1 md:mb-0"><i class="fas fa-venus-mars mr-2 text-sky-500"></i>Пол:</span>
                                <span class="text-slate-900 font-semibold text-right"><?php echo htmlspecialchars($user['gender'] ?? 'Не указан'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Passport Information Section -->
                <div class="mb-8 md:mb-12">
                    <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-xl border border-sky-100 p-6 md:p-8 hover:shadow-2xl transition-shadow">
                        <h2 class="heading-font text-xl md:text-2xl font-bold text-slate-900 mb-6 flex items-center">
                            <div class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-gradient-to-r from-sky-300 via-sky-400 to-sky-500 flex items-center justify-center mr-3 md:mr-4 shadow-md">
                                <i class="fas fa-passport text-white text-sm md:text-base"></i>
                            </div>
                            Паспортные данные
                        </h2>
                        <form method="POST" class="space-y-4 md:space-y-5">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Серия паспорта</label>
                                <input type="text" name="passport_series" value="<?php echo htmlspecialchars($user['passport_series'] ?? ''); ?>" class="w-full px-4 py-3 border border-sky-200 rounded-xl focus:ring-2 focus:ring-sky-300 focus:border-sky-400 transition bg-white text-base">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Номер паспорта</label>
                                <input type="text" name="passport_number" value="<?php echo htmlspecialchars($user['passport_number'] ?? ''); ?>" class="w-full px-4 py-3 border border-sky-200 rounded-xl focus:ring-2 focus:ring-sky-300 focus:border-sky-400 transition bg-white text-base">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Кем выдан</label>
                                <input type="text" name="passport_issued_by" value="<?php echo htmlspecialchars($user['passport_issued_by'] ?? ''); ?>" class="w-full px-4 py-3 border border-sky-200 rounded-xl focus:ring-2 focus:ring-sky-300 focus:border-sky-400 transition bg-white text-base">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Дата выдачи</label>
                                <input type="date" name="passport_issue_date" value="<?php echo htmlspecialchars($user['passport_issue_date'] ?? ''); ?>" class="w-full px-4 py-3 border border-sky-200 rounded-xl focus:ring-2 focus:ring-sky-300 focus:border-sky-400 transition bg-white text-base">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Дата окончания</label>
                                <input type="date" name="passport_expiry_date" value="<?php echo htmlspecialchars($user['passport_expiry_date'] ?? ''); ?>" class="w-full px-4 py-3 border border-sky-200 rounded-xl focus:ring-2 focus:ring-sky-300 focus:border-sky-400 transition bg-white text-base">
                            </div>
                            <button type="submit" name="update_passport" class="w-full bg-gradient-to-r from-sky-300 via-sky-400 to-sky-500 text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg shadow-md shadow-sky-200/60 transition mt-4 text-base">
                                <i class="fas fa-save mr-2"></i>Сохранить паспортные данные
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Delete Profile Section -->
                <div class="mt-8 md:mt-12 bg-gradient-to-r from-red-50 to-rose-50 border-2 border-red-200 rounded-2xl p-6 md:p-8 shadow-lg">
                    <h2 class="heading-font text-xl md:text-2xl font-bold text-red-800 mb-4 flex items-center">
                        <div class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-red-100 flex items-center justify-center mr-3 md:mr-4">
                            <i class="fas fa-exclamation-triangle text-red-600 text-sm md:text-base"></i>
                        </div>
                        Опасная зона
                    </h2>
                    <p class="text-red-700 mb-6 text-base md:text-lg">Удаление профиля необратимо. Все ваши данные будут удалены навсегда.</p>
                    <form method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить свой профиль? Это действие нельзя отменить.')">
                        <button type="submit" name="delete_profile" class="w-full md:w-auto bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-6 md:px-8 py-3 rounded-xl font-semibold shadow-lg transition text-base">
                            <i class="fas fa-trash mr-2"></i>
                            Удалить профиль
                        </button>
                    </form>
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