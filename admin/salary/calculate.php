<div class="card shadow-sm">
    <div class="card-header bg-warning"><h5><i class="fas fa-calculator"></i> Tính lương tháng <?php echo date('m/Y'); ?></h5></div>
    <div class="card-body">
        <form action="admin/salary/process.php" method="POST">
            <div class="mb-3">
                <label>Đối tượng tính:</label>
                <select name="target" class="form-control" onchange="toggleFields(this.value)" required>
                    <option value="all">Tất cả nhân viên</option>
                    <option value="dept">Theo phòng ban</option>
                    <option value="user">Cá nhân cụ thể</option>
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
                <label>Chọn nhân viên:</label>
                <select name="user_id" class="form-control">
                    <?php 
                    $users = $conn->query("SELECT id, fullname FROM users WHERE email != 'hp5836697@gmail.com'");
                    while($u = $users->fetch_assoc()) echo "<option value='{$u['id']}'>{$u['fullname']}</option>";
                    ?>
                </select>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Tiền thưởng:</label>
                    <input type="number" name="bonus" class="form-control" value="0" required>
                </div>
                <div class="col-md-6">
                    <label>Khoản trừ:</label>
                    <input type="number" name="deductions" class="form-control" value="0" required>
                </div>
            </div>
            <div class="mb-3">
                <label>Ghi chú:</label>
                <textarea name="note" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> Tính & Lưu lương</button>
        </form>
    </div>
</div>

<div class="card shadow-sm mt-4">
    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-history"></i> Lịch sử tính lương</h5>
        <input type="text" id="searchSalary" class="form-control w-25" placeholder="Tìm tên nhân viên...">
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle" id="salaryTable">
                <thead>
                    <tr>
                        <th>Nhân viên</th>
                        <th>Tháng/Năm</th>
                        <th>Tổng nhận</th>
                        <th>Ghi chú</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $list = $conn->query("SELECT u.fullname, s.* FROM salary_records s LEFT JOIN users u ON s.user_id = u.id ORDER BY s.id DESC");
                    while($row = $list->fetch_assoc()): 
                    ?>
                    <tr>
                        <td class="emp-name"><?php echo htmlspecialchars($row['fullname'] ?? 'N/A'); ?></td>
                        <td><?php echo $row['month'].'/'.$row['year']; ?></td>
                        <td><strong class="text-success"><?php echo number_format($row['final_salary']); ?> VNĐ</strong></td>
                        <td><?php echo htmlspecialchars($row['note'] ?? ''); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function toggleFields(val) {
    document.getElementById('dept_field').style.display = (val == 'dept') ? 'block' : 'none';
    document.getElementById('user_field').style.display = (val == 'user') ? 'block' : 'none';
}

document.getElementById('searchSalary').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('#salaryTable tbody tr');
    rows.forEach(row => {
        let name = row.querySelector('.emp-name').textContent.toLowerCase();
        row.style.display = name.includes(filter) ? '' : 'none';
    });
});
</script>