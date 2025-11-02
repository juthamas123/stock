<?php
session_start();
require_once __DIR__ . '/db_connect.php';

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

include 'navbar.php';


// ✅ ดึงข้อมูลสินค้าทั้งหมดจากฐานข้อมูล
$stmt = $pdo->query("SELECT * FROM products ORDER BY product_code ASC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ✅ ฟังก์ชันกรองสินค้าแต่ละหมวด
function filterCategory($products, $prefix) {
    return array_filter($products, fn($p) => strpos($p['product_code'], $prefix) === 0);
}

// ✅ หมวดหมู่สินค้า
$categories = [
    '2' => ['label' => 'แผ่นรั้วสำเร็จรูป', 'prefix' => 'AN'],
    '3' => ['label' => 'เสารั้วไอ15', 'prefix' => 'AK'],
    '6' => ['label' => 'เสาเข็มไอ18', 'prefix' => 'AP'],
    '4' => ['label' => 'ทับหลัง', 'prefix' => 'AM'],
    '5' => ['label' => 'แผ่นกันดิน', 'prefix' => 'AR']
];

// ✅ ปิด cache เพื่อให้ข้อมูลใหม่โหลดทุกครั้ง

?>

<?php
require_once __DIR__ . '/db_connect.php'; // ✅ เชื่อมต่อฐานข้อมูล

$search = "";
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
}

// ✅ ถ้ามีคำค้นหา → ดึงเฉพาะสินค้าที่ชื่อใกล้เคียง
if ($search !== "") {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE product_name LIKE ?");
    $stmt->execute(["%$search%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY product_id ASC");
}
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<?php
require_once __DIR__ . '/db_connect.php';

// ✅ ดึงยอดรวมคงเหลือตามประเภทสินค้า
$stmt = $pdo->query("
    SELECT 
        SUM(CASE WHEN product_name LIKE '%แผ่นรั้ว%' THEN remaining ELSE 0 END) AS totalFence,
        SUM(CASE WHEN product_name LIKE '%เสา%' THEN remaining ELSE 0 END) AS totalPole,
        SUM(CASE WHEN product_name LIKE '%ทับหลัง%' THEN remaining ELSE 0 END) AS totalRoof,
        SUM(CASE WHEN product_name LIKE '%กันดิน%' THEN remaining ELSE 0 END) AS totalSoil
    FROM products
");
$totals = $stmt->fetch(PDO::FETCH_ASSOC);

// ✅ ตัวแปรส่งให้ HTML
$totalFence = $totals['totalFence'] ?? 0;
$totalPole  = $totals['totalPole'] ?? 0;
$totalRoof  = $totals['totalRoof'] ?? 0;
$totalSoil  = $totals['totalSoil'] ?? 0;
?>

<!DOCTYPE html>
<html lang="th">
<style>



/* ✅ ตั้งค่าสไตล์ตารางสินค้า */
.table-container {
    margin: 60px auto;
    width: 110%;
    max-width: 1120px;
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 6px 12px rgba(0,0,0,0.05);
    overflow-x: auto; /* ป้องกันล้นขวา */
}

.product-table {
    width: 100%;
    border-collapse: collapse;
    font-family: "Prompt", sans-serif;
    color: #333;
}

.product-table th, .product-table td {
    text-align: center;
    padding: 12px;
    border-bottom: 1px solid #eee;
    vertical-align: middle;
    white-space: nowrap; /* ป้องกันบรรทัดใหม่ */
}

.product-table thead {
    background-color: #f7f9fa;
    color: #555;
    font-weight: 600;
}

.product-table tbody tr:hover {
    background-color: #f9fdf9;
    transition: 0.2s;
}

.product-table td img {
    width: 80px;   /* ✅ ปรับขนาดรูป */
    height: 80px;
    object-fit: cover;
    border-radius: 10px;
}

/* ✅ สีของตัวเลข */
.stock { color: #4CAF50; font-weight: bold; }
.reserved { color: #FFA726; font-weight: bold; }
.sold { color: #E53935; font-weight: bold; }
</style>

<head>
    <meta charset="utf-8">
    <title>สต็อกสินค้า | MY STOCK</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- ✅ CSS เดิมทั้งหมด -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
/>

</head>

<body>




    <!-- ✅ Header -->
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">สต็อกสินค้า</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="#">หน้าแรก</a></li>
            <li class="breadcrumb-item active text-white">สต็อก</li>
        </ol>
    </div>

    <!-- ✅ เนื้อหา -->
    <div class="container py-5">
        <div class="text-end mb-3">
            <a href="shop.php" class="btn btn-outline-success btn-sm">🔄 รีเฟรชข้อมูล</a>
        </div> 

<div class="d-flex justify-content-center mb-5">
  <form method="get" action="shop.php" class="d-flex align-items-center shadow-sm p-3 bg-white rounded-pill" style="width: 500px;">
    <input 
      type="text" 
      name="search" 
      class="form-control border-0 rounded-pill me-2" 
      placeholder="🔍 ค้นหาสินค้า..." 
      style="background: #f9f9f9;"
      value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
    <button class="btn btn-success rounded-pill px-4" type="submit">
      <i class="fa fa-search me-1"></i> ค้นหา
    </button>
  </form>
</div>

<div class="row mt-4 mb-4 g-3">

  <!-- 🧱 จำนวนแผ่นรั้ว -->
  <div class="col-md-3">
    <div class="card text-center shadow-lg border-0" style="background: #e3f2fd; border-radius: 18px;">
      <div class="card-body">
        <div style="font-size: 40px;">🧱</div>
        <h6 class="fw-bold text-primary mt-2">จำนวนแผ่นรั้วคงเหลือทั้งหมด</h6>
        <h3 id="fence-count" class="fw-bold mt-2 text-dark">0</h3>
      </div>
    </div>
  </div>

  <!-- 🚧 จำนวนเสา -->
  <div class="col-md-3">
    <div class="card text-center shadow-lg border-0" style="background: #fff3cd; border-radius: 18px;">
      <div class="card-body">
        <div style="font-size: 40px;">🪵</div>
        <h6 class="fw-bold text-warning mt-2">จำนวนเสาคงเหลือทั้งหมด</h6>
        <h3 id="pole-count" class="fw-bold mt-2 text-dark">0</h3>
      </div>
    </div>
  </div>

  <!-- 💰 จำนวนทับหลัง -->
  <div class="col-md-3">
    <div class="card text-center shadow-lg border-0" style="background: #d1e7dd; border-radius: 18px;">
      <div class="card-body">
        <div style="font-size: 40px;">⚒️</div>
        <h6 class="fw-bold text-success mt-2">จำนวนทับหลังคงเหลือทั้งหมด</h6>
        <h3 id="roof-count" class="fw-bold mt-2 text-dark">0</h3>
      </div>
    </div>
  </div>

  <!-- 📊 จำนวนแผ่นกันดิน -->
  <div class="col-md-3">
    <div class="card text-center shadow-lg border-0" style="background: #f8d7da; border-radius: 18px;">
      <div class="card-body">
        <div style="font-size: 40px;">🚧</div>
        <h6 class="fw-bold text-danger mt-2">จำนวนแผ่นกันดินคงเหลือทั้งหมด</h6>
        <h3 id="soil-count" class="fw-bold mt-2 text-dark">0</h3>
      </div>
    </div>
  </div>

</div>


        <!-- ✅ Tabs -->
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
                                    <a class="d-flex mx-1 py-2 bg-light rounded-pill active" data-bs-toggle="pill" href="#tab-1">
                                        <span class="text-dark" style="width: 130px;">สินค้าทั้งหมด</span>
                                    </a>
                                </li>
                                <?php foreach ($categories as $id => $cat): ?>
                                <li class="nav-item">
                                    <a class="d-flex mx-1 py-2 bg-light rounded-pill" data-bs-toggle="pill" href="#tab-<?= $id ?>">
                                        <span class="text-dark" style="width: 130px;"><?= $cat['label'] ?></span>
                                    </a>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>

    
                    <!-- ✅ ตารางสินค้า -->
                    <div class="tab-content">
                        <?php
                        // ✅ แท็บสินค้าทั้งหมด
                        echo '<div id="tab-1" class="tab-pane fade show active">';
                        echo '<div class="table-container">';
                        echo '<table class="product-table">
                                <thead>
                                    <tr>
                                        <th>รูปภาพ</th>
                                        <th>รหัสสินค้า</th>
                                        <th>สินค้า</th>
                                        <th>ราคา</th>
                                        <th>สต็อก</th>
                                        <th>จอง</th>
                                        <th>คงเหลือ</th>
                                    </tr>
                                </thead><tbody>';
                        foreach ($products as $p) {
                            $img = $p['image_path'] ?: 'no_image.png';
                            echo "<tr>
                                    <td><img src='img/{$img}' alt=''></td>
                                    <td>{$p['product_code']}</td>
                                    <td>{$p['product_name']}</td>
                                    <td>{$p['price']}</td>
                                    <td class='stock'>{$p['stock']}</td>
                                    <td class='reserved'>{$p['reserved']}</td>
                                    <td class='sold'>{$p['remaining']}</td>
                                </tr>";
                        }
                        echo '</tbody></table></div></div>';

                        // ✅ แท็บตามหมวดหมู่
                        foreach ($categories as $id => $cat) {
                            $filtered = filterCategory($products, $cat['prefix']);
                            echo "<div id='tab-{$id}' class='tab-pane fade'>
                                    <div class='table-container'>
                                        <table class='product-table'>
                                            <thead>
                                                <tr>
                                                    <th>รูปภาพ</th>
                                                    <th>รหัสสินค้า</th>
                                                    <th>สินค้า</th>
                                                    <th>ราคา</th>
                                                    <th>สต็อก</th>
                                                    <th>จอง</th>
                                                    <th>คงเหลือ</th>
                                                </tr>
                                            </thead><tbody>";

                            if (empty($filtered)) {
                                echo "<tr><td colspan='7'>ไม่มีข้อมูลในหมวดนี้</td></tr>";
                            } else {
                                foreach ($filtered as $p) {
                                    $img = $p['image_path'] ?: 'no_image.png';
                                    echo "<tr>
                                            <td><img src='img/{$img}' alt=''></td>
                                            <td>{$p['product_code']}</td>
                                            <td>{$p['product_name']}</td>
                                            <td>{$p['price']}</td>
                                            <td class='stock'>{$p['stock']}</td>
                                            <td class='reserved'>{$p['reserved']}</td>
                                            <td class='sold'>{$p['remaining']}</td>
                                        </tr>";
                                }
                            }
                            echo '</tbody></table></div></div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <!-- Footer Start -->
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
        <!-- Footer End -->

        <!-- Copyright Start -->
        <div class="container-fluid copyright bg-dark py-4">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        <span class="text-light"><a href="#"><i class="fas fa-copyright text-light me-2"></i>Your Site Name</a>www.nopporn.com</span>
                    </div>
      
            </div>
        </div>
        <!-- Copyright End -->



        <!-- Back to Top -->
        <a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>   

        
    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>


    <!-- ส่วนอื่น ๆ ของหน้าเว็บ (เช่น ตารางสินค้า) วางได้เหมือนเดิม -->
    <!-- เธอสามารถวางโค้ดตารางสินค้าทั้งหมดของเดิมต่อจากนี้ได้เลย -->

    <!-- JS Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>

    <script>
$(document).ready(function() {
  $.getJSON("get_totals.php", function(data) {
    $("#fence-count").text(data.totalFence);
    $("#pole-count").text(data.totalPole);
    $("#roof-count").text(data.totalRoof);
    $("#soil-count").text(data.totalSoil);
  });
});
</script>

</body>
</html>
