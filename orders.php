<?php
session_start(); // ✅ ต้องอยู่บนสุดเสมอ
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// =======================================================
// ตั้งค่าเชื่อมต่อฐานข้อมูล
// =======================================================
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "admin_dashboard"; 

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// =======================================================
// เมื่อกด Submit มาจากฟอร์ม
// =======================================================
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // ✅ แปลง session เดิมให้เป็นรูปแบบใหม่ (กันหลุด login)
    if (!isset($_SESSION['user_id']) && isset($_SESSION['user'])) {
        $_SESSION['user_id'] = $_SESSION['user'];
        $_SESSION['username'] = $_SESSION['user'];
    }

    // ✅ ตรวจสอบว่าผู้ใช้ล็อกอินอยู่ไหม
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
        die("⚠️ กรุณาเข้าสู่ระบบก่อนสั่งออร์เดอร์");
    }

    // ✅ ดึงข้อมูลผู้ใช้จาก session
    $user_id  = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    

    $product_categories = $_POST['product_category'] ?? [];
    $product_items      = $_POST['products'] ?? []; 
    $quantities         = $_POST['quantity'] ?? [];
    $coupon_code        = $_POST['coupon_code'] ?? '';

    $order_date    = date('Y-m-d H:i:s');
    $status        = 'Pending'; 
    $customer_info = 'New Order - ข้อมูลลูกค้าเบื้องต้น'; 

    $conn->begin_transaction();
    $order_saved = false; 

    try {
        // -------------------------------------------------------
        // ✅ 1. บันทึกข้อมูลหลักของออร์เดอร์ (เพิ่ม user_id, username)
        // -------------------------------------------------------
        $sql_order = "
            INSERT INTO orders (user_id, username, order_date, customer_name, order_status, coupon_code)
            VALUES (?, ?, ?, ?, ?, ?)
        ";
        $stmt_order = $conn->prepare($sql_order);
        if (!$stmt_order) {
            throw new Exception("Prepare statement failed for orders: " . $conn->error);
        }

        $stmt_order->bind_param("isssss", $user_id, $username, $order_date, $customer_info, $status, $coupon_code);

        if (!$stmt_order->execute()) {
            throw new Exception("Order execution failed: " . $stmt_order->error);
        }

        $order_id = $conn->insert_id;
        $stmt_order->close();

        // -------------------------------------------------------
        // ✅ 2. บันทึกรายการสินค้าใน order_items
        // -------------------------------------------------------
        $sql_item = "INSERT INTO order_items (order_id, product_category, product_name, quantity)
                     VALUES (?, ?, ?, ?)";
        $stmt_item = $conn->prepare($sql_item);
        if (!$stmt_item) {
            throw new Exception("Prepare statement failed for order_items: " . $conn->error);
        }

        $item_count = 0;
        for ($i = 0; $i < count($product_items); $i++) {
            $category = $product_categories[$i] ?? '';
            $item     = $product_items[$i] ?? '';
            $qty      = (int)($quantities[$i] ?? 0);

            if (!empty($item) && $item != '--- เลือกสินค้า ---' && $qty > 0) {
                $stmt_item->bind_param("issi", $order_id, $category, $item, $qty);
                if (!$stmt_item->execute()) {
                    throw new Exception("Item execution failed: " . $stmt_item->error);
                }
                $item_count++;
            }
        }
        $stmt_item->close();

        // ✅ ต้องมีสินค้าอย่างน้อย 1 รายการ
        if ($item_count > 0) {
            $conn->commit();
            $order_saved = true;
        } else {
            throw new Exception("No valid items selected for order.");
        }

    } catch (Exception $e) {
        $conn->rollback();
        die("
        <div style='background:#f8d7da;color:#721c24;border:1px solid #f5c6cb;
                    padding:20px;margin:50px auto;max-width:800px;'>
            <h1>❌ Order Saving Failed (Transaction Rolled Back)</h1>
            <p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>
            <p>ตรวจสอบชื่อคอลัมน์ในตาราง orders และ order_items</p>
        </div>");
    }

    // ✅ 3. Redirect หลังบันทึกสำเร็จ
    if ($order_saved) {
        header("Location: order_success.php?id=" . $order_id);
        exit(); 
    } else {
        header("Location: cart.php?status=error");
        exit();
    }

} else {
    header("Location: cart.php");
    exit();
}

$conn->close();
?>
