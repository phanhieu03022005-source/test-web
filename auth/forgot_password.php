<?php
session_start();
include '../db.php';
require '../PHPMailer/Exception.php';
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;

if(isset($_POST['send_otp'])){
    $email = trim($_POST['email']);
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if($stmt->get_result()->num_rows > 0) {
        $otp = rand(100000, 999999);
        $_SESSION['reset_email'] = $email;
        $_SESSION['reset_otp'] = $otp;
        $_SESSION['otp_time'] = time();

        // Gửi mail (Thay thông tin cấu hình mail của bạn vào đây)
        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; $mail->SMTPAuth = true;
        $mail->Username = 'phanhieu03022005@gmail.com'; 
        $mail->Password = 'adkyosjkruvzlekl'; 
        $mail->Port = 587; $mail->setFrom('phanhieu03022005@gmail.com');
        $mail->addAddress($email);
        $mail->Subject = 'Mã xác thực đổi mật khẩu';
        $mail->Body = 'Mã OTP của bạn là: ' . $otp;
        $mail->send();
        
        header("Location: reset_otp.php");
        exit();
    } else { $error = "Email không tồn tại!"; }
}
?>
<!DOCTYPE html>
<html lang="vi"><head><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light mt-5"><div class="container col-md-4 shadow p-4 bg-white rounded">
    <h3 class="text-center">Quên mật khẩu</h3>
    <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="POST"><input type="email" name="email" class="form-control mb-3" placeholder="Nhập email" required>
    <button type="submit" name="send_otp" class="btn btn-primary w-100">Gửi mã OTP</button></form>
</div></body></html>