<?php
declare(strict_types=1);

$dbPath = __DIR__ . '/../config/user_management.db';

// Создаем директорию для базы данных, если её нет
$dbDir = dirname($dbPath);
if (!is_dir($dbDir)) {
    mkdir($dbDir, 0755, true);
}

$pdo = new PDO('sqlite:' . $dbPath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Проверяем, существует ли таблица users
try {
    $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='users'");
    if (!$stmt->fetchColumn()) {
        echo "Ошибка: Таблица users не существует. Запустите сначала backend/admin/init_sqlite.php\n";
        exit(1);
    }
} catch (PDOException $e) {
    echo "Ошибка проверки таблиц: " . $e->getMessage() . "\n";
    exit(1);
}

// Проверяем, не существует ли уже администратор с таким email
$checkStmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
$checkStmt->execute(['admin@example.com']);
if ($checkStmt->fetch()) {
    echo "Администратор с email admin@example.com уже существует.\n";
    exit(0);
}

$password = password_hash('TempPass123!', PASSWORD_DEFAULT);
$stmt = $pdo->prepare('INSERT INTO users (name,email,password,role) VALUES (?,?,?,?)');
$stmt->execute(['Admin', 'admin@example.com', $password, 'admin']);

echo "Администратор создан успешно!\n";
echo "Email: admin@example.com\n";
echo "Пароль: TempPass123!\n";