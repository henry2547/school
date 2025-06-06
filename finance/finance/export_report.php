<?php
include("dbconnect.php");

$type = isset($_GET['type']) ? $_GET['type'] : 'all_payments';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=finance_report_' . $type . '_' . date('Ymd_His') . '.csv');

$output = fopen('php://output', 'w');

if ($type === 'all_payments') {
    // All payments report
    fputcsv($output, ['Reg No', 'Name', 'Course', 'Amount Paid', 'Mode', 'Code', 'Date', 'Status']);
    $q = mysqli_query($dbcon, "
        SELECT p.*, s.fname, s.sname, c.course_name
        FROM payments p
        JOIN student s ON p.reg_no = s.reg_no
        JOIN courses c ON s.courseId = c.courseId
        ORDER BY p.date DESC
    ");
    while ($row = mysqli_fetch_assoc($q)) {
        fputcsv($output, [
            $row['reg_no'],
            $row['fname'] . ' ' . $row['sname'],
            $row['course_name'],
            $row['amount_paid'],
            $row['mode_payment'],
            $row['code'],
            $row['date'],
            $row['payStatus']
        ]);
    }
} elseif ($type === 'defaulters') {
    // Fee defaulters report
    fputcsv($output, ['Reg No', 'Name', 'Course', 'Total Fee', 'Total Paid', 'Balance']);
    $q = mysqli_query($dbcon, "
        SELECT s.reg_no, s.fname, s.sname, c.course_name, c.fee,
            IFNULL(SUM(p.amount_paid),0) as total_paid
        FROM student s
        JOIN courses c ON s.courseId = c.courseId
        LEFT JOIN payments p ON s.reg_no = p.reg_no AND p.payStatus='approved'
        WHERE s.studentStatus='approved'
        GROUP BY s.reg_no
        HAVING (c.fee - IFNULL(SUM(p.amount_paid),0)) > 0
        ORDER BY (c.fee - IFNULL(SUM(p.amount_paid),0)) DESC
    ");
    while ($row = mysqli_fetch_assoc($q)) {
        $balance = $row['fee'] - $row['total_paid'];
        fputcsv($output, [
            $row['reg_no'],
            $row['fname'] . ' ' . $row['sname'],
            $row['course_name'],
            $row['fee'],
            $row['total_paid'],
            $balance
        ]);
    }
} elseif ($type === 'summary') {
    // Summary report
    fputcsv($output, ['Metric', 'Value']);
    // Total students
    $q = mysqli_query($dbcon, "SELECT COUNT(*) as total FROM student WHERE studentStatus='approved'");
    $row = mysqli_fetch_assoc($q);
    fputcsv($output, ['Total Students', $row['total']]);
    // Total collected
    $q = mysqli_query($dbcon, "SELECT SUM(amount_paid) as total FROM payments WHERE payStatus='approved'");
    $row = mysqli_fetch_assoc($q);
    fputcsv($output, ['Total Collected', $row['total']]);
    // Pending payments
    $q = mysqli_query($dbcon, "SELECT COUNT(*) as total FROM payments WHERE payStatus='pending'");
    $row = mysqli_fetch_assoc($q);
    fputcsv($output, ['Pending Payments', $row['total']]);
    // Total courses
    $q = mysqli_query($dbcon, "SELECT COUNT(*) as total FROM courses");
    $row = mysqli_fetch_assoc($q);
    fputcsv($output, ['Total Courses', $row['total']]);
} else {
    fputcsv($output, ['Invalid report type']);
}

fclose($output);
exit();