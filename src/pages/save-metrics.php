<?php
/**
 * Обробка запитів на збереження метрик користувача (вага, зріст)
 */

// Підключення необхідних компонентів
session_start();
require_once '../components/config.php';
require_once '../components/metrics_functions.php';

// Перевірка авторизації користувача
if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Користувач не авторизований']);
    exit;
}

// Отримання даних з запиту
$input = json_decode(file_get_contents('php://input'), true);
$user_id = $_SESSION['user_id'];
$response = ['success' => false];

// Перевірка на наявність необхідних даних
if (isset($input['action']) && $input['action'] === 'update_metrics') {
    // Перевірка на правильність вхідних даних
    if (isset($input['weight']) && isset($input['height']) &&
        is_numeric($input['weight']) && is_numeric($input['height']) &&
        $input['weight'] > 0 && $input['height'] > 0) {

        $weight = (float) $input['weight'];
        $height = (int) $input['height'];
        $is_base = isset($input['is_base']) && $input['is_base'];

        if ($is_base) {
            // Збереження базових метрик
            $result = saveUserBaseMetrics($mysqli, $user_id, $weight, $height);
        } else {
            // Додавання запису в історію
            $result = addUserMetricsHistory($mysqli, $user_id, $weight, $height);
        }

        if ($result) {
            $response['success'] = true;
            $response['message'] = $is_base ? 'Базові метрики оновлено' : 'Нові метрики додано';

            // Повертаємо оновлені дані
            $base_metrics = getUserBaseMetrics($mysqli, $user_id);
            $history = getUserMetricsHistory($mysqli, $user_id, 1);

            $response['data'] = [
                'base' => $base_metrics,
                'current' => !empty($history) ? $history[0] : null,
            ];

            // Додаємо різницю, якщо є і базові, і поточні метрики
            if ($base_metrics && !empty($history)) {
                $response['data']['difference'] = calculateMetricsDifference($history[0], $base_metrics);
            }
        } else {
            $response['message'] = 'Помилка при збереженні даних';
        }
    } else {
        $response['message'] = 'Некоректні дані вимірювань';
    }
} else {
    $response['message'] = 'Некоректний запит';
}

// Повернення результату
header('Content-Type: application/json');
echo json_encode($response);