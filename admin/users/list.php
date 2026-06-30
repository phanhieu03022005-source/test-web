<?php
// Truy vấn lấy tất cả nhân viên, loại trừ Admin (dựa trên email)
// Tự thực hiện query ngay tại đây để tránh lỗi Undefined variable
$query = "SELECT * FROM users WHERE email != 'hp5836697@gmail.com'";
$result = $conn->query($query);
?>

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-users"></i> Quản lý nhân viên</h5>
        <input type="text" id="searchUser" class="form-control w-25" placeholder="Tìm kiếm tên...">
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="userTable">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>ID</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Phòng ban</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $stt = 1;
                    while ($row = $result->fetch_assoc()): 
                    ?>
                    <tr>
                        <td><strong><?php echo $stt++; ?></strong></td>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td>
                            <form action="admin/users/update_process.php" method="POST" class="d-flex">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <input type="text" name="department" value="<?php echo htmlspecialchars($row['department'] ?? ''); ?>" class="form-control form-control-sm">
                                <button type="submit" name="update" class="btn btn-sm btn-success ms-1">Lưu</button>
                            </form>
                        </td>
                        <td>
                            <a href="admin/users/delete_process.php?id=<?php echo $row['id']; ?>" 
                               class="btn btn-sm btn-danger" 
                               onclick="return confirm('Bạn chắc chắn muốn xóa nhân viên này?')">
                               <i class="fas fa-trash"></i> Xóa
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Script tìm kiếm nhân viên theo tên (cột thứ 3 - index 2)
document.getElementById('searchUser').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('#userTable tbody tr');
    rows.forEach(row => {
        // Cột "Họ tên" hiện tại là cell thứ 3 (index 2)
        let name = row.cells[2].textContent.toLowerCase();
        row.style.display = name.includes(filter) ? '' : 'none';
    });
});
</script>