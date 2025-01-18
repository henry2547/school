
<?php
session_start();
include('dbconnect.php');
include('header.php');
if (isset($_POST['login'])) {

	$username = mysqli_real_escape_string($dbcon, trim($_POST['username']));
	$pwd = mysqli_real_escape_string($dbcon, trim($_POST['pwd']));


	$query = mysqli_query($dbcon, "SELECT * FROM student WHERE reg_no = '$username' AND password= SHA1('$pwd')");

	$count = mysqli_num_rows($query);
	if ($count == 1) {
		$row = mysqli_fetch_array($query);
		$_SESSION['reg_no'] = $row['reg_no'];
		$_SESSION['role'] = $row['status'];
		

	} else {
		$_SESSION['error'] = 'Your Staff ID or password is not valid';
	}
}
header('location: login.php');
?>

