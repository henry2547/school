<?php
include('session.php');
include('dbconnect.php');
$query = mysqli_query($dbcon, "select * from userlogin where staffid = '$session_id'") or die(mysqli_error($dbcon));
$row = mysqli_fetch_array($query);
?>

<nav class="navbar navbar-expand-lg navbar-success bg-success mb-4">
    <div class="container-fluid">
        <span class="navbar-brand text-white fw-bold">
            <i class="bi bi-mortarboard"></i> SCHOOL MANAGEMENT SYSTEM
        </span>
        <div class="d-flex align-items-center ms-auto">
            <span class="text-white me-3">
                <i class="bi bi-person-circle"></i>
                <?php echo $row['surname'] . " " . $row['othernames'] . " (" . $row['staffid'] . ")"; ?>
            </span>
            <a href="profile.php" class="btn btn-outline-light btn-sm me-2">
                <i class="bi bi-pencil-square"></i> Edit
            </a>
            <a href="logout.php" class="btn btn-outline-light btn-sm">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row g-4">
        <!-- Student Management -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="addStudent.php" class="text-decoration-none">
                <div class="card h-100 shadow-sm text-center">
                    <div class="card-body">
                        <i class="bi bi-person-plus-fill display-4 text-primary"></i>
                        <h5 class="card-title mt-3">New Student</h5>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="viewStudent.php" class="text-decoration-none">
                <div class="card h-100 shadow-sm text-center">
                    <div class="card-body">
                        <i class="bi bi-people-fill display-4 text-info"></i>
                        <h5 class="card-title mt-3">View Students</h5>
                    </div>
                </div>
            </a>
        </div>
        <!-- Lecturer Management -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="addLecturer.php" class="text-decoration-none">
                <div class="card h-100 shadow-sm text-center">
                    <div class="card-body">
                        <i class="bi bi-person-badge-fill display-4 text-warning"></i>
                        <h5 class="card-title mt-3">Add Lecturer</h5>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="viewLecturer.php" class="text-decoration-none">
                <div class="card h-100 shadow-sm text-center">
                    <div class="card-body">
                        <i class="bi bi-person-lines-fill display-4 text-secondary"></i>
                        <h5 class="card-title mt-3">View Lecturers</h5>
                    </div>
                </div>
            </a>
        </div>
        <!-- Finance -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="viewFinance.php" class="text-decoration-none">
                <div class="card h-100 shadow-sm text-center">
                    <div class="card-body">
                        <i class="bi bi-cash-stack display-4 text-success"></i>
                        <h5 class="card-title mt-3">Finance Statements</h5>
                    </div>
                </div>
            </a>
        </div>
        <!-- Course Management -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="viewCourses.php" class="text-decoration-none">
                <div class="card h-100 shadow-sm text-center">
                    <div class="card-body">
                        <i class="bi bi-book display-4 text-info"></i>
                        <h5 class="card-title mt-3">Courses</h5>
                    </div>
                </div>
            </a>
        </div>
        <!-- Reports -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="reports.php" class="text-decoration-none">
                <div class="card h-100 shadow-sm text-center">
                    <div class="card-body">
                        <i class="bi bi-bar-chart-line-fill display-4 text-primary"></i>
                        <h5 class="card-title mt-3">Reports & Analytics</h5>
                    </div>
                </div>
            </a>
        </div>
        <!-- Attendance -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="attendance.php" class="text-decoration-none">
                <div class="card h-100 shadow-sm text-center">
                    <div class="card-body">
                        <i class="bi bi-calendar-check display-4 text-success"></i>
                        <h5 class="card-title mt-3">Attendance</h5>
                    </div>
                </div>
            </a>
        </div>
        <!-- User Management -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="users.php" class="text-decoration-none">
                <div class="card h-100 shadow-sm text-center">
                    <div class="card-body">
                        <i class="bi bi-person-gear display-4 text-secondary"></i>
                        <h5 class="card-title mt-3">User Management</h5>
                    </div>
                </div>
            </a>
        </div>
        <!-- Document Management -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="documents.php" class="text-decoration-none">
                <div class="card h-100 shadow-sm text-center">
                    <div class="card-body">
                        <i class="bi bi-folder2-open display-4 text-warning"></i>
                        <h5 class="card-title mt-3">Documents</h5>
                    </div>
                </div>
            </a>
        </div>
        <!-- System Settings -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="settings.php" class="text-decoration-none">
                <div class="card h-100 shadow-sm text-center">
                    <div class="card-body">
                        <i class="bi bi-gear-fill display-4 text-danger"></i>
                        <h5 class="card-title mt-3">System Settings</h5>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>