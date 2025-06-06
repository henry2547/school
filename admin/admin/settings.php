<?php

include('session.php');
include('dbconnect.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>System Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h3 class="mb-4"><i class="bi bi-gear-fill"></i> System Settings</h3>
    <div class="row">
        <div class="col-md-6">
            <!-- Change Password -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-key"></i> Change Password
                </div>
                <div class="card-body">
                    <form method="post" action="changePassword.php">
                        <div class="mb-3">
                            <label for="currentPassword" class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="currentPassword" name="currentPassword" required>
                        </div>
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Change Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <!-- System Info -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <i class="bi bi-info-circle"></i> System Information
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <strong>System Name:</strong> School Management System
                        </li>
                        <li class="list-group-item">
                            <strong>Version:</strong> 1.0.0
                        </li>
                        <li class="list-group-item">
                            <strong>Current User:</strong> <?php echo htmlspecialchars($_SESSION['staffid']); ?>
                        </li>
                        <li class="list-group-item">
                            <strong>Server Time:</strong> <?php echo date('Y-m-d H:i:s'); ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- home button -->
    <div class="text-center mt-4">
        <a href="index.php" class="btn btn-outline-success">
            <i class="bi bi-house"></i> Back to Dashboard
        </a>
    </div>



</body>
</html>