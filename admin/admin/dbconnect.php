<?php
$dbcon = mysqli_connect("localhost", "root", "", "school");
mysqli_set_charset($dbcon, 'utf8');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "school";

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
// set the PDO error mode to exception
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

date_default_timezone_set("Africa/Nairobi");
