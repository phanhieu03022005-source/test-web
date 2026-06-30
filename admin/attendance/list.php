<?php
// 1. Xử lý từ khóa tìm kiếm
$search = $_GET['search'] ?? '';
$search_sql = "";
if (!empty($search)) {
    $search_safe = $conn->real_escape_string($search);
    $search_sql = " AND fullname LIKE '%$search_safe%'";
}
?>

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white"><h5>Điểm danh nhân viên - Ngày: <?php echo date('d/m/Y'); ?></h5></div>
    <div class="card-body">
        
        <form method="GET" action="" class="row g-3 mb-4">
            <input type="hidden" name="page" value="admin_attendance">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Nhập tên nhân viên cần tìm..." 
                       value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                <a href="?page=admin_attendance" class="btn btn-secondary">Xóa lọc</a>
            </div>
        </form>

        <table class="table table-hover">
            <thead><tr><th>ID</th><th>Họ tên</th><th>Thao tác</th><th>Chi tiết</th></tr></thead>
            <tbody>
                <?php
                $today = date('Y-m-d');
                $today_status = $conn->query("SELECT user_id, status FROM attendance WHERE date = '$today'")->fetch_all(MYSQLI_ASSOC);
                $status_map = [];
                foreach($today_status as $s) $status_map[$s['user_id']] = $s['status'];

                // 2. Sử dụng $search_sql để lọc danh sách nhân viên
                $query = "SELECT id, fullname FROM users WHERE email != 'hp5836697@gmail.com' $search_sql";
                $users = $conn->query($query);
                
                while($u = $users->fetch_assoc()): 
                    $current = $status_map[$u['id']] ?? null;
                ?>
                <tr>
                    <td><?php echo $u['id']; ?></td>
                    <td><?php echo htmlspecialchars($u['fullname']); ?></td>
                    <td>
                        <a href="admin/attendance/process.php?uid=<?php echo $u['id']; ?>&status=Có mặt" 
                           class="btn btn-sm <?php echo ($current == 'Có mặt') ? 'btn-success' : 'btn-outline-success'; ?>">Có mặt</a>
                        <a href="admin/attendance/process.php?uid=<?php echo $u['id']; ?>&status=Vắng mặt" 
                           class="btn btn-sm <?php echo ($current == 'Vắng mặt') ? 'btn-danger' : 'btn-outline-danger'; ?>">Vắng mặt</a>
                    </td>
                    <td><a href="?page=admin_attendance_detail&uid=<?php echo $u['id']; ?>" class="btn btn-info btn-sm">Xem chi tiết</a></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>