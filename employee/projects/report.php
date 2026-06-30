<?php
session_start();
require_once '../../db.php';

// 1. Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 2. Lấy dữ liệu an toàn
    $project_id = (int)$_POST['project_id'];
    $progress   = (int)$_POST['progress'];
    $note       = trim($_POST['note']);
    
    // 3. Xử lý file tải lên
    $filename = null;
    if (isset($_FILES['report_file']) && $_FILES['report_file']['error'] == 0) {
        $target_dir = "../../uploads/reports/";
        
        // Tạo thư mục nếu chưa có
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        // Đổi tên file để tránh trùng lặp và bảo mật
        $extension = pathinfo($_FILES["report_file"]["name"], PATHINFO_EXTENSION);
        $filename = time() . "_" . bin2hex(random_bytes(4)) . "." . $extension;
        
        // Di chuyển file
        if (!move_uploaded_file($_FILES["report_file"]["tmp_name"], $target_dir . $filename)) {
            $filename = null; // Nếu lỗi upload, đặt lại là null
        }
    }

    // 4. Lưu vào Database bằng Prepared Statement (Chống SQL Injection)
    $stmt = $conn->prepare("INSERT INTO project_reports (project_id, user_id, progress, note, report_file) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiss", $project_id, $_SESSION['user_id'], $progress, $note, $filename);
    
    if ($stmt->execute()) {
        $status = "success";
    } else {
        $status = "error";
    }
    
    // 5. Chuyển hướng kèm trạng thái
    header("Location: ../../home.php?page=my_projects&report=$status");
    exit();
}
?>