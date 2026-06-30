<?php
$my_id = $_SESSION['user_id'];
$m = date('m'); $y = date('Y');
$days_in_month = cal_days_in_month(CAL_GREGORIAN, $m, $y);

// Lấy dữ liệu điểm danh của nhân viên này trong tháng hiện tại
$data = $conn->query("SELECT DAY(date) as d, status FROM attendance WHERE user_id = $my_id AND MONTH(date) = $m AND YEAR(date) = $y")->fetch_all(MYSQLI_ASSOC);

$attendance_map = [];
$total_present = 0; $total_absent = 0;
foreach($data as $d) {
    $attendance_map[$d['d']] = $d['status'];
    if($d['status'] == 'Có mặt') $total_present++; else $total_absent++;
}
?>

<div class="card shadow-sm">
    <div class="card-header bg-info text-white"><h5>Lịch làm việc của tôi - Tháng <?php echo "$m/$y"; ?></h5></div>
    <div class="card-body">
        <div class="row">
            <?php for($i=1; $i <= $days_in_month; $i++): 
                $status = $attendance_map[$i] ?? null;
                $bg_color = ($status == 'Có mặt') ? 'bg-success' : (($status == 'Vắng mặt') ? 'bg-danger' : 'bg-light');
                $text_color = ($status == 'Có mặt' || $status == 'Vắng mặt') ? 'text-white' : 'text-dark';
            ?>
            <div class="col-1 text-center p-2 border <?php echo $bg_color . ' ' . $text_color; ?>" style="margin: 2px; border-radius: 5px; font-weight: bold;">
                <?php echo $i; ?>
            </div>
            <?php endfor; ?>
        </div>
        
        <hr>
        <div class="mt-3">
            <h5 class="text-primary">Tổng kết tháng:</h5>
            <p class="text-success">Số ngày đi làm: <strong><?php echo $total_present; ?></strong></p>
            <p class="text-danger">Số ngày vắng mặt: <strong><?php echo $total_absent; ?></strong></p>
        </div>
    </div>
</div>