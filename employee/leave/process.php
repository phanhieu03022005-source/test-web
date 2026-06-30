<?php
session_start();
require_once __DIR__ . '/../../db.php';

if (isset($_POST['submit_leave'])) {
    $start = $_POST['start_date']; // Format: YYYY-MM-DD
    $end = $_POST['end_date'];
    
    // Hàm kiểm tra tính hợp lệ của một ngày (YYYY-MM-DD)
    function isValidDate($date) {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

    // 1. Kiểm tra định dạng ngày tháng có thật không (Ví dụ: 31/04 là sai)
    if (!isValidDate($start) || !isValidDate($end)) {
        $_SESSION['error'] = "Ngày tháng không hợp lệ!";
    } 
    // 2. Kiểm tra ngày bắt đầu không được nhỏ hơn ngày hôm nay
    elseif ($start < date('Y-m-d')) {
        $_SESSION['error'] = "Ngày bắt đầu phải là ngày trong tương lai!";
    } 
    // 3. Kiểm tra ngày kết thúc không được nhỏ hơn ngày bắt đầu
    elseif ($end < $start) {
        $_SESSION['error'] = "Ngày kết thúc không được trước ngày bắt đầu!";
    } 
    else {
        // Hợp lệ: Thực hiện Insert vào database
        $user_id = $_SESSION['user_id'];
        $reason = $_POST['reason'];
        
        $stmt = $conn->prepare("INSERT INTO leave_requests (user_id, reason, start_date, end_date, status) VALUES (?, ?, ?, ?, 'Chờ duyệt')");
        $stmt->bind_param("isss", $user_id, $reason, $start, $end);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "Đơn nghỉ phép đã được gửi!";
        } else {
            $_SESSION['error'] = "Lỗi hệ thống, vui lòng thử lại.";
        }
    }

    header("Location: ../../home.php?page=leave");
    exit();
}
?>