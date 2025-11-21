<?php
/**
 * Полный тест всех функций базы данных
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/config.php';

echo "=== Полный тест базы данных Travel Hub ===\n\n";

$errors = [];
$success = [];

// 1. Проверка подключения
echo "1. Проверка подключения...\n";
if (!$pdo) {
    $errors[] = "Подключение к базе данных не установлено";
    echo "   ✗ ОШИБКА: Подключение к базе данных не установлено\n";
    exit(1);
} else {
    $success[] = "Подключение к базе данных установлено";
    echo "   ✓ Подключение установлено\n";
}

// 2. Проверка таблиц
echo "\n2. Проверка существования таблиц...\n";
$tables = ['users', 'user_logs', 'bookings', 'newsletter_subscriptions'];
foreach ($tables as $table) {
    try {
        $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='$table'");
        if ($stmt->fetchColumn()) {
            echo "   ✓ Таблица '$table' существует\n";
            $success[] = "Таблица '$table' существует";
        } else {
            echo "   ✗ Таблица '$table' не найдена\n";
            $errors[] = "Таблица '$table' не найдена";
        }
    } catch (PDOException $e) {
        echo "   ✗ Ошибка проверки таблицы '$table': " . $e->getMessage() . "\n";
        $errors[] = "Ошибка проверки таблицы '$table'";
    }
}

// 3. Тест регистрации пользователя
echo "\n3. Тест регистрации пользователя...\n";
$testEmail = 'test_user_' . time() . '@example.com';
$testPassword = 'TestPassword123!';
$testName = 'Test User';

try {
    // Проверяем, что email не занят
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$testEmail]);
    if ($stmt->fetch()) {
        echo "   ⚠ Пользователь с таким email уже существует\n";
    }
    
    // Создаем пользователя (имитация регистрации)
    $hashedPassword = password_hash($testPassword, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)');
    $stmt->execute([$testName, $testEmail, $hashedPassword, 'user']);
    $userId = $pdo->lastInsertId();
    
    echo "   ✓ Пользователь зарегистрирован (ID: $userId)\n";
    $success[] = "Регистрация пользователя работает";
    
    // Тест входа (имитация process_login.php)
    $stmt = $pdo->prepare('SELECT id, name, email, password, role, status FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$testEmail]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($testPassword, $user['password'])) {
        echo "   ✓ Вход пользователя работает\n";
        $success[] = "Вход пользователя работает";
        
        // Обновляем last_login
        $stmt = $pdo->prepare('UPDATE users SET last_login = datetime("now") WHERE id = ?');
        $stmt->execute([$user['id']]);
        echo "   ✓ Обновление last_login работает\n";
    } else {
        echo "   ✗ Ошибка входа\n";
        $errors[] = "Ошибка входа пользователя";
    }
    
    // Удаляем тестового пользователя
    $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
    $stmt->execute([$userId]);
    echo "   ✓ Тестовый пользователь удален\n";
    
} catch (PDOException $e) {
    echo "   ✗ Ошибка: " . $e->getMessage() . "\n";
    $errors[] = "Ошибка теста регистрации: " . $e->getMessage();
}

// 4. Тест бронирования
echo "\n4. Тест бронирования...\n";
try {
    // Создаем пользователя
    $testEmail = 'booking_user_' . time() . '@example.com';
    $hashedPassword = password_hash('TestPassword123!', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)');
    $stmt->execute(['Booking User', $testEmail, $hashedPassword, 'user']);
    $userId = $pdo->lastInsertId();
    
    // Создаем бронирование
    $stmt = $pdo->prepare('INSERT INTO bookings (user_id, tour_title, hotel_name, destination, stars, nights, price, currency, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([
        $userId,
        'Test Tour Package',
        'Test Hotel',
        'Test Destination',
        5,
        7,
        50000.00,
        'RUB',
        'pending'
    ]);
    $bookingId = $pdo->lastInsertId();
    
    echo "   ✓ Бронирование создано (ID: $bookingId)\n";
    $success[] = "Создание бронирования работает";
    
    // Проверяем бронирование
    $stmt = $pdo->prepare('SELECT * FROM bookings WHERE id = ?');
    $stmt->execute([$bookingId]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($booking && $booking['tour_title'] === 'Test Tour Package') {
        echo "   ✓ Чтение бронирования работает\n";
    }
    
    // Удаляем тестовые данные
    $pdo->prepare('DELETE FROM bookings WHERE id = ?')->execute([$bookingId]);
    $pdo->prepare('DELETE FROM users WHERE id = ?')->execute([$userId]);
    echo "   ✓ Тестовые данные удалены\n";
    
} catch (PDOException $e) {
    echo "   ✗ Ошибка: " . $e->getMessage() . "\n";
    $errors[] = "Ошибка теста бронирования: " . $e->getMessage();
}

// 5. Тест подписки на рассылку
echo "\n5. Тест подписки на рассылку...\n";
try {
    $testEmail = 'subscribe_' . time() . '@example.com';
    
    // Проверяем, что email не подписан
    $stmt = $pdo->prepare('SELECT id FROM newsletter_subscriptions WHERE email = ? AND is_active = 1');
    $stmt->execute([$testEmail]);
    if ($stmt->fetch()) {
        echo "   ⚠ Email уже подписан\n";
    }
    
    // Добавляем подписку
    $stmt = $pdo->prepare('INSERT INTO newsletter_subscriptions (email, name, is_active) VALUES (?, ?, ?)');
    $stmt->execute([$testEmail, 'Test Subscriber', 1]);
    $subscriberId = $pdo->lastInsertId();
    
    echo "   ✓ Подписка создана (ID: $subscriberId)\n";
    $success[] = "Подписка на рассылку работает";
    
    // Удаляем тестовую подписку
    $pdo->prepare('DELETE FROM newsletter_subscriptions WHERE id = ?')->execute([$subscriberId]);
    echo "   ✓ Тестовая подписка удалена\n";
    
} catch (PDOException $e) {
    echo "   ✗ Ошибка: " . $e->getMessage() . "\n";
    $errors[] = "Ошибка теста подписки: " . $e->getMessage();
}

// 6. Проверка foreign keys
echo "\n6. Проверка внешних ключей...\n";
try {
    $stmt = $pdo->query("PRAGMA foreign_keys");
    $fkEnabled = $stmt->fetchColumn();
    if ($fkEnabled) {
        echo "   ✓ Внешние ключи включены\n";
        $success[] = "Внешние ключи включены";
    } else {
        echo "   ⚠ Внешние ключи отключены\n";
    }
} catch (PDOException $e) {
    echo "   ✗ Ошибка проверки внешних ключей: " . $e->getMessage() . "\n";
}

// Итоги
echo "\n" . str_repeat("=", 50) . "\n";
echo "ИТОГИ ТЕСТИРОВАНИЯ:\n";
echo "Успешно: " . count($success) . "\n";
echo "Ошибок: " . count($errors) . "\n\n";

if (empty($errors)) {
    echo "✓ ВСЕ ТЕСТЫ ПРОЙДЕНЫ УСПЕШНО!\n";
    echo "База данных работает идеально! ✓\n";
    exit(0);
} else {
    echo "✗ ОБНАРУЖЕНЫ ОШИБКИ:\n";
    foreach ($errors as $error) {
        echo "  - $error\n";
    }
    exit(1);
}


