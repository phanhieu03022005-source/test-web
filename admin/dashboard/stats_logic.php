<?php
// Kiểm tra nếu biến $conn chưa tồn tại thì dừng lại
if (!isset($conn)) die("Lỗi kết nối CSDL!");

// 1. Thống kê nhanh
$total_users = getStat($conn, "SELECT COUNT(*) FROM users");
$present = getStat($conn, "SELECT COUNT(*) FROM attendance WHERE date = CURDATE() AND status = 'Có mặt'");
$pending_leaves = getStat($conn, "SELECT COUNT(*) FROM leave_requests WHERE status = 'Chờ duyệt'");

// 2. Thống kê nhân sự theo phòng ban
// Giả định bảng 'users' có cột 'department'. Nếu tên cột khác, hãy đổi tên 'department' bên dưới.
$dept_query = "SELECT department, COUNT(*) as count 
               FROM users 
               GROUP BY department";
$dept_result = $conn->query($dept_query);
?>