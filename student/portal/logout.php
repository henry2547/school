<?php

include('dbconnect.php');
include('session.php');
$logintime = $_SESSION['$reg_no'];
unset($_SESSION['$reg_no']);
session_destroy();
header('location:../login.php');
