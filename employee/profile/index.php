<?php
// Đảm bảo đã có kết nối DB và session
$user_id = $_SESSION['user_id'];

// Lấy thông tin nhân viên từ Database
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<div class="card shadow-sm">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0">Hồ sơ cá nhân</h5>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-sm-3"><strong>ID Nhân viên:</strong></div>
            <div class="col-sm-9"><?php echo $user['id']; ?></div>
        </div>
        
        <div class="row mb-3">
            <div class="col-sm-3"><strong>Email:</strong></div>
            <div class="col-sm-9"><?php echo htmlspecialchars($user['email']); ?></div>
        </div>
        
        <div class="row mb-3">
            <div class="col-sm-3"><strong>Họ tên:</strong></div>
            <div class="col-sm-9"><?php echo htmlspecialchars($user['fullname']); ?></div>
        </div>
        
        <div class="row mb-3">
            <div class="col-sm-3"><strong>Phòng ban:</strong></div>
            <div class="col-sm-9"><?php echo htmlspecialchars($user['department'] ?? 'Chưa cập nhật'); ?></div>
        </div>
    </div>
</div>