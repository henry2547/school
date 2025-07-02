<?php

include('session.php');
include('dbconnect.php');

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $staffid = mysqli_real_escape_string($dbcon, $_POST['staffid']);
    $surname = mysqli_real_escape_string($dbcon, $_POST['surname']);
    $othernames = mysqli_real_escape_string($dbcon, $_POST['othernames']);
    $role = mysqli_real_escape_string($dbcon, $_POST['role']);
    $status = mysqli_real_escape_string($dbcon, $_POST['status']);
    $password = mysqli_real_escape_string($dbcon, $_POST['password']);
    $courseId = mysqli_real_escape_string($dbcon, $_POST['courseId']);

    // Set is_lecturer based on role
    $is_lecturer = ($role === 'Lecturer') ? 1 : 0;

    // Hash the password using SHA1
    $hashed_password = sha1($password);

    // Check if staffid already exists
    $check = mysqli_query($dbcon, "SELECT * FROM userlogin WHERE staffid='$staffid'");
    if (mysqli_num_rows($check) > 0) {
        // Redirect back with error
        header("Location: users.php?error=exists");
        exit();
    }

    // if the courseId is not empty, insert into userlogin table with courseId
    if (!empty($courseId)) {
        $insert = mysqli_query($dbcon, "INSERT INTO userlogin (staffid, status, surname, othernames, is_lecturer, course, password, userStatus) VALUES (
        '$staffid', '$role', '$surname', '$othernames', $is_lecturer, '$courseId', '$hashed_password', '$status'
    )");
    }

    // if the courseId is empty, insert into userlogin without course
    if (empty($courseId)) {
        $insert = mysqli_query($dbcon, "INSERT INTO userlogin (staffid, status, surname, othernames, is_lecturer, password, userStatus) VALUES (
            '$staffid', '$role', '$surname', '$othernames', $is_lecturer, '$hashed_password', '$status'
        )");
    }

    if ($insert) {
        header("Location: users.php?success=added");
        exit();
    } else {
        header("Location: users.php?error=failed");
        exit();
    }
} else {
    // If accessed directly, redirect to users page
    header("Location: users.php");
    exit();
}
