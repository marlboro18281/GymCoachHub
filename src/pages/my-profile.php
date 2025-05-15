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
            <ul id="saved-trainings"></ul>
            <a href="training.php">Додати нове тренування</a>
        </section>
    </main>

<?php include '../components/footer.php'; ?>