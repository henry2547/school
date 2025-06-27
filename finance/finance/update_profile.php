<?php
include('session.php');
include('dbconnect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $staffid = mysqli_real_escape_string($dbcon, $_POST['staffid']);
    $fname = mysqli_real_escape_string($dbcon, $_POST['fname']);
    $sname = mysqli_real_escape_string($dbcon, $_POST['sname']);
    $old_password = mysqli_real_escape_string($dbcon, $_POST['old_password']);
    $new_password = isset($_POST['password']) ? $_POST['password'] : '';

    // Fetch current user data
    $query = mysqli_query($dbcon, "SELECT * FROM userlogin WHERE staffid = '$staffid'") or die(mysqli_error($dbcon));
    $user = mysqli_fetch_array($query);

    // Verify old password
    if (sha1($old_password) !== $user['password']) {
        echo "<script>alert('Old password is incorrect.'); window.history.back();</script>";
        exit();
    }

    // Prepare update query
    $update_fields = "surname='$fname', othernames='$sname'";
    
    if (!empty($new_password)) {
        $hashed_password = sha1($new_password);
        $update_fields .= ", password='$hashed_password'";
    }

    $update_query = "UPDATE userlogin SET $update_fields WHERE staffid='$staffid'";
    if (mysqli_query($dbcon, $update_query)) {
        echo "<script>alert('Profile updated successfully.'); window.location='edit_profile.php';</script>";
    } else {
        echo "<script>alert('Error updating profile.'); window.history.back();</script>";
    }
} else {
    header("Location: edit_profile.php");
    exit();
}
?>