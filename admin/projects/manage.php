<?php
// Kiểm tra quyền truy cập (nếu cần thiết)
// Đảm bảo $conn đã được kết nối từ db.php ở file home.php
?>

<div class="card p-3 shadow-sm mb-4">
    <h4 class="mb-3"><i class="fas fa-plus-circle"></i> Giao dự án mới</h4>
    <form action="admin/projects/process.php" method="POST" enctype="multipart/form-data">
        <div class="row g-2">
            <div class="col-md-3">
                <input type="text" name="title" class="form-control" placeholder="Tên dự án" required>
            </div>
            <div class="col-md-3">
                <input type="date" name="deadline" class="form-control" min="<?=date('Y-m-d', strtotime('+1 day'))?>" required>
            </div>
            <div class="col-md-3">
                <select name="assigned_to" class="form-control" required>
                    <option value="all">Gửi cho tất cả nhân viên</option>
                    <?php 
                    $users = $conn->query("SELECT id, fullname FROM users");
                    while($u = $users->fetch_assoc()): ?>
                        <option value="<?=$u['id']?>">NV: <?=$u['fullname']?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-3">
                <input type="file" name="project_file" class="form-control" required>
            </div>
        </div>
        <div class="mt-2">
            <textarea name="description" class="form-control" placeholder="Ghi chú nội dung dự án..." rows="2"></textarea>
        </div>
        <button type="submit" class="btn btn-success mt-2"><i class="fas fa-paper-plane"></i> Giao dự án</button>
    </form>
</div>

<div class="card p-3 shadow-sm">
    <h4 class="mb-3"><i class="fas fa-list"></i> Lịch sử dự án & Tiến độ</h4>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Tên dự án</th>
                    <th>Đối tượng</th>
                    <th>Deadline</th>
                    <th>Tài liệu gốc</th>
                    <th>File nhân viên</th>
                    <th>Tiến độ</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Truy vấn lấy cả thông tin đối tượng nhận (assigned_to)
                $projects = $conn->query("SELECT * FROM projects ORDER BY id DESC");
                if ($projects->num_rows > 0):
                    while($p = $projects->fetch_assoc()):
                        $rep = $conn->query("SELECT * FROM project_reports WHERE project_id = {$p['id']} ORDER BY id DESC LIMIT 1")->fetch_assoc();
                        $total_reports = $conn->query("SELECT COUNT(*) FROM project_reports WHERE project_id = {$p['id']}")->fetch_row()[0];
                        $is_overdue = ($p['deadline'] < date('Y-m-d') && ($rep['progress'] ?? 0) < 100);
                        
                        // Xác định tên đối tượng hiển thị
                        $target = ($p['assigned_to'] == 'all') ? 'Tất cả' : 'ID: ' . $p['assigned_to'];
                ?>
                <tr class="<?= $is_overdue ? 'table-danger' : '' ?>">
                    <td>
                        <strong><?=htmlspecialchars($p['title'])?></strong>
                        <br><small class="text-muted"><?=htmlspecialchars(substr($p['description'], 0, 30))?></small>
                    </td>
                    <td><span class="badge bg-secondary"><?=$target?></span></td>
                    <td>
                        <?= $p['deadline'] ?>
                        <?= $is_overdue ? '<br><span class="badge bg-danger">Quá hạn</span>' : '' ?>
                    </td>
                    <td>
                        <a href="uploads/<?=$p['file_path']?>" class="btn btn-sm btn-outline-secondary" download>
                            <i class="fas fa-file-download"></i> Tải yêu cầu
                        </a>
                    </td>
                    <td>
                        <?php if (!empty($rep['report_file'])): ?>
                            <a href="uploads/reports/<?=$rep['report_file']?>" class="btn btn-sm btn-outline-success" download>
                                <i class="fas fa-file-archive"></i> Tải báo cáo
                            </a>
                        <?php else: ?> 
                            <span class="text-muted small">Chưa có</span> 
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge bg-info p-2"><?=($rep['progress'] ?? 0)?>%</span>
                        <br><small class="text-muted">Tổng: <?=$total_reports?> lần</small>
                    </td>
                    <td>
                        <a href="?page=admin_project_history&id=<?=$p['id']?>" class="btn btn-sm btn-info" title="Xem lịch sử">
                            <i class="fas fa-history"></i>
                        </a>
                        <a href="admin/projects/delete.php?id=<?=$p['id']?>" 
                           class="btn btn-sm btn-danger" title="Xóa dự án"
                           onclick="return confirm('Bạn có chắc chắn muốn xóa dự án này?');">
                           <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endwhile; 
                else: ?>
                    <tr><td colspan="7" class="text-center">Chưa có dự án nào.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>