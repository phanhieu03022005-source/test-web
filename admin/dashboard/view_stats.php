<div class='container-fluid'>
    <h3>Bảng điều khiển</h3>
    
    <div class='row mb-4'>
        <div class='col-md-4'>
            <div class='card bg-primary text-white p-3 shadow'>
                <h5>Tổng nhân viên</h5>
                <h2><?php echo $total_users; ?></h2>
            </div>
        </div>
        <div class='col-md-4'>
            <div class='card bg-success text-white p-3 shadow'>
                <h5>Đang đi làm</h5>
                <h2><?php echo $present; ?></h2>
            </div>
        </div>
        <div class='col-md-4'>
            <div class='card bg-warning text-dark p-3 shadow'>
                <h5>Đơn chờ duyệt</h5>
                <h2><?php echo $pending_leaves; ?></h2>
            </div>
        </div>
    </div>

    <div class='card p-3 shadow-sm'>
        <h5>Phân bổ nhân sự theo phòng ban</h5>
        <table class='table table-striped table-hover mt-3'>
            <thead>
                <tr>
                    <th>Tên phòng ban</th>
                    <th>Số lượng nhân viên</th>
                </tr>
            </thead>
            <tbody>
            <?php 
            if ($dept_result && $dept_result->num_rows > 0): 
                while ($row = $dept_result->fetch_assoc()): 
                    $dept_name = !empty($row['department']) ? htmlspecialchars($row['department']) : 'Chưa phân loại';
            ?>
                <tr>
                    <td><?php echo $dept_name; ?></td>
                    <td><span class="badge bg-primary"><?php echo $row['count']; ?> người</span></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2" class="text-center text-muted">Chưa có dữ liệu phòng ban.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>