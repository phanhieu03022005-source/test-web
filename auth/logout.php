<?php
session_start();
session_destroy();
// Chuyển hướng về trang chủ ở thư mục gốc
header("Location: ../home.php"); 
exit();
?>