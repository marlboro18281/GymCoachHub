<?php
// Включаємо відображення помилок для розробки (прибрати в продакшені)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Запускаємо сесію
session_start();

// Підключення до бази даних
//$mysqli = new mysqli("localhost", "root", "", "db_sport");
//Підключення до бази docker
$mysqli = new mysqli("mysql", "root", "rootpassword", "db_sport");
if ($mysqli->connect_errno) {
    header("Content-Type: application/json; charset=utf-8");
    die(json_encode(["status" => "error", "message" => "Помилка підключення до БД: " . $mysqli->connect_error], JSON_UNESCAPED_UNICODE));
}

// Встановлюємо кодування
$mysqli->set_charset("utf8mb4");