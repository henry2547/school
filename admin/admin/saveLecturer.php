<?php
include('dbconnect.php');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname = mysqli_real_escape_string($dbcon, $_POST['fname']);
    $sname = mysqli_real_escape_string($dbcon, $_POST['sname']);
    $email = mysqli_real_escape_string($dbcon, $_POST['email']);
    $phone = mysqli_real_escape_string($dbcon, $_POST['phone']);
    $department = mysqli_real_escape_string($dbcon, $_POST['department']);
    $gender = mysqli_real_escape_string($dbcon, $_POST['gender']);
    $defaultPwd = password_hash('123456', PASSWORD_DEFAULT);

    // Check if email already exists
    $check = mysqli_query($dbcon, "SELECT * FROM lecturer WHERE email='$email'");
    if(mysqli_num_rows($check) > 0) {
        echo json_encode(['error' => 'Email already exists!']);
        exit;
    }

    $sql = "INSERT INTO lecturer (fname, sname, email, phone, department, gender, password) 
            VALUES ('$fname', '$sname', '$email', '$phone', '$department', '$gender', '$defaultPwd')";
    if (mysqli_query($dbcon, $sql)) {
        echo json_encode(['success' => 'Lecturer added successfully!']);
    } else {
        echo json_encode(['error' => 'Error: ' . mysqli_error($dbcon)]);
    }
} else {
    echo json_encode(['error' => 'Invalid request.']);
}