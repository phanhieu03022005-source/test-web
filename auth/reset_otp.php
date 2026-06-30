<?php
session_start();
if(isset($_POST['verify'])){
    if($_POST['otp'] == $_SESSION['reset_otp'] && (time() - $_SESSION['otp_time']) < 120){
        header("Location: new_password.php"); exit();
    } else { $error = "OTP sai hoặc hết hạn!"; }
}
?>
<!DOCTYPE html>
<html lang="vi"><head><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light mt-5"><div class="container col-md-4 shadow p-4 bg-white rounded text-center">
    <h3>Nhập mã OTP</h3>
    <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="POST"><input type="text" name="otp" class="form-control mb-3 text-center" maxlength="6" required>
    <button type="submit" name="verify" class="btn btn-primary w-100">Xác nhận</button></form>
</div></body></html>