<div class="card shadow-sm">
    <div class="card-header bg-info text-white"><h5>Thông báo của tôi</h5></div>
    <div class="card-body">
        <?php
        $user_id = $_SESSION['user_id'];
        // Lấy thông báo cá nhân hoặc thông báo chung (NULL)
        $sql = "SELECT * FROM notifications 
                WHERE user_id = ? OR user_id IS NULL 
                ORDER BY created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='alert alert-light border mb-2'>
                        <strong>" . htmlspecialchars($row['message']) . "</strong>
                        <br><small class='text-muted'>" . $row['created_at'] . "</small>
                      </div>";
            }
        } else {
            echo "<p>Hiện không có thông báo mới.</p>";
        }
        ?>
    </div>
</div>