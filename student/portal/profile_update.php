<?php
require("dbconnect.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reg_no = mysqli_real_escape_string($dbcon, $_POST['reg_no']);
    $fname = mysqli_real_escape_string($dbcon, $_POST['fname']);
    $sname = mysqli_real_escape_string($dbcon, $_POST['sname']);
    $email = mysqli_real_escape_string($dbcon, $_POST['email']);
    $phone = mysqli_real_escape_string($dbcon, $_POST['phone']);
    $gender = mysqli_real_escape_string($dbcon, $_POST['gender']);

    $sql = "UPDATE student SET 
                fname = '$fname',
                sname = '$sname',
                email = '$email',
                phone = '$phone',
                gender = '$gender'
            WHERE reg_no = '$reg_no'";

    if (mysqli_query($dbcon, $sql)) {
        $_SESSION['success'] = "Profile updated successfully!";
    } else {
        $_SESSION['error'] = "Failed to update profile. Please try again.";
    }
    header("Location: profile.php");
    exit();
} else {
    header("Location: profile.php");
    exit();
}