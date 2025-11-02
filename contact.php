<?php
session_start();
require 'db_connect.php'; // เชื่อมฐานข้อมูล
$current_page = basename($_SERVER['PHP_SELF']);

// ฟังก์ชันส่งข้อความติดต่อ
if (isset($_POST['submit_contact'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    if (!empty($name) && !empty($email) && !empty($message)) {
        $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $message]);
        echo "<script>alert('ส่งข้อความเรียบร้อยแล้ว!');</script>";
    } else {
        echo "<script>alert('กรุณากรอกข้อมูลให้ครบถ้วน!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <title>MY STOCK - ติดต่อเรา</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@600;800&display=swap"
        rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.5.0/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries -->
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Bootstrap + Style -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <style>
        .btn-logout {
            border: 1.8px solid #dc3545;
            color: #dc3545;
            font-weight: 600;
            border-radius: 15px;
            padding: 5px 15px;
            transition: all 0.3s;
        }

        .btn-logout:hover {
            background-color: #dc3545;
            color: white;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            color: #157347;
        }

        .user-info i {
            font-size: 22px;
            color: #157347;
        }
    </style>
</head>

<body>


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
    <!-- 🔹 Header -->
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">ติดต่อเรา</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="index.php">หน้าแรก</a></li>
            <li class="breadcrumb-item active text-white">ติดต่อเรา</li>
        </ol>
    </div>
    <!-- Content -->
    <div class="container py-5">
        <div class="text-end mb-3">
            <a href="contact.php" class="btn btn-outline-success btn-sm">🔄 รีเฟรชข้อมูล</a>
        </div>
    <!-- 🔹 Contact Form -->
    <div class="container-fluid contact py-5">
        <div class="container py-5">
            <div class="p-5 bg-light rounded">
                <div class="row g-4">
                    <div class="col-12 text-center">
                        <h1 class="text-primary mb-4">ติดต่อเรา</h1>
                    </div>
                     <div class="col-lg-12">
                            <div class="h-100 rounded">
                                <iframe class="rounded w-100" 
                                style="height: 400px;" src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d3817.3890018289294!2d102.74405157515399!3d16.906093083901332!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zMTbCsDU0JzIxLjkiTiAxMDLCsDQ0JzQ3LjkiRQ!5e0!3m2!1sen!2sth!4v1759938763451!5m2!1sen!2sth" 
                                width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
</div>
                    <div class="col-lg-7">
                        <form action="" method="POST">
                            <input type="text" name="name" class="form-control border-0 py-3 mb-4"
                                placeholder="ชื่อของคุณ" required>
                            <input type="email" name="email" class="form-control border-0 py-3 mb-4"
                                placeholder="อีเมลของคุณ" required>
                            <textarea name="message" class="form-control border-0 mb-4" rows="5"
                                placeholder="ข้อความของคุณ" required></textarea>
                            <button type="submit" name="submit_contact"
                                class="w-100 btn border-secondary py-3 bg-white text-primary">ส่งข้อความ</button>
                        </form>
                    </div>

                    <div class="col-lg-5">
                        <div class="d-flex p-4 bg-white rounded mb-3">
                            <i class="fas fa-map-marker-alt fa-2x text-primary me-3"></i>
                            <div>
                                <h4>ที่อยู่</h4>
                                <p>75/11 อำเภอเขาสวนกวาง จังหวัดขอนแก่น</p>
                            </div>
                        </div>
                        <div class="d-flex p-4 bg-white rounded mb-3">
                            <i class="fas fa-envelope fa-2x text-primary me-3"></i>
                            <div>
                                <h4>อีเมล</h4>
                                <p>juthamaspromwong@gmail.com</p>
                            </div>
                        </div>
                        <div class="d-flex p-4 bg-white rounded">
                            <i class="fas fa-phone fa-2x text-primary me-3"></i>
                            <div>
                                <h4>โทรศัพท์</h4>
                                <p>063-893-1030</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
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
