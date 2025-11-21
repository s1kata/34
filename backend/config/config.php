<?php
declare(strict_types=1);

/**
 * Global configuration and secure database bootstrap.
 *
 * This script loads environment variables from a local .env file (when present),
 * establishes a PDO connection using a SQL driver (MySQL by default) and exposes
 * the `$pdo` instance to the rest of the application.
 *
 * ⚠️ Place your database server outside the public web host or restrict remote
 * access by IP/SSL. Credentials should be stored in the .env file that is never
 * committed to the repository.
 */

// Harden PHP error display in production
if (!defined('APP_DEBUG')) {
    define('APP_DEBUG', filter_var(getenv('APP_DEBUG') ?: false, FILTER_VALIDATE_BOOLEAN));
}

if (!APP_DEBUG) {
    ini_set('display_errors', '0');
    error_reporting(0);
} else {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
}

/**
 * Lightweight .env loader (supports KEY=VALUE, comments with #, and quoted values)
 */
if (!function_exists('load_env_file')) {
    function load_env_file(string $path): void
    {
        if (!is_file($path) || !is_readable($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (!$lines) {
            return;
        }

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            if (!str_contains($line, '=')) {
                continue;
            }

            [$key, $value] = array_map('trim', explode('=', $line, 2));

            if ($key === '') {
                continue;
            }

            // Remove surrounding quotes
            if ($value !== '' && (($value[0] === '"' && substr($value, -1) === '"') || ($value[0] === "'" && substr($value, -1) === "'"))) {
                $value = substr($value, 1, -1);
            }

            putenv("$key=$value");
            $_ENV[$key] = $value;
        }
    }
}

$envPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env';
load_env_file($envPath);

$dbDriver = strtolower(getenv('DB_DRIVER') ?: 'sqlite');
$pdo = null;

try {
    if ($dbDriver === 'mysql') {
        $host = getenv('DB_HOST') ?: '127.0.0.1';
        $port = getenv('DB_PORT') ?: '3306';
        $database = getenv('DB_DATABASE') ?: 'travel_hub';
        $username = getenv('DB_USERNAME') ?: 'travel_user';
        $password = getenv('DB_PASSWORD') ?: '';
        $charset = getenv('DB_CHARSET') ?: 'utf8mb4';

        $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s', $host, $port, $database, $charset);
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        // Optional SSL certificate (for managed MySQL services)
        $sslCa = getenv('DB_SSL_CA') ?: null;
        if (!empty($sslCa) && defined('PDO::MYSQL_ATTR_SSL_CA')) {
            $options[PDO::MYSQL_ATTR_SSL_CA] = $sslCa;
        }

        $pdo = new PDO($dsn, $username, $password, $options);
    } elseif ($dbDriver === 'sqlite') {
        $sqlitePath = getenv('SQLITE_PATH');
        if (!$sqlitePath) {
            $sqlitePath = __DIR__ . DIRECTORY_SEPARATOR . 'user_management.db';
        } elseif (!str_starts_with($sqlitePath, DIRECTORY_SEPARATOR) && !preg_match('/^[A-Za-z]:\\\\/', $sqlitePath)) {
            // относительный путь — считаем от корня проекта
            $sqlitePath = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . $sqlitePath;
        }

        // Создаем директорию для базы данных, если её нет
        $dbDir = dirname($sqlitePath);
        if (!is_dir($dbDir)) {
            mkdir($dbDir, 0755, true);
        }

        $dsn = 'sqlite:' . $sqlitePath;
        $pdo = new PDO($dsn);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        
        // Автоматическая инициализация таблиц, если их нет
        try {
            $tablesCheck = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='users'");
            if ($tablesCheck->fetchColumn() === false) {
                // Таблицы не существуют, создаем их
                // Сначала включаем поддержку внешних ключей
                $pdo->exec("PRAGMA foreign_keys = ON");
                
                $schemaPath = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'schema_sqlite.sql';
                if (file_exists($schemaPath)) {
                    $schema = file_get_contents($schemaPath);
                    // Разделяем на отдельные запросы и выполняем каждый
                    $statements = array_filter(
                        array_map('trim', explode(';', $schema)),
                        function($stmt) {
                            $stmt = trim($stmt);
                            return !empty($stmt);
                        }
                    );
                    foreach ($statements as $statement) {
                        $statement = trim($statement);
                        if (!empty($statement)) {
                            $pdo->exec($statement);
                        }
                    }
                } else {
                    // Если файл схемы не найден, создаем таблицы напрямую
                    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
                        id INTEGER PRIMARY KEY AUTOINCREMENT,
                        name TEXT NOT NULL,
                        email TEXT NOT NULL UNIQUE,
                        password TEXT NOT NULL,
                        phone TEXT,
                        city TEXT,
                        age INTEGER,
                        gender TEXT,
                        passport_series TEXT,
                        passport_number TEXT,
                        passport_issued_by TEXT,
                        passport_issue_date DATE,
                        passport_expiry_date DATE,
                        role TEXT DEFAULT 'user',
                        reg_date DATETIME DEFAULT CURRENT_TIMESTAMP,
                        last_login DATETIME,
                        status TEXT DEFAULT 'active',
                        source TEXT DEFAULT 'website' CHECK(source IN ('website', 'app'))
                    )");
                    $pdo->exec("CREATE TABLE IF NOT EXISTS user_logs (
                        id INTEGER PRIMARY KEY AUTOINCREMENT,
                        user_id INTEGER,
                        action TEXT NOT NULL,
                        details TEXT,
                        ip_address TEXT,
                        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
                    )");
                    $pdo->exec("CREATE TABLE IF NOT EXISTS bookings (
                        id INTEGER PRIMARY KEY AUTOINCREMENT,
                        user_id INTEGER NOT NULL,
                        tour_title TEXT NOT NULL,
                        hotel_name TEXT NOT NULL,
                        destination TEXT NOT NULL,
                        stars INTEGER,
                        nights INTEGER,
                        price NUMERIC(10,2),
                        currency TEXT DEFAULT 'RUB',
                        meals TEXT,
                        departure_date DATE,
                        return_date DATE,
                        status TEXT DEFAULT 'pending',
                        booking_date DATETIME DEFAULT CURRENT_TIMESTAMP,
                        notes TEXT,
                        source TEXT DEFAULT 'website' CHECK(source IN ('website', 'app')),
                        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
                    )");
                    $pdo->exec("CREATE TABLE IF NOT EXISTS newsletter_subscriptions (
                        id INTEGER PRIMARY KEY AUTOINCREMENT,
                        email TEXT NOT NULL UNIQUE,
                        name TEXT,
                        subscribed_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                        is_active INTEGER DEFAULT 1,
                        preferences TEXT
                    )");
                }
            } else {
                // Таблица существует, но убедимся, что foreign_keys включены
                $pdo->exec("PRAGMA foreign_keys = ON");
            }
        } catch (PDOException $e) {
            error_log('[DB] Table initialization failed: ' . $e->getMessage());
        }
    } else {
        throw new RuntimeException(sprintf('Unsupported database driver "%s".', $dbDriver));
    }

    // Restrict SQL modes for safety if MySQL/MariaDB
    if ($dbDriver === 'mysql') {
        $pdo->exec("SET sql_mode = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");
    }
} catch (Throwable $e) {
    error_log('[DB] Connection failed: ' . $e->getMessage());
    $pdo = null;
}
?>