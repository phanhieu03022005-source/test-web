<?php

function calculateSalaryForUser($conn, $uid, $deductions, $bonus_input, $note) {
    // 1. Đếm ngày đi làm trong tháng hiện tại
    $sql = "SELECT COUNT(*) as c FROM attendance WHERE user_id = $uid AND status = 'Có mặt' AND MONTH(date) = MONTH(CURRENT_DATE) AND YEAR(date) = YEAR(CURRENT_DATE)";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $work_days = $row['c'];
    
    // 2. Tính toán lương
    $base_salary = $work_days * 300;
    $attendance_bonus = ($work_days >= 22) ? 500 : 0;
    $total_bonus = $attendance_bonus + $bonus_input;
    $final_salary = $base_salary + $total_bonus - $deductions;
    
    // 3. Lưu bản ghi mới vào database (Không DELETE, giúp giữ lại lịch sử)
    $stmt = $conn->prepare("INSERT INTO salary_records 
        (user_id, month, year, total_days_worked, base_salary, deductions, bonus, final_salary, note, status) 
        VALUES (?, MONTH(CURRENT_DATE), YEAR(CURRENT_DATE), ?, ?, ?, ?, ?, ?, 'sent')");
    
    $stmt->bind_param("iiiiiss", $uid, $work_days, $base_salary, $deductions, $total_bonus, $final_salary, $note);
    $stmt->execute();
    
    return ['final' => $final_salary, 'days' => $work_days, 'bonus' => $total_bonus];
}
?>