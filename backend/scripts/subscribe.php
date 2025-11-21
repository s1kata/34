<?php
require_once __DIR__ . '/../config/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Метод не разрешен']);
    exit;
}

$email = trim($_POST['email'] ?? '');
$name = trim($_POST['name'] ?? '');

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Введите корректный email']);
    exit;
}

try {
    // Проверяем, не подписан ли уже
    $stmt = $pdo->prepare("SELECT id FROM newsletter_subscriptions WHERE email = ? AND is_active = 1");
    $stmt->execute([$email]);

    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Этот email уже подписан на рассылку']);
        exit;
    }

    // Добавляем подписку
    $stmt = $pdo->prepare("INSERT INTO newsletter_subscriptions (email, name) VALUES (?, ?)");
    $stmt->execute([$email, $name]);

    echo json_encode(['success' => true, 'message' => 'Вы успешно подписались на рассылку!']);

} catch (PDOException $e) {
    error_log("Ошибка подписки: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Произошла ошибка. Попробуйте позже.']);
}
?>