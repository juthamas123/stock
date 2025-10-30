<?php
session_start();

// ✅ ดึงข้อมูลออร์เดอร์จาก URL
$order_id = $_GET['id'] ?? 'N/A';
$username = $_SESSION['username'] ?? 'ผู้ใช้';
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>✅ สั่งซื้อสำเร็จ | MY STOCK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: "Prompt", sans-serif;
        }
        .success-box {
            max-width: 600px;
            margin: 100px auto;
            padding: 40px;
            border: 1px solid #d4edda;
            border-radius: 15px;
            background-color: #ffffff;
            box-shadow: 0 6px 15px rgba(0,0,0,.1);
        }
        .checkmark {
            font-size: 70px;
            color: #28a745;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="success-box text-center">
            <div class="checkmark mb-3">✅</div>
            <h2 class="text-success mb-3">สั่งซื้อสำเร็จแล้ว!</h2>
            <p class="lead mb-1">ขอบคุณ <strong><?= htmlspecialchars($username) ?></strong></p>
            <p>สำหรับการสั่งซื้อสินค้ากับเรา</p>
            <hr>
            <p class="text-muted">เจ้าหน้าที่จะตรวจสอบและดำเนินการภายใน 24 ชั่วโมง</p>

            <div class="mt-4">
                <a href="cart.php" class="btn btn-outline-secondary me-2 px-4">สร้างออร์เดอร์ใหม่</a>
                <a href="index.php" class="btn btn-success px-4">กลับสู่หน้าแรก</a>
            </div>
        </div>
    </div>
</body>
</html>
