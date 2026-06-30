<?php
session_start();
require_once __DIR__ . '/../../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['email'] === 'hp5836697@gmail.com') {
    $user_id = ($_POST['user_id'] == 0) ? NULL : $_POST['user_id'];
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $message);
    $stmt->execute();
    header("Location: ../../home.php?page=admin_notifications&status=sent");
}
?>