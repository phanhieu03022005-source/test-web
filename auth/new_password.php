<?php
session_start();
include '../db.php';
if(isset($_POST['update'])){
    if($_POST['pass1'] === $_POST['pass2'] && strlen($_POST['pass1']) >= 8){
        $pass = password_hash($_POST['pass1'], PASSWORD_DEFAULT);
        $email = $_SESSION['reset_email'];
        $conn->query("UPDATE users SET password = '$pass' WHERE email = '$email'");
        session_destroy();
        header("Location: login.php"); exit();
    } else { $error = "Mật khẩu không khớp hoặc quá ngắn!"; }
}
?>
<!DOCTYPE html>
<html lang="vi"><head><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light mt-5"><div class="container col-md-4 shadow p-4 bg-white rounded">
    <h3>Đổi mật khẩu</h3>
    <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="POST">
        <input type="password" name="pass1" class="form-control mb-3" placeholder="Mật khẩu mới" required>
        <input type="password" name="pass2" class="form-control mb-3" placeholder="Nhập lại mật khẩu" required>
        <button type="submit" name="update" class="btn btn-success w-100">Cập nhật</button>
    </form>
</div></body></html>