<?php
require_once __DIR__ . '/db_connect.php';
session_start();

// ปิด cache เพื่อให้แสดงชื่อใหม่หลังแก้ไขได้ทันที
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

// ระบุหน้าเว็บปัจจุบัน
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- 🔹 Navbar Start -->
<div class="container-fluid fixed-top">
    <!-- Top bar -->
    <div class="container topbar bg-primary d-none d-lg-block">
        <div class="d-flex justify-content-between">
            <div class="top-info ps-2">
                <small class="me-3">
                    <i class="fas fa-map-marker-alt me-2 text-warning"></i>
                    <a href="#" class="text-white">75/11 ต.ดงเมืองแอม อ.เขาสวนกวาง จ.ขอนแก่น</a>
                </small>
                <small class="me-3">
                    <i class="fas fa-envelope me-2 text-warning"></i>
                    <a href="#" class="text-white">juthamaspromwong@gmail.com</a>
                </small>
            </div>
            <div class="top-link pe-2">
                <a href="#" class="text-white"><small class="text-white mx-2">อัปเดตสถานะ</small>/</a>
                <a href="#" class="text-white"><small class="text-white mx-2">สต็อกแบบเรียลไทม์</small>/</a>
                <a href="#" class="text-white"><small class="text-white ms-2">พร้อมจัดส่ง</small></a>
            </div>
        </div>
    </div>

    <!-- Navbar -->
    <div class="container px-0">
        <nav class="navbar navbar-light bg-white navbar-expand-xl">
            <a href="index.php" class="navbar-brand">
                <h1 class="text-primary display-6">MY STOCK</h1>
            </a>

            <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars text-primary"></span>
            </button>

            <div class="collapse navbar-collapse bg-white" id="navbarCollapse">
                <div class="navbar-nav mx-auto">
                    <a href="index.php" class="nav-item nav-link <?= ($current_page == 'index.php') ? 'active' : '' ?>">หน้าแรก</a>
                    <a href="shop.php" class="nav-item nav-link <?= ($current_page == 'shop.php') ? 'active' : '' ?>">สต็อก</a>
                    <a href="shop-detail.php" class="nav-item nav-link <?= ($current_page == 'shop-detail.php') ? 'active' : '' ?>">คิวขนส่ง</a>
                    <a href="cart.php" class="nav-item nav-link <?= ($current_page == 'cart.php') ? 'active' : '' ?>">สั่งสินค้า</a>
                    <a href="contact.php" class="nav-item nav-link <?= ($current_page == 'contact.php') ? 'active' : '' ?>">ติดต่อ</a>
                </div>

                <!-- 🔹 ส่วนแสดงชื่อผู้ใช้ -->
                <div class="d-flex align-items-center">
                    <?php if (isset($_SESSION['user_id']) && isset($_SESSION['username'])): ?>
                        <div class="d-flex align-items-center me-3">
         <a href="chackout.php" 
   class="me-3 fw-bold text-decoration-none d-flex align-items-center" 
   style="color: #289aa7ff; font-size: 18px;">
   <i class="fas fa-user-circle me-2" style="font-size: 20px; color: #289aa7ff;"></i>
   <?= htmlspecialchars($_SESSION['username']) ?>
</a>

                        </div>
                        <!-- ปุ่มออกจากระบบ -->
                        <a href="logout.php" class="btn btn-outline-danger btn-sm">ออกจากระบบ</a>
                    <?php else: ?>
                        <!-- ✅ ปุ่มเข้าสู่ระบบเท่านั้น (สมัครสมาชิกถูกลบออกแล้ว) -->
                        <a href="chackout.php" class="btn btn-outline-primary btn-sm me-2">เข้าสู่ระบบ</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </div>
</div>
<!-- 🔹 Navbar End -->

<?php
require_once __DIR__ . '/db_connect.php';
session_start();

// ปิด cache เพื่อให้แสดงชื่อใหม่หลังแก้ไขได้ทันที
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

// ระบุหน้าเว็บปัจจุบัน
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- 🔹 Navbar Start -->
<div class="container fixed-top">
    <!-- Top bar -->
    <div class="container topbar bg-primary d-none d-lg-block">
        <div class="d-flex justify-content-between">
            <div class="top-info ps-2">
                <small class="me-3">
                    <i class="fas fa-map-marker-alt me-2 text-warning"></i>
                    <a href="#" class="text-white">75/11 ต.ดงเมืองแอม อ.เขาสวนกวาง จ.ขอนแก่น</a>
                </small>
                <small class="me-3">
                    <i class="fas fa-envelope me-2 text-warning"></i>
                    <a href="#" class="text-white">juthamaspromwong@gmail.com</a>
                </small>
            </div>
            <div class="top-link pe-2">
                <a href="#" class="text-white"><small class="text-white mx-2">อัปเดตสถานะ</small>/</a>
                <a href="#" class="text-white"><small class="text-white mx-2">สต็อกแบบเรียลไทม์</small>/</a>
                <a href="#" class="text-white"><small class="text-white ms-2">พร้อมจัดส่ง</small></a>
            </div>
        </div>
    </div>

    <!-- Navbar -->
    <div class="container px-0">
        <nav class="navbar navbar-light bg-white navbar-expand-xl">
            <a href="index.php" class="navbar-brand">
                <h1 class="text-primary display-6">MY STOCK</h1>
            </a>

            <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars text-primary"></span>
            </button>

            <div class="collapse navbar-collapse bg-white" id="navbarCollapse">
                <div class="navbar-nav mx-auto">
                    <a href="index.php" class="nav-item nav-link <?= ($current_page == 'index.php') ? 'active' : '' ?>">หน้าแรก</a>
                    <a href="shop.php" class="nav-item nav-link <?= ($current_page == 'shop.php') ? 'active' : '' ?>">สต็อก</a>
                    <a href="shop-detail.php" class="nav-item nav-link <?= ($current_page == 'shop-detail.php') ? 'active' : '' ?>">คิวขนส่ง</a>
                    <a href="cart.php" class="nav-item nav-link <?= ($current_page == 'cart.php') ? 'active' : '' ?>">สั่งสินค้า</a>
                    <a href="contact.php" class="nav-item nav-link <?= ($current_page == 'contact.php') ? 'active' : '' ?>">ติดต่อ</a>
                </div>

                <!-- 🔹 ส่วนแสดงชื่อผู้ใช้ -->
                <div class="d-flex align-items-center">
                    <?php if (isset($_SESSION['user_id']) && isset($_SESSION['username'])): ?>
                        <div class="d-flex align-items-center me-3">
         <a href="chackout.php" 
   class="me-3 fw-bold text-decoration-none d-flex align-items-center" 
   style="color: #289aa7ff; font-size: 18px;">
   <i class="fas fa-user-circle me-2" style="font-size: 20px; color: #289aa7ff;"></i>
   <?= htmlspecialchars($_SESSION['username']) ?>
</a>

                        </div>
                        
                        <!-- ปุ่มออกจากระบบ -->
                        <a href="logout.php" class="btn btn-outline-danger btn-sm">ออกจากระบบ</a>
                    <?php else: ?>
                        <!-- ✅ ปุ่มเข้าสู่ระบบเท่านั้น (สมัครสมาชิกถูกลบออกแล้ว) -->
                        <a href="chackout.php" class="btn btn-outline-primary btn-sm me-2">เข้าสู่ระบบ</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </div>
</div>
<!-- 🔹 Navbar End -->


<?php
// ✅ ตรวจสอบ session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/db_connect.php';

// ✅ ดึงข้อมูลผู้ใช้จาก session
if (!isset($_SESSION['user_id'])) {
    $user_id = 0;
    $username = 'Guest';
    $profile_image = 'img/profiles/default.png';
} else {
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'] ?? 'User';

    // ✅ ดึงรูปโปรไฟล์จากฐานข้อมูล
    try {
        $stmt_user = $pdo->prepare("SELECT profile_image FROM admin_users WHERE id = ?");
        $stmt_user->execute([$user_id]);
        $user_data = $stmt_user->fetch(PDO::FETCH_ASSOC);

        if (!empty($user_data['profile_image'])) {
            $check_path = strpos($user_data['profile_image'], 'uploads/') !== false
                ? $user_data['profile_image']
                : 'uploads/' . $user_data['profile_image'];

            $profile_image = file_exists(__DIR__ . '/' . $check_path)
                ? $check_path
                : 'img/profiles/default.png';
        } else {
            $profile_image = 'img/profiles/default.png';
        }
    } catch (PDOException $e) {
        $profile_image = 'img/profiles/default.png';
    }
}
?>


<!-- Navbar Start -->
<div class="container fixed-top">
    <div class="container topbar bg-primary d-none d-lg-block">
        <div class="d-flex justify-content-between">
            <div class="top-info ps-2">
                <small class="me-3">
                    <i class="fas fa-map-marker-alt me-2 text-warning"></i>
                    <a href="#" class="text-white">75/11 ต.ดงเมืองแอม อ.เขาสวนกวาง จ.ขอนแก่น</a>
                </small>
                <small class="me-3">
                    <i class="fas fa-envelope me-2 text-warning"></i>
                    <a href="#" class="text-white">juthamaspromwong@gmail.com</a>
                </small>
            </div>
            <div class="top-link pe-2">
                <a href="#" class="text-white"><small class="text-white mx-2">อัปเดตสถานะ</small>/</a>
                <a href="#" class="text-white"><small class="text-white mx-2">สต็อกแบบเรียลไทม์</small>/</a>
                <a href="#" class="text-white"><small class="text-white ms-2">พร้อมจัดส่ง</small></a>
            </div>
        </div>
    </div>

    <div class="container px-0">
        <nav class="navbar navbar-light bg-white navbar-expand-xl">
            <a href="index.php" class="navbar-brand">
                <h1 class="text-primary display-6">MY STOCK</h1>
            </a>
            <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars text-primary"></span>
            </button>

            <div class="collapse navbar-collapse bg-white" id="navbarCollapse">
                <div class="navbar-nav mx-auto">
                    <a href="index.php" class="nav-item nav-link <?= ($current_page == 'index.php') ? 'active' : '' ?>">หน้าแรก</a>
                    <a href="shop.php" class="nav-item nav-link <?= ($current_page == 'shop.php') ? 'active' : '' ?>">สต็อก</a>
                    <a href="shop-detail.php" class="nav-item nav-link <?= ($current_page == 'shop-detail.php') ? 'active' : '' ?>">คิวขนส่ง</a>
                    <a href="cart.php" class="nav-item nav-link <?= ($current_page == 'cart.php') ? 'active' : '' ?>">สั่งสินค้า</a>
                    <a href="contact.php" class="nav-item nav-link <?= ($current_page == 'contact.php') ? 'active' : '' ?>">ติดต่อ</a>
                </div>

                <!-- ✅ ส่วนโปรไฟล์ -->
                <div class="d-flex align-items-center">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php
                        require_once 'db_connect.php';
                        $stmt = $pdo->prepare("SELECT profile_image FROM users WHERE id = ?");
                        $stmt->execute([$_SESSION['user_id']]);
                        $user = $stmt->fetch(PDO::FETCH_ASSOC);
                        $profile_image = (!empty($user['profile_image']) && file_exists($user['profile_image']))
                            ? htmlspecialchars($user['profile_image'])
                            : 'img/profiles/default.png';
                        ?>
                        <div class="d-flex align-items-center me-3">
                            <a href="chackout.php" class="text-decoration-none d-flex align-items-center" style="color:#289aa7ff; font-size:18px;">
                                <div class="profile-wrapper me-2">
                                    <img src="<?= $profile_image ?>" alt="Profile" class="profile-circle">
                                </div>
                            </a>
                        </div>
                              <form action="shop.php" method="get" class="d-flex align-items-center me-2">

                        <a href="logout.php" class="btn btn-outline-danger btn-sm">ออกจากระบบ</a>
                    <?php else: ?>
                        <a href="chackout.php" class="btn btn-outline-primary btn-sm me-2">เข้าสู่ระบบ</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </div>
</div>
<!-- Navbar End -->

<!-- ✅ CSS สำหรับรูปโปรไฟล์ -->
<style>
.profile-wrapper {
    width: 35px;
    height: 35px;
    border: 2px solid #289aa7ff;
    border-radius: 50%;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff;
}
.profile-circle {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}
.profile-wrapper:hover {
    transform: scale(1.05);
    transition: 0.2s ease-in-out;
}
</style>