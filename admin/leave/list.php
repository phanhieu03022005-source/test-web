<div class="card shadow-sm">
    <div class="card-header bg-primary text-white"><h5>Quản lý đơn xin nghỉ của nhân viên</h5></div>
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nhân viên</th><th>Lý do</th><th>Thời gian</th><th>Trạng thái</th><th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT l.*, u.fullname FROM leave_requests l 
                        JOIN users u ON l.user_id = u.id ORDER BY l.created_at DESC";
                $res = $conn->query($sql);
                while($row = $res->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                    <td><?php echo htmlspecialchars($row['reason']); ?></td>
                    <td><?php echo $row['start_date'] . ' đến ' . $row['end_date']; ?></td>
                    <td>
                        <span class="badge <?php echo ($row['status'] == 'Đã duyệt') ? 'bg-success' : (($row['status'] == 'Từ chối') ? 'bg-danger' : 'bg-warning'); ?>">
                            <?php echo $row['status']; ?>
                        </span>
                    </td>
                    <td>
                        <a href="admin/leave/approve.php?id=<?php echo $row['id']; ?>&action=approve" class="btn btn-sm btn-success">Duyệt</a>
                        <a href="admin/leave/approve.php?id=<?php echo $row['id']; ?>&action=reject" class="btn btn-sm btn-danger">Từ chối</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>