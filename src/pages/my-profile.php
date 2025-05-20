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
        <section class="profile-container">
            <div class="info-box">
                <h2>Інформація</h2>
                <label>Вага (кг): <input type="number" id="weight" min="0" step="0.1"></label>
                <label>Зріст (см): <input type="number" id="height" min="0"></label>
                <button id="update-btn">Оновити</button>
            </div>
            <img src="/src/images/ball.jpg" class="profile-img" alt="Профільне зображення">
            <div class="graphic-box">
                <h3>Графік прогресу</h3>
                <canvas id="progressChart"></canvas>
            </div>
        </section>

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