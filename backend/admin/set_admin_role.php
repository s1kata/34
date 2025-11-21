<?php
require_once __DIR__ . '/../config/config.php';

session_start();

if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? null) !== 'admin') {
    header('Location: ../../frontend/window/login.html');
    exit;
}

$message = null;
$messageType = 'info';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $userId = (int) $_POST['user_id'];

    if ($pdo) {
        try {
            $stmt = $pdo->prepare('UPDATE users SET role = :role WHERE id = :id');
            $stmt->execute([':role' => 'admin', ':id' => $userId]);

            $message = "Роль администратора назначена пользователю ID {$userId}.";
            $messageType = 'success';

            if ($userId === (int) $_SESSION['user_id']) {
                $_SESSION['user_role'] = 'admin';
                $message .= ' Ваши права обновлены.';
            }
        } catch (PDOException $e) {
            $message = 'Ошибка обновления роли: ' . $e->getMessage();
            $messageType = 'error';
        }
    } else {
        $message = 'База данных недоступна.';
        $messageType = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Назначение роли администратора | Travel Hub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Open+Sans:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-body: #f4f9ff;
            --accent-primary: #3ba3ff;
            --accent-secondary: #7bc4ff;
            --text-primary: #1f2a44;
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
            <a href="admin.php" class="flex items-center gap-3">
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
            <div class="max-w-3xl mx-auto space-y-8">
                <div class="text-center space-y-4">
                    <span class="eyebrow-badge inline-flex items-center gap-2"><i class="fas fa-user-crown"></i> Role Management</span>
                    <h1 class="heading-font text-3xl md:text-4xl font-bold text-slate-900">Назначение роли администратора</h1>
                    <p class="text-slate-600">Введите ID пользователя, чтобы выдать ему права администратора. Все изменения фиксируются в защищённой SQL базе.</p>
                </div>

                <?php if ($message): ?>
                    <div class="rounded-xl px-4 py-3 <?php echo $messageType === 'success' ? 'bg-emerald-50 border border-emerald-200 text-emerald-700' : 'bg-red-50 border border-red-200 text-red-600'; ?>">
                        <?php echo htmlspecialchars($message, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>
                    </div>
                <?php endif; ?>

                <section class="border border-sky-100 rounded-2xl bg-white p-8 shadow-xl shadow-sky-200/60">
                    <form method="POST" class="space-y-6">
                        <div>
                            <label for="user_id" class="block text-sm text-slate-600 mb-2">ID пользователя</label>
                            <input type="number" id="user_id" name="user_id" min="1" required class="w-full rounded-xl border border-sky-100 bg-white px-4 py-3 text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-300 focus:border-sky-300 transition" placeholder="Например, 42" />
                        </div>
                        <button type="submit" class="w-full heading-font uppercase tracking-[0.3em] text-xs bg-gradient-to-r from-rose-300 via-rose-400 to-amber-400 text-white py-3 rounded-xl shadow-md hover:shadow-lg transition">
                            Назначить роль admin
                        </button>
                    </form>
                </section>

                <div class="text-sm text-slate-500 text-center">
                    <p>Совет: используйте <code class="px-2 py-1 rounded bg-sky-50 border border-sky-100">view_database.php</code>, чтобы сверить ID пользователя перед назначением роли.</p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>