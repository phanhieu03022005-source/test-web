<?php
session_start();
require_once '../../db.php';

// 1. Kiểm tra xác thực (Session & Quyền Admin)
if (!isset($_SESSION['user_id']) || $_SESSION['email'] !== 'hp5836697@gmail.com') {
    header("Location: ../../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // 2. Xóa các file báo cáo của nhân viên trước
    $reports = $conn->query("SELECT report_file FROM project_reports WHERE project_id = $id AND report_file IS NOT NULL");
    while ($r = $reports->fetch_assoc()) {
        $report_file_path = "../../uploads/reports/" . $r['report_file'];
        if (file_exists($report_file_path)) {
            unlink($report_file_path);
        }
    }

    // 3. Xóa file gốc của dự án
    $project = $conn->query("SELECT file_path FROM projects WHERE id = $id")->fetch_assoc();
    if ($project) {
        $file_path = "../../uploads/" . $project['file_path'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }

    // 4. Bắt đầu giao dịch (Transaction) để đảm bảo dữ liệu toàn vẹn
    $conn->begin_transaction();
    try {
        $conn->query("DELETE FROM project_reports WHERE project_id = $id");
        $conn->query("DELETE FROM projects WHERE id = $id");
        $conn->commit(); // Lưu thay đổi
    } catch (Exception $e) {
        $conn->rollback(); // Nếu lỗi thì quay lại trạng thái cũ
    }
}

// 5. Chuyển hướng về trang quản lý
header("Location: ../../home.php?page=admin_projects");
exit();
?>