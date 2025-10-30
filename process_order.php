<?php
session_start();

// เปิดการแสดง error
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// =============================================
// 🔗 ตั้งค่าเชื่อมต่อฐานข้อมูล
// =============================================
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "admin_dashboard";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// =============================================
// 🧾 ตรวจสอบการล็อกอิน
// =============================================
// ✅ รองรับ session เดิม
if (!isset($_SESSION['user_id']) && isset($_SESSION['user'])) {
    $_SESSION['user_id'] = $_SESSION['user'];
    $_SESSION['username'] = $_SESSION['user'];
}

if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    die("⚠️ ผู้ใช้ยังไม่ได้ล็อกอิน");
}

// =============================================
// 🧩 เมื่อกด Submit มาจากฟอร์ม
// =============================================
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $product_categories = $_POST['product_category'] ?? [];
    $product_items = $_POST['products'] ?? [];
    $quantities = $_POST['quantity'] ?? [];
    $coupon_code = $_POST['coupon_code'] ?? '';
    $status = "pending";
    $order_date = date('Y-m-d H:i:s');
    $customer_info = "New Order - ข้อมูลลูกค้าเบื้องต้น";

    // ✅ ตรวจว่ามีสินค้าหรือไม่
    if (empty($product_items) || count(array_filter($quantities)) === 0) {
        die("<div style='background:#f8d7da;color:#721c24;padding:20px;border:1px solid #f5c6cb;'>
            ❌ ไม่พบสินค้าที่เลือก กรุณาเลือกสินค้าอย่างน้อย 1 รายการ
        </div>");
    }

    // =============================================
    // 1️⃣ เพิ่มข้อมูลลงตาราง orders
    // =============================================
    $sql_order = "INSERT INTO orders (customer_name, user_id, username, order_date, order_status, coupon_code)
                  VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_order = $conn->prepare($sql_order);
    if (!$stmt_order) {
        die("❌ SQL Prepare Error (orders): " . $conn->error);
    }

    $stmt_order->bind_param("sissss", $customer_info, $user_id, $username, $order_date, $status, $coupon_code);
    if (!$stmt_order->execute()) {
        die("❌ SQL Execute Error (orders): " . $stmt_order->error);
    }

    $order_id = $conn->insert_id;
    $stmt_order->close();

    // =============================================
    // 2️⃣ เพิ่มรายการสินค้าลง order_items
    // =============================================
    if (!empty($product_items) && !empty($quantities)) {
        $stmt_item = $conn->prepare("
            INSERT INTO order_items (order_id, product_category, product_name, quantity)
            VALUES (?, ?, ?, ?)
        ");
        if (!$stmt_item) {
            die("❌ SQL Prepare Error (order_items): " . $conn->error);
        }

        for ($i = 0; $i < count($product_items); $i++) {
            $category = $product_categories[$i] ?? '';
            $product = $product_items[$i] ?? '';
            $qty = intval($quantities[$i] ?? 0);

            if (!empty($product) && $qty > 0) {
                $stmt_item->bind_param("issi", $order_id, $category, $product, $qty);
                $stmt_item->execute();
            }
        }

        $stmt_item->close();
    }

    // =============================================
    // 3️⃣ กลับไปหน้าหลักหลังบันทึกสำเร็จ
    // =============================================
    header("Location: order_success.php?added=success");
    exit;

} else {
    die("❌ ไม่มีข้อมูลที่ส่งมา");
}

// ✅ หลังบันทึกสำเร็จ
if ($order_saved) {
    header("Location: order_success.php?id=" . $order_id);
    exit();
} else {
    header("Location: cart.php?status=error");
    exit();
}

$conn->close();


session_start();
require_once __DIR__ . '/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ✅ ดึงข้อมูลจากฟอร์ม
    $user_id = $_POST['user_id'] ?? 0;
    $username = $_POST['username'] ?? 'Guest';
    $coupon_code = $_POST['coupon_code'] ?? '';

    $products = $_POST['products'] ?? [];
    $quantities = $_POST['quantity'] ?? [];
    $categories = $_POST['product_category'] ?? [];

    // ✅ ตรวจสอบข้อมูลก่อน
    if (empty($products) || empty($quantities)) {
        die("❌ ไม่มีรายการสินค้า กรุณาเพิ่มสินค้าใหม่");
    }

    // ✅ เริ่มต้นการทำงานใน Transaction
    $pdo->beginTransaction();
    try {
        // ✅ 1. เพิ่มข้อมูลลงในตาราง orders
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, order_date, coupon_code) VALUES (?, NOW(), ?)");
        $stmt->execute([$user_id, $coupon_code]);

        // ✅ ดึง order_id ล่าสุด
        $order_id = $pdo->lastInsertId();

        // ✅ 2. เพิ่มสินค้าแต่ละรายการลงใน order_items
        $stmt_item = $pdo->prepare("
            INSERT INTO order_items (order_id, product_name, product_category, quantity)
            VALUES (?, ?, ?, ?)
        ");

        for ($i = 0; $i < count($products); $i++) {
            $product_name = trim($products[$i]);
            $product_category = trim($categories[$i] ?? '-');
            $quantity = intval($quantities[$i]);

            // ✅ ป้องกันไม่ให้บันทึก "ไม่มีรายการสินค้า"
            if ($product_name === 'ไม่มีรายการสินค้า' || $quantity <= 0) continue;

            $stmt_item->execute([$order_id, $product_name, $product_category, $quantity]);
        }

        // ✅ บันทึกการทำธุรกรรม
        $pdo->commit();

        // ✅ กลับไปหน้าประวัติการสั่งซื้อ
        header("Location: order_history.php");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "❌ เกิดข้อผิดพลาด: " . $e->getMessage();
    }
} else {
    echo "❌ ไม่มีข้อมูลที่ส่งเข้ามา";
}

for ($i = 0; $i < count($products); $i++) {
    $product_name = trim($products[$i]);
    $product_category = trim($categories[$i] ?? '-');
    $quantity = intval($quantities[$i]);

    if ($product_name === 'ไม่มีรายการสินค้า' || $quantity <= 0) continue;

    $stmt_item->execute([$order_id, $product_name, $product_category, $quantity]);
}
 
?>
