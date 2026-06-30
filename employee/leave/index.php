<?php
// Xử lý hiển thị thông báo phản hồi từ hệ thống
if(isset($_SESSION['error'])) {
    echo "<div class='alert alert-danger'>".$_SESSION['error']."</div>";
    unset($_SESSION['error']);
}
if(isset($_SESSION['message'])) {
    echo "<div class='alert alert-success'>".$_SESSION['message']."</div>";
    unset($_SESSION['message']);
}
?>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-warning"><h5>Viết đơn xin nghỉ</h5></div>
    <div class="card-body">
        <form action="employee/leave/process.php" method="POST">
            <textarea name="reason" class="form-control mb-2" placeholder="Lý do..." required></textarea>
            <div class="row">
                <div class="col"><input type="date" name="start_date" class="form-control" required></div>
                <div class="col"><input type="date" name="end_date" class="form-control" required></div>
            </div>
            <button type="submit" name="submit_leave" class="btn btn-primary mt-2">Gửi đơn</button>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-info text-white"><h5>Lịch sử đơn nghỉ</h5></div>
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Từ</th>
                    <th>Đến</th>
                    <th>Lý do</th>
                    <th>Ngày gửi đơn</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Truy vấn lấy dữ liệu theo user_id, mới nhất nằm trên cùng
                // LƯU Ý: Nếu cột thời gian của bạn tên khác 'created_at', hãy đổi lại tại đây
                $res = $conn->query("SELECT * FROM leave_requests WHERE user_id = ".$_SESSION['user_id']." ORDER BY created_at DESC");
                
                if($res && $res->num_rows > 0):
                    while($row = $res->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['start_date']; ?></td>
                    <td><?php echo $row['end_date']; ?></td>
                    <td><?php echo htmlspecialchars($row['reason']); ?></td>
                    <td><?php echo $row['created_at']; ?></td>
                    <td>
                        <?php 
                            // Định dạng badge cho trạng thái
                            $status_class = ($row['status'] == 'Chờ duyệt') ? 'bg-warning text-dark' : 'bg-secondary';
                        ?>
                        <span class="badge <?php echo $status_class; ?>"><?php echo $row['status']; ?></span>
                    </td>
                    <td>
                        <a href="employee/leave/delete.php?id=<?php echo $row['id']; ?>" 
                           class="btn btn-danger btn-sm" 
                           onclick="return confirm('Bạn có chắc chắn muốn xóa đơn này không?')">Xóa</a>
                    </td>
                </tr>
                <?php endwhile; 
                else: ?>
                <tr>
                    <td colspan="6" class="text-center">Chưa có đơn nghỉ nào.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>