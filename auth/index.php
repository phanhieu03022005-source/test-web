<?php
session_start();
require_once '../db.php';
require_once '../PHPMailer/Exception.php';
require_once '../PHPMailer/PHPMailer.php';
require_once '../PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if(isset($_POST['register'])){
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $raw_pass = $_POST['pass'];

    $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    if($check_stmt->get_result()->num_rows > 0) {
        $error = "Email này đã được đăng ký!";
    } else {
        $uppercase = preg_match('@[A-Z]@', $raw_pass);
        $lowercase = preg_match('@[a-z]@', $raw_pass);
        $number    = preg_match('@[0-9]@', $raw_pass);
        $special   = preg_match('@[^\w]@', $raw_pass);

        if(!$uppercase || !$lowercase || !$number || !$special || strlen($raw_pass) < 8) {
            $error = "Mật khẩu yếu! Cần ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt.";
        } else {
            $pass = password_hash($raw_pass, PASSWORD_DEFAULT);
            $otp = rand(100000, 999999);
            $_SESSION['temp_user'] = ['name' => $name, 'email' => $email, 'pass' => $pass, 'otp' => $otp, 'created_at' => time()];

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'phanhieu03022005@gmail.com'; 
                $mail->Password   = 'adkyosjkruvzlekl'; 
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;
                $mail->CharSet    = 'UTF-8';
                $mail->setFrom('phanhieu03022005@gmail.com', 'Hệ thống');
                $mail->addAddress($email);
                $mail->Subject = 'Mã xác thực OTP';
                $mail->Body    = 'Mã OTP của bạn là: ' . $otp;
                $mail->send();
                
                header("Location: verify.php");
                exit();
            } catch (Exception $e) {
                $error = "Lỗi gửi mail: " . $mail->ErrorInfo;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký tài khoản</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4 shadow p-4 bg-white rounded">
            <h2 class="text-center mb-4">Đăng ký tài khoản</h2>
            
            <?php if(isset($error)): ?>
                <div class="alert alert-danger text-center"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Họ tên</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mật khẩu</label>
                    <input type="password" name="pass" class="form-control" required>
                    <small class="text-muted">8+ ký tự, bao gồm chữ hoa, thường, số & ký tự đặc biệt.</small>
                </div>
                <button type="submit" name="register" class="btn btn-success w-100 py-2">Đăng ký</button>
            </form>
            
            <p class="text-center mt-3">Đã có tài khoản? <a href="login.php" class="text-decoration-none">Đăng nhập</a></p>
        </div>
    </div>
</div>

</body>
</html>