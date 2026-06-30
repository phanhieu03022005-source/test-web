<div class="card shadow-sm">
    <div class="card-header bg-success text-white"><h5>Lịch sử lương của tôi</h5></div>
    <div class="card-body">
        <table class="table">
            <thead><tr><th>Tháng</th><th>Ngày công</th><th>Lương</th><th>Ghi chú</th></tr></thead>
            <tbody>
                <?php
                $my_id = $_SESSION['user_id'];
                $rows = $conn->query("SELECT * FROM salary_records WHERE user_id = $my_id ORDER BY id DESC");
                while($r = $rows->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $r['month'].'/'.$r['year']; ?></td>
                    <td><?php echo $r['total_days_worked']; ?></td>
                    <td><strong><?php echo number_format($r['final_salary']); ?></strong></td>
                    <td><?php echo $r['note']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>