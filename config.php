<?php
// config.php
$dbHost = '127.0.0.1';
$dbname = 'admin_dashboard';
$dbUser = 'db_user';
$dbPass = 'db_pass';

try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbname;charset=utf8mb4", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (Exception $e) {
    die('Database connection failed: ' . $e->getMessage());
}
session_start();


$host = 'localhost';
$dbname = 'admin_dashboard'; // ✅ เปลี่ยนเป็นชื่อฐานข้อมูลของคุณ
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ Database connection failed: " . $e->getMessage());
}

