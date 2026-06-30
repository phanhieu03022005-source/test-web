<?php
session_start();
include '../db.php';
require '../PHPMailer/Exception.php';
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if(isset($_POST['login'])){
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // 1. Lấy thông tin user
    $stmt = $conn->prepare("SELECT id, fullname, password, failed_attempts, lockout_time FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if(!$user) {
        $_SESSION['message'] = "Email không tồn tại!";
        header("Location: login.php");
        exit();
    }

    // 2. Kiểm tra trạng thái khóa
    if($user['lockout_time'] !== NULL) {
        if(time() < strtotime($user['lockout_time'])) {
            $_SESSION['message'] = "Tài khoản đang bị khóa. Vui lòng thử lại sau 5 phút.";
            header("Location: login.php");
            exit();
        } else {
            $conn->query("UPDATE users SET failed_attempts = 0, lockout_time = NULL WHERE email = '$email'");
            $user['failed_attempts'] = 0;
        }
    }

    // 3. Xác thực mật khẩu
    if(password_verify($password, $user['password'])){
        // Đăng nhập thành công, reset trạng thái khóa
        $conn->query("UPDATE users SET failed_attempts = 0, lockout_time = NULL WHERE email = '$email'");

        // Thiết lập Session Admin/User
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = ($user['email'] === "hp5836697@gmail.com") ? 'admin' : 'user';

        // Tạo OTP
        $otp = rand(100000, 999999);
        $_SESSION['temp_login'] = ['email' => $email, 'fullname' => $user['fullname'], 'otp' => $otp, 'created_at' => time()];
        
        // Gửi mail OTP
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
            $mail->Subject = 'OTP xác thực';
            $mail->Body    = 'Mã OTP của bạn là: ' . $otp;
            $mail->send();
            
            header("Location: verify_login.php");
            exit();
        } catch (Exception $e) {
            $_SESSION['message'] = "Lỗi gửi mail: " . $mail->ErrorInfo;
            header("Location: login.php");
            exit();
        }
        
    } else {
        // 4. Xử lý khi sai mật khẩu
        $new_attempts = $user['failed_attempts'] + 1;
        
        if($new_attempts >= 5) {
            $lockout_time = date('Y-m-d H:i:s', strtotime('+5 minutes'));
            $conn->query("UPDATE users SET failed_attempts = 0, lockout_time = '$lockout_time' WHERE email = '$email'");
            $_SESSION['message'] = "Sai quá 5 lần. Tài khoản bị khóa 5 phút.";
        } else {
            $conn->query("UPDATE users SET failed_attempts = $new_attempts WHERE email = '$email'");
            $_SESSION['message'] = "Sai mật khẩu! Còn " . (5 - $new_attempts) . " lần thử.";
        }
        header("Location: login.php");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>