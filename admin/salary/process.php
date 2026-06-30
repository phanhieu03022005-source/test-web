<?php
session_start();
require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['email'] === 'hp5836697@gmail.com') {
    $target = $_POST['target'] ?? 'all';
    $bonus = (int)($_POST['bonus'] ?? 0);
    $deductions = (int)($_POST['deductions'] ?? 0);
    $note = $conn->real_escape_string($_POST['note'] ?? '');
    
    // Xây dựng truy vấn chọn đối tượng
    if ($target == 'all') {
        $sql = "SELECT id FROM users WHERE email != 'hp5836697@gmail.com'";
    } elseif ($target == 'dept') {
        $dept = $conn->real_escape_string($_POST['department']);
        $sql = "SELECT id FROM users WHERE department = '$dept'";
    } else {
        $uid = (int)$_POST['user_id'];
        $sql = "SELECT id FROM users WHERE id = $uid";
    }

    $result = $conn->query($sql);
    while($u = $result->fetch_assoc()) {
        $data = calculateSalaryForUser($conn, $u['id'], $deductions, $bonus, $note);
        
        // Gửi thông báo
        $msg = "Lương tháng " . date('m/Y') . ": " . number_format($data['final']) . " VNĐ.";
        $conn->query("INSERT INTO notifications (user_id, message, created_at) VALUES ({$u['id']}, '$msg', NOW())");
    }
    header("Location: ../../home.php?page=admin_salary&status=success");
} else {
    header("Location: ../../home.php?page=admin_salary&status=error");
}
?>