<?php
require_once __DIR__ . '/db_connect.php';
header('Content-Type: application/json; charset=utf-8');

$stmt = $pdo->query("
    SELECT 
        SUM(CASE WHEN product_name LIKE '%แผ่นรั้ว%' THEN remaining ELSE 0 END) AS totalFence,
        SUM(CASE WHEN product_name LIKE '%เสา%' THEN remaining ELSE 0 END) AS totalPole,
        SUM(CASE WHEN product_name LIKE '%ทับหลัง%' THEN remaining ELSE 0 END) AS totalRoof,
        SUM(CASE WHEN product_name LIKE '%กันดิน%' THEN remaining ELSE 0 END) AS totalSoil
    FROM products
");
$totals = $stmt->fetch(PDO::FETCH_ASSOC);
echo json_encode($totals);
?>
