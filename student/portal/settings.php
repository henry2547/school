<?php
require("dbconnect.php");
include("session.php");

// Get student details
$student_query = "SELECT * FROM student WHERE reg_no = '$session_id'";
$student_result = mysqli_query($dbcon, $student_query);
$student_data = mysqli_fetch_assoc($student_result);

// Initialize variables
$success_message = $error_message = "";

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Profile Update
    if (isset($_POST['update_profile'])) {
        $fname = mysqli_real_escape_string($dbcon, $_POST['fname']);
        $sname = mysqli_real_escape_string($dbcon, $_POST['sname']);
        $email = mysqli_real_escape_string($dbcon, $_POST['email']);
        $phone = mysqli_real_escape_string($dbcon, $_POST['phone']);

        $update_query = "UPDATE student SET 
                        fname = '$fname',
                        sname = '$sname',
                        email = '$email',
                        phone = '$phone'
                        WHERE reg_no = '$session_id'";

        if (mysqli_query($dbcon, $update_query)) {
            $success_message = "Profile updated successfully!";
            // Refresh student data
            $student_result = mysqli_query($dbcon, $student_query);
            $student_data = mysqli_fetch_assoc($student_result);
        } else {
            $error_message = "Error updating profile: " . mysqli_error($dbcon);
        }
    }

    // Password Change
    if (isset($_POST['change_password'])) {
        $current_password = mysqli_real_escape_string($dbcon, $_POST['current_password']);
        $new_password = mysqli_real_escape_string($dbcon, $_POST['new_password']);
        $confirm_password = mysqli_real_escape_string($dbcon, $_POST['confirm_password']);

        // Verify current password
        if (password_verify($current_password, $student_data['password'])) {
            if ($new_password === $confirm_password) {
                if (strlen($new_password) >= 8) {
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $update_query = "UPDATE student SET password = '$hashed_password' WHERE reg_no = '$session_id'";

                    if (mysqli_query($dbcon, $update_query)) {
                        $success_message = "Password changed successfully!";
                    } else {
                        $error_message = "Error changing password: " . mysqli_error($dbcon);
                    }
                } else {
                    $error_message = "New password must be at least 8 characters long";
                }
            } else {
                $error_message = "New passwords do not match";
            }
        } else {
            $error_message = "Current password is incorrect";
        }
    }

    // Notification Preferences
    if (isset($_POST['update_notifications'])) {
        $email_notifications = isset($_POST['email_notifications']) ? 1 : 0;
        $sms_notifications = isset($_POST['sms_notifications']) ? 1 : 0;
        $push_notifications = isset($_POST['push_notifications']) ? 1 : 0;

        $update_query = "UPDATE student SET 
                        email_notifications = '$email_notifications',
                        sms_notifications = '$sms_notifications',
                        push_notifications = '$push_notifications'
                        WHERE reg_no = '$session_id'";

        if (mysqli_query($dbcon, $update_query)) {
            $success_message = "Notification preferences updated!";
            // Refresh student data
            $student_result = mysqli_query($dbcon, $student_query);
            $student_data = mysqli_fetch_assoc($student_result);
        } else {
            $error_message = "Error updating notification preferences: " . mysqli_error($dbcon);
        }
    }

    // Profile Picture Upload
    if (isset($_POST['upload_photo']) && isset($_FILES['profile_pic'])) {
        $target_dir = "uploads/profile_pics/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $target_file = $target_dir . basename($_FILES["profile_pic"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image
        $check = getimagesize($_FILES["profile_pic"]["tmp_name"]);
        if ($check !== false) {
            // Check file size (max 2MB)
            if ($_FILES["profile_pic"]["size"] <= 2000000) {
                // Allow certain file formats
                if ($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif") {
                    // Generate unique filename
                    $new_filename = $session_id . "_" . time() . "." . $imageFileType;
                    $target_file = $target_dir . $new_filename;

                    if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
                        // Update database with new profile picture path
                        $update_query = "UPDATE student SET profile_pic = '$target_file' WHERE reg_no = '$session_id'";
                        if (mysqli_query($dbcon, $update_query)) {
                            $success_message = "Profile picture updated successfully!";
                            // Refresh student data
                            $student_result = mysqli_query($dbcon, $student_query);
                            $student_data = mysqli_fetch_assoc($student_result);
                        } else {
                            $error_message = "Error updating profile picture in database: " . mysqli_error($dbcon);
                        }
                    } else {
                        $error_message = "Sorry, there was an error uploading your file.";
                    }
                } else {
                    $error_message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                }
            } else {
                $error_message = "Sorry, your file is too large (max 2MB).";
            }
        } else {
            $error_message = "File is not an image.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal - Settings</title>
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #f8f9fa;
        }

        .settings-card {
            border-radius: 0.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .settings-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.10);
        }

        .profile-img-container {
            position: relative;
            width: 150px;
            height: 150px;
            margin: 0 auto;
        }

        .profile-img {
            width: 30%;
            height: 30%;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #198754;
        }

        .profile-img-upload {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background: #198754;
            color: white;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        #profile-pic-input {
            display: none;
        }

        .sidebar {
            min-height: 100vh;
            background: #fff;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            width: 280px;
            transition: all 0.3s;
            z-index: 1000;
        }

        .sidebar.collapsed {
            margin-left: -280px;
        }

        .sidebar-header {
            padding: 1rem;
            background: #198754;
            color: white;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
        }

        .sidebar-menu li a {
            display: block;
            padding: 0.75rem 1rem;
            color: #495057;
            text-decoration: none;
            transition: all 0.2s;
        }

        .sidebar-menu li a:hover {
            background: #f1f1f1;
            color: #198754;
        }

        .sidebar-menu li a.active {
            background: #e9f5ee;
            color: #198754;
            border-left: 4px solid #198754;
        }

        .sidebar-menu li a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .main-content {
            margin-left: 280px;
            transition: all 0.3s;
        }

        .main-content.expanded {
            margin-left: 0;
        }

        .navbar-toggler {
            border: none;
            outline: none;
        }

        .hamburger-icon {
            font-size: 1.5rem;
        }

        @media (max-width: 992px) {
            .sidebar {
                margin-left: -280px;
            }

            .sidebar.show {
                margin-left: 0;
            }

            .main-content {
                margin-left: 0;
            }
        }

        .form-section {
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #eee;
        }

        .form-section:last-child {
            border-bottom: none;
        }

        .section-title {
            color: #198754;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #198754;
        }

        .password-toggle {
            cursor: pointer;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
        }
    </style>

</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar d-lg-block" id="sidebar">
        <div class="sidebar-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-mortarboard me-2"></i>Student Portal
            </h5>
            <button class="btn btn-sm btn-outline-light d-lg-none" id="closeSidebar">
                <i class="bi bi-x"></i>
            </button>
        </div>

        <!-- Student Profile Summary -->
        <div class="p-3 border-bottom">
            <div class="d-flex align-items-center">
                <img src="https://cdn-icons-png.flaticon.com/512/4537/4537019.png"
                    class="profile-img me-3" alt="Profile">
                <div>
                    <h6 class="mb-0"><?php echo htmlspecialchars($student_data['fname'] . ' ' . $student_data['sname']); ?></h6>
                    <small class="text-muted"><?php echo htmlspecialchars($student_data['reg_no']); ?></small>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <ul class="sidebar-menu">
            <li>
                <a href="index.php">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="profile.php">
                    <i class="bi bi-person"></i> My Profile
                </a>
            </li>
            <li>
                <a href="finance.php">
                    <i class="bi bi-wallet2"></i> Finance
                </a>
            </li>
            <li>
                <a href="courses.php">
                    <i class="bi bi-book"></i> My Courses
                </a>
            </li>
            <li>
                <a href="timetable.php">
                    <i class="bi bi-calendar-week"></i> Timetable
                </a>
            </li>
            <li>
                <a href="results.php">
                    <i class="bi bi-journal-text"></i> Results
                </a>
            </li>
            
            <li>
                <a href="settings.php" class="active">
                    <i class="bi bi-gear"></i> Settings
                </a>
            </li>
            <li>
                <a href="logout.php">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm mb-4">
            <div class="container-fluid">
                <button class="navbar-toggler me-2" type="button" id="sidebarToggle">
                    <i class="bi bi-list hamburger-icon"></i>
                </button>
                <a class="navbar-brand fw-bold d-none d-sm-block" href="#">
                    <i class="bi bi-gear"></i> Settings
                </a>

                <div class="d-flex align-items-center ms-auto">
                    <div class="dropdown me-3">
                        <a href="#" class="text-white dropdown-toggle" id="notificationsDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-bell"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                3
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown">
                            <li>
                                <h6 class="dropdown-header">Notifications</h6>
                            </li>
                            <li><a class="dropdown-item" href="#">New assignment posted</a></li>
                            <li><a class="dropdown-item" href="#">Fee payment received</a></li>
                            <li><a class="dropdown-item" href="#">Library book due soon</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-center" href="notifications.php">View all</a></li>
                        </ul>
                    </div>

                    <div class="dropdown">
                        <a href="#" class="text-white dropdown-toggle d-flex align-items-center"
                            id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://cdn-icons-png.flaticon.com/512/4537/4537019.png"
                                class="profile-img me-2" alt="Profile" style="width: 32px; height: 32px;">
                            <span class="d-none d-md-inline"><?php echo htmlspecialchars($student_data['fname']); ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                            <li><a class="dropdown-item" href="profile.php"><i class="bi bi-person me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="settings.php"><i class="bi bi-gear me-2"></i>Settings</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <div class="container pb-4">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0"><i class="bi bi-gear me-2"></i>Account Settings</h2>
            </div>

            <!-- Success/Error Messages -->
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $success_message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $error_message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="row">
                
                <div class="col-lg-12">
                    <div class="card settings-card">
                        <div class="card-body">
                            <!-- Profile Information Section -->
                            <div class="form-section">
                                <h4 class="section-title"><i class="bi bi-person me-2"></i>Profile Information</h4>
                                <form method="post">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="fname" class="form-label">First Name</label>
                                            <input type="text" class="form-control" id="fname" name="fname"
                                                value="<?php echo htmlspecialchars($student_data['fname']); ?>" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="sname" class="form-label">Last Name</label>
                                            <input type="text" class="form-control" id="sname" name="sname"
                                                value="<?php echo htmlspecialchars($student_data['sname']); ?>" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="email" class="form-label">Email Address</label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                value="<?php echo htmlspecialchars($student_data['email']); ?>" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="phone" class="form-label">Phone Number</label>
                                            <input type="tel" class="form-control" id="phone" name="phone"
                                                value="<?php echo htmlspecialchars($student_data['phone']); ?>">
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <button type="submit" name="update_profile" class="btn btn-success">
                                            <i class="bi bi-save me-1"></i> Save Changes
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Change Password Section -->
                            <div class="form-section">
                                <h4 class="section-title"><i class="bi bi-shield-lock me-2"></i>Change Password</h4>
                                <form method="post">
                                    <div class="mb-3">
                                        <label for="current_password" class="form-label">Current Password</label>
                                        <div class="position-relative">
                                            <input type="password" class="form-control" id="current_password"
                                                name="current_password" required>
                                            <i class="bi bi-eye-slash password-toggle"
                                                onclick="togglePassword('current_password', this)"></i>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="new_password" class="form-label">New Password</label>
                                        <div class="position-relative">
                                            <input type="password" class="form-control" id="new_password"
                                                name="new_password" required>
                                            <i class="bi bi-eye-slash password-toggle"
                                                onclick="togglePassword('new_password', this)"></i>
                                        </div>
                                        <small class="text-muted">Must be at least 8 characters long</small>
                                    </div>
                                    <div class="mb-3">
                                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                                        <div class="position-relative">
                                            <input type="password" class="form-control" id="confirm_password"
                                                name="confirm_password" required>
                                            <i class="bi bi-eye-slash password-toggle"
                                                onclick="togglePassword('confirm_password', this)"></i>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <button type="submit" name="change_password" class="btn btn-success">
                                            <i class="bi bi-key me-1"></i> Change Password
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Notification Preferences -->
                            <div class="form-section">
                                <h4 class="section-title"><i class="bi bi-bell me-2"></i>Notification Preferences</h4>
                                <form method="post">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="email_notifications"
                                            name="email_notifications" <?php echo $student_data['email_notifications'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="email_notifications">
                                            Email Notifications
                                        </label>
                                    </div>
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="sms_notifications"
                                            name="sms_notifications" <?php echo $student_data['sms_notifications'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="sms_notifications">
                                            SMS Notifications
                                        </label>
                                    </div>
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="push_notifications"
                                            name="push_notifications" <?php echo $student_data['push_notifications'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="push_notifications">
                                            Push Notifications
                                        </label>
                                    </div>
                                    <div class="text-end">
                                        <button type="submit" name="update_notifications" class="btn btn-success">
                                            <i class="bi bi-save me-1"></i> Save Preferences
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Account Security -->
                            <div class="form-section">
                                <h4 class="section-title"><i class="bi bi-shield-check me-2"></i>Account Security</h4>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h6 class="mb-0">Two-Factor Authentication</h6>
                                        <small class="text-muted">Add an extra layer of security to your account</small>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="twoFactorAuth" disabled>
                                        <label class="form-check-label" for="twoFactorAuth">Disabled</label>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">Login Activity</h6>
                                        <small class="text-muted">View recent login attempts</small>
                                    </div>
                                    <a href="#" class="btn btn-outline-success btn-sm">View Logs</a>
                                </div>
                            </div>

                            <!-- Danger Zone -->
                            <div class="form-section">
                                <h4 class="section-title text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Danger Zone</h4>
                                <div class="alert alert-danger">
                                    <h6 class="alert-heading">Deactivate Account</h6>
                                    <p class="mb-2">This will temporarily disable your account. You can reactivate it by logging in again.</p>
                                    <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deactivateModal">
                                        Deactivate Account
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Deactivate Account Modal -->
        <div class="modal fade" id="deactivateModal" tabindex="-1" aria-labelledby="deactivateModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="deactivateModalLabel">Deactivate Account</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to deactivate your account? This will:</p>
                        <ul>
                            <li>Log you out immediately</li>
                            <li>Prevent you from accessing the portal</li>
                            <li>Keep all your data intact for when you reactivate</li>
                        </ul>
                        <p>You can reactivate your account by simply logging in again.</p>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="confirmDeactivate">
                            <label class="form-check-label" for="confirmDeactivate">
                                I understand the consequences
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirmDeactivateBtn" disabled>
                            Deactivate Account
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <footer class="text-center py-3 mt-4 bg-white border-top">
            <small>&copy; <?php echo date('Y'); ?> School Management System. All rights reserved.</small>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        function togglePassword(inputId, icon) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            }
        }

        // Profile picture preview
        document.getElementById('profile-pic-input').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('profile-pic-preview').src = event.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        // Confirm deactivate checkbox
        document.getElementById('confirmDeactivate').addEventListener('change', function() {
            document.getElementById('confirmDeactivateBtn').disabled = !this.checked;
        });

        // Sidebar toggle functionality
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });

        // Close sidebar when clicking the close button
        if (document.getElementById('closeSidebar')) {
            document.getElementById('closeSidebar').addEventListener('click', function() {
                document.getElementById('sidebar').classList.remove('show');
            });
        }

        // Automatically collapse sidebar on small screens
        function handleResize() {
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth < 992) {
                sidebar.classList.remove('show');
            } else {
                sidebar.classList.add('show');
            }
        }

        // Initial check on page load
        window.addEventListener('load', handleResize);
        // Check when window is resized
        window.addEventListener('resize', handleResize);
    </script>
</body>

</html>