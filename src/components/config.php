<?php
// Start output buffering and session at the very beginning
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Enable error reporting for development (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connect to the database
//$mysqli = new mysqli("localhost", "root", "", "db_sport");
// Docker database connection
$mysqli = new mysqli("mysql", "root", "rootpassword", "db_sport");
// Set the character encoding
$mysqli->set_charset("utf8mb4");

if ($mysqli->connect_errno) {
    header("Content-Type: application/json; charset=utf-8");
    echo json_encode(["status" => "error", "message" => "Помилка підключення до БД: " . $mysqli->connect_error], JSON_UNESCAPED_UNICODE);
    exit;
}
?>