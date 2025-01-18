<div class="col-md-12">
	<div class="panel panel-success">
		<div class="panel-heading" style="padding-bottom: 40px;">
			<center>
				<h3> SCHOOL MANAGEMENT SYSTEM</h3>
			</center>




			<?php

			include('session.php');
			include('dbconnect.php');

			$query = mysqli_query($dbcon, "select * from userlogin where staffid = '$session_id'") or die(mysqli_error($dbcon));
			$row = mysqli_fetch_array($query);

			?>
			<span class="pull-right">
				<?php echo $row['surname'] . " " . $row['othernames'] . " (" . $row['staffid'] . ")";  ?>

				<a href="profile.php"><i class="icon-signout icon-large"></i>&nbsp;Edit</a>
				<a href="logout.php"><i class="icon-signout icon-large"></i>&nbsp;Logout</a>
			</span>

		</div>




	</div>

	<div class="panel-body">


		<div class="row">

			<div class="col-md-3">
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title"> <a href="index.php">
								<span class="glyphicon glyphicon-home" aria-hidden="true"></span> Home</a>
					</div>

				</div>

			</div>

			<div class="col-md-3">
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title"> <a href="addStudent.php">
								<span class="glyphicon glyphicon-user" aria-hidden="true"></span> New Student</a>
					</div>

				</div>

			</div>

			<div class="col-md-3">
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title"> <a href="viewStudent.php">
								<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> View students</a>
					</div>

				</div>

			</div>

			<div class="col-md-3">
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title"> <a href="addLecturer.php">
								<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add new Lecturer</a>
					</div>

				</div>

			</div>

			<div class="col-md-3">
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title"> <a href="viewLecturer.php">
								<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> View Lecturers</a>
					</div>

				</div>

			</div>

			<div class="col-md-3">
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title"> <a href="viewFinance.php">
								<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Finance statements</a>
					</div>

				</div>

			</div>

		</div>



	</div>
</div>
</div>