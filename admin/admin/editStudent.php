<?php
include('dbconnect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and sanitize input
    $studentId = intval($_POST['studentId']);
    $fname = mysqli_real_escape_string($dbcon, $_POST['fname']);
    $sname = mysqli_real_escape_string($dbcon, $_POST['sname']);
    $email = mysqli_real_escape_string($dbcon, $_POST['email']);
    $phone = mysqli_real_escape_string($dbcon, $_POST['phone']);

    // Update query
    $sql = "UPDATE student SET fname='$fname', sname='$sname', email='$email', phone='$phone' WHERE studentId=$studentId";
    if (mysqli_query($dbcon, $sql)) {
        // Redirect back with success message (optional: use session or GET param)
        header("Location: viewStudent.php?edit=success");
        exit();
    } else {
        // Redirect back with error message
        header("Location: viewStudent.php?edit=error");
        exit();
    }
} else {
    // Invalid access
    header("Location: viewStudent.php");
    exit();
}