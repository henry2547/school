<?php
include("dbconnect.php");

// Initialize response array
$response = array();

try {
    // Retrieve form data
    $password = $_POST['pwd'];
    $gender = $_POST['gender'];
    $county = $_POST['county'];
    $subcounty = $_POST['subcounty'];
    $fname = $_POST['fname'];
    $course = $_POST['course'];
    $sname = $_POST['sname'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    // Check if the email is already present
    $isEmailPresent = "SELECT email FROM student WHERE email = ?";
    if ($stmt = mysqli_prepare($dbcon, $isEmailPresent)) {
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $response["success"] = false;
            $response["message"] = "Email already present";
            echo json_encode($response);
            exit;
        }
        mysqli_stmt_close($stmt);
    }

    // Check if the phone number is already present
    $isPhonePresent = "SELECT phone FROM student WHERE phone = ?";
    if ($stmt = mysqli_prepare($dbcon, $isPhonePresent)) {
        mysqli_stmt_bind_param($stmt, 's', $phone);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $response["success"] = false;
            $response["message"] = "Phone number already present";
            echo json_encode($response);
            exit;
        }
        mysqli_stmt_close($stmt);
    }

    // Generate a unique registration number
    $currentYear = date('Y');
    $prefix = 'S' . $currentYear . '-';
    $lastRegNoQuery = "SELECT reg_no FROM student WHERE reg_no LIKE ? ORDER BY reg_no DESC LIMIT 1";
    $lastRegNo = '';

    if ($stmt = mysqli_prepare($dbcon, $lastRegNoQuery)) {
        $likePattern = $prefix . '%';
        mysqli_stmt_bind_param($stmt, 's', $likePattern);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $lastRegNo);
        if (mysqli_stmt_fetch($stmt)) {
            // Extract the number part from the last registration number
            $lastNumber = intval(substr($lastRegNo, strlen($prefix))) + 1;
        } else {
            $lastNumber = 1; // Start with 1 if no previous record
        }
        mysqli_stmt_close($stmt);
    }

    $reg_no = $prefix . $lastNumber;

    // Prepare SQL statement for insertion
    $sql = "INSERT INTO student (studentId, courseId, reg_no, fname, sname, email, phone, county, subcounty, gender, password, studentStatus) 
            VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, SHA1(?), 'approved')";

    if ($stmt = mysqli_prepare($dbcon, $sql)) {
        mysqli_stmt_bind_param($stmt, 'isssssssss', $course, $reg_no, $fname, $sname, $email, $phone, $county, $subcounty, $gender, $password);
        if (mysqli_stmt_execute($stmt)) {
            $response['success'] = true;
            $response['message'] = 'Student added successfully';
        } else {
            $response['success'] = false;
            $response['error'] = 'Database query failed';
        }
        mysqli_stmt_close($stmt);
    } else {
        $response['success'] = false;
        $response['error'] = 'Failed to prepare SQL statement for insertion';
    }
} catch (Exception $e) {
    $response['success'] = false;
    $response['error'] = 'An error occurred: ' . $e->getMessage();
}

// Set content type to JSON
header('Content-Type: application/json');

// Output JSON response
echo json_encode($response);
