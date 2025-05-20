<?php
/**
 * Функції для роботи з фізичними параметрами користувача (вага, зріст)
 */

/**
 * Отримання базових метрик користувача
 *
 * @param mysqli $mysqli З'єднання з базою даних
 * @param int $user_id ID користувача
 * @return array|null Базові метрики або null, якщо не знайдено
 */
function getUserBaseMetrics($mysqli, $user_id) {
    $stmt = $mysqli->prepare("SELECT weight, height, created_at, updated_at FROM user_base_metrics WHERE user_id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $metrics = $result->fetch_assoc();
        $stmt->close();
        return $metrics;
    }
    return null;
}

/**
 * Збереження або оновлення базових метрик користувача
 *
 * @param mysqli $mysqli З'єднання з базою даних
 * @param int $user_id ID користувача
 * @param float $weight Вага
 * @param int $height Зріст
 * @return bool Результат операції
 */
function saveUserBaseMetrics($mysqli, $user_id, $weight, $height) {
    $stmt = $mysqli->prepare("
        INSERT INTO user_base_metrics (user_id, weight, height)
        VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE weight = ?, height = ?
    ");

    if ($stmt) {
        $stmt->bind_param("ididi", $user_id, $weight, $height, $weight, $height);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    return false;
}

/**
 * Додавання запису історії метрик користувача
 *
 * @param mysqli $mysqli З'єднання з базою даних
 * @param int $user_id ID користувача
 * @param float $weight Вага
 * @param int $height Зріст
 * @return bool Результат операції
 */
function addUserMetricsHistory($mysqli, $user_id, $weight, $height) {
    $stmt = $mysqli->prepare("
        INSERT INTO user_metrics_history (user_id, weight, height)
        VALUES (?, ?, ?)
    ");

    if ($stmt) {
        $stmt->bind_param("idi", $user_id, $weight, $height);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    return false;
}

/**
 * Отримання історії метрик користувача (останні N записів)
 *
 * @param mysqli $mysqli З'єднання з базою даних
 * @param int $user_id ID користувача
 * @param int $limit Максимальна кількість записів
 * @return array Історія метрик
 */
function getUserMetricsHistory($mysqli, $user_id, $limit = 10) {
    $stmt = $mysqli->prepare("
        SELECT weight, height, recorded_at
        FROM user_metrics_history
        WHERE user_id = ?
        ORDER BY recorded_at DESC
        LIMIT ?
    ");

    if ($stmt) {
        $stmt->bind_param("ii", $user_id, $limit);
        $stmt->execute();
        $result = $stmt->get_result();

        $history = [];
        while ($row = $result->fetch_assoc()) {
            $history[] = $row;
        }

        $stmt->close();
        return $history;
    }
    return [];
}

/**
 * Обчислення різниці між поточними та базовими метриками
 *
 * @param array $current Поточні метрики
 * @param array $base Базові метрики
 * @return array Різниця метрик
 */
function calculateMetricsDifference($current, $base) {
    if (!$current || !$base) {
        return ['weight' => 0, 'height' => 0];
    }

    return [
        'weight' => round($current['weight'] - $base['weight'], 2),
        'height' => $current['height'] - $base['height']
    ];
}