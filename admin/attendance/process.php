<?php
session_start();
require_once __DIR__ . '/../../db.php';
$uid = $_GET['uid'];
$status = $_GET['status'];
$date = date('Y-m-d');

// Xóa nếu đã điểm danh ngày hôm nay rồi thì cập nhật lại
$conn->query("DELETE FROM attendance WHERE user_id = $uid AND date = '$date'");
$conn->query("INSERT INTO attendance (user_id, status, date) VALUES ($uid, '$status', '$date')");

header("Location: ../../home.php?page=admin_attendance");
?>