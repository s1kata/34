<?php
require_once __DIR__ . '/../config/config.php';

session_start();

if (!defined('REMEMBER_TOKEN_SALT')) {
    define('REMEMBER_TOKEN_SALT', getenv('AUTH_REMEMBER_SALT') ?: 'travelhub-remember-token');
}

$errors = [];
$successMessage = '';
$name = $email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $passwordValue = trim($_POST['password'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $age = (int) ($_POST['age'] ?? 0);
    $gender = trim($_POST['gender'] ?? '');

    if ($name === '') {
        $errors['name'] = 'Пожалуйста, введите имя.';
    } elseif (mb_strlen($name) > 60) {
        $errors['name'] = 'Имя не должно превышать 60 символов.';
    } elseif (!preg_match('/^[\p{L}\s\-]+$/u', $name)) {
        $errors['name'] = 'Имя может содержать только буквы и дефисы.';
    }

    if ($email === '') {
        $errors['email'] = 'Пожалуйста, введите email.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Пожалуйста, введите корректный email.';
    }

    if ($passwordValue === '') {
        $errors['password'] = 'Пожалуйста, введите пароль.';
    } elseif (mb_strlen($passwordValue) < 12) {
        $errors['password'] = 'Пароль должен содержать не менее 12 символов.';
    }

    if ($age < 0 || $age > 120) {
        $age = null;
    }

    $allowedGenders = ['male', 'female', 'other', 'prefer_not_to_say'];
    if (!in_array($gender, $allowedGenders, true)) {
        $gender = 'prefer_not_to_say';
    }

    if (empty($errors)) {
        try {
            if (!$pdo) {
                $errors['database'] = 'База данных недоступна.';
            } else {
                $duplicateFields = [];
                
                // Проверка email на дубликат
                $checkEmail = $pdo->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
                $checkEmail->execute([':email' => $email]);
                if ($checkEmail->fetch()) {
                    $duplicateFields[] = 'email';
                    $errors['email'] = 'Этот email уже зарегистрирован.';
                }
                
                // Проверка телефона на дубликат (если указан)
                if (!empty($phone)) {
                    $checkPhone = $pdo->prepare('SELECT id FROM users WHERE phone = :phone LIMIT 1');
                    $checkPhone->execute([':phone' => $phone]);
                    if ($checkPhone->fetch()) {
                        $duplicateFields[] = 'phone';
                        $errors['phone'] = 'Этот телефон уже зарегистрирован.';
                    }
                }
                
                // Если есть дубликаты, отправляем ошибку с флагом
                if (!empty($duplicateFields)) {
                    $errors['duplicate'] = true;
                } else {
                    // Если дубликатов нет, создаем пользователя
                    $hashedPassword = password_hash($passwordValue, PASSWORD_DEFAULT);
                    $insert = $pdo->prepare('INSERT INTO users (name, email, password, phone, city, age, gender, role) VALUES (:name, :email, :password, :phone, :city, :age, :gender, :role)');
                    $insert->execute([
                        ':name' => $name,
                        ':email' => $email,
                        ':password' => $hashedPassword,
                        ':phone' => $phone ?: null,
                        ':city' => $city ?: null,
                        ':age' => $age ?: null,
                        ':gender' => $gender,
                        ':role' => 'user',
                    ]);

                    // Редирект на страницу регистрации с параметром успеха
                    header('Location: /frontend/window/registration.html?success=1');
                    exit;
                }
            }
        } catch (PDOException $e) {
            $errors['database'] = 'Ошибка базы данных: ' . $e->getMessage();
        }
    }
}

if (!empty($errors)) {
    header('Location: /frontend/window/registration.html?data=' . urlencode(json_encode([
        'errors' => $errors,
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'city' => $city,
        'age' => $age,
        'gender' => $gender,
    ])));
    exit;
}