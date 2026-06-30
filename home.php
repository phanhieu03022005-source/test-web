<?php

session_start();

require_once __DIR__ . '/db.php';



// 1. Kiểm tra đăng nhập

if (!isset($_SESSION['user_id'])) {

    header("Location: auth/login.php");

    exit();

}



$email = $_SESSION['email'] ?? '';

$isAdmin = ($email === 'hp5836697@gmail.com');

$page = $_GET['page'] ?? 'dashboard';



// Hàm lấy dữ liệu an toàn để tránh lỗi Fatal Error

function getStat($conn, $sql) {

    $res = $conn->query($sql);

    return ($res && $res->num_rows > 0) ? $res->fetch_row()[0] : 0;

}

?>



<!DOCTYPE html>

<html lang="vi">

<head>

    <meta charset="UTF-8">

    <title>Hệ thống Quản lý Nhân sự</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>

        .sidebar { width: 250px; height: 100vh; position: fixed; background: #212529; color: white; padding: 20px; overflow-y: auto; }

        .main-content { margin-left: 250px; padding: 20px; }

        .card:hover { transform: scale(1.01); transition: 0.3s; }

    </style>

</head>

<body>



<div class="sidebar">

    <h4><?php echo $isAdmin ? 'Admin Panel' : 'Nhân viên'; ?></h4>

    <hr>

    <ul class="nav flex-column">

    

        <?php if ($isAdmin): ?>

            <li class="nav-item"><a href="?page=dashboard" class="nav-link text-white"><i class="fas fa-home"></i> Trang chủ</a></li>

            <li class="nav-item"><a href="?page=users_list" class="nav-link text-white">Quản lý nhân viên</a></li>

            <li class="nav-item"><a href="?page=admin_projects" class="nav-link text-white">Quản lý dự án</a></li>

            <li class="nav-item"><a href="?page=admin_leave" class="nav-link text-white">Duyệt đơn nghỉ</a></li>

            <li class="nav-item"><a href="?page=admin_notifications" class="nav-link text-white">Gửi thông báo</a></li>

            <li class="nav-item"><a href="?page=admin_attendance" class="nav-link text-white">Điểm danh</a></li>

            <li class="nav-item"><a href="?page=admin_salary" class="nav-link text-white">Tính lương</a></li>

        <?php else: ?>

            <li class="nav-item"><a href="?page=profile" class="nav-link text-white">Hồ sơ cá nhân</a></li>

            <li class="nav-item"><a href="?page=my_projects" class="nav-link text-white">Dự án của tôi</a></li>

            <li class="nav-item"><a href="?page=leave" class="nav-link text-white">Viết đơn xin nghỉ</a></li>

            <li class="nav-item"><a href="?page=my_salary" class="nav-link text-white">Xem lương</a></li>

            <li class="nav-item"><a href="?page=my_notifications" class="nav-link text-white">Xem thông báo</a></li>

            <li class="nav-item"><a href="?page=my_checkin" class="nav-link text-white">Điểm danh Check-in</a></li>
    

        <?php endif; ?>

        <hr><li class="nav-item"><a href="auth/logout.php" class="nav-link text-danger">Đăng xuất</a></li>

    </ul>

</div>



<div class="main-content">

    <?php

   


    // NAVIGATION SWITCH

    switch ($page) {
        // TRANG CHỦ
        case 'dashboard': 
            if ($isAdmin) {
                // Bạn có thể dùng include file rời hoặc để code dashboard tại đây
                include __DIR__ . '/admin/dashboard/index.php'; 
            } else {
                echo "<h3>Chào mừng bạn đến với hệ thống!</h3>";
            }
            break;

        // CÁC CHỨC NĂNG ADMIN
        case 'users_list': include __DIR__ . '/admin/users/list.php'; break;
        case 'admin_projects': include __DIR__ . '/admin/projects/manage.php'; break;
        case 'admin_attendance': include __DIR__ . '/admin/attendance/list.php'; break;
        case 'admin_attendance_detail': include __DIR__ . '/admin/attendance/detail.php'; break;
        case 'admin_salary': include __DIR__ . '/admin/salary/calculate.php'; break;
        case 'admin_leave': include __DIR__ . '/admin/leave/list.php'; break;
        case 'admin_notifications': include __DIR__ . '/admin/notifications/send.php'; break;

        // CÁC CHỨC NĂNG NHÂN VIÊN
        case 'profile': include __DIR__ . '/employee/profile/index.php'; break;
        case 'my_projects': include __DIR__ . '/employee/projects/view.php'; break;
        case 'leave': include __DIR__ . '/employee/leave/index.php'; break;
        case 'my_salary': include __DIR__ . '/employee/salary/view.php'; break;
        case 'my_notifications': 
            $target = __DIR__ . '/employee/notifications/index.php';
            if (file_exists($target)) { include $target; } else { echo "File not found."; }
            break;
        case 'my_checkin': 
            include __DIR__ . '/employee/attendance/my_history.php'; 
            break;

        default: 
            if ($page != 'dashboard') echo "<h3>Trang không tồn tại</h3>"; 
            break;
    }

    ?>

</div>



</body>

</html>