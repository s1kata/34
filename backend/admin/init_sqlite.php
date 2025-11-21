<?php
declare(strict_types=1);

$dbPath = __DIR__ . '/../config/user_management.db';
$schemaPath = __DIR__ . '/../../database/schema_sqlite.sql';

// Создаем директорию для базы данных, если её нет
$dbDir = dirname($dbPath);
if (!is_dir($dbDir)) {
    mkdir($dbDir, 0755, true);
}

if (!file_exists($schemaPath)) {
    exit("Не найден файл схемы {$schemaPath}\n");
}

try {
    $pdo = new PDO("sqlite:$dbPath");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $schema = file_get_contents($schemaPath);
    
    // Разделяем схему на отдельные запросы
    $statements = array_filter(
        array_map('trim', explode(';', $schema)),
        function($stmt) {
            return !empty($stmt);
        }
    );
    
    foreach ($statements as $statement) {
        if (!empty(trim($statement))) {
            $pdo->exec($statement);
        }
    }
    
    echo "Таблицы созданы успешно!\n";
    
    // Проверяем создание таблиц
    $tables = ['users', 'user_logs', 'bookings', 'newsletter_subscriptions'];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='$table'");
        if ($stmt->fetchColumn()) {
            echo "✓ Таблица '$table' существует\n";
        } else {
            echo "✗ Таблица '$table' не найдена\n";
        }
    }
} catch (PDOException $e) {
    exit("Ошибка: {$e->getMessage()}\n");
}