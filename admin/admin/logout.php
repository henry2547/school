<?php


include('dbconnect.php');
include('session.php');
$logintime = $_SESSION['$staffid'];

// update userStatus to inactive
$updateStatus = mysqli_query($dbcon, "UPDATE userlogin SET userStatus='inactive' WHERE staffid='$logintime'");

unset($_SESSION['$staffid']);
session_destroy();
header('location:../login.php'); 
?>