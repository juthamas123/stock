<?php
session_start();
require_once __DIR__ . '/db_connect.php';

// ✅ ตรวจสอบว่าผู้ใช้ล็อกอินหรือยัง
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

// ✅ ฟังก์ชัน Map ชื่อสินค้า
$product_name_map = [
    'AN001' => 'แผ่นรั้วสำเร็จรูปแบบลิ่ม ขนาด 2.91',
    'AN003' => 'แผ่นรั้วสำเร็จรูปแบบเรียบ ขนาด 2.91',
    'AN002' => 'แผ่นรั้วสำเร็จรูปแบบลิ่ม ขนาด 2.42',
    'AN004' => 'แผ่นรั้วสำเร็จรูปแบบเรียบ ขนาด 2.42',
    'AM001' => 'ทับหลังครอบ 2.85',
    'AM003' => 'ทับหลังตัวที 2.91',
    'AM002' => 'ทับหลังครอบ 2.42',
    'AK001' => 'เสารั้วไอ15 2.00',
    'AK002' => 'เสารั้วไอ15 2.20',
    'AK003' => 'เสารั้วไอ15 2.50',
    'AK004' => 'เสารั้วไอ15 2.75',
    'AK005' => 'เสารั้วไอ15 3.00',
    'AK006' => 'เสารั้วไอ15 3.50',
    'AK007' => 'เสารั้วไอ15 4.00',
    'AK008' => 'เสารั้วไอ15 5.00',
    'AK009' => 'เสารั้วไอ15 6.00',
    'AP001' => 'เสาเข็มไอ18*18 2.00',
    'AP002' => 'เสาเข็มไอ18*18 3.00',
    'AP003' => 'เสาเข็มไอ18*18 4.00',
    'AP004' => 'เสาเข็มไอ18*18 5.00',
    'AP005' => 'เสาเข็มไอ18*18 6.00',
    'AR001' => 'แผ่นกันดิน ขนาด 1.00',
    'AR002' => 'แผ่นกันดิน ขนาด 1.42',
    'AR003' => 'แผ่นกันดิน ขนาด 2.00',
    'AR004' => 'แผ่นกันดิน ขนาด 2.42',
    'AR005' => 'แผ่นกันดิน ขนาด 2.91',
    'AR006' => 'แผ่นกันดิน ขนาด 3.00',
    'AR007' => 'แผ่นกันดิน ขนาด 3.50',
];

// ✅ ถ้ามีการลบออเดอร์
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_order_id'])) {
    $order_id = intval($_POST['delete_order_id']);

    // ลบเฉพาะของผู้ใช้คนนี้เท่านั้น (ป้องกันลบของคนอื่น)
    $stmt = $pdo->prepare("SELECT user_id FROM orders WHERE order_id = ?");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($order && $order['user_id'] == $userId) {
        // ✅ ลบข้อมูลใน order_items ก่อน
        $pdo->prepare("DELETE FROM order_items WHERE order_id = ?")->execute([$order_id]);
        // ✅ ลบข้อมูลใน orders
        $pdo->prepare("DELETE FROM orders WHERE order_id = ?")->execute([$order_id]);
        $message = "✅ ลบออเดอร์เรียบร้อยแล้ว";
    } else {
        $message = "❌ ไม่สามารถลบออเดอร์นี้ได้";
    }
}

// ✅ ดึงข้อมูลจาก orders + order_items + users
$stmt = $pdo->prepare("
    SELECT 
        o.order_id, 
        o.order_date, 
        o.coupon_code,
        u.username,
        oi.product_name,
        oi.product_category,
        oi.quantity
    FROM orders o
    JOIN order_items oi ON o.order_id = oi.order_id
    JOIN users u ON o.user_id = u.id
    WHERE o.user_id = ?
      AND oi.product_name != 'ไม่มีรายการสินค้า'
    ORDER BY o.order_date DESC, o.order_id DESC
");
$stmt->execute([$userId]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ✅ จัดกลุ่มตาม order_id
$orders = [];
foreach ($rows as $r) {
    $oid = $r['order_id'];
    if (!isset($orders[$oid])) {
        $orders[$oid] = [
            'date' => $r['order_date'],
            'username' => $r['username'],
            'note' => $r['coupon_code'] ?? '',
            'items' => []
        ];
    }
    $orders[$oid]['items'][] = $r;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="utf-8">
<title>ประวัติการสั่งซื้อของฉัน | My STOCK</title>
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
body {
    background: #f8f9fa;
    font-family: "Prompt", sans-serif;
}
table {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
}
th {
    background: #198754;
    color: white;
    text-align: center;
}
td { vertical-align: middle; }
.product-list { list-style-type: "• "; padding-left: 20px; margin: 0; }
.note-box {
    background: #f1f3f5;
    border-radius: 6px;
    padding: 8px 10px;
    color: #333;
}
.btn-delete {
    background: #dc3545;
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 5px 10px;
}
.btn-delete:hover {
    background: #b02a37;
}
</style>
</head>
<body>

<div class="container mt-5 mb-5">
    <!-- ✅ ปุ่มย้อนกลับ -->
    <div class="mb-3">
        <a href="cart.php" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> ย้อนกลับ
        </a>
    </div>

    <h3 class="text-center mb-4 text-success">📦 ประวัติการสั่งซื้อของฉัน</h3>

    <!-- ✅ แสดงข้อความแจ้งเตือน -->
    <?php if (!empty($message)): ?>
        <div class="alert alert-info text-center"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <?php if (empty($orders)): ?>
        <div class="alert alert-warning text-center">ยังไม่มีประวัติการสั่งซื้อ</div>
    <?php else: ?>
        <table class="table table-bordered align-middle text-center">
            <thead>
                <tr>
                    <th style="width: 5%">ลำดับ</th>
                    <th style="width: 12%">วันที่</th>
                    <th style="width: 10%">ชื่อผู้ใช้</th>
                    <th style="width: 55%">รายการสินค้า</th>
                    <th style="width: 15%">หมายเหตุ</th>
                    <th style="width: 8%">ลบ</th>
                </tr>
            </thead>
            <tbody>
                <?php $i=1; foreach ($orders as $oid => $o): ?>
                    <?php
                        $productList = "";
                        foreach ($o['items'] as $item) {
                            $code = htmlspecialchars($item['product_name']);
                            $name = htmlspecialchars($product_name_map[$code] ?? $code);
                            $qty = intval($item['quantity']);
                            $productList .= "<li>$code $name ($qty)</li>";
                        }
                    ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= date('d/m/Y', strtotime($o['date'])) ?></td>
                        <td><?= htmlspecialchars($o['username']) ?></td>
                        <td class="text-start"><ul class="product-list"><?= $productList ?></ul></td>
                        <td>
                            <?php if (!empty(trim($o['note']))): ?>
                                <div class="note-box"><?= nl2br(htmlspecialchars($o['note'])) ?></div>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form method="POST" onsubmit="return confirm('คุณต้องการลบออเดอร์นี้หรือไม่?');">
                                <input type="hidden" name="delete_order_id" value="<?= $oid ?>">
                                <button type="submit" class="btn-delete"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

</body>
</html>
