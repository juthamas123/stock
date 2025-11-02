<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<p>ยังไม่มีบัญชีผู้ใช้? <a href="register.php" style="color:#8BC34A; text-decoration:none;">สมัครสมาชิก</a></p>

    <div class="container">
        <h2>สร้างบัญชี</h2>
        <form id="signupForm" action="register.php" method="POST"> 
            
            <div class="form-group">
                <label for="username">ชื่อผู้ใช้:</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="email">อีเมล:</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">รหัสผ่าน:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">ยืนยันรหัสผ่าน:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
                <p id="passwordError" class="error-message"></p>
            </div>
            
            <button type="submit">สมัครสมาชิก</button>
            
            <p class="login-link">
                มีบัญชีอยู่แล้ว? <a href="login.html">เข้าสู่ระบบ</a>
            </p>
        </form>
    </div>

    <script src="script.js"></script>
</body>
</html>