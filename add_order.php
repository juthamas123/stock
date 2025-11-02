<?php
session_start();
require 'db_connect.php';

// ตรวจสอบว่ามีการล็อกอินหรือยัง
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // ดึงชื่อผู้ใช้จาก session
    $customer_name = $_SESSION['username'] ?? 'ไม่ทราบชื่อผู้ใช้';
    $customer_phone = $_POST['phone'] ?? '';
    $customer_address = $_POST['address'] ?? '';
    $order_date = date('Y-m-d H:i:s');

    // บันทึกออร์เดอร์ใหม่
    $stmt = $pdo->prepare("
        INSERT INTO orders (customer_name, customer_phone, customer_address, order_date, order_status)
        VALUES (?, ?, ?, ?, 'pending')
    ");
    $stmt->execute([$customer_name, $customer_phone, $customer_address, $order_date]);

    // ดึง order_id ล่าสุด
    $order_id = $pdo->lastInsertId();

    // ✅ บันทึกสินค้าแต่ละรายการ
    foreach ($_POST['products'] as $product_id => $quantity) {
        $stmt = $pdo->prepare("
            INSERT INTO order_items (order_id, product_name, quantity, product_category)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$order_id, $product_id, $quantity, 'แผ่นรั้วสำเร็จรูป']);
    }

    header("Location: orders.php");
    exit;
}
?>
