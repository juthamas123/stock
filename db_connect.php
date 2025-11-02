<?php
// ================================================
// ✅ db_connect.php (ไฟล์เชื่อมฐานข้อมูลกลาง)
// ================================================

$host = 'localhost';
$dbname = 'admin_dashboard'; // ✅ ชื่อฐานข้อมูลของคุณ
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("<h3 style='color:red;text-align:center;'>❌ Database connection failed: " . $e->getMessage() . "</h3>");
}

require_once 'db_connect.php';

// ✅ ฟังก์ชัน: ดึงรายการสินค้า
function getProductOptions($pdo, $groupName) {
    $html = '<option selected disabled value="">--- เลือกสินค้า ---</option>';
    try {
        $stmt = $pdo->prepare("SELECT sku, name, stock_count FROM stock WHERE name LIKE :groupName ORDER BY name ASC");
        $stmt->execute(['groupName' => $groupName . '%']);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $stock_display = ($row['stock_count'] > 0) ? " (เหลือ {$row['stock_count']} ชิ้น)" : " (หมด)";
            $disabled = ($row['stock_count'] <= 0) ? 'disabled' : '';
            $html .= "<option value='{$row['sku']}' data-name='{$row['name']}' {$disabled}>{$row['name']}{$stock_display}</option>";
        }
    } catch (Exception $e) {
        $html .= "<option disabled>Error: " . htmlspecialchars($e->getMessage()) . "</option>";
    }
    return $html;
}

// ✅ ดึงรายการสินค้า
$options_slab = getProductOptions($pdo, 'แผ่นรั้วสำเร็จรูปแบบ');
$options_header = getProductOptions($pdo, 'ทับหลัง');
$options_post_i15 = getProductOptions($pdo, 'เสารั้วไอ15');
$options_pile_i18 = getProductOptions($pdo, 'เสาเข็มไอ18');
$options_earth_slab = getProductOptions($pdo, 'แผ่นกันดิน');

// ✅ ถ้ามีการส่งฟอร์ม
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product = $_POST['product'] ?? '';
    $sender = $_POST['sender'] ?? 'ระบบอัตโนมัติ';
    $buyer = $_POST['buyer'] ?? '';
    $day_th = $_POST['day_th'] ?? '';
    $date = date('d/m/y');

    if ($product && $buyer && $day_th) {
        try {
            $pdo->beginTransaction();

            // 🔹 1. ลดจำนวนสินค้าในสต็อก
            $updateStock = $pdo->prepare("UPDATE stock SET stock_count = stock_count - 1 WHERE sku = :sku AND stock_count > 0");
            $updateStock->execute(['sku' => $product]);

            // 🔹 2. เพิ่มคิวขนส่งอัตโนมัติ
            $insertQueue = $pdo->prepare("INSERT INTO shipping_queue (day_th, date, sender, buyer, status)
                                          VALUES (:day_th, :date, :sender, :buyer, 'รอดำเนินการ')");
            $insertQueue->execute([
                'day_th' => $day_th,
                'date' => $date,
                'sender' => $sender,
                'buyer' => $buyer
            ]);

            $pdo->commit();
            $msg = "✅ เพิ่มออร์เดอร์และคิวขนส่งเรียบร้อยแล้ว!";
        } catch (Exception $e) {
            $pdo->rollBack();
            $msg = "❌ เกิดข้อผิดพลาด: " . $e->getMessage();
        }
    } else {
        $msg = "⚠️ โปรดกรอกข้อมูลให้ครบถ้วน";
    }
}
?>
<?php
$pdo = new PDO("mysql:host=localhost;dbname=admin_dashboard;charset=utf8", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>


<?php
//ติดต่อ
$pdo = new PDO("mysql:host=localhost;dbname=admin_dashboard;charset=utf8", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>

<?php
//สมัครสมาชิก 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "admin_dashboard";

$pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>

<?php 
//เข้าระบบ
$host = "localhost";
$username = "root";
$password = "";
$dbname = "admin_dashboard";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

?>

<?php
$host = "localhost"; // ชื่อเซิร์ฟเวอร์
$dbname = "admin_dashboard"; // ✅ ชื่อฐานข้อมูล (ต้องตรงกับของ products.php)
$username = "root"; // XAMPP ปกติใช้ root
$password = ""; // XAMPP ปกติรหัสผ่านว่าง

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ Database connection failed: " . $e->getMessage());
}
?>




