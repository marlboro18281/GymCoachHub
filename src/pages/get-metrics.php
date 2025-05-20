<?php
/**
 * Отримання метрик користувача для відображення
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

$user_id = $_SESSION['user_id'];
$response = ['success' => true];

// Отримання базових метрик користувача
$base_metrics = getUserBaseMetrics($mysqli, $user_id);
$response['base'] = $base_metrics;

// Отримання історії метрик
$history = getUserMetricsHistory($mysqli, $user_id, 10);
$response['history'] = $history;

// Якщо є історія, додаємо поточні метрики (останній запис)
if (!empty($history)) {
    $response['current'] = $history[0];
}

// Якщо є і базові, і поточні метрики, додаємо різницю
if ($base_metrics && !empty($history)) {
    $response['difference'] = calculateMetricsDifference($history[0], $base_metrics);
}

// Повернення результату
header('Content-Type: application/json');
echo json_encode($response);