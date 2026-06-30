<?php
session_start();
include '../db.php';

if (!isset($_SESSION['temp_user'])) {
    header("Location: index.php");
    exit();
}

$error = "";
if(isset($_POST['verify'])){
    $user_otp = $_POST['otp'];
    $temp = $_SESSION['temp_user'];
    
    if((time() - $temp['created_at']) > 120){
        $error = "Mã đã hết hạn!";
    } elseif($user_otp == $temp['otp']){
        $stmt = $conn->prepare("INSERT INTO users (fullname, email, password, is_verified) VALUES (?, ?, ?, 1)");
        $stmt->bind_param("sss", $temp['name'], $temp['email'], $temp['pass']);
        $stmt->execute();
        
        unset($_SESSION['temp_user']);
        $_SESSION['message'] = "Đăng ký thành công!";
        header("Location: login.php");
        exit();
    } else {
        $error = "Sai mã OTP!";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác thực OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4 shadow p-4 bg-white rounded text-center">
            <h2 class="mb-4">Xác thực OTP</h2>
            <p class="text-muted">Nhập mã 6 số chúng tôi đã gửi vào Email.</p>
            
            <?php if(!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <input type="text" name="otp" class="form-control text-center fs-3" 
                           placeholder="000000" maxlength="6" required style="letter-spacing: 5px;">
                </div>
                <button type="submit" name="verify" class="btn btn-primary w-100 py-2">Xác nhận</button>
            </form>
            
            <p class="mt-3 small text-muted">Chưa nhận được mã? <a href="index.php" class="text-decoration-none">Đăng ký lại</a></p>
        </div>
    </div>
</div>

</body>
</html>