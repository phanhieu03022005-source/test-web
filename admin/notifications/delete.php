<?php
session_start();
require_once __DIR__ . '/../../db.php';

// Chỉ admin
if ($_SESSION['email'] === 'hp5836697@gmail.com' && isset($_GET['ids'])) {
    $ids = $_GET['ids']; // Ví dụ: "1,2,3"
    $conn->query("DELETE FROM notifications WHERE id IN ($ids)");
}
header("Location: ../../home.php?page=admin_notifications");
?>