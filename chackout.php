<?php
session_start();
// ตรวจสอบว่าไฟล์ db_connect.php มีอยู่และเรียกใช้งานได้
require 'db_connect.php'; 

$success_message = '';
$error = '';
$login_error = '';

// ✅ ตรวจสอบสถานะผู้ใช้
if (isset($_SESSION['user_id'])) {
    // 💡 ควรตรวจสอบว่า $pdo ถูกสร้างขึ้นและเชื่อมต่อสำเร็จใน db_connect.php 
    if (!isset($pdo)) {
        die("❌ ข้อผิดพลาด: ไม่สามารถเชื่อมต่อฐานข้อมูลได้");
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // หากไม่พบผู้ใช้ในฐานข้อมูลแม้จะมี session ให้ล้าง session และแสดงหน้า Login
    if (!$user) {
        session_unset();
        session_destroy();
        // ไม่ต้อง die() แต่จะไปแสดงหน้า login แทน
    } else {
        // กำหนดพาธรูปโปรไฟล์เริ่มต้น
        // 'img/profiles/default.png' คือค่าที่ควรใช้เป็นค่าเริ่มต้นหากไม่มีรูป
        $profile_image = $user['profile_image'] ?? 'img/profiles/default.png'; 
        if (!file_exists($profile_image) || empty($user['profile_image'])) {
            $profile_image = 'img/profiles/default.png'; 
        }


        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
            $new_username = trim($_POST['new_username'] ?? '');
            $new_email = trim($_POST['new_email'] ?? '');
            $new_date = trim($_POST['new_date'] ?? '');
            $new_password = trim($_POST['new_password'] ?? '');
            
            $image_path_update = false;

            // 💡 1. จัดการการอัปโหลดรูปภาพโปรไฟล์
            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
                $file_tmp = $_FILES['profile_image']['tmp_name'];
                $file_name = $_FILES['profile_image']['name'];
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
                $upload_dir = 'img/profiles/'; 
                
                // ตรวจสอบ Folder หากยังไม่มีให้สร้าง
                if (!is_dir($upload_dir)) {
                    @mkdir($upload_dir, 0755, true); // ใช้ @ เพื่อซ่อน error หากไม่มีสิทธิ์
                }

                if (in_array($file_ext, $allowed_ext)) {
                    $unique_filename = 'profile_' . $_SESSION['user_id'] . '_' . time() . '.' . $file_ext;
                    
                    // ลบรูปเก่าก่อน (ถ้ามีและไม่ใช่ default.png)
                    if (!empty($user['profile_image']) && file_exists($user['profile_image']) && basename($user['profile_image']) !== 'default.png') {
                        @unlink($user['profile_image']);
                    }

                    if (move_uploaded_file($file_tmp, $upload_dir . $unique_filename)) {
                        $new_profile_image_path = $upload_dir . $unique_filename;
                        $image_path_update = true;
                    } else {
                        $error = "❌ ไม่สามารถอัปโหลดไฟล์ได้ โปรดตรวจสอบสิทธิ์ในการเขียนโฟลเดอร์ 'img/profiles/'";
                    }
                } else {
                    $error = "❌ อนุญาตเฉพาะไฟล์ JPG, JPEG, PNG, GIF เท่านั้น";
                }
            } else if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] !== UPLOAD_ERR_NO_FILE) {
                 $error = "❌ เกิดข้อผิดพลาดในการอัปโหลดรูปภาพ (โค้ด: " . $_FILES['profile_image']['error'] . ")";
            }


            // 💡 2. อัปเดตข้อมูลผู้ใช้ในฐานข้อมูล
            if ($new_username !== '' && $new_email !== '' && $new_date !== '' && empty($error)) {
                
                // 🔔 การตรวจสอบความซ้ำซ้อนของ Username/Email (Best Practice)
                
                // 2.1 ตรวจสอบ Username ซ้ำซ้อน
                $check_user_stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
                $check_user_stmt->execute([$new_username, $_SESSION['user_id']]);
                if ($check_user_stmt->fetch()) {
                    $error = "❌ ชื่อผู้ใช้ **'{$new_username}'** ถูกใช้งานแล้วโดยผู้ใช้คนอื่น";
                }

                // 2.2 ตรวจสอบ Email ซ้ำซ้อน
                if (empty($error)) {
                    $check_email_stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
                    $check_email_stmt->execute([$new_email, $_SESSION['user_id']]);
                    if ($check_email_stmt->fetch()) {
                        $error = "❌ อีเมล **'{$new_email}'** ถูกใช้งานแล้วโดยผู้ใช้คนอื่น";
                    }
                }
                
                // 2.3 ดำเนินการอัปเดต
                if (empty($error)) {
                    $sql = "UPDATE users SET username=?, email=?, created_at=?";
                    $params = [$new_username, $new_email, $new_date];
                    
                    if (!empty($new_password)) {
                        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                        $sql .= ", password=?";
                        $params[] = $hashed_password;
                    }
                    
                    if ($image_path_update) {
                        $sql .= ", profile_image=?";
                        $params[] = $new_profile_image_path;
                    }

                    $sql .= " WHERE id=?";
                    $params[] = $_SESSION['user_id'];
                    
                    $update = $pdo->prepare($sql);
                    
                    if ($update->execute($params)) {
                        $_SESSION['username'] = $new_username;
                        $success_message = "🎉 บันทึกข้อมูลโปรไฟล์เรียบร้อยแล้ว!";
                        // โหลดข้อมูลผู้ใช้ใหม่และตั้งค่ารูปโปรไฟล์
                        $stmt->execute([$_SESSION['user_id']]);
                        $user = $stmt->fetch(PDO::FETCH_ASSOC);
                        $profile_image = $user['profile_image'] ?? 'img/profiles/default.png';
                    } else {
                        $error = "⚠️ ไม่สามารถบันทึกข้อมูลได้ (DB Error)";
                    }
                }

            } else if (empty($error)) {
                $error = "⚠️ กรุณากรอกข้อมูลให้ครบถ้วน";
            }
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ✅ ส่วนเข้าสู่ระบบ (LOGIN)
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $login_error = "⚠️ กรุณากรอกข้อมูลให้ครบถ้วน";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            // 🔔 Redirect ไปยังไฟล์เดิม (chackout.php) เพื่อแสดงหน้าโปรไฟล์
            header("Location: chackout.php");
            exit;
        } else {
            $login_error = "❌ ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
        }
    }
}

$current_page = basename($_SERVER['PHP_SELF']);
// ตรวจสอบ profile_image อีกครั้งสำหรับผู้ใช้ที่อาจจะเพิ่งล็อกอิน
if (!isset($profile_image) && isset($_SESSION['user_id']) && isset($user)) {
     $profile_image = $user['profile_image'] ?? 'img/profiles/default.png';
     if (!file_exists($profile_image) || empty($user['profile_image'])) {
        $profile_image = 'img/profiles/default.png'; 
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

        .navbar-profile-img {
    width: 30px; 
    height: 30px; 
    border-radius: 50%;
    object-fit: cover;
    margin-right: 5px;
    border: 1px solid #289aa7ff;
}
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
        /* 🌸 พื้นหลังหลักแบบ Gradient + ลายดอกไม้โปร่ง */
body {
  padding-top: 120px;
  font-family: "Prompt", sans-serif;
  background: linear-gradient(135deg, #e2f5d2ff 0%, #f4fff8 100%);
  background-attachment: fixed;
  color: #333;
  overflow-x: hidden;
}

/* 🌸 เพิ่มลายดอกไม้ watercolor แบบนุ่มๆ */
body::before {
  content: "";
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: 
    url('https://cdn.pixabay.com/photo/2018/03/06/16/32/watercolor-3205345_1280.png') no-repeat center/cover;
  opacity: 0.15;
  filter: blur(6px);
  z-index: -1;
}

/* 🌿 เอฟเฟกต์แก้วใส (Glassmorphism) ให้กับกล่องหลัก */
.card {
  border-radius: 20px;
  border: none;
  background: rgba(249, 252, 248, 0.85);
  backdrop-filter: blur(10px);
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
  transition: transform 0.4s ease, box-shadow 0.4s ease;
}
.card:hover {
  transform: translateY(-6px);
  box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
}


/* 🌼 ปุ่ม */
.btn-success {
  background: linear-gradient(90deg, #6cbd45, #86e07d);
  border: none;
  color: white;
  font-weight: 600;
  border-radius: 50px;
  transition: 0.3s;
}
.btn-success:hover {
  background: linear-gradient(90deg, #57a13b, #7ddc73);
  transform: scale(1.05);
}

/* 🌹 ปุ่มออกจากระบบ */
.btn-danger {
  background: linear-gradient(90deg, #e14d4d, #f07b7b);
  border: none;
  border-radius: 50px;
  font-weight: 600;
  transition: 0.3s;
}
.btn-danger:hover {
  transform: scale(1.05);
}

/* 🪷 ช่องกรอกข้อมูล */
.form-control {
  border-radius: 12px;
  border: 1px solid #ddd;
  transition: all 0.3s ease;
}
.form-control:focus {
  border-color: #6cbd45;
  box-shadow: 0 0 0 0.25rem rgba(108, 189, 69, 0.25);
}

/* 🌼 หัวข้อ */
.icon-label {
  color: #5ca94a;
  font-weight: 600;
}

/* 🌺 รูปโปรไฟล์ */
.profile-img-container {
  width: 130px;
  height: 130px;
  border-radius: 50%;
  overflow: hidden;
  margin: 0 auto 20px;
  border: 5px solid #8eea84ff;
  box-shadow: 0 0 25px rgba(109, 195, 100, 0.5);
  transition: transform 0.4s ease;
}
.profile-img-container:hover {
  transform: scale(1.08);
}
.profile-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

/* 🌷 Header */
.page-header {
  background: 
    linear-gradient(rgba(0,0,0,0.35), rgba(0,0,0,0.3)),
    url('https://images.unsplash.com/photo-1519681393784-d120267933ba?auto=format&fit=crop&w=1500&q=80') center/cover no-repeat;
  position: relative;
}
.page-header h1 {
  color: #fff;
  text-shadow: 0 3px 8px rgba(0,0,0,0.5);
  font-weight: 700;
}

/* 💫 เอฟเฟกต์ fade-in */
@keyframes fadeInUp {
  0% { opacity: 0; transform: translateY(30px); }
  100% { opacity: 1; transform: translateY(0); }
}
.container, .card {
  animation: fadeInUp 0.8s ease both;
}
    </style>
</head>

<body>

    <!-- 🔹 Navbar -->
    <div class="container-fluid fixed-top">
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
                <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars text-primary"></span>
                </button>

                <div class="collapse navbar-collapse bg-white" id="navbarCollapse">
                    <div class="navbar-nav mx-auto">
                        <a href="index.php"
                            class="nav-item nav-link <?= ($current_page == 'index.php') ? 'active' : '' ?>">หน้าแรก</a>
                        <a href="shop.php"
                            class="nav-item nav-link <?= ($current_page == 'shop.php') ? 'active' : '' ?>">สต็อก</a>
                        <a href="shop-detail.php"
                            class="nav-item nav-link <?= ($current_page == 'shop-detail.php') ? 'active' : '' ?>">คิวขนส่ง</a>
                        <a href="cart.php"
                            class="nav-item nav-link <?= ($current_page == 'cart.php') ? 'active' : '' ?>">สั่งสินค้า</a>
                        <a href="contact.php"
                            class="nav-item nav-link <?= ($current_page == 'contact.php') ? 'active' : '' ?>">ติดต่อ</a>
                    </div>

                    <!-- ✅ ส่วนแสดงชื่อผู้ใช้ -->
<div class="d-flex align-items-center">
    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="d-flex align-items-center me-3">

            <!-- ✅ แสดงรูปโปรไฟล์ขนาดเล็กใน Navbar -->
            <?php
                $nav_profile_img = isset($user['profile_image']) && file_exists($user['profile_image']) && !empty($user['profile_image'])
                    ? htmlspecialchars($user['profile_image'])
                    : 'img/profiles/default.png';
            ?>
            <a href="chackout.php" class="me-3 fw-bold text-decoration-none d-flex align-items-center" 
               style="color: #e9d41cff; font-size: 18px;">
                <img src="<?= $nav_profile_img ?>" 
                     alt="Profile" 
                     class="navbar-profile-img me-2">
                <?= htmlspecialchars($_SESSION['username']) ?>
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


<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6"><?= isset($_SESSION['user_id']) ? 'โปรไฟล์ของฉัน' : 'เข้าสู่ระบบ' ?></h1>
</div>

<?php if (!isset($_SESSION['user_id']) || !isset($user)): // ตรวจสอบ $user ซ้ำด้วยเผื่อ session มีแต่ user ไม่มีใน DB ?> 
<div class="container my-5 py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-8">
            <div class="card shadow p-4 border-0 rounded-4">
                <h3 class="text-center mb-4 text-success">เข้าสู่ระบบ</h3>

                <?php if (!empty($login_error)): ?>
                    <div class="alert alert-danger text-center"><?= $login_error ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label icon-label">ชื่อผู้ใช้</label>
                        <input type="text" class="form-control p-3" id="username" name="username" placeholder="กรอกชื่อผู้ใช้" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label icon-label">รหัสผ่าน</label>
                        <input type="password" class="form-control p-3" id="password" name="password" placeholder="กรอกรหัสผ่านของคุณ" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100 py-2 rounded-pill fw-bold mt-3">เข้าสู่ระบบ</button>
                </form>

                <div class="text-center mt-4">
                    <p class="mb-0">ยังไม่มีบัญชี? <a href="register.php" class="text-primary fw-bold">สมัครสมาชิก</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php else: ?>
<div class="container my-5 py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card shadow border-0 rounded-4 p-5">
                <div class="text-center">
                    <h3 class="text-success mb-4">ข้อมูลสมาชิก</h3>
                </div>

                <?php if (!empty($success_message)): ?>
                    <div class="alert alert-success text-center fw-bold"><?= $success_message ?></div>
                <?php endif; ?>
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger text-center"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST" class="mt-4" enctype="multipart/form-data">
                    
                    <div class="text-center mb-4">
                        <div class="profile-img-container">
                            <img src="<?= htmlspecialchars($profile_image) ?>" id="image-preview" class="profile-img" alt="Profile Image">
                        </div>
                        <label for="profile_image" class="btn btn-outline-success btn-sm rounded-pill px-3 fw-bold">
                            <i class="fa fa-camera me-2"></i> เปลี่ยนรูปโปรไฟล์
                            <input type="file" name="profile_image" id="profile_image" accept="image/*" style="display: none;">
                        </label>
                    </div>
                    <div class="mb-3">
                        <label class="form-label icon-label"><i class="fa fa-user me-2"></i>ชื่อผู้ใช้</label>
                        <input type="text" name="new_username" class="form-control text-center fw-bold p-3" value="<?= htmlspecialchars($user['username']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label icon-label"><i class="fa fa-envelope me-2"></i>อีเมล</label>
                        <input type="email" name="new_email" class="form-control text-center p-3" value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label icon-label"><i class="fa fa-lock me-2"></i>เปลี่ยนรหัสผ่าน (เว้นว่างหากไม่เปลี่ยน)</label>
                        <input type="password" name="new_password" class="form-control text-center p-3" placeholder="กรอกรหัสผ่านใหม่">
                    </div>

                    <div class="mb-4">
                        <label class="form-label icon-label"><i class="fa fa-calendar me-2"></i>วันที่สมัคร</label>
                        <input type="datetime-local" name="new_date" class="form-control text-center p-3" value="<?= date('Y-m-d\TH:i', strtotime($user['created_at'])) ?>" required>
                    </div>

                    <div class="text-center mt-3">
                        <button type="submit" name="update_profile" class="btn btn-success rounded-pill px-5 py-2 fw-bold">💾 บันทึกข้อมูล</button>
                    </div>
                </form>

                <hr class="my-4">
                <div class="text-center">
                    <a href="logout.php" class="btn btn-danger rounded-pill px-5 py-2">
                        <i class="fas fa-sign-out-alt me-2"></i> ออกจากระบบ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php include 'footer.php'; ?> 

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// ตรวจสอบว่ามีการโหลด jQuery มาแล้วก่อนใช้งาน
if (window.jQuery) {
    $(document).ready(function() {
        $('#profile_image').on('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#image-preview').attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
            }
        });
    });
}
</script>
</body>
</html>