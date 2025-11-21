<?php
require_once __DIR__ . '/../config/config.php';

session_start();

if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? null) !== 'admin') {
    header('Location: ../../frontend/window/login.html');
    exit;
}

$stats = [
    'users' => 0,
    'admins' => 0,
    'bookings' => 0,
    'subscribers' => 0,
    'tours' => 0,
];

$recentUsers = [];
$recentBookings = [];

if ($pdo) {
    try {
        $stats['users'] = (int) $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
        $stats['admins'] = (int) $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")->fetchColumn();
        $stats['bookings'] = (int) $pdo->query('SELECT COUNT(*) FROM bookings')->fetchColumn();
        $stats['subscribers'] = (int) $pdo->query('SELECT COUNT(*) FROM newsletter_subscriptions WHERE is_active = 1')->fetchColumn();
        
        // Проверяем существование таблицы tours перед запросом
        try {
            $toursCheck = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='tours'");
            if ($toursCheck->fetchColumn()) {
                $stats['tours'] = (int) $pdo->query('SELECT COUNT(*) FROM tours')->fetchColumn();
            } else {
                $stats['tours'] = 0;
            }
        } catch (PDOException $e) {
            $stats['tours'] = 0;
        }

        $recentUsersStmt = $pdo->query('SELECT name, email, reg_date FROM users ORDER BY reg_date DESC LIMIT 5');
        $recentUsers = $recentUsersStmt->fetchAll();

        // Используем правильные имена колонок из таблицы bookings
        $recentBookingsStmt = $pdo->query('SELECT tour_title, hotel_name, destination, status, booking_date FROM bookings ORDER BY booking_date DESC LIMIT 5');
        $recentBookings = $recentBookingsStmt->fetchAll();
    } catch (PDOException $e) {
        error_log('[admin] ' . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель | Travel Hub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Open+Sans:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-body: #f4f9ff;
            --bg-surface: #ffffff;
            --bg-muted: #eaf3ff;
            --accent-primary: #3ba3ff;
            --accent-secondary: #7bc4ff;
            --text-primary: #1f2a44;
            --text-secondary: #4f5f78;
            --border-soft: rgba(59, 163, 255, 0.18);
            --shadow-soft: 0 22px 48px rgba(59, 163, 255, 0.18);
        }
        body {
            font-family: 'Open Sans', sans-serif;
            background: linear-gradient(180deg, #f8fbff 0%, #eff5ff 45%, #fdfdff 100%);
            color: var(--text-primary);
        }
        .heading-font { font-family: 'Montserrat', sans-serif; }
        .metric-card {
            background: var(--bg-surface);
            border-radius: 20px;
            border: 1px solid var(--border-soft);
            box-shadow: var(--shadow-soft);
        }
        .eyebrow-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 16px;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: 0.32em;
            font-size: 0.65rem;
            background: rgba(59, 163, 255, 0.12);
            border: 1px solid rgba(59, 163, 255, 0.24);
            color: var(--text-primary);
        }
    </style>
</head>
<body class="bg-transparent text-slate-900 min-h-screen">
    <header class="backdrop-blur-md bg-white/90 border-b border-sky-100 sticky top-0 z-40">
        <div class="container mx-auto px-6 py-5 flex flex-wrap items-center justify-between gap-4">
            <a href="/index.php" class="flex items-center gap-3">
                <span class="w-10 h-10 rounded-full bg-gradient-to-r from-sky-300 via-sky-400 to-sky-500 flex items-center justify-center shadow-lg">
                    <i class="fas fa-plane text-white"></i>
                </span>
                <span class="heading-font text-2xl font-bold text-sky-600">Travel Hub Admin</span>
            </a>
            <div class="flex items-center gap-3 text-sm text-slate-500">
                <span><i class="fas fa-user-shield mr-2 text-sky-500"></i><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?></span>
                <a href="view_database.php" class="px-4 py-2 rounded-full border border-sky-100 text-slate-600 hover:bg-sky-50 transition">База данных</a>
                <a href="../scripts/logout.php" class="px-4 py-2 rounded-full bg-gradient-to-r from-sky-300 via-sky-400 to-sky-500 text-white shadow-md hover:shadow-lg transition">Выход</a>
            </div>
        </div>
    </header>

    <main class="py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto space-y-12">
                <div class="text-center space-y-4">
                    <span class="eyebrow-badge inline-flex items-center gap-2">
                        <i class="fas fa-chart-pie"></i>
                        Admin Console
                    </span>
                    <h1 class="heading-font text-4xl font-bold text-slate-900">Контрольная панель Travel Hub</h1>
                    <p class="text-slate-600 max-w-2xl mx-auto">Отслеживайте пользователей, заявки и туры, хранящиеся в защищённой SQL-базе, и управляйте сервисом в едином интерфейсе.</p>
                </div>

                <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                        <?php
                        $metricIcons = [
                            'users' => 'fa-users',
                            'admins' => 'fa-user-shield',
                            'bookings' => 'fa-briefcase',
                            'subscribers' => 'fa-envelope-open-text',
                            'tours' => 'fa-map-location-dot',
                        ];
                        $metricLabels = [
                            'users' => 'Пользователи',
                            'admins' => 'Админы',
                            'bookings' => 'Бронирования',
                            'subscribers' => 'Подписки',
                            'tours' => 'Туры',
                        ];
                        foreach ($stats as $key => $value):
                    ?>
                        <div class="metric-card p-5">
                            <div class="flex items-center justify-between text-slate-500 text-xs uppercase tracking-[0.3em] mb-3">
                                <span><?php echo $metricLabels[$key]; ?></span>
                                <i class="fas <?php echo $metricIcons[$key]; ?> text-sky-500"></i>
                            </div>
                            <div class="heading-font text-3xl font-semibold text-slate-900"><?php echo number_format($value, 0, '.', ' '); ?></div>
                        </div>
                    <?php endforeach; ?>
                </section>

                <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="metric-card p-6">
                        <h2 class="heading-font text-2xl font-semibold text-slate-900 mb-4 flex items-center gap-2"><i class="fas fa-user-plus text-sky-500"></i>Новые пользователи</h2>
                        <?php if (!empty($recentUsers)): ?>
                            <div class="space-y-3">
                                <?php foreach ($recentUsers as $user): ?>
                                    <div class="rounded-xl border border-sky-100 bg-white px-4 py-3 flex items-center justify-between">
                                        <div>
                                            <p class="heading-font text-sm text-slate-900"><?php echo htmlspecialchars($user['name']); ?></p>
                                            <p class="text-xs text-slate-500"><?php echo htmlspecialchars($user['email']); ?></p>
                                        </div>
                                        <span class="text-xs text-slate-400 uppercase tracking-[0.3em]"><?php echo htmlspecialchars($user['reg_date']); ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-slate-500">Пока нет новых регистраций.</p>
                        <?php endif; ?>
                    </div>

                    <div class="metric-card p-6">
                        <h2 class="heading-font text-2xl font-semibold text-slate-900 mb-4 flex items-center gap-2"><i class="fas fa-briefcase text-sky-500"></i>Последние заявки</h2>
                        <?php if (!empty($recentBookings)): ?>
                            <div class="space-y-3">
                                <?php foreach ($recentBookings as $booking): ?>
                                    <div class="rounded-xl border border-sky-100 bg-white px-4 py-3">
                                        <div class="flex items-center justify-between">
                                            <p class="heading-font text-sm text-slate-900"><?php echo htmlspecialchars($booking['tour_title'] ?? 'Без названия'); ?></p>
                                            <span class="text-xs uppercase tracking-[0.3em] text-sky-500"><?php echo htmlspecialchars($booking['status'] ?? 'pending'); ?></span>
                                        </div>
                                        <p class="text-xs text-slate-500"><?php echo htmlspecialchars($booking['hotel_name'] ?? ''); ?> - <?php echo htmlspecialchars($booking['destination'] ?? ''); ?></p>
                                        <p class="text-xs text-slate-400 mt-1"><?php echo htmlspecialchars($booking['booking_date'] ?? ''); ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-slate-500">Заявки отсутствуют.</p>
                        <?php endif; ?>
                    </div>

                </section>

                <section class="metric-card p-6">
                    <h2 class="heading-font text-2xl font-semibold text-slate-900 mb-6 flex items-center gap-2"><i class="fas fa-toolbox text-sky-500"></i>Утилиты администратора</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <a href="view_database.php" class="rounded-xl bg-gradient-to-r from-sky-300 via-sky-400 to-sky-500 text-white px-4 py-3 font-medium shadow-md hover:shadow-lg transition flex items-center justify-between">
                            Просмотр базы данных <i class="fas fa-database"></i>
                        </a>
                        <a href="check_db.php" class="rounded-xl border border-sky-100 bg-white px-4 py-3 text-slate-700 font-medium flex items-center justify-between hover:bg-sky-50 transition">
                            Проверка БД <i class="fas fa-check-circle text-sky-500"></i>
                        </a>
                        <a href="create_db.php" class="rounded-xl border border-sky-100 bg-white px-4 py-3 text-slate-700 font-medium flex items-center justify-between hover:bg-sky-50 transition">
                            Создать БД <i class="fas fa-database text-sky-500"></i>
                        </a>
                        <a href="view_database.php" class="rounded-xl border border-sky-100 bg-white px-4 py-3 text-slate-700 font-medium flex items-center justify-between hover:bg-sky-50 transition">
                            Структура таблиц <i class="fas fa-table text-sky-500"></i>
                        </a>
                        <a href="set_admin_role.php" class="rounded-xl border border-sky-100 bg-white px-4 py-3 text-slate-700 font-medium flex items-center justify-between hover:bg-sky-50 transition">
                            Назначить администратора <i class="fas fa-user-crown text-sky-500"></i>
                        </a>
                    </div>
                </section>
            </div>
        </div>
    </main>
</body>
</html>