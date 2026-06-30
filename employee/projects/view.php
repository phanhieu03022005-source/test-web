<h4 class="mb-4">Dự án được giao</h4>
<?php
// Lấy ID của nhân viên đang đăng nhập
$uid = $_SESSION['user_id'];

// TRUY VẤN: Chỉ lấy dự án dành cho 'all' hoặc dành riêng cho ID của nhân viên này
$sql = "SELECT * FROM projects 
        WHERE assigned_to = 'all' OR assigned_to = '$uid' 
        ORDER BY id DESC";
$my_projects = $conn->query($sql);

if ($my_projects->num_rows > 0):
    while($p = $my_projects->fetch_assoc()): 
        // Truy vấn lịch sử báo cáo của CHÍNH NHÂN VIÊN này cho dự án này
        $reports = $conn->query("SELECT * FROM project_reports WHERE project_id = {$p['id']} AND user_id = '$uid' ORDER BY created_at DESC");
?>
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body">
            <h5 class="card-title text-primary"><?=htmlspecialchars($p['title'])?></h5>
            <p class="card-text text-muted"><?=htmlspecialchars($p['description'])?></p>
            <p><strong>Hạn chót:</strong> <span class="text-danger"><?=$p['deadline']?></span></p>
            
            <a href="uploads/<?=$p['file_path']?>" class="btn btn-sm btn-outline-primary" download>
                <i class="fas fa-file-download"></i> Tải file yêu cầu từ Admin
            </a>
            
            <hr>
            <h6>Lịch sử báo cáo của bạn:</h6>
            <div class="table-responsive">
                <table class="table table-sm table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Tiến độ</th>
                            <th>Ghi chú</th>
                            <th>Tệp báo cáo</th>
                            <th>Thời gian</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($r = $reports->fetch_assoc()): ?>
                        <tr>
                            <td><span class="badge bg-primary"><?=$r['progress']?>%</span></td>
                            <td><?=htmlspecialchars($r['note'])?></td>
                            <td>
                                <?php if($r['report_file']): ?>
                                    <a href="uploads/reports/<?=$r['report_file']?>" class="text-success" download>
                                        <i class="fas fa-file-download"></i> Tải file
                                    </a>
                                <?php else: ?> <span class="text-muted">Không có</span> <?php endif; ?>
                            </td>
                            <td><?=date('d/m/Y H:i', strtotime($r['created_at']))?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <hr>
            <h6 class="text-info">Gửi báo cáo mới:</h6>
            <form action="employee/projects/report.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="project_id" value="<?=$p['id']?>">
                <div class="row">
                    <div class="col-md-3">
                        <input type="number" name="progress" class="form-control mb-2" placeholder="Tiến độ % (0-100)" min="0" max="100" required>
                    </div>
                    <div class="col-md-9">
                        <input type="text" name="note" class="form-control mb-2" placeholder="Ghi chú báo cáo..." required>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="file" name="report_file" class="form-control">
                    <button type="submit" class="btn btn-info text-white">Gửi báo cáo</button>
                </div>
            </form>
        </div>
    </div>
<?php 
    endwhile; 
else:
    echo "<div class='alert alert-info'>Hiện tại bạn chưa có dự án nào được giao.</div>";
endif; 
?>