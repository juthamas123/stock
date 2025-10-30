<?php
// ✅ ป้องกัน header error
ob_start();
session_start();

// ✅ ตรวจสอบการเชื่อมต่อฐานข้อมูล
require_once __DIR__ . '/db_connect.php'; 

// ✅ ตั้งค่า Header ก่อนมีการ output ใด ๆ
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

// ✅ ตรวจสอบการ Login
if (!isset($_SESSION['user_id'])) {
    $user_id = 0;
    $username = 'Guest';
} else {
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'] ?? 'User';
}

// ✅ ดึงรูปโปรไฟล์ของผู้ใช้
if ($user_id > 0) {
    try {
        $stmt_user = $pdo->prepare("SELECT profile_image FROM users WHERE id = ?");
        $stmt_user->execute([$user_id]);
        $user_data = $stmt_user->fetch(PDO::FETCH_ASSOC);
        $profile_image = $user_data['profile_image'] ?? 'img/profiles/default.png';
        if (!file_exists($profile_image) || empty($user_data['profile_image'])) {
            $profile_image = 'img/profiles/default.png';
        }
    } catch (PDOException $e) {
        $profile_image = 'img/profiles/default.png';
    }
} else {
    $profile_image = 'img/profiles/default.png';
}

// ✅ ข้อมูลสินค้าเดิม (ไม่เปลี่ยน)
$product_data = [
    'แผ่นรั้วสำเร็จรูป' => [
        'img' => 'img/vegetable-item-3.png',
        'products' => [
            ['name' => 'ไม่มีรายการสั่งซื้อ', 'code' => 'ไม่มีรายการสินค้า', 'img' => 'img/vegetable-item-2.jpg'],
            ['name' => 'แผ่นรั้วสำเร็จรูปแบบลิ่ม(2.91เมตร)', 'code' => 'AN001', 'img' => 'img/vegetable-item-3.png'],
            ['name' => 'แผ่นรั้วสำเร็จรูปแบบเรียบ(2.91เมตร)', 'code' => 'AN003', 'img' => 'img/vegetable-item-5.jpg'],
            ['name' => 'แผ่นรั้วสำเร็จรูปแบบลิ่ม(2.42เมตร)', 'code' => 'AN002', 'img' => 'img/vegetable-item-2.jpg'],
            ['name' => 'แผ่นรั้วสำเร็จรูปแบบเรียบ(2.42เมตร)', 'code' => 'AN004', 'img' => 'img/vegetable-item-5.jpg'],
        ]
    ],
    'ทับหลัง' => [
        'img' => 'img/vegetable-item-5.jpg',
        'products' => [
            ['name' => 'ไม่มีรายการสั่งซื้อ', 'code' => 'ไม่มีรายการสินค้า', 'img' => 'img/vegetable-item-2.jpg'],
            ['name' => 'ทับหลังครอบ (2.85 เมตร)', 'code' => 'AM001', 'img' => 'img/vegetable-item-3.png'],
            ['name' => 'ทับหลังตัวที (2.91 เมตร)', 'code' => 'AM003', 'img' => 'img/vegetable-item-5.jpg'],
            ['name' => 'ทับหลังครอบ (2.42 เมตร)', 'code' => 'AM002', 'img' => 'img/vegetable-item-2.jpg'],
        ]
    ],
    'เสารั้วไอ15' => [
        'img' => 'img/vegetable-item-2.jpg',
        'products' => [
            ['name' => 'ไม่มีรายการสั่งซื้อ', 'code' => 'ไม่มีรายการสินค้า', 'img' => 'img/vegetable-item-2.jpg'],
            ['name' => 'เสารั้วไอ15 (2.00 เมตร)', 'code' => 'AK001', 'img' => 'img/vegetable-item-3.png'],
            ['name' => 'เสารั้วไอ15(2.20 เมตร)', 'code' => 'AK002', 'img' => 'img/vegetable-item-5.jpg'],
            ['name' => 'เสารั้วไอ15 (2.50 เมตร)', 'code' => 'AK003', 'img' => 'img/vegetable-item-2.jpg'],
            ['name' => 'เสารั้วไอ15 (2.75 เมตร)', 'code' => 'AK004', 'img' => 'img/vegetable-item-3.png'],
            ['name' => 'เสารั้วไอ15 (3.00 เมตร)', 'code' => 'AK005', 'img' => 'img/vegetable-item-3.png'],
            ['name' => 'เสารั้วไอ15 (3.50 เมตร)', 'code' => 'AK006', 'img' => 'img/vegetable-item-3.png'],
            ['name' => 'เสารั้วไอ15 (4.00 เมตร)', 'code' => 'AK007', 'img' => 'img/vegetable-item-3.png'],
            ['name' => 'เสารั้วไอ15 (5.00 เมตร)', 'code' => 'AK008', 'img' => 'img/vegetable-item-3.png'],
            ['name' => 'เสารั้วไอ15 (6.00 เมตร)', 'code' => 'AK009', 'img' => 'img/vegetable-item-3.png'],
        ]
    ],
    'เสาเข็มไอ18' => [ 
        'img' => 'img/vegetable-item-7.png',
        'products' => [
            ['name' => 'ไม่มีรายการสั่งซื้อ', 'code' => 'ไม่มีรายการสินค้า', 'img' => 'img/vegetable-item-2.jpg'],
            ['name' => 'เสาเข็มไอ18*18*2.00 เมตร', 'code' => 'AP001', 'img' => 'img/AP001.jpg'],
            ['name' => 'เสาเข็มไอ18*18*3.00 เมตร', 'code' => 'AP002', 'img' => 'img/AP002.jpg'],
            ['name' => 'เสาเข็มไอ18*18*4.00 เมตร', 'code' => 'AP003', 'img' => 'img/AP003.jpg'],
            ['name' => 'เสาเข็มไอ18*18*5.00 เมตร', 'code' => 'AP004', 'img' => 'img/AP004.jpg'],
            ['name' => 'เสาเข็มไอ18*18*6.00 เมตร', 'code' => 'AP005', 'img' => 'img/AP005.jpg'],
        ]
    ],
    'แผ่นกันดิน' => [
        'img' => 'img/vegetable-item-1.jpg',
        'products' => [
            ['name' => 'ไม่มีรายการสั่งซื้อ', 'code' => 'ไม่มีรายการสินค้า', 'img' => 'img/vegetable-item-2.jpg'],
            ['name' => 'แผ่นกันดิน ขนาด 1.00 เมตร', 'code' => 'AR001', 'img' => 'img/AK001.jpg'],
            ['name' => 'แผ่นกันดิน ขนาด 1.42 เมตร', 'code' => 'AR002', 'img' => 'img/AK002.jpg'],
            ['name' => 'แผ่นกันดิน ขนาด 2.00 เมตร', 'code' => 'AR003', 'img' => 'img/AK003.jpg'],
            ['name' => 'แผ่นกันดิน ขนาด 2.42 เมตร', 'code' => 'AR004', 'img' => 'img/AK004.jpg'],
            ['name' => 'แผ่นกันดิน ขนาด 2.91 เมตร', 'code' => 'AR005', 'img' => 'img/AK005.jpg'],
            ['name' => 'แผ่นกันดิน ขนาด 3.00 เมตร', 'code' => 'AR006', 'img' => 'img/AK006.jpg'],
            ['name' => 'แผ่นกันดิน ขนาด 3.50 เมตร', 'code' => 'AR007', 'img' => 'img/AK007.jpg'],
        ]
    ]
];

// ✅ ฟังก์ชันสร้าง option (เหมือนเดิม)
function generate_product_options($products) {
    $options = '';
    foreach ($products as $product) {
        $default_class = ($product['code'] === 'ไม่มีรายการสินค้า') ? 'default-option' : '';
        $options .= "<option value=\"{$product['code']}\" data-img=\"{$product['img']}\" class=\"{$default_class}\">{$product['name']}</option>";
    }
    return $options;
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

    <div class="container-fluid page-header py-5 mt-5">
        <h1 class="text-center text-white display-6">เพิ่มออเดอร์</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="index.php">หน้าแรก</a></li>
            <li class="breadcrumb-item active text-white">ออเดอร์</li>
        </ol>
    </div>
    <div class="container py-5">
        <div class="text-end mb-3">
            <a href="cart.php" class="btn btn-outline-success btn-sm">🔄 รีเฟรชข้อมูล</a>
        </div>

        <!-- ปุ่มประวัติการสั่งซื้อ -->
<div class="mt-2">
  <a href="order_history.php" class="btn btn-outline-primary btn-sm w-100">
    <i class="bi bi-clock-history"></i> คลิกเพื่อประวัติการสั่งซื้อ
  </a>
</div>

        <div class="container-fluid py-5">
            <div class="container py-5">
                <form action="process_order.php" method="POST" id="orderForm"> 
                    <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id) ?>">
                    <input type="hidden" name="username" value="<?= htmlspecialchars($username) ?>">

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 10%;">ภาพ</th>
                                    <th scope="col" style="width: 20%;">ชื่อหมวดหมู่หลัก</th>
                                    <th scope="col" style="width: 40%;">รายการสินค้า</th>
                                    <th scope="col" style="width: 15%;">จำนวน</th>
                                    <th scope="col" style="width: 15%;">เพิ่มรายการ</th>
                                </tr>
                            </thead>
                            <tbody id="product-rows">
                            
                            <?php foreach ($product_data as $category_name => $data): ?>
                                <?php
                                    $category_id = str_replace(' ', '_', $category_name);
                                    $options_html = generate_product_options($data['products']);
                                ?>
                                <tr class="product-row" id="row-<?= $category_id ?>" data-category="<?= $category_name ?>" data-category-id="<?= $category_id ?>">
                                    
                                    <th scope="row" rowspan="1" class="align-middle category-image-cell">
                                        <div class="d-flex align-items-center">
                                            <img src="<?= htmlspecialchars($data['img']) ?>" class="img-fluid me-5 rounded-circle"
                                                style="width: 80px; height: 80px; object-fit: cover;" alt="<?= htmlspecialchars($category_name) ?>">
                                        </div>
                                    </th>
                                    
                                    <td class="align-middle category-name-cell">
                                        <input type="hidden" name="product_category[]" value="<?= htmlspecialchars($category_name) ?>">
                                        <p class="mb-0 mt-4 fw-bold"><?= htmlspecialchars($category_name) ?></p>
                                    </td>
                                    
                                    <td>
                                        <select class="form-select product-select w-100" name="products[]" required>
                                            <?= $options_html ?>
                                        </select>
                                    </td>
                                    
                                    <td>
                                        <div class="input-group quantity mt-4" style="width: 120px;">
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-sm btn-minus rounded-circle bg-light border">
                                                    <i class="fa fa-minus"></i>
                                                </button>
                                            </div>
                                            <input type="text" class="form-control form-control-sm text-center border-0" value="1" name="quantity[]" min="1">
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-sm btn-plus rounded-circle bg-light border">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="align-middle">
                                        <button type="button" class="btn btn-outline-success btn-sm add-item-btn" data-category-id="<?= $category_id ?>">
                                            <i class="fa fa-plus"></i> เพิ่มรายการ
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-5">
                        <input type="text" class="border-0 border-bottom rounded me-5 py-3 mb-4" placeholder="Coupon Code / หมายเหตุ" name="coupon_code">
                        
                        <button class="btn border-secondary rounded-pill px-4 py-3 text-primary" type="submit">
                            <i class="fa fa-check-circle me-2"></i> ยืนยันสั่งสินค้า
                        </button>
                    </div>
                </form>
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
        // ฟังก์ชันควบคุมจำนวนสินค้า (เดิม)
        function setupQuantityControls($row) {
            $row.find(".btn-plus").off('click').on('click', function () {
                var $input = $(this).closest('.quantity').find('input[name="quantity[]"]');
                var currentValue = parseInt($input.val());
                var minValue = parseInt($input.attr('min')) || 0; 
                if (!isNaN(currentValue) && currentValue >= minValue) {
                    $input.val(currentValue + 1);
                } else {
                    $input.val(1);
                }
            });

            $row.find(".btn-minus").off('click').on('click', function () {
                var $input = $(this).closest('.quantity').find('input[name="quantity[]"]');
                var currentValue = parseInt($input.val());
                var minValue = parseInt($input.attr('min')) || 1; 
                if (!isNaN(currentValue) && currentValue > minValue) {
                    $input.val(currentValue - 1);
                } else {
                    $input.val(minValue);
                }
            });
            
            $row.find('input[name="quantity[]"]').off('change keyup').on('change keyup', function() {
                var value = parseInt($(this).val());
                var minValue = parseInt($(this).attr('min')) || 1;
                
                if (isNaN(value) || value < minValue) {
                    $(this).val(minValue); 
                }
            });
        }

        // 💡 ฟังก์ชันจัดการปุ่ม "เพิ่มรายการสั่งซื้อ" (Add Item)
        // ต้องใช้ $(document).on เพราะแถวใหม่ที่เพิ่มเข้ามาก็ต้องมีปุ่มนี้ด้วย
        $(document).on('click', '.add-item-btn', function() {
            const categoryId = $(this).data('category-id');
            const $currentRow = $(this).closest('.product-row');
            
            // 1. Clone แถวสินค้าปัจจุบัน
            const $newRow = $currentRow.clone();
            
            // 2. ลบ id (ต้องไม่ซ้ำกัน) และ reset ค่า
            $newRow.removeAttr('id');
            $newRow.find('input[name="quantity[]"]').val('1');
            $newRow.find('select[name="products[]"]').prop('selectedIndex', 0); // เลือก option แรก (มักจะเป็น "ไม่มีรายการสั่งซื้อ")
            
            // 3. ล้างคอลัมน์รูปภาพและชื่อหมวดหมู่ เพื่อเตรียมรวมเซลล์
            $newRow.find('.category-image-cell').empty();
            $newRow.find('.category-name-cell').empty();

            // 4. เปลี่ยนปุ่ม "เพิ่มรายการ" เป็นปุ่ม "ลบรายการ"
            $newRow.find('.add-item-btn')
                .removeClass('btn-outline-success add-item-btn')
                .addClass('btn-outline-danger remove-item-btn')
                .html('<i class="fa fa-trash"></i> ลบรายการ');

            // 5. ตั้งค่า Quantity Control ใหม่สำหรับแถวใหม่
            setupQuantityControls($newRow);

            // 6. เพิ่มแถวใหม่ต่อจากแถวปัจจุบัน
            $currentRow.after($newRow);
            
            // 7. ปรับปรุง rowspan ของแถวทั้งหมดในหมวดหมู่นี้
            updateRowspan(categoryId); 
        });

        // 💡 ฟังก์ชันจัดการปุ่ม "ลบรายการสั่งซื้อ" (Remove Item)
        $(document).on('click', '.remove-item-btn', function() {
            const categoryId = $(this).data('category-id');
            const $rowToRemove = $(this).closest('.product-row');
            
            // ลบแถว
            $rowToRemove.remove();
            
            // ปรับปรุง rowspan
            updateRowspan(categoryId); 
        });

        // 💡 ฟังก์ชันปรับ rowspan ให้กับแถวรูปภาพและหมวดหมู่หลัก
        function updateRowspan(categoryId) {
            const $categoryRows = $(`#product-rows > tr[data-category-id="${categoryId}"]`);
            const totalRows = $categoryRows.length;
            
            if (totalRows > 0) {
                // อ้างอิงแถวแรก (แถวที่ควรมีรูปภาพ/ชื่อหมวดหมู่)
                const $firstRow = $categoryRows.first();
                
                // 1. นำเนื้อหา (รูปภาพ/ชื่อหมวดหมู่) กลับไปใส่ในแถวแรก
                const categoryData = <?= json_encode($product_data); ?>[ $firstRow.data('category') ];

                // **กู้คืนรูปภาพในแถวแรก**
                if ($firstRow.find('.category-image-cell').is(':empty')) {
                     $firstRow.find('.category-image-cell').html(`
                        <div class="d-flex align-items-center">
                            <img src="${categoryData.img}" class="img-fluid me-5 rounded-circle"
                                style="width: 80px; height: 80px; object-fit: cover;" alt="${$firstRow.data('category')}">
                        </div>
                    `);
                }
                // **กู้คืนชื่อหมวดหมู่ในแถวแรก**
                if ($firstRow.find('.category-name-cell').is(':empty')) {
                     $firstRow.find('.category-name-cell').html(`
                        <input type="hidden" name="product_category[]" value="${$firstRow.data('category')}">
                        <p class="mb-0 mt-4 fw-bold">${$firstRow.data('category')}</p>
                    `);
                }

                // 2. ตั้งค่า rowspan ใหม่ในแถวแรก
                $firstRow.find('.category-image-cell').attr('rowspan', totalRows);
                $firstRow.find('.category-name-cell').attr('rowspan', totalRows);
                
                // 3. สำหรับแถวที่ตามมาทั้งหมด (ยกเว้นแถวแรก) ให้ล้างเนื้อหาและลบ rowspan
                $categoryRows.slice(1).each(function() {
                    $(this).find('.category-image-cell').empty().removeAttr('rowspan');
                    $(this).find('.category-name-cell').empty().removeAttr('rowspan');
                });
            }
        }

        // ตั้งค่าเริ่มต้นสำหรับแถวทั้งหมดเมื่อโหลดหน้าเว็บ
        $("#product-rows tr.product-row").each(function() {
            setupQuantityControls($(this));
            updateRowspan($(this).data('category-id')); // ตั้งค่า rowspan เริ่มต้น
        });
    });
    </script>

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