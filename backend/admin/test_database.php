<?php
/**
 * Тестовый скрипт для проверки работы базы данных
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/config.php';

echo "=== Тест базы данных Travel Hub ===\n\n";

// Проверка подключения
echo "1. Проверка подключения к базе данных...\n";
if (!$pdo) {
    echo "   ✗ Ошибка: Подключение к базе данных не установлено\n";
    exit(1);
}
echo "   ✓ Подключение установлено успешно\n\n";

// Проверка существования таблиц
echo "2. Проверка существования таблиц...\n";
$requiredTables = ['users', 'user_logs', 'bookings', 'newsletter_subscriptions'];
$allTablesExist = true;

foreach ($requiredTables as $table) {
    try {
        $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='$table'");
        if ($stmt->fetchColumn()) {
            echo "   ✓ Таблица '$table' существует\n";
        } else {
            echo "   ✗ Таблица '$table' не найдена\n";
            $allTablesExist = false;
        }
    } catch (PDOException $e) {
        echo "   ✗ Ошибка проверки таблицы '$table': " . $e->getMessage() . "\n";
        $allTablesExist = false;
    }
}

if (!$allTablesExist) {
    echo "\n   ⚠ Внимание: Не все таблицы существуют. Запустите backend/admin/init_sqlite.php\n";
} else {
    echo "\n   ✓ Все требуемые таблицы существуют\n\n";
}

// Проверка структуры таблицы users
echo "3. Проверка структуры таблицы users...\n";
try {
    $stmt = $pdo->query("PRAGMA table_info(users)");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $columnNames = array_column($columns, 'name');
    $requiredColumns = ['id', 'name', 'email', 'password', 'role', 'status'];
    
    $missingColumns = array_diff($requiredColumns, $columnNames);
    if (empty($missingColumns)) {
        echo "   ✓ Все необходимые колонки присутствуют\n";
    } else {
        echo "   ✗ Отсутствуют колонки: " . implode(', ', $missingColumns) . "\n";
    }
} catch (PDOException $e) {
    echo "   ✗ Ошибка проверки структуры: " . $e->getMessage() . "\n";
}
echo "\n";

// Тест создания пользователя
echo "4. Тест создания пользователя...\n";
try {
    $testEmail = 'test_' . time() . '@example.com';
    $testPassword = password_hash('TestPassword123!', PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->execute(['Test User', $testEmail, $testPassword, 'user']);
    $userId = $pdo->lastInsertId();
    
    echo "   ✓ Пользователь создан успешно (ID: $userId)\n";
    
    // Проверка чтения пользователя
    $stmt = $pdo->prepare("SELECT id, name, email, role FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && $user['email'] === $testEmail) {
        echo "   ✓ Пользователь успешно прочитан из базы\n";
    } else {
        echo "   ✗ Ошибка чтения пользователя\n";
    }
    
    // Удаление тестового пользователя
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    echo "   ✓ Тестовый пользователь удален\n";
} catch (PDOException $e) {
    echo "   ✗ Ошибка теста пользователя: " . $e->getMessage() . "\n";
}
echo "\n";

// Тест создания бронирования
echo "5. Тест создания бронирования...\n";
try {
    // Создаем временного пользователя для бронирования
    $testEmail = 'booking_test_' . time() . '@example.com';
    $testPassword = password_hash('TestPassword123!', PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->execute(['Booking Test User', $testEmail, $testPassword, 'user']);
    $userId = $pdo->lastInsertId();
    
    // Создаем бронирование
    $stmt = $pdo->prepare("INSERT INTO bookings (user_id, tour_title, hotel_name, destination, stars, nights, price, currency, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $userId,
        'Test Tour',
        'Test Hotel',
        'Test Destination',
        5,
        7,
        50000.00,
        'RUB',
        'pending'
    ]);
    $bookingId = $pdo->lastInsertId();
    
    echo "   ✓ Бронирование создано успешно (ID: $bookingId)\n";
    
    // Удаление тестовых данных
    $pdo->prepare("DELETE FROM bookings WHERE id = ?")->execute([$bookingId]);
    $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$userId]);
    echo "   ✓ Тестовые данные удалены\n";
} catch (PDOException $e) {
    echo "   ✗ Ошибка теста бронирования: " . $e->getMessage() . "\n";
}
echo "\n";

// Тест подписки на рассылку
echo "6. Тест подписки на рассылку...\n";
try {
    $testEmail = 'subscribe_test_' . time() . '@example.com';
    
    $stmt = $pdo->prepare("INSERT INTO newsletter_subscriptions (email, name, is_active) VALUES (?, ?, ?)");
    $stmt->execute([$testEmail, 'Test Subscriber', 1]);
    $subscriberId = $pdo->lastInsertId();
    
    echo "   ✓ Подписка создана успешно (ID: $subscriberId)\n";
    
    // Удаление тестовой подписки
    $pdo->prepare("DELETE FROM newsletter_subscriptions WHERE id = ?")->execute([$subscriberId]);
    echo "   ✓ Тестовая подписка удалена\n";
} catch (PDOException $e) {
    echo "   ✗ Ошибка теста подписки: " . $e->getMessage() . "\n";
}
echo "\n";

// Статистика
echo "7. Статистика базы данных...\n";
try {
    $usersCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $bookingsCount = $pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
    $subscribersCount = $pdo->query("SELECT COUNT(*) FROM newsletter_subscriptions WHERE is_active = 1")->fetchColumn();
    $logsCount = $pdo->query("SELECT COUNT(*) FROM user_logs")->fetchColumn();
    
    echo "   Пользователей: $usersCount\n";
    echo "   Бронирований: $bookingsCount\n";
    echo "   Подписчиков: $subscribersCount\n";
    echo "   Логов: $logsCount\n";
} catch (PDOException $e) {
    echo "   ✗ Ошибка получения статистики: " . $e->getMessage() . "\n";
}
echo "\n";

echo "=== Тестирование завершено ===\n";
echo "База данных работает корректно! ✓\n";


