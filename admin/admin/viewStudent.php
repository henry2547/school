<?php
//require_once('session_login.php');
include('dbconnect.php');
include('header.php');

?>


<br />
<div class="container-fluid">
	<?php include('menubar.php'); ?>


	<div class="col-md-1"></div>
	<div class="col-md-8">
		<div class="panel panel-success">
			<div class="panel panel-success">
				<div class="panel-heading">
					<h3 class="panel-title">
						Student List
					</h3>
				</div>
				<div id="trans-table">
					<table id="myTable-trans" class="table table-bordered table-hover" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>S/N</th>
                                <th>Regno</th>
								<th>Fullname</th>
								<th>Email</th>
								<th>Phone</th>
								<th>Action</th>

							</tr>
						</thead>
						<tbody>
							<?php
							// The serial number variable
							$sn = 0;
							$query = mysqli_query($dbcon, "SELECT * FROM student WHERE studentStatus = 'approved'");
							while ($row = mysqli_fetch_array($query)) {
								$studentId = $row['studentId'];
								$sn++;
							?>
								<tr>

									<td><?php echo $sn; ?></td>
                                    <td><?php echo $row['reg_no']; ?></td>
									<td><?php echo $row['fname']; ?> <?php echo $row['sname']; ?></td>
									<td><?php echo $row['email']; ?></td>
									<td><?php echo $row['phone']; ?></td>

									<td class="empty" width="">
										<button type="button" data-toggle="modal" data-target="#edit<?php echo $row['studentId']; ?>" data-placement="left" title="Click to edit" class="btn btn-success"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
                                        <a data-placement="left" title="Click to view" id="view<?php echo $id; ?>" href="studentDetails.php<?php echo '?studentId=' . $studentId; ?>" class="btn btn-success">Details<i class="icon-pencil icon-large"></i></a>
										<?php include('modal_edit_user.php'); ?>



									</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>

		</div>
		<div class="col-md-1"></div>
	</div>


	<?php include('scripts.php'); ?>

	<script type="text/javascript">
		$(document).ready(function() {
			$('#myTable-trans').DataTable();
		});
	</script>
	</body>

	</html>