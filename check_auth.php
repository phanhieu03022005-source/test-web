<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra xem đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    header("Location: /my_app/auth/login.php");
    exit();
}

// Hàm kiểm tra quyền Admin
function checkAdmin() {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        die("Truy cập bị từ chối! Bạn không phải là Admin.");
    }
}
?>