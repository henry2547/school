<?php
include('session.php');
include('dbconnect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = mysqli_real_escape_string($dbcon, $_POST['currentPassword']);
    $newPassword = mysqli_real_escape_string($dbcon, $_POST['newPassword']);
    $confirmPassword = mysqli_real_escape_string($dbcon, $_POST['confirmPassword']);

    $staffid = $session_id;

    // Fetch current password hash from DB
    $q = mysqli_query($dbcon, "SELECT password FROM userlogin WHERE staffid='$staffid' LIMIT 1");
    if ($q && $row = mysqli_fetch_assoc($q)) {
        $currentHash = $row['password'];
        if (sha1($currentPassword) !== $currentHash) {
            // Wrong current password
            header("Location: settings.php?error=wrongcurrent");
            exit();
        }
        if ($newPassword !== $confirmPassword) {
            // Passwords do not match
            header("Location: settings.php?error=nomatch");
            exit();
        }
        if (empty($newPassword)) {
            // New password empty
            header("Location: settings.php?error=empty");
            exit();
        }
        // Update password
        $newHash = sha1($newPassword);
        $update = mysqli_query($dbcon, "UPDATE userlogin SET password='$newHash' WHERE staffid='$staffid'");
        if ($update) {
            header("Location: settings.php?success=changed");
            exit();
        } else {
            header("Location: settings.php?error=failed");
            exit();
        }
    } else {
        header("Location: settings.php?error=notfound");
        exit();
    }
} else {
    header("Location: settings.php");
    exit();
}