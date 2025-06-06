<?php
session_start();
include('dbconnect.php');
if (isset($_SESSION['reg_no'])) {
	if ($_SESSION['role'] == 'Student') {
		header("Location: portal/");
	}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Student Login</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Bootstrap 5 & Icons -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
	<style>
		.loading-overlay {
			display: none;
			position: fixed;
			top: 0;
			left: 0;
			width: 100vw;
			height: 100vh;
			background: rgba(255, 255, 255, 0.7);
			z-index: 9999;
			align-items: center;
			justify-content: center;
		}
	</style>
</head>

<body class="bg-light">

	<div class="container min-vh-100 d-flex align-items-center justify-content-center">
		<div class="col-12 col-sm-8 col-md-6 col-lg-5">
			<div class="card shadow">
				<div class="card-header bg-success text-white text-center">
					<h4 class="mb-0"><i class="bi bi-person-circle"></i> Student Login</h4>
				</div>
				<div class="card-body">
					<form action="logincheck.php" method="post" id="login-form" autocomplete="off">
						<div class="mb-3">
							<label for="un" class="form-label">Reg No</label>
							<input type="text" class="form-control" name="username" id="un" placeholder="Enter registration number" required autofocus>
						</div>
						<div class="mb-3">
							<label for="pwd" class="form-label">Password</label>
							<div class="input-group">
								<input type="password" class="form-control" name="pwd" id="pwd" placeholder="Enter password" required>
								<button class="btn btn-outline-secondary" type="button" id="togglePassword" tabindex="-1">
									<i class="bi bi-eye" id="eyeIcon"></i>
								</button>
							</div>
						</div>
						<?php if (isset($_SESSION['error'])): ?>
							<div class="alert alert-danger text-center py-2">
								<?php echo $_SESSION['error'];
								unset($_SESSION['error']); ?>
							</div>
						<?php endif; ?>
						<div class="d-grid">
							<button type="submit" name="login" class="btn btn-success">
								<i class="bi bi-box-arrow-in-right"></i> Login
							</button>
						</div>
					</form>
				</div>
			</div>
			<!-- Loading overlay with spinner -->
			<div class="loading-overlay" id="loading-overlay" style="display:none;">
				<div class="spinner-border text-success" role="status" style="width:3rem;height:3rem;">
					<span class="visually-hidden">Loading...</span>
				</div>
			</div>
		</div>
	</div>

	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<script>
		document.getElementById('login-form').addEventListener('submit', function() {
			document.getElementById('loading-overlay').style.display = 'flex';
		});

		document.getElementById('togglePassword').addEventListener('click', function() {
			const pwd = document.getElementById('pwd');
			const eyeIcon = document.getElementById('eyeIcon');
			if (pwd.type === "password") {
				pwd.type = "text";
				eyeIcon.classList.remove('bi-eye');
				eyeIcon.classList.add('bi-eye-slash');
			} else {
				pwd.type = "password";
				eyeIcon.classList.remove('bi-eye-slash');
				eyeIcon.classList.add('bi-eye');
			}
		});
	</script>
</body>

</html>