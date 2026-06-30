<?php
session_start();
require_once __DIR__ . '/../../db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $user_id = $_SESSION['user_id'];
    // Chỉ cho phép xóa đơn của chính mình
    $conn->query("DELETE FROM leave_requests WHERE id = $id AND user_id = $user_id");
}
header("Location: ../../home.php?page=leave");
?>