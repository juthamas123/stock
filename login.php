// เมื่อเข้าสู่ระบบสำเร็จ
$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username']; // หรือเปลี่ยนเป็น $user['name'] ถ้าในฐานข้อมูลใช้ชื่อจริง
