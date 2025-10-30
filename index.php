<?php
session_start();
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title>My STOCK</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@600;800&display=swap" rel="stylesheet"> 

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
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
<div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50 d-flex align-items-center justify-content-center">
    <div class="spinner-grow text-primary" role="status"></div>
</div>
<!-- Spinner End -->


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

<!-- ✅ ส่วนเนื้อหาเดิมทั้งหมด (Hero, Featurs, Product, Footer ฯลฯ) -->
<!-- 👇 คงทุกอย่างไว้เหมือนเดิม -->
 <!-- Navbar End -->


    <!-- Hero Start -->
        <div class="container-fluid py-5 mb-5 hero-header">
            <div class="container py-5">
            <div class="row g-5 align-items-center">
                <div class="col-md-12 col-lg-7">
                    <h4 class="mb-3 text-secondary">100% my stock</h4>
                    <h1 class="mb-5 display-3 text-primary">"หากสินค้าหมดชั่วคราว ทีมงานจะแจ้งกำหนดการเติม สต็อก
                        ใหม่ให้เร็วที่สุด"</h1>

                   
                </div>
                <div class="col-md-12 col-lg-5">
                    <div id="carouselId" class="carousel slide position-relative" data-bs-ride="carousel">
                        <div class="carousel-inner" role="listbox">
                            <div class="carousel-item active rounded">
                                <img src="img/hero-img-1.jpg" class="img-fluid w-100 h-100 bg-secondary rounded"
                                    alt="First slide">
                                <a href="#" class="btn px-4 py-2 text-white rounded">สต็อกแบบเรียลไทม์</a>
                            </div>
                            <div class="carousel-item rounded">
                                <img src="img/hero-img-1.jpg" class="img-fluid w-100 h-100 rounded" alt="Second slide">
                                <a href="#" class="btn px-4 py-2 text-white rounded">มั่นใจ100%</a>
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselId"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselId"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Hero End -->


    <!-- Featurs Section Start -->
        <div class="container-fluid featurs py-6">
            <div class="container py-6">
            <div class="row g-4">

<div class="col-md-6 col-lg-3">
    <a href="shop.php" class="text-decoration-none">
        <div class="featurs-item text-center rounded bg-light p-4 shadow-sm hover-shadow">
            <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                <i class="fas fa-boxes fa-3x text-white"></i>
            </div>
            <div class="featurs-content text-center">
                <h5 class="text-dark fw-bold">ระบบสต็อกสินค้า</h5>
                <p class="text-muted mb-0">ตรวจสอบปริมาณสินค้าแบบเรียลไทม์</p>
            </div>
        </div>
    </a>
</div>

<div class="col-md-6 col-lg-3">
    <a href="shop-detail.php" class="text-decoration-none">
        <div class="featurs-item text-center rounded bg-light p-4 shadow-sm hover-shadow">
                <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                <i class="fas fa-shipping-fast fa-3x text-white"></i>
            </div>
            <div class="featurs-content text-center">
                <h5 class="text-dark fw-bold">คิวขนส่ง</h5>
                <p class="text-muted mb-0">ตรวจสอบสถานะการจัดส่งสินค้า</p>
            </div>
        </div>
    </a>
</div>

<div class="col-md-6 col-lg-3">
    <a href="orders.php" class="text-decoration-none">
        <div class="featurs-item text-center rounded bg-light p-4 shadow-sm hover-shadow">
            <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                <i class="fas fa-shopping-cart fa-3x text-white"></i>
            </div>
            <div class="featurs-content text-center">
                <h5 class="text-dark fw-bold">สั่งสินค้า</h5>
                <p class="text-muted mb-0">จัดการรายการสั่งซื้อของลูกค้า</p>
            </div>
        </div>
    </a>
</div>
<div class="col-md-6 col-lg-3">
    <a href="contact.php" class="text-decoration-none">
        <div class="featurs-item text-center rounded bg-light p-4 shadow-sm hover-shadow">
              <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                <i class="fas fa-headset fa-3x text-white"></i>
            </div>
            <div class="featurs-content text-center">
                <h5 class="text-dark fw-bold">ติดต่อเรา</h5>
                <p class="text-muted mb-0">สอบถามข้อมูลผู้ดูแลระบบ</p>
            </div>
        </div>
    </a>
</div>

            </div>
        </div>
    </div>
    <!-- Featurs Section End -->


    <!-- Fruits Shop Start-->
        <div class="container-fluid fruite py-5">
            <div class="container py-5">
            <div class="tab-class text-center">
                <div class="row g-4">
                    <div class="col-lg-4 text-start">
                        <h1>สินค้าทั้งหมด</h1>
                    </div>
                    <div class="col-lg-8 text-end">
                        <ul class="nav nav-pills d-inline-flex text-center mb-5">
                            <li class="nav-item">
                                <a class="d-flex m-2 py-2 bg-light rounded-pill active" data-bs-toggle="pill"
                                    href="#tab-1">
                                    <span class="text-dark" style="width: 130px;">สินค้าทั้งหมด</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="d-flex py-2 m-2 bg-light rounded-pill" data-bs-toggle="pill" href="#tab-2">
                                    <span class="text-dark" style="width: 130px;">แผ่นรั้วสำเร็จรูป</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="d-flex m-2 py-2 bg-light rounded-pill" data-bs-toggle="pill" href="#tab-3">
                                    <span class="text-dark" style="width: 130px;">เสารั้ว</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="d-flex m-2 py-2 bg-light rounded-pill" data-bs-toggle="pill" href="#tab-4">
                                    <span class="text-dark" style="width: 130px;">ทับหลัง</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="d-flex m-2 py-2 bg-light rounded-pill" data-bs-toggle="pill" href="#tab-5">
                                    <span class="text-dark" style="width: 130px;">แผ่นกันดิน</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <div class="tab-content">
                    <div id="tab-1" class="tab-pane fade show p-0 active">
                        <div class="row g-4">
                            <div class="col-lg-12">
                                <div class="row g-4">
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <div class="rounded position-relative fruite-item">
                                            <div class="fruite-img">
                                                <img src="img/fruite-item-5.jpg" class="img-fluid w-100 rounded-top"
                                                    alt="">
                                            </div>
                                            <div class="text-white bg-secondary px-3 py-1 rounded position-absolute"
                                                style="top: 10px; left: 10px;">สินค้า</div>
                                            <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                                <h4>แผ่นรั้วสำเร็จรูปแบบลิ่ม</h4>
                                                <p>ขนาด 0.245*0.06*2.91 เมตร</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <div class="rounded position-relative fruite-item">
                                            <div class="fruite-img">
                                                <img src="img/fruite-item-3.jpg" class="img-fluid w-100 rounded-top"
                                                    alt="">
                                            </div>
                                            <div class="text-white bg-secondary px-3 py-1 rounded position-absolute"
                                                style="top: 10px; left: 10px;">สินค้า</div>
                                            <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                                <h4>แผ่นกันดิน 1.00 เมตร</h4>
                                                <p>ขนาด 0.05*0.35*1.00 เมตร</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <div class="rounded position-relative fruite-item">
                                            <div class="fruite-img">
                                                <img src="img/fruite-item-6.jpg" class="img-fluid w-100 rounded-top"
                                                    alt="">
                                            </div>
                                            <div class="text-white bg-secondary px-3 py-1 rounded position-absolute"
                                                style="top: 10px; left: 10px;">สินค้า</div>
                                            <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                                <h4>แผ่นรั้วสำเร็จรูปแบบเรียบ</h4>
                                                <p>ขนาด 0.245*0.04*2.91 เมตร</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <div class="rounded position-relative fruite-item">
                                            <div class="fruite-img">
                                                <img src="img/fruite-item-4.jpg" class="img-fluid w-100 rounded-top"
                                                    alt="">
                                            </div>
                                            <div class="text-white bg-secondary px-3 py-1 rounded position-absolute"
                                                style="top: 10px; left: 10px;">สินค้า</div>
                                            <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                                <h4>ทับหลังตัวที</h4>
                                                <p>ขนาด 0.06*0.29*2.91 เมตร</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <div class="rounded position-relative fruite-item">
                                            <div class="fruite-img">
                                                <img src="img/fruite-item-3.jpg" class="img-fluid w-100 rounded-top"
                                                    alt="">
                                            </div>
                                            <div class="text-white bg-secondary px-3 py-1 rounded position-absolute"
                                                style="top: 10px; left: 10px;">สินค้า</div>
                                            <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                                <h4>แผ่นกันดิน 3.00 เมตร</h4>
                                                <p>ขนาด 0.05*0.35*3.00 เมตร</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <div class="rounded position-relative fruite-item">
                                            <div class="fruite-img">
                                                <img src="img/fruite-item-1.jpg" class="img-fluid w-100 rounded-top"
                                                    alt="">
                                            </div>
                                            <div class="text-white bg-secondary px-3 py-1 rounded position-absolute"
                                                style="top: 10px; left: 10px;">สินค้า</div>
                                            <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                                <h4>เสารั้วไอ15</h4>
                                                <p>สินค้าสั่งผลิต</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <div class="rounded position-relative fruite-item">
                                            <div class="fruite-img">
                                                <img src="img/fruite-item-6.jpg" class="img-fluid w-100 rounded-top"
                                                    alt="">
                                            </div>
                                            <div class="text-white bg-secondary px-3 py-1 rounded position-absolute"
                                                style="top: 10px; left: 10px;">สินค้า</div>
                                            <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                                <h4>ทับหลังครอบ</h4>
                                                <p>ขนาด 0.05*0.15*2.85 เมตร</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <div class="rounded position-relative fruite-item">
                                            <div class="fruite-img">
                                                <img src="img/fruite-item-7.jpg" class="img-fluid w-100 rounded-top"
                                                    alt="">
                                            </div>
                                            <div class="text-white bg-secondary px-3 py-1 rounded position-absolute"
                                                style="top: 10px; left: 10px;">สินค้า</div>
                                            <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                                <h4>เสาไอ18</h4>
                                                <p>สินค้าสั่งผลิต</p>  
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tab-2" class="tab-pane fade show p-0">
                        <div class="row g-4">
                            <div class="col-lg-12">
                                <div class="row g-4">
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <div class="rounded position-relative fruite-item">
                                            <div class="fruite-img">
                                                <img src="img/fruite-item-5.jpg" class="img-fluid w-100 rounded-top"
                                                    alt="">
                                            </div>
                                            <div class="text-white bg-secondary px-3 py-1 rounded position-absolute"
                                                style="top: 10px; left: 10px;">สินค้า</div>
                                            <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                             <h4>แผ่นรั้วสำเร็จรูปแบบลิ่ม</h4>
                                                <p>ขนาด 0.245*0.06*2.91 เมตร</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <div class="rounded position-relative fruite-item">
                                            <div class="fruite-img">
                                                <img src="img/fruite-item-6.jpg" class="img-fluid w-100 rounded-top"
                                                    alt="">
                                            </div>
                                            <div class="text-white bg-secondary px-3 py-1 rounded position-absolute"
                                                style="top: 10px; left: 10px;">สินค้า</div>
                                            <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                                <h4>แผ่นรั้วสำเร็จรูปแบบเรียบ</h4>
                                                <p>ขนาด 0.245*0.04*2.91 เมตร</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tab-3" class="tab-pane fade show p-0">
                        <div class="row g-4">
                            <div class="col-lg-12">
                                <div class="row g-4">
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <div class="rounded position-relative fruite-item">
                                            <div class="fruite-img">
                                                <img src="img/fruite-item-1.jpg" class="img-fluid w-100 rounded-top"
                                                    alt="">
                                            </div>
                                            <div class="text-white bg-secondary px-3 py-1 rounded position-absolute"
                                                style="top: 10px; left: 10px;">สินค้า</div>
                                            <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                                <h4>เสารั้วไอ15</h4>
                                                <p>สินค้าสั่งผลิต</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <div class="rounded position-relative fruite-item">
                                            <div class="fruite-img">
                                                <img src="img/fruite-item-7.jpg" class="img-fluid w-100 rounded-top"
                                                    alt="">
                                            </div>
                                            <div class="text-white bg-secondary px-3 py-1 rounded position-absolute"
                                                style="top: 10px; left: 10px;">สินค้า</div>
                                            <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                                <h4>เสาไอ18</h4>
                                                <p>สินค้าสั่งผลิต</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tab-4" class="tab-pane fade show p-0">
                        <div class="row g-4">
                            <div class="col-lg-12">
                                <div class="row g-4">
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <div class="rounded position-relative fruite-item">
                                            <div class="fruite-img">
                                                <img src="img/fruite-item-2.jpg" class="img-fluid w-100 rounded-top"
                                                    alt="">
                                            </div>
                                            <div class="text-white bg-secondary px-3 py-1 rounded position-absolute"
                                                style="top: 10px; left: 10px;">สินค้า</div>
                                            <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                                <h4>ทับหลังครอบ</h4>
                                                <p>ขนาด 0.05*0.15*2.85 เมตร</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <div class="rounded position-relative fruite-item">
                                            <div class="fruite-img">
                                                <img src="img/fruite-item-4.jpg" class="img-fluid w-100 rounded-top"
                                                    alt="">
                                            </div>
                                            <div class="text-white bg-secondary px-3 py-1 rounded position-absolute"
                                                style="top: 10px; left: 10px;">สินค้า</div>
                                            <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                            <h4>ทับหลังตัวที</h4>
                                                <p>ขนาด 0.06*0.29*2.91 เมตร</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tab-5" class="tab-pane fade show p-0">
                        <div class="row g-4">
                            <div class="col-lg-12">
                                <div class="row g-4">
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <div class="rounded position-relative fruite-item">
                                            <div class="fruite-img">
                                                <img src="img/fruite-item-3.jpg" class="img-fluid w-100 rounded-top"
                                                    alt="">
                                            </div>
                                            <div class="text-white bg-secondary px-3 py-1 rounded position-absolute"
                                                style="top: 10px; left: 10px;">สินค้า</div>
                                            <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                                <h4>แผ่นกันดิน 1.00 เมตร</h4>
                                                <p>ขนาด 0.05*0.35*1.00 เมตร</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <div class="rounded position-relative fruite-item">
                                            <div class="fruite-img">
                                                <img src="img/fruite-item-3.jpg" class="img-fluid w-100 rounded-top"
                                                    alt="">
                                            </div>
                                            <div class="text-white bg-secondary px-3 py-1 rounded position-absolute"
                                                style="top: 10px; left: 10px;">สินค้า</div>
                                            <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                                <h4>แผ่นกันดิน 3.00 เมตร</h4>
                                                <p>ขนาด 0.05*0.35*3.00 เมตร </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <div class="rounded position-relative fruite-item">
                                            <div class="fruite-img">
                                                <img src="img/fruite-item-3.jpg" class="img-fluid w-100 rounded-top"
                                                    alt="">
                                            </div>
                                            <div class="text-white bg-secondary px-3 py-1 rounded position-absolute"
                                                style="top: 10px; left: 10px;">สินค้า</div>
                                            <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                                <h4>แผ่นกันดิน 2.00 เมตร</h4>
                                                <p>ขนาด 0.05*0.35*2.00 เมตร </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Fruits Shop End-->

            <!-- Banner Section Start-->
        <div class="container-fluid banner bg-secondary my-5">
            <div class="container py-5">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-6">
                        <div class="py-4">
                            <h1 class="display-2.5 text-white">จัดส่งทั่งประเทศไทย </h1>
                            <p class="fw-normal display-2 text-dark mb-2">ระบบจะทำการแจ้งวันในการขนส่งภายใน 24 ชั่วโมง</p>
                            <p class="mb-4 text-dark">หากไม่มีการอัพเดตข้อมูลกรุณาติดต่อที่ช่องทางการติดต่อด้านล่าง </p>
                            <a href="shop-detail.php" class="banner-btn btn border-2 border-white rounded-pill text-dark py-3 px-5">คิวขนส่ง</a>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="position-relative">
                            <img src="img/mm1.png" class="img-fluid w-80 rounded" alt="">
                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Banner Section End -->


    <!-- Featurs Start -->
        <div class="container-fluid fruite py-3">
            <div class="container py-3">
            <div class="row g-4 justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <a href="#">
                        <div class="service-item bg-secondary rounded border border-secondary">
                            <img src="img/featur-1.jpg" class="img-fluid rounded-top w-100" alt="">
                            <div class="px-4 rounded-bottom">
                                <div class="service-content bg-primary text-center p-4 rounded">
                                    <h2 class="text-white">แข็งแรง</h2>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 col-lg-4">
                    <a href="#">
                        <div class="service-item bg-dark rounded border border-dark">
                            <img src="img/featur-2.jpg" class="img-fluid rounded-top w-100" alt="">
                            <div class="px-4 rounded-bottom">
                                <div class="service-content bg-light text-center p-4 rounded">
                                    <h2 class="text-primary">ทนทาน</h2>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 col-lg-4">
                    <a href="#">
                        <div class="service-item bg-primary rounded border border-primary">
                            <img src="img/featur-3.jpg" class="img-fluid rounded-top w-100" alt="">
                            <div class="px-4 rounded-bottom">
                                <div class="service-content bg-secondary text-center p-4 rounded">
                                    <h2 class="text-white">มั่นใจ100%</h2>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Featurs End -->


    <!-- Bestsaler Product Start -->
 <div class="container-fluid fruite py-">
            <div class="container py-4">
            <div class="text-center mx-auto mb-5" style="max-width: 700px;">
                <h1 class="display-4">รายชื่อขนส่ง</h1>
                <p>รายชื่อด้านล่างเฉพาะพนักงานในบริษัท </p>
            </div>
            <div class="row g-4">
                <div class="col-lg-6 col-xl-4">
                    <div class="p-4 rounded bg-light">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <img src="img/best-product-1.jpg" class="img-fluid rounded-circle w-100" alt="">
                            </div>
                            <div class="col-6">
                                <a href="#" class="h5">	วิคเตอร์ (Victor)</a>
                                <div class="d-flex my-3">
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <h4 class="mb-3">อายุ 37 ปี</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-4">
                    <div class="p-4 rounded bg-light">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <img src="img/best-product-2.jpg" class="img-fluid rounded-circle w-100" alt="">
                            </div>
                            <div class="col-6">
                                <a href="#" class="h5">	แจ็ค (Jack)</a>
                                <div class="d-flex my-3">
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <h4 class="mb-3">อายุ 45 ปี</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-4">
                    <div class="p-4 rounded bg-light">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <img src="img/best-product-3.jpg" class="img-fluid rounded-circle w-100" alt="">
                            </div>
                            <div class="col-6">
                                <a href="#" class="h5">โทนี่ (Tony)</a>
                                <div class="d-flex my-3">
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <h4 class="mb-3">อายุ 45 ปี</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-4">
                    <div class="p-4 rounded bg-light">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <img src="img/best-product-4.jpg" class="img-fluid rounded-circle w-100" alt="">
                            </div>
                            <div class="col-6">
                                <a href="#" class="h5">เจมส์ (James)</a>
                                <div class="d-flex my-3">
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <h4 class="mb-3">อายุ 28 ปี</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-4">
                    <div class="p-4 rounded bg-light">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <img src="img/best-product-5.jpg" class="img-fluid rounded-circle w-100" alt="">
                            </div>
                            <div class="col-6">
                                <a href="#" class="h5">ลีโอ (Leo)</a>
                                <div class="d-flex my-3">
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <h4 class="mb-3">อายุ 33 ปี</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-4">
                    <div class="p-4 rounded bg-light">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <img src="img/best-product-6.jpg" class="img-fluid rounded-circle w-100" alt="">
                            </div>
                            <div class="col-6">
                                <a href="#" class="h5">	เอมมี่ (Amy)</a>
                                <div class="d-flex my-3">
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <h4 class="mb-3">อายุ 29 ปี</h4>
                            </div>
                        </div>
                    </div>
                </div>
    <!-- Bestsaler Product End -->





    <!-- Tastimonial Start -->
    <div class="container-fluid testimonial py-5">
        <div class="container-fluid py-5">
            <div class="testimonial-header text-center">
                <h1 class="display-5 mb-5 text-dark">สอบถามเพิ่มเติมติดต่อ</h1>
            </div>
            <div class="owl-carousel testimonial-carousel">
                <div class="testimonial-item img-border-radius bg-light rounded p-4">
                    <div class="position-relative">
                        <i class="fa fa-quote-right fa-2x text-secondary position-absolute"
                            style="bottom: 30px; right: 0;"></i>
                        <div class="mb-4 pb-4 border-bottom border-secondary">
                            <p class="mb-0">โทร :063-893-1030 หรือ 
                                ID Line :0638931030
                            </p>
                        </div>
                        <div class="d-flex align-items-center flex-nowrap">
                            <div class="bg-secondary rounded">
                                <img src="img/testimonial-1.jpg" class="img-fluid rounded"
                                    style="width: 100px; height: 100px;" alt="">
                            </div>
                            <div class="ms-4 d-block">
                                <h4 class="text-dark">จุฑามาศ พรมวงศ์(นุ่น)</h4>
                                <p class="m-0 pb-3">ผู้ดูแลระบบ</p>
                                <div class="d-flex pe-5">
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="testimonial-item img-border-radius bg-light rounded p-4">
                    <div class="position-relative">
                        <i class="fa fa-quote-right fa-2x text-secondary position-absolute"
                            style="bottom: 30px; right: 0;"></i>
                        <div class="mb-4 pb-4 border-bottom border-secondary">
                            <p class="mb-0">	โทร :063-893-1030 หรือ 
                                ID Line :0638931030
                            </p>
                        </div>
                        <div class="d-flex align-items-center flex-nowrap">
                            <div class="bg-secondary rounded">
                                <img src="img/testimonial-1.jpg" class="img-fluid rounded"
                                    style="width: 100px; height: 100px;" alt="">
                            </div>
                            <div class="ms-4 d-block">
                                <h4 class="text-dark">จุฑามาศ พรมวงศ์(นุ่น)</h4>
                                <p class="m-0 pb-3">ผู้ดูแลระบบ</p>
                                <div class="d-flex pe-5">
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="testimonial-item img-border-radius bg-light rounded p-4">
                    <div class="position-relative">
                        <i class="fa fa-quote-right fa-2x text-secondary position-absolute"
                            style="bottom: 30px; right: 0;"></i>
                        <div class="mb-4 pb-4 border-bottom border-secondary">
                            <p class="mb-0">โทร :063-893-1030 หรือ 
                                ID Line :0638931030
                            </p>
                        </div>
                        <div class="d-flex align-items-center flex-nowrap">
                            <div class="bg-secondary rounded">
                                <img src="img/testimonial-1.jpg" class="img-fluid rounded"
                                    style="width: 100px; height: 100px;" alt="">
                            </div>
                            <div class="ms-4 d-block">
                                <h4 class="text-dark">จุฑามาศ พรมวงศ์(นุ่น)</h4>
                                <p class="m-0 pb-3">ผู้ดูแลระบบ</p>
                                <div class="d-flex pe-5">
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        </div>
            </div>
    </div>
    <!-- Tastimonial End -->


 <!-- 🔹 Footer -->
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
                        <a class="btn-link" href="cart.html">สั่งสินค้า</a>
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
    <div class="container-fluid copyright bg-dark py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <span class="text-light"><a href="#"><i class="fas fa-copyright text-light me-2"></i>Your Site Name</a>www.nopporn.com</span>
                </div>
                <div class="col-md-6 my-auto text-center text-md-end text-white">
                    Designed By <a class="border-bottom" href="https://htmlcodex.com">HTML Codex</a> Distributed By <a class="border-bottom" href="https://themewagon.com">ThemeWagon</a>
                </div>
            </div>
        </div>
    </div>
    <a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a> 

    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <script src="js/main.js"></script>
    
    <script>
        $(document).ready(function () {
            // ฟังก์ชันสำหรับจัดการปุ่มเพิ่มจำนวน
            $(".btn-plus").click(function () {
                var $input = $(this).closest('.quantity').find('input[name="quantity[]"]');
                var currentValue = parseInt($input.val());
                var minValue = parseInt($input.attr('min')) || 0; 
                
                if (!isNaN(currentValue) && currentValue >= minValue) {
                    $input.val(currentValue + 1);
                } else {
                    $input.val(1);
                }
            });

            // ฟังก์ชันสำหรับจัดการปุ่มลดจำนวน
            $(".btn-minus").click(function () {
                var $input = $(this).closest('.quantity').find('input[name="quantity[]"]');
                var currentValue = parseInt($input.val());
                var minValue = parseInt($input.attr('min')) || 1; 
                
                if (!isNaN(currentValue) && currentValue > minValue) {
                    $input.val(currentValue - 1);
                } else {
                    $input.val(minValue);
                }
            });

            // ป้องกันการใส่ค่าที่เป็นลบหรือค่าที่ไม่ใช่ตัวเลขในช่องกรอกจำนวนโดยตรง
            $('input[name="quantity[]"]').on('change keyup', function() {
                var value = parseInt($(this).val());
                var minValue = parseInt($(this).attr('min')) || 1;
                
                if (isNaN(value) || value < minValue) {
                    $(this).val(minValue); 
                }
            });
        });


    </script>
</body>

</html>

</body>

</html>  