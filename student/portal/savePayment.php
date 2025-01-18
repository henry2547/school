<?php
session_start();
include("dbconnect.php");

// Initialize response array
$response = array();

try {
    // Retrieve form data
    $amount = $_POST['amount'];
    $code = $_POST['code'];
    $mode = $_POST['mode'];
    $balance = $_POST['balance'];
    $semester = $_POST['semester'];
    $reg_no = $_POST['reg_no'];

    // Check if the code is already present
    $isCodePresent = "SELECT code FROM payments WHERE code = ?";
    if ($stmt = mysqli_prepare($dbcon, $isCodePresent)) {
        mysqli_stmt_bind_param($stmt, 's', $code);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) > 0) {

            $_SESSION["message"] = "Transaction code already used";
           
        }
        mysqli_stmt_close($stmt);
    }


    // Prepare SQL statement for insertion
    $sql = "INSERT INTO `payments` (`paymentId`, `semesterId`, `reg_no`, `amount_paid`, `balance`, `code`, `mode_payment`, `date`, `payStatus`) 
    VALUES (NULL, ?, ?, ?, ?, ?, ?, current_timestamp(), 'pending');";

    if ($stmt = mysqli_prepare($dbcon, $sql)) {
        mysqli_stmt_bind_param($stmt, 'isiiss', $semester, $reg_no, $amount, $balance, $code, $mode);
        if (mysqli_stmt_execute($stmt)) {
            // success
            header("location: payment.php");
            exit();
            
        } else {
            $_SESSION['message'] = 'Database query failed';
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['message'] = 'Failed to prepare SQL statement for insertion';
    }
} catch (Exception $e) {
    $_SESSION['message'] = 'An error occurred: ' . $e->getMessage();
}

// Set content type to JSON
header('Content-Type: application/json');

// Output JSON response
echo json_encode($response);
