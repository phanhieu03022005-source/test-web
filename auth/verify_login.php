<?php
session_start();
include '../db.php';

// Kiểm tra xem có dữ liệu đăng nhập tạm thời không
if (!isset($_SESSION['temp_login'])) {
    header("Location: login.php");
    exit();
}

$error = "";

// Xử lý khi nhấn nút Xác nhận
if (isset($_POST['verify_login'])) {
    $user_otp = $_POST['otp'];
    $temp = $_SESSION['temp_login'];

    // 1. Kiểm tra mã OTP và thời hạn (120 giây)
    if ($user_otp == $temp['otp'] && (time() - $temp['created_at']) < 120) {
        
        // 2. Lấy thông tin user từ database
        $stmt = $conn->prepare("SELECT id, fullname, email FROM users WHERE email = ?");
        $stmt->bind_param("s", $temp['email']);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        
        if ($user) {
            // 3. Ghi nhật ký đăng nhập (Lưu IP của máy truy cập)
            $ip_address = $_SERVER['REMOTE_ADDR'];
            $stmt_log = $conn->prepare("INSERT INTO login_logs (user_id, email, ip_address, login_time) VALUES (?, ?, ?, NOW())");
            $stmt_log->bind_param("iss", $user['id'], $user['email'], $ip_address);
            $stmt_log->execute();

            // 4. Thiết lập Session chính thức
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['email'] = $user['email'];
            
            // Thiết lập quyền quản trị
            $_SESSION['role'] = ($user['email'] === "hp5836697@gmail.com") ? 'admin' : 'user';
            
            // Xóa dữ liệu tạm và chuyển hướng
            unset($_SESSION['temp_login']);
            header("Location: ../home.php");
            exit();
        } else {
            $error = "Tài khoản không tồn tại.";
        }
    } else {
        $error = "Mã OTP sai hoặc đã hết hạn!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác thực đăng nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4 shadow p-4 bg-white rounded text-center">
            <h2 class="mb-4">Xác thực đăng nhập</h2>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <input type="text" name="otp" class="form-control text-center fs-3" 
                           placeholder="000000" maxlength="6" required style="letter-spacing: 5px;">
                </div>
                <button type="submit" name="verify_login" class="btn btn-primary w-100 py-2">Xác nhận</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>