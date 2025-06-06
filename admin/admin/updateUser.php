<?php

include('session.php');
include('dbconnect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $staffid = mysqli_real_escape_string($dbcon, $_POST['staffid']);
    $surname = mysqli_real_escape_string($dbcon, $_POST['surname']);
    $othernames = mysqli_real_escape_string($dbcon, $_POST['othernames']);
    $role = mysqli_real_escape_string($dbcon, $_POST['role']);
    $userStatus = mysqli_real_escape_string($dbcon, $_POST['userStatus']);
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Build update query
    if (!empty($password)) {
        $hashed_password = sha1($password);
        $query = "UPDATE userlogin SET surname = '$surname', othernames = '$othernames', status ='$role', userStatus = '$userStatus', password='$hashed_password' WHERE staffid = '$staffid'";
    } else {
        $query = "UPDATE userlogin SET surname = '$surname', othernames = '$othernames', status ='$role', userStatus = '$userStatus' WHERE staffid = '$staffid'";
    }

    $result = mysqli_query($dbcon, $query);

    if ($result) {
        //echo "success";
        exit();
    } else {
        //echo "error";
        exit();
    }
} else {
    // Not a POST request
    //echo "error";
    exit();
}