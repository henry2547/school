<?php
include('dbconnect.php');
include('header.php');
?>

<div class="container py-4">
	<div class="row justify-content-center">
		<div class="col-lg-10">
			<div class="card shadow">
				<div class="card-header bg-success text-white">
					<h4 class="mb-0"><i class="bi bi-people-fill"></i> Student List</h4>
				</div>

				<div class="card-body">
					<div class="table-responsive">
						<table id="myTable-trans" class="table table-bordered table-hover align-middle">
							<thead class="table-success">
								<tr>
									<th>S/N</th>
									<th><i class="bi bi-person-badge"></i> Regno</th>
									<th><i class="bi bi-person"></i> Fullname</th>
									<th><i class="bi bi-envelope"></i> Email</th>
									<th><i class="bi bi-telephone"></i> Phone</th>
									<th><i class="bi bi-gear"></i> Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
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
										<td>

											<button type="button" data-bs-toggle="modal" data-bs-target="#edit<?php echo $row['studentId']; ?>" title="Edit" class="btn btn-outline-success btn-sm me-1">
												<i class="bi bi-pencil-square"></i>
											</button>

											<!-- Edit Student Modal -->
											<div class="modal fade" id="edit<?php echo $row['studentId']; ?>" tabindex="-1" aria-labelledby="editLabel<?php echo $row['studentId']; ?>" aria-hidden="true">
												<div class="modal-dialog modal-dialog-centered">
													<div class="modal-content">
														<form action="editStudent.php" method="post">
															<div class="modal-header bg-success text-white">
																<h5 class="modal-title" id="editLabel<?php echo $row['studentId']; ?>">
																	<i class="bi bi-pencil-square"></i> Edit Student
																</h5>
																<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
															</div>
															<div class="modal-body">
																<input type="hidden" name="studentId" value="<?php echo $row['studentId']; ?>">
																<div class="mb-3">
																	<label for="fname<?php echo $row['studentId']; ?>" class="form-label">Firstname</label>
																	<input type="text" class="form-control" id="fname<?php echo $row['studentId']; ?>" name="fname" value="<?php echo htmlspecialchars($row['fname']); ?>" required>
																</div>
																<div class="mb-3">
																	<label for="sname<?php echo $row['studentId']; ?>" class="form-label">Secondname</label>
																	<input type="text" class="form-control" id="sname<?php echo $row['studentId']; ?>" name="sname" value="<?php echo htmlspecialchars($row['sname']); ?>" required>
																</div>
																<div class="mb-3">
																	<label for="email<?php echo $row['studentId']; ?>" class="form-label">Email</label>
																	<input type="email" class="form-control" id="email<?php echo $row['studentId']; ?>" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>
																</div>
																<div class="mb-3">
																	<label for="phone<?php echo $row['studentId']; ?>" class="form-label">Phone</label>
																	<input type="tel" class="form-control" id="phone<?php echo $row['studentId']; ?>" name="phone" value="<?php echo htmlspecialchars($row['phone']); ?>" required>
																</div>
															</div>
															<div class="modal-footer">
																<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
																	<i class="bi bi-x-circle"></i> Cancel
																</button>
																<button type="submit" class="btn btn-success">
																	<i class="bi bi-save"></i> Save Changes
																</button>
															</div>
														</form>
													</div>
												</div>
											</div>

											<a href="studentDetails.php?studentId=<?php echo $studentId; ?>" class="btn btn-outline-primary btn-sm" title="Details">
												<i class="bi bi-info-circle"></i> Details
											</a>
											<?php include('modal_edit_user.php'); ?>
										</td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>


				<!-- home button -->
				<div class="card-footer text-center">
					<a href="index.php" class="btn btn-primary">
						<i class="bi bi-house"></i> Home
					</a>
				</div>


			</div>
		</div>
	</div>
</div>

<?php include('scripts.php'); ?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#myTable-trans').DataTable({
			"lengthMenu": [10, 25, 50, 100],
			"pageLength": 10,
			"language": {
				"search": "<i class='bi bi-search'></i> Search:"
			}
		});
	});
</script>
</body>

</html>