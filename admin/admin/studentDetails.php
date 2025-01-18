<?php
$studentId = $_GET['studentId'];

require_once('dbconnect.php');
include('header.php');

?>

<div class="container-fluid">
	<div class="col-md-1"></div>
	<div class="col-md-10">

	</div>

	<div class="container-fluid">
		<?php include('menubar.php') ?>
		<div class="col-md-2"></div>
		
		<div class="col-md-8">
			<a href="#" onClick="window_print()" class="btn btn-info" style="margin-bottom:20px"><i class="icon-print icon-large"></i> Print</a>

			</script>

			<div class="panel panel-success" id="outprint">

				<div class="panel panel-success">
					<div class="panel-heading">
						<h3 class="panel-title">Student Details</h3>
					</div>
					<div class="panel-body">
						<br />

						<div class="panel panel-success">
							<div class="panel-heading">

								<h3 class="panel-title"> <span class="glyphicon glyphicon-user" aria-hidden="true"></span> Student information</h3>
							</div>

							<div class="panel-body">
								<?php
								$query = mysqli_query($dbcon, "SELECT student.*, courses.* 
								FROM student
								JOIN courses ON student.courseId = courses.courseId
								WHERE student.studentStatus = 'approved'
								AND student.studentId = '$studentId'");

								while ($row = mysqli_fetch_array($query)) {
								?>
									<table class="table">
										<tr>
											<td width="160px">Regno:</td>
											<td> <input type="text" value="<?php echo $row['reg_no'] ?>" readonly=""> </td>
										</tr>
										<tr>
											<td>Firstname:</td>
											<td><?php echo $row['fname'] ?></td>
										</tr>
										<tr>
											<td>Secondname:</td>
											<td><?php echo $row['sname'] ?></td>
										</tr>
										<tr>
											<td>Email address:</td>
											<td><?php echo $row['email'] ?></td>
										</tr>

										<tr>
											<td>Phone number:</td>
											<td><?php echo $row['phone'] ?></td>
										</tr>

										<tr>
											<td>Registration Date:</td>
											<td><?php echo $row['regDate'] ?></td>
										</tr>

										<tr>
											<td>County:</td>
											<td><?php echo $row['county'] ?></td>

										</tr>
										<tr>
											<td>Subcounty:</td>
											<td><?php echo $row['subcounty'] ?></td>

										</tr>

										<hr>

										<tr>
											<td><b>Taking Course:</b></td>
											<td><b><?php echo $row['course_name'] ?></b></td>

										</tr>



									<?php
								}
									?>

									</table>

							</div>

						</div>




					</div>
				</div>


			</div>

			<center>
				<a href="viewStudent.php" class="btn btn-success">Return Home
					<span class="glyphicon glyphicon-backward" aria-hidden="true"></span>
				</a>
			</center>


		</div>

	</div>
	<div class="col-md-2"></div>

</div>

<?php include('scripts.php') ?>

<script type="text/javascript">
	function window_print() {
		var _head = $('head').clone();
		var _p = $('#outprint').clone();
		var _html = $('<div>')
		_html.append("<head>" + _head.html() + "</head>")
		_html.append("<h3 class='text-center'>SCHOOL MANAGEMENT SYSTEM</h3>")
		_html.append(_p)
		console.log(_html.html())
		var nw = window.open("", "_blank", "width:900;height:800")
		nw.document.write(_html.html())
		nw.document.close()
		nw.print()
		setTimeout(() => {
			nw.close()
		}, 500);
	}
</script>

</body>

</html>