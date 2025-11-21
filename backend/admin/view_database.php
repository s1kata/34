<?php
require_once __DIR__ . '/../config/config.php';

session_start();

if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? null) !== 'admin') {
    header('Location: ../../frontend/window/login.html');
    exit;
}

function displayTable(PDO $pdo, string $tableName, string $title): string
{
    $allowedTables = ['users', 'user_logs', 'bookings', 'newsletter_subscriptions', 'tours', 'tour_tags'];
    if (!in_array($tableName, $allowedTables, true)) {
        return '';
    }

    $html = "<section class='bg-white border border-sky-100 rounded-2xl p-6 mb-8 shadow-xl shadow-sky-200/60'>";
    $html .= "<h2 class='heading-font text-2xl font-semibold text-slate-900 mb-6 flex items-center gap-2'><i class='fas fa-table text-sky-500'></i>$title</h2>";

    try {
        $stmt = $pdo->query('SELECT * FROM `' . $tableName . '`');
        $rows = $stmt->fetchAll();

        if (empty($rows)) {
            $html .= "<p class='text-slate-500'>Таблица пуста.</p>";
        } else {
            $columns = array_keys($rows[0]);
            $html .= "<div class='overflow-x-auto'>";
            $html .= "<table class='min-w-full border-collapse'>";
            $html .= "<thead class='bg-sky-50'><tr>";
            foreach ($columns as $column) {
                $html .= "<th class='px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.28em] text-slate-500'>$column</th>";
            }
            $html .= "</tr></thead><tbody>";
            foreach ($rows as $row) {
                $html .= "<tr class='border-t border-sky-100 hover:bg-sky-50 transition'>";
                foreach ($columns as $column) {
                    $value = $row[$column];
                    if ($value === null || $value === '') {
                        $value = '<span class="text-slate-400">—</span>';
                    } else {
                        $value = htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                    }
                    $html .= "<td class='px-4 py-2 text-sm text-slate-700'>$value</td>";
                }
                $html .= '</tr>';
            }
            $html .= '</tbody></table></div>';
        }
    } catch (PDOException $e) {
        $html .= "<p class='text-red-500'>Ошибка получения данных: " . htmlspecialchars($e->getMessage(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</p>';
    }

    $html .= '</section>';

    return $html;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>База данных | Travel Hub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Open+Sans:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-body: #f4f9ff;
            --accent-primary: #3ba3ff;
            --accent-secondary: #7bc4ff;
            --text-primary: #1f2a44;
            --text-secondary: #4f5f78;
        }
        body {
            font-family: 'Open Sans', sans-serif;
            background: linear-gradient(180deg, #f8fbff 0%, #eff5ff 45%, #fdfdff 100%);
            color: var(--text-primary);
        }
        .heading-font { font-family: 'Montserrat', sans-serif; }
        .eyebrow-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
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
<body class="min-h-screen text-slate-900">
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
                <a href="admin.php" class="px-4 py-2 rounded-full border border-sky-100 text-slate-600 hover:bg-sky-50 transition">Панель</a>
                <a href="../scripts/logout.php" class="px-4 py-2 rounded-full bg-gradient-to-r from-sky-300 via-sky-400 to-sky-500 text-white shadow-md hover:shadow-lg transition">Выход</a>
            </div>
        </div>
    </header>

    <main class="py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-12 space-y-4">
                    <span class="eyebrow-badge inline-flex items-center gap-2">
                        <i class="fas fa-database"></i>
                        Database Viewer
                    </span>
                    <h1 class="heading-font text-3xl md:text-4xl font-bold text-slate-900">Просмотр защищенной SQL базы</h1>
                    <p class="text-slate-600 max-w-2xl mx-auto">Данные хранятся на выносной SQL-инстанции. Используйте таблицы ниже для аудита и оперативного контроля. Чувствительные операции выполняются с подготовленными выражениями.</p>
                </div>

                <?php if ($pdo): ?>
                    <?php
                        echo displayTable($pdo, 'users', 'Пользователи');
                        echo displayTable($pdo, 'tours', 'Туры');
                        echo displayTable($pdo, 'tour_tags', 'Теги туров');
                        echo displayTable($pdo, 'bookings', 'Бронирования');
                        echo displayTable($pdo, 'newsletter_subscriptions', 'Подписки на рассылку');
                        echo displayTable($pdo, 'user_logs', 'Логи пользователей');
                    ?>
                <?php else: ?>
                    <section class="bg-red-50 border border-red-200 rounded-2xl p-6 text-center">
                        <h2 class="heading-font text-2xl font-semibold text-red-600 mb-2">База данных недоступна</h2>
                        <p class="text-red-500">Проверьте подключения в файле <code>backend/config/config.php</code> и убедитесь, что SQL сервер принимает соединение.</p>
                    </section>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>
</html>