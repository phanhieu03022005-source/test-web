<div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white"><h5>Gửi thông báo mới</h5></div>
    <div class="card-body">
        <form action="admin/notifications/process.php" method="POST">
            <div class="mb-3">
                <label>Gửi theo:</label>
                <select name="target_type" class="form-control" onchange="toggleFields(this.value)">
                    <option value="all">Tất cả nhân viên</option>
                    <option value="department">Theo phòng ban</option>
                    <option value="individual">Chọn cá nhân</option>
                </select>
            </div>
            
            <div id="dept_field" class="mb-3" style="display:none;">
                <label>Chọn phòng ban:</label>
                <select name="department" class="form-control">
                    <?php 
                    $depts = $conn->query("SELECT DISTINCT department FROM users WHERE department IS NOT NULL");
                    while($d = $depts->fetch_assoc()) echo "<option value='{$d['department']}'>{$d['department']}</option>";
                    ?>
                </select>
            </div>

            <div id="user_field" class="mb-3" style="display:none;">
                <label>Chọn nhân viên (giữ Ctrl để chọn nhiều):</label>
                <select name="user_ids[]" class="form-control" multiple style="height: 100px;">
                    <?php 
                    $users = $conn->query("SELECT id, fullname FROM users");
                    while($u = $users->fetch_assoc()) echo "<option value='{$u['id']}'>{$u['fullname']}</option>";
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label>Nội dung:</label>
                <textarea name="message" class="form-control" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-success">Gửi thông báo</button>
        </form>
    </div>
</div>

<script>
function toggleFields(val) {
    document.getElementById('dept_field').style.display = (val == 'department') ? 'block' : 'none';
    document.getElementById('user_field').style.display = (val == 'individual') ? 'block' : 'none';
}
</script>

<div class="card shadow-sm">
    <div class="card-header bg-secondary text-white"><h5>Lịch sử thông báo đã gửi</h5></div>
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr><th>Nội dung</th><th>Ngày gửi</th><th>Thao tác</th></tr>
            </thead>
            <tbody>
                <?php
                $history = $conn->query("SELECT message, created_at, GROUP_CONCAT(id) as ids 
                                         FROM notifications GROUP BY message, created_at ORDER BY created_at DESC");
                while($row = $history->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['message']); ?></td>
                    <td><?php echo $row['created_at']; ?></td>
                    <td>
                        <a href="admin/notifications/delete.php?ids=<?php echo $row['ids']; ?>" 
                           class="btn btn-danger btn-sm" onclick="return confirm('Bạn chắc chắn muốn xóa?')">Xóa</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>