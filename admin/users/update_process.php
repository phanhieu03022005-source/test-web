<?php
require_once __DIR__ . '/../../db.php';
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $dept = $_POST['department'];
    $stmt = $conn->prepare("UPDATE users SET department = ? WHERE id = ?");
    $stmt->bind_param("si", $dept, $id);
    $stmt->execute();
    header("Location: ../../home.php?page=users_list");
}
?>