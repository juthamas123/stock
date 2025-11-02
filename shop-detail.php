<?php
// ✅ เริ่มการทำงานของ session และป้องกัน header error
ob_start();
session_start(); // ต้องอยู่บนสุดเสมอ
require_once __DIR__ . '/db_connect.php';

// ✅ ปิดการแคชหน้า (ต้องอยู่ก่อนมีการแสดงผล HTML หรือ include อื่น ๆ)
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

// ✅ ดึงข้อมูลจากฐานข้อมูลสด ๆ ทุกครั้ง
try {
    $stmt = $pdo->query("SELECT * FROM shipping_queue ORDER BY id DESC");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $rows = [];
}

// ✅ include navbar หลังจากตั้งค่า header เสร็จ
include 'navbar.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <title>คิวขนส่ง | My STOCK</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@600;800&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>

    <!-- Spinner Start -->
    <div id="spinner"
        class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" role="status"></div>
    </div>
    <!-- Spinner End -->


    <!-- ✅ Navbar -->
    <!-- (navbar อยู่ใน include แล้ว ไม่ต้องแก้) -->


    <!-- Header -->
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">คิวขนส่งสินค้า</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="#">หน้าแรก</a></li>
            <li class="breadcrumb-item active text-white">คิวขนส่ง</li>
        </ol>
    </div>


    <!-- Content -->
    <div class="container py-5">
        <div class="text-end mb-3">
            <a href="shop-detail.php" class="btn btn-outline-success btn-sm">🔄 รีเฟรชข้อมูล</a>
        </div>

        <div class="text-center mb-5">
            <h1 class="fw-bold text-primary display-6 mb-3">🚚 ตารางคิวขนส่งสินค้า</h1>
            <p class="text-secondary">ระบบอัปเดตอัตโนมัติจากการเพิ่มข้อมูลในแอดมิน</p>
            <hr class="w-50 mx-auto text-primary">
        </div>

        <?php if (count($rows) > 0): ?>
            <div class="table-responsive shadow-lg rounded-4">
                <table class="table table-hover align-middle text-center mb-0">
                    <thead class="table-primary text-dark">
                        <tr>
                            <th scope="col">ลำดับ</th>
                            <th scope="col">วันจัดส่ง</th>
                            <th scope="col">วันที่ (วัน/เดือน/ปี)</th>
                            <th scope="col">ผู้ส่ง</th>
                            <th scope="col">ผู้ขาย</th>
                            <th scope="col">สถานะ</th>
                            <th scope="col">หมายเหตุ</th>
                        </tr>
                    </thead>
                    <tbody class="table-light">
                        <?php $i = 1;
                        foreach ($rows as $r):
                            $statusColor = match($r['status']) {
                                'รอดำเนินการ' => 'bg-warning text-dark',
                                'กำลังจัดส่ง' => 'bg-info text-white',
                                'จัดส่งสำเร็จ' => 'bg-success text-white',
                                'ยกเลิก' => 'bg-danger text-white',
                                default => 'bg-secondary text-white'
                            }; ?>
                            <tr class="hover-row">
                                <td><?= $i++ ?></td>
                                <td><strong class="text-primary"><?= htmlspecialchars($r['day_th']) ?></strong></td>
                                <td><?= htmlspecialchars($r['date']) ?></td>
                                <td><?= htmlspecialchars($r['sender']) ?></td>
                                <td><?= htmlspecialchars($r['buyer']) ?></td>
                                <td><span class="badge rounded-pill px-3 py-2 <?= $statusColor ?>"><?= htmlspecialchars($r['status']) ?></span></td>
                                <td><?= !empty($r['remark']) ? htmlspecialchars($r['remark']) : '-' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <img src="img/no-data.png" alt="No data" class="img-fluid mb-4" style="max-width:250px;">
                <h4 class="text-muted">ยังไม่มีข้อมูลคิวขนส่งในระบบ</h4>
                <p class="text-secondary">ข้อมูลจะปรากฏอัตโนมัติเมื่อเพิ่มจากระบบแอดมิน</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- ✅ Footer เหมือนเดิม -->
    <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5">
        <div class="container py-5">
            <div class="pb-4 mb-4" style="border-bottom: 1px solid rgba(226, 175, 24, 0.5) ;">
                <div class="row g-4">
                    <div class="col-lg-3">
                        <a href="#">
                            <h1 class="text-primary mb-0">My STOCK</h1>
                            <p class="text-secondary mb-0">รั้วคอนกรีตสำเร็จรูป</p>
                        </a>
                    </div>
                    <div class="col-lg-6">
                        <div class="position-relative mx-auto">
                            <input class="form-control border-0 w-100 py-3 px-4 rounded-pill" type="number" placeholder="Your Email">
                            <button type="submit" class="btn btn-primary border-0 border-secondary py-3 px-4 position-absolute rounded-pill text-white" style="top: 0; right: 0;">Subscribe Now</button>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="d-flex justify-content-end pt-3">
                            <a class="btn  btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-youtube"></i></a>
                            <a class="btn btn-outline-secondary btn-md-square rounded-circle" href=""><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-5">
                <div class="col-lg-3 col-md-6">
                    <div class="footer-item">
                        <h4 class="text-light mb-3">ทำไมถึงทำระบบสต็อกสินค้า!</h4>
                        <p class="mb-4">ป้องกันสินค้าขาดสต็อก </p>
                        <p class="mb-4">ป้องกันสินค้ามีมากเกินไป </p>
                        <p class="mb-4">ลดสินค้าส่วนเกิน </p>
                        <p class="mb-4">การจัดส่งที่รวดเร็วและแม่นยำ </p>
                        <a href="" class="btn border-secondary py-2 px-4 rounded-pill text-primary">Read More</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="d-flex flex-column text-start footer-item">
                        <h4 class="text-light mb-3">เกี่ยวกับเรา</h4>
                        <a class="btn-link" href="index.html">หน้าแรก</a> 
                        <a class="btn-link" href="contact.html">ติดต่อเรา</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="d-flex flex-column text-start footer-item">
                        <h4 class="text-light mb-3">ข้อมูลสต็อกสินค้า</h4>
                        <a class="btn-link" href="shop.html">สต็อกสินค้า</a>
                        <a class="btn-link" href="cart.html">ออร์เดอร์</a>
                        <a class="btn-link" href="shop-detail.html">คิวขนส่ง</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="footer-item">
                        <h4 class="text-light mb-3">ติดต่อเรา</h4>
                        <p>ที่อยู่ 75/11 อ.เขาสวนกวาง จ.ขอนแก่น</p>
                        <p>อีเมล:juthamaspromwong@gmail.com</p>
                        <p>โทร: 063-893-1030</p>
                        <p>Payment Accepted</p>
                        <img src="img/payment.png" class="img-fluid" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Copyright -->
    <div class="container-fluid copyright bg-dark py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <span class="text-light"><a href="#"><i class="fas fa-copyright text-light me-2"></i>Your Site Name</a>www.nopporn.com</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Back to Top -->
    <a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>

    <!-- JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>

<?php ob_end_flush(); // ✅ ปล่อย output หลัง header แล้ว ?>
