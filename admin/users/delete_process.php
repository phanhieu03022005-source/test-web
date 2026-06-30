<?php
require_once __DIR__ . '/../../db.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // 1. Xóa dữ liệu liên quan trong các bảng khác trước
    // Dùng lệnh query bình thường vì nếu bảng không tồn tại thì cũng không sao
    $conn->query("DELETE FROM salary_records WHERE user_id = $id");
    $conn->query("DELETE FROM attendance WHERE user_id = $id");
    // Bạn có thể thêm các bảng khác ở đây nếu có (ví dụ: leave, notifications...)

    // 2. Xóa nhân viên chính
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        // Xóa thành công
        header("Location: ../../home.php?page=users_list&status=success");
    } else {
        // Nếu vẫn lỗi, hiển thị thông báo để debug
        echo "Lỗi khi xóa nhân viên: " . $conn->error;
    }
    $stmt->close();
}
?>