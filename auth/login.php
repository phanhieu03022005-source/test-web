<?php
session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4 shadow-sm p-4 bg-white rounded">
            <h2 class="text-center mb-4">Đăng nhập</h2>

            <?php if(isset($_SESSION['message'])): ?>
                <div class="alert alert-danger text-center">
                    <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
                </div>
            <?php endif; ?>

            <form action="login_process.php" method="POST">
                <div class="mb-3">
                    <label class="form-label">Email:</label>
                    <input type="email" name="email" class="form-control" placeholder="Nhập email" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Mật khẩu:</label>
                    <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu" required>
                </div>

                <div class="mb-3 text-end">
                    <a href="forgot_password.php" class="text-decoration-none small">Quên mật khẩu?</a>
                </div>

                <button type="submit" name="login" class="btn btn-primary w-100 py-2">Đăng nhập</button>
            </form>

            <p class="text-center mt-3">
                Chưa có tài khoản? <a href="index.php" class="text-decoration-none">Đăng ký ngay</a>
            </p>
        </div>
    </div>
</div>

</body>
</html>