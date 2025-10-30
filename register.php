<?php
session_start();
require 'db_connect.php';

// --- แก้ไข: กำหนดตัวแปร $error และ $success ตั้งแต่แรกเพื่อป้องกัน Undefined variable Warning ---
$error = null;
$success = null; 
// -----------------------------------------------------------------------------------------

// กำหนดตัวแปรสำหรับเก็บค่าเดิมของฟอร์ม (ถ้ามี)
$username_val = '';
$email_val = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password_input = $_POST['password'];

    // เก็บค่าที่ผู้ใช้กรอกไว้ (ยกเว้นรหัสผ่าน) เพื่อนำไปแสดงอีกครั้งเมื่อเกิด error
    $username_val = $username;
    $email_val = $email;

    // แฮชรหัสผ่าน
    $password_hashed = password_hash($password_input, PASSWORD_DEFAULT);

    // ตรวจสอบว่าชื่อผู้ใช้มีอยู่แล้วหรือไม่
    // ตรวจสอบทั้ง username และ email เพื่อป้องกันการลงทะเบียนซ้ำซ้อน
    $check = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
    $check->execute([$username, $email]);
    
    if ($check->fetchColumn() > 0) {
        $error = "ชื่อผู้ใช้หรืออีเมลนี้มีผู้ใช้งานแล้ว";
    } else {
        // ทำการเพิ่มข้อมูล
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $password_hashed]);

        // ตั้งค่า session และทำการ redirect
        $_SESSION['username'] = $username;
        header("Location: chackout.php");
        exit;
        
        // **หมายเหตุ:** หากคุณต้องการแสดงข้อความ $success ก่อน redirect 
        // คุณต้องลบ header() และ exit; ออกชั่วคราว แต่การทำงานแบบปัจจุบันคือ redirect ไปเลยเมื่อสำเร็จ
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>สมัครสมาชิก</title>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;600&display=swap" rel="stylesheet">
<style>
    /* ... (CSS style remains the same) ... */
    body {
      background: linear-gradient(135deg, #a8e6cf, #dcedc1);
      font-family: 'Prompt', sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .register-card {
      width: 420px;
      background: #fff;
      padding: 35px;
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .title {
      text-align: center;
      font-size: 26px;
      font-weight: 700;
      color: #0b9c57;
      margin-bottom: 10px;
    }
    .subtitle {
      text-align: center;
      color: #6c757d;
      font-size: 14px;
      margin-bottom: 25px;
    }
    .btn-register {
      background-color: #0b9c57;
      color: #fff;
      border: none;
      width: 100%;
      padding: 10px;
      border-radius: 10px;
      transition: 0.3s;
      font-weight: 600;
    }
    .btn-register:hover {
      background-color: #098f4e;
    }
    .form-label {
      font-weight: 600;
      color: #0b9c57;
    }
    .form-control {
      border-radius: 10px;
      border: 1px solid #bcd9c4;
      padding: 10px;
      width: 100%;
      box-sizing: border-box;
    }
    .mb-3 {
        margin-bottom: 1rem;
    }
    .alert {
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 5px;
    }
    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
    }
    .alert-success {
        color: #155724;
        background-color: #d4edda;
        border: 1px solid #c3e6cb;
    }
  </style>
</head>


<body>
  <div class="register-card">
    <div class="title">🌀 สมัครสมาชิก</div>
    <div class="subtitle">กรอกข้อมูลของคุณเพื่อสร้างบัญชีใหม่ในระบบ</div>

    <?php if ($error): ?>
      <div class="alert alert-danger text-center"><?php echo htmlspecialchars($error); ?></div>
    <?php elseif ($success): ?>
      <div class="alert alert-success text-center"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form method="post">
      <div class="mb-3">
        <label class="form-label">ชื่อผู้ใช้ (Username)</label>
        <input type="text" name="username" class="form-control" required placeholder="ตั้งชื่อผู้ใช้ เช่น juthamas" value="<?php echo htmlspecialchars($username_val); ?>">
      </div>

      <div class="mb-3">
        <label class="form-label">อีเมล (Email)</label>
        <input type="email" name="email" class="form-control" required placeholder="กรอกอีเมลของคุณ" value="<?php echo htmlspecialchars($email_val); ?>">
      </div>
      
      <div class="mb-3">
        <label class="form-label">รหัสผ่าน</label>
        <input type="password" name="password" class="form-control" required placeholder="ตั้งรหัสผ่านอย่างน้อย 4 ตัวอักษร">
      </div>

      <button type="submit" class="btn-register">สมัครสมาชิก</button>
    </form>

    <div class="text-center mt-3">
      มีบัญชีแล้ว? <a href="chackout.php" class="text-success fw-bold">เข้าสู่ระบบ</a>
    </div>
  </div>
</body>
</html>