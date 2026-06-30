<?php
session_start();
require_once '../../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $target_dir = "../../uploads/";
    if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);

    $filename = time() . "_" . basename($_FILES["project_file"]["name"]);
    if (move_uploaded_file($_FILES["project_file"]["tmp_name"], $target_dir . $filename)) {
        $stmt = $conn->prepare("INSERT INTO projects (title, description, file_path, deadline, assigned_to) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $_POST['title'], $_POST['description'], $filename, $_POST['deadline'], $_POST['assigned_to']);
        $stmt->execute();
    }
    header("Location: ../../home.php?page=admin_projects&status=success");
}
?>