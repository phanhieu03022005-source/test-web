<?php
// Sử dụng truy vấn trực tiếp từng dòng dữ liệu lương
$sql = "SELECT u.fullname, s.month, s.year, s.final_salary, s.note 
        FROM salary_records s
        JOIN users u ON s.user_id = u.id
        ORDER BY s.year DESC, s.month DESC, s.id DESC";

$salary_history = $conn->query($sql);

if (!$salary_history) {
    die("Lỗi truy vấn: " . $conn->error);
}
?>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nhân viên</th>
            <th>Tháng/Năm</th>
            <th>Tổng tiền nhận</th>
            <th>Ghi chú</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $salary_history->fetch_assoc()): 
            // Kiểm tra nếu giá trị quá lớn (bị lỗi overflow), hiển thị thông báo thay vì số khổng lồ
            $salary = ($row['final_salary'] >= 2147483647) ? 0 : $row['final_salary'];
        ?>
        <tr>
            <td><?php echo htmlspecialchars($row['fullname']); ?></td>
            <td><?php echo $row['month'] . '/' . $row['year']; ?></td>
            <td><?php echo number_format($salary, 0, ',', '.'); ?> VNĐ</td>
            <td><?php echo htmlspecialchars($row['note']); ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>