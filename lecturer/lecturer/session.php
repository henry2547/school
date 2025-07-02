<?php
session_start();
if (!isset($_SESSION['staffid']) || ($_SESSION['staffid'] == '')) {
    header("location: ../login.php");
    exit();
}

$session_id = $_SESSION['staffid'];
