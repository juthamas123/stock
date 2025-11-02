<?php
require 'db_connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // ✅ บันทึกข้อมูลผู้ใช้ใหม่
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$username, $email, $password]);

    // ✅ ดึง ID ล่าสุดของผู้ใช้ (เพื่อใช้เก็บใน session)
    $user_id = $pdo->lastInsertId();

    // ✅ เก็บ session สำหรับระบบ login
    $_SESSION["user_id"] = $user_id;
    $_SESSION["username"] = $username;

    // ✅ ไปหน้าแรก หรือหน้า add_order.php ก็ได้
    header("Location: index.php");
    exit();
}
?>
