<?php
session_start();
include("dbconnect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reg_no = mysqli_real_escape_string($dbcon, $_POST['reg_no']);
    $amount = mysqli_real_escape_string($dbcon, $_POST['amount_paid']);
    $mode = mysqli_real_escape_string($dbcon, $_POST['mode_payment']);
    $code = mysqli_real_escape_string($dbcon, $_POST['code']);
    $date = mysqli_real_escape_string($dbcon, $_POST['date']);

    // Check if transaction code already exists
    $check = mysqli_query($dbcon, "SELECT code FROM payments WHERE code = '$code'");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['message'] = "Transaction code already used!";
        header("Location: index.php");
        exit();
    }

    // Get total fee for the student
    $feeResult = mysqli_query($dbcon, "SELECT courses.fee FROM student JOIN courses ON student.courseId = courses.courseId WHERE student.reg_no = '$reg_no'");
    $feeRow = mysqli_fetch_assoc($feeResult);
    $total_fee = isset($feeRow['fee']) ? (int)$feeRow['fee'] : 0;

    // Calculate total paid so far
    $paidResult = mysqli_query($dbcon, "SELECT SUM(amount_paid) as total_paid FROM payments WHERE reg_no = '$reg_no'");
    $paidRow = mysqli_fetch_assoc($paidResult);
    $total_paid = isset($paidRow['total_paid']) ? (int)$paidRow['total_paid'] : 0;

    // Calculate new balance
    $new_total_paid = $total_paid + (int)$amount;
    $balance = $total_fee - $new_total_paid;

    // Insert payment
    $sql = "INSERT INTO payments (reg_no, amount_paid, balance, code, mode_payment, date, payStatus) 
            VALUES ('$reg_no', '$amount', '$balance', '$code', '$mode', '$date', 'pending')";
    if (mysqli_query($dbcon, $sql)) {
        $_SESSION['message'] = "Payment submitted successfully!";
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['message'] = "Failed to submit payment. Please try again.";
        header("Location: index.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}