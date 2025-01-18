<?php
//Start session
session_start();
//Check whether the session variable SESS_MEMBER_ID is present or not
if (!isset($_SESSION['reg_no']) || ($_SESSION['reg_no'] == '')) {
    header("location: ../login.php");
    exit();
}

$session_id = $_SESSION['reg_no'];

?>