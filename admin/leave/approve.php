<?php
session_start();
require_once __DIR__ . '/../../db.php';

// Chỉ admin mới được quyền thực hiện
if ($_SESSION['email'] !== 'hp5836697@gmail.com') {
    die("Truy cập bị từ chối!");
}

if (isset($_GET['id']) && isset($_GET['action'])) {
    $id = $_GET['id'];
    $action = ($_GET['action'] == 'approve') ? 'Đã duyệt' : 'Từ chối';
    
    $stmt = $conn->prepare("UPDATE leave_requests SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $action, $id);
    $stmt->execute();
}
header("Location: ../../home.php?page=admin_leave");
?>