<?php
include '../components/auth_check.php';
$page_title = "Мій профіль";
$user_id = $_SESSION['user_id'];
$phone_number = $_SESSION['user_number'];

// Додаткова перевірка даних користувача в базі
$stmt = $mysqli->prepare("SELECT user_name FROM users WHERE id = ? AND phone_number = ?");

if ($stmt) {
    $stmt->bind_param("is", $user_id, $phone_number);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $username = htmlspecialchars($row['user_name'], ENT_QUOTES, 'UTF-8');
    } else {
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit();
    }
    $stmt->close();
} else {
    $errors[] = "Помилка підготовки запиту: " . $mysqli->error;
}
?>
<?php include '../components/header.php'; ?>
<?php include '../components/navbar.php'; ?>

    <link rel="stylesheet" href="/src/styles/my-profile.css">
    <header>
        <p class="tagline">Вітаємо, <?= $username ?>!</p>
    </header>

    <main class="container">
        <main class="container">
            <section class="profile-container">
                <div class="info-box">
                    <h2>Базова інформація</h2>
                    <?php
                    require_once '../components/metrics_functions.php';

                    // Отримання базових метрик користувача
                    $base_metrics = getUserBaseMetrics($mysqli, $user_id);

                    // Отримання останнього запису історії
                    $history = getUserMetricsHistory($mysqli, $user_id, 1);
                    $current_metrics = !empty($history) ? $history[0] : null;

                    // Розрахунок різниці метрик
                    $diff = null;
                    if ($base_metrics && $current_metrics) {
                        $diff = calculateMetricsDifference($current_metrics, $base_metrics);
                    }
                    ?>

                    <div class="metrics-container">
                        <div class="base-metrics">
                            <h3>Базові показники</h3>
                            <?php if ($base_metrics): ?>
                                <div class="metric-item">
                                    <span class="metric-label">Вага:</span>
                                    <span class="metric-value"><span id="base-weight"><?= htmlspecialchars($base_metrics['weight']) ?></span> кг</span>
                                </div>
                                <div class="metric-item">
                                    <span class="metric-label">Зріст:</span>
                                    <span class="metric-value"><span id="base-height"><?= htmlspecialchars($base_metrics['height']) ?></span> см</span>
                                </div>
                                <button id="base-metrics-btn" class="btn-secondary">Змінити базові показники</button>
                            <?php else: ?>
                                <p class="no-data">Базові показники не встановлені</p>
                                <button id="base-metrics-btn" class="btn-primary">Встановити базові показники</button>
                            <?php endif; ?>
                        </div>

                        <div class="current-metrics">
                            <h3>Поточні показники</h3>
                            <?php if ($current_metrics): ?>
                                <div class="metric-item">
                                    <span class="metric-label">Вага:</span>
                                    <span class="metric-value">
                                <?= htmlspecialchars($current_metrics['weight']) ?> кг
                                <?php if ($diff): ?>
                                    <span id="weight-diff" class="<?= $diff['weight'] > 0 ? 'increased' : ($diff['weight'] < 0 ? 'decreased' : '') ?>">
                                        <?= $diff['weight'] > 0 ? '+' . $diff['weight'] : $diff['weight'] ?>
                                    </span>
                                <?php endif; ?>
                            </span>
                                </div>
                                <div class="metric-item">
                                    <span class="metric-label">Зріст:</span>
                                    <span class="metric-value">
                                <?= htmlspecialchars($current_metrics['height']) ?> см
                                <?php if ($diff): ?>
                                    <span id="height-diff" class="<?= $diff['height'] > 0 ? 'increased' : ($diff['height'] < 0 ? 'decreased' : '') ?>">
                                        <?= $diff['height'] > 0 ? '+' . $diff['height'] : $diff['height'] ?>
                                    </span>
                                <?php endif; ?>
                            </span>
                                </div>
                                <p class="recorded-at">Записано: <?= date('d.m.Y H:i', strtotime($current_metrics['recorded_at'])) ?></p>
                            <?php else: ?>
                                <p class="no-data">Поточні показники не записані</p>
                            <?php endif; ?>
                            <button id="update-btn" class="btn-primary">Оновити показники</button>
                        </div>
                    </div>
                </div>

                <img src="/src/images/ball.jpg" class="profile-img" alt="Профільне зображення">

                <div class="graphic-box">
                    <h3>Графік прогресу</h3>
                    <!-- Canvas для графіка з Chart.js -->
                    <canvas id="progressChart"></canvas>

                    <!-- Таблиця з історією метрик -->
                    <div class="metrics-history">
                        <h4>Історія змін</h4>
                        <div class="table-responsive">
                            <table class="metrics-table" id="metrics-table">
                                <thead>
                                <tr>
                                    <th>Дата</th>
                                    <th>Вага</th>
                                    <th>Зріст</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $full_history = getUserMetricsHistory($mysqli, $user_id);
                                if (!empty($full_history)):
                                    foreach ($full_history as $record):
                                        ?>
                                        <tr>
                                            <td><?= date('d.m.Y H:i', strtotime($record['recorded_at'])) ?></td>
                                            <td><?= htmlspecialchars($record['weight']) ?> кг</td>
                                            <td><?= htmlspecialchars($record['height']) ?> см</td>
                                        </tr>
                                    <?php
                                    endforeach;
                                else:
                                    ?>
                                    <tr>
                                        <td colspan="3" class="no-data">Немає записів в історії</td>
                                    </tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Модальне вікно для додавання/оновлення метрик -->
            <div id="metrics-modal" class="modal">
                <div class="modal-content">
                    <span id="modal-close" class="close">&times;</span>
                    <h2 id="modal-title">Оновлення показників</h2>
                    <form id="metrics-form" data-is-base="false">
                        <div class="form-group">
                            <label for="weight">Вага (кг):</label>
                            <input type="number" id="weight" name="weight" min="0" step="0.1" required>
                        </div>
                        <div class="form-group">
                            <label for="height">Зріст (см):</label>
                            <input type="number" id="height" name="height" min="0" required>
                        </div>
                        <button type="submit" class="btn-primary">Зберегти</button>
                    </form>
                </div>
            </div>

            <!-- Елемент для відображення повідомлень -->
            <div id="message" class="message" style="display: none;"></div>

            <!-- Підключення Chart.js для графіків -->
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <!-- Підключення скрипту для метрик -->
            <script src="/src/js/metrics.js"></script>


        <section class="trainings">
            <h2>Ваші тренування</h2>

            <?php
            // Отримуємо дані про тренування користувача з трьох таблиць

            // Фітнес
            $fitness_data = [];
            $sql_fitness = "SELECT * FROM fitness WHERE user_id = ?";
            $stmt_fitness = $mysqli->prepare($sql_fitness); // Готуємо запит до бази для таблиці fitness
            if ($stmt_fitness) {
                $stmt_fitness->bind_param("i", $user_id); // Передаємо id користувача у запит
                $stmt_fitness->execute(); // Виконуємо запит
                $result_fitness = $stmt_fitness->get_result(); // Отримуємо результат
                if ($result_fitness->num_rows > 0) {
                    $fitness_data = $result_fitness->fetch_assoc(); // Зберігаємо дані у масив
                }
                $stmt_fitness->close(); // Закриваємо запит
            }

            // Силові тренування
            $hardwork_data = [];
            $sql_hardwork = "SELECT * FROM hardwork WHERE user_id = ?";
            $stmt_hardwork = $mysqli->prepare($sql_hardwork); // Готуємо запит до бази для таблиці hardwork
            if ($stmt_hardwork) {
                $stmt_hardwork->bind_param("i", $user_id); // Передаємо id користувача у запит
                $stmt_hardwork->execute(); // Виконуємо запит
                $result_hardwork = $stmt_hardwork->get_result(); // Отримуємо результат
                if ($result_hardwork->num_rows > 0) {
                    $hardwork_data = $result_hardwork->fetch_assoc(); // Зберігаємо дані у масив
                }
                $stmt_hardwork->close(); // Закриваємо запит
            }

            // Пілатес
            $pilates_data = [];
            $sql_pilates = "SELECT * FROM pilates WHERE user_id = ?";
            $stmt_pilates = $mysqli->prepare($sql_pilates); // Готуємо запит до бази для таблиці pilates
            if ($stmt_pilates) {
                $stmt_pilates->bind_param("i", $user_id); // Передаємо id користувача у запит
                $stmt_pilates->execute(); // Виконуємо запит
                $result_pilates = $stmt_pilates->get_result(); // Отримуємо результат
                if ($result_pilates->num_rows > 0) {
                    $pilates_data = $result_pilates->fetch_assoc(); // Зберігаємо дані у масив
                }
                $stmt_pilates->close(); // Закриваємо запит
            }

            // Функція для отримання назв вправ з таблиці *_data
            function getExerciseTitles($table) {
                global $mysqli;
                $titles = [];
                $sql = "SELECT data_id, title FROM {$table}_data"; // Запит для отримання назв вправ
                $result = $mysqli->query($sql); // Виконуємо запит
                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        $titles[$row['data_id']] = $row['title']; // Зберігаємо id та назву вправи у масив
                    }
                }
                return $titles; // Повертаємо масив назв вправ
            }

            // Отримуємо назви вправ для кожної дисципліни
            $fitness_titles = getExerciseTitles('fitness');
            $hardwork_titles = getExerciseTitles('hardwork');
            $pilates_titles = getExerciseTitles('pilates');
            ?>

            <div class="training-stats">
                <!-- Фітнес -->
                <div class="discipline-card">
                    <h3>Фітнес</h3>
                    <?php // Перевіряємо, чи є дані по фітнесу
                    if (!empty($fitness_data)): ?>
                        <div class="table-responsive">
                            <table class="training-table">
                                <thead>
                                <tr>
                                    <th>Вправа</th>
                                    <th>Кількість</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                // Перебираємо всі дані по фітнесу
                                foreach ($fitness_data as $key => $value):
                                    // Пропускаємо id та user_id
                                    if ($key != 'id' && $key != 'user_id'): ?>
                                        <tr>
                                            <!-- Виводимо назву вправи або ключ, якщо назви немає -->
                                            <td><?= isset($fitness_titles[$key]) ? $fitness_titles[$key] : $key ?></td>
                                            <!-- Виводимо кількість -->
                                            <td><?= $value ?></td>
                                        </tr>
                                    <?php endif;
                                endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <!-- Якщо даних немає, показуємо повідомлення -->
                        <p class="no-data">Ви ще не виконували фітнес-тренування</p>
                    <?php endif; ?>
                </div>

                <!-- Силові тренування -->
                <div class="discipline-card">
                    <h3>Силові тренування</h3>
                    <?php // Перевіряємо, чи є дані по силовим тренуванням
                    if (!empty($hardwork_data)): ?>
                        <div class="table-responsive">
                            <table class="training-table">
                                <thead>
                                <tr>
                                    <th>Вправа</th>
                                    <th>Кількість</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                // Перебираємо всі дані по силовим тренуванням
                                foreach ($hardwork_data as $key => $value):
                                    // Пропускаємо id та user_id
                                    if ($key != 'id' && $key != 'user_id' ): ?>
                                        <tr>
                                            <!-- Виводимо назву вправи або ключ, якщо назви немає -->
                                            <td><?= isset($hardwork_titles[$key]) ? $hardwork_titles[$key] : $key ?></td>
                                            <!-- Виводимо кількість -->
                                            <td><?= $value ?></td>
                                        </tr>
                                    <?php endif;
                                endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <!-- Якщо даних немає, показуємо повідомлення -->
                        <p class="no-data">Ви ще не виконували силові тренування</p>
                    <?php endif; ?>
                </div>

                <!-- Пілатес -->
                <div class="discipline-card">
                    <h3>Пілатес</h3>
                    <?php // Перевіряємо, чи є дані по пілатесу
                    if (!empty($pilates_data)): ?>
                        <div class="table-responsive">
                            <table class="training-table">
                                <thead>
                                <tr>
                                    <th>Вправа</th>
                                    <th>Кількість</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                // Перебираємо всі дані по пілатесу
                                foreach ($pilates_data as $key => $value):
                                    // Пропускаємо id та user_id
                                    if ($key != 'id' && $key != 'user_id'): ?>
                                        <tr>
                                            <!-- Виводимо назву вправи або ключ, якщо назви немає -->
                                            <td><?= isset($pilates_titles[$key]) ? $pilates_titles[$key] : $key ?></td>
                                            <!-- Виводимо кількість -->
                                            <td><?= $value ?></td>
                                        </tr>
                                    <?php endif;
                                endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <!-- Якщо даних немає, показуємо повідомлення -->
                        <p class="no-data">Ви ще не виконували тренування з пілатесу</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Кнопка для додавання нового тренування -->
            <a href="/training.php" class="add-training-btn">Додати нове тренування</a>
        </section>
    </main>

<?php include '../components/footer.php'; ?>