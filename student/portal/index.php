<?php
require("dbconnect.php");
include("session.php");

// Get student details
$student_query = "SELECT student.*, courses.course_name 
                  FROM student 
                  JOIN courses ON student.courseId = courses.courseId 
                  WHERE student.reg_no = '$session_id'";
$student_result = mysqli_query($dbcon, $student_query);
$student_data = mysqli_fetch_assoc($student_result);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal - Dashboard</title>
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #f8f9fa;
        }

        .dashboard-card {
            min-height: 180px;
            border-radius: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .dashboard-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.10);
            transform: translateY(-5px);
        }

        .dashboard-icon {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .profile-img {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
        }

        .recent-activity {
            max-height: 300px;
            overflow-y: auto;
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

        .course-progress {
            height: 8px;
            border-radius: 4px;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            font-size: 0.6rem;
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
                <a href="index.php" class="active">
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
                <a href="settings.php">
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
                    <i class="bi bi-speedometer2"></i> Dashboard
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
                                class="profile-img me-2" alt="Profile">
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
            <!-- Welcome Banner -->
            <div class="alert alert-success mb-4">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="alert-heading mb-1">Welcome back, <?php echo htmlspecialchars($student_data['fname']); ?>!</h4>
                        <p class="mb-0">You have 2 new announcements and 1 upcoming assignment.</p>
                    </div>
                    <button class="btn btn-outline-success">View Announcements</button>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <!-- Total Fee -->
                <div class="col-12 col-md-4">
                    <div class="card dashboard-card text-center p-3">
                        <div class="dashboard-icon text-success">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                        <h5 class="mb-1">Total Fee</h5>
                        <h3>
                            <?php
                            $select_sum_payment = "SELECT student.*, courses.* 
                            FROM student
                            JOIN courses ON student.courseId = courses.courseId
                            WHERE student.studentStatus = 'approved'
                            AND student.reg_no = '$session_id'";
                            $result = mysqli_query($dbcon, $select_sum_payment);
                            $fee = ($result && $row = mysqli_fetch_assoc($result)) ? number_format($row['fee'], 2) : "0.00";
                            echo "Kshs $fee";
                            ?>
                        </h3>
                    </div>
                </div>
                <!-- Amount Paid -->
                <div class="col-12 col-md-4">
                    <div class="card dashboard-card text-center p-3">
                        <div class="dashboard-icon text-primary">
                            <i class="bi bi-wallet2"></i>
                        </div>
                        <h5 class="mb-1">Amount Paid</h5>
                        <h3>
                            <?php
                            $query = "
                            SELECT payments.amount_paid
                            FROM payments
                            JOIN student ON payments.reg_no = student.reg_no
                            WHERE student.reg_no = '$session_id'
                            AND payments.payStatus = 'approved'";
                            $result = mysqli_query($dbcon, $query);
                            $total_amount_paid = 0;
                            if ($result) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $total_amount_paid += (int)$row['amount_paid'];
                                }
                            }
                            $formatTotal = number_format($total_amount_paid, 2);
                            echo "Kshs $formatTotal";
                            ?>
                        </h3>
                    </div>
                </div>
                <!-- Fee Balance -->
                <div class="col-12 col-md-4">
                    <div class="card dashboard-card text-center p-3">
                        <div class="dashboard-icon text-danger">
                            <i class="bi bi-cash-coin"></i>
                        </div>
                        <h5 class="mb-1">Fee Balance</h5>
                        <h3>
                            <?php
                            $query = "
                            SELECT payments.amount_paid, courses.fee
                            FROM payments
                            JOIN student ON payments.reg_no = student.reg_no
                            JOIN courses ON student.courseId = courses.courseId
                            WHERE student.reg_no = '$session_id'
                            AND payments.payStatus = 'approved'";
                            $result = mysqli_query($dbcon, $query);
                            $total_amount_paid = 0;
                            $fee = 0;
                            if ($result) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $total_amount_paid += (int)$row['amount_paid'];
                                    if ($fee === 0) {
                                        $fee = (int)$row['fee'];
                                    }
                                }
                            }
                            $balance = $fee - $total_amount_paid;
                            $formatBalance = number_format($balance, 2);
                            echo "Kshs $formatBalance";
                            ?>
                        </h3>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="row g-4 mb-4">
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="finance.php" class="text-decoration-none">
                        <div class="card h-100 text-center p-3 shadow-sm">
                            <div class="dashboard-icon text-success"><i class="bi bi-receipt"></i></div>
                            <h6 class="mb-0">Finance Statement</h6>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="profile.php" class="text-decoration-none">
                        <div class="card h-100 text-center p-3 shadow-sm">
                            <div class="dashboard-icon text-primary"><i class="bi bi-person-badge"></i></div>
                            <h6 class="mb-0">Profile</h6>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#makePaymentModal">
                        <div class="card h-100 text-center p-3 shadow-sm">
                            <div class="dashboard-icon text-warning"><i class="bi bi-credit-card"></i></div>
                            <h6 class="mb-0">Make Payment</h6>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="courses.php" class="text-decoration-none">
                        <div class="card h-100 text-center p-3 shadow-sm">
                            <div class="dashboard-icon text-info"><i class="bi bi-journal-bookmark"></i></div>
                            <h6 class="mb-0">My Courses</h6>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="timetable.php" class="text-decoration-none">
                        <div class="card h-100 text-center p-3 shadow-sm">
                            <div class="dashboard-icon text-secondary"><i class="bi bi-calendar-week"></i></div>
                            <h6 class="mb-0">Timetable</h6>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="results.php" class="text-decoration-none">
                        <div class="card h-100 text-center p-3 shadow-sm">
                            <div class="dashboard-icon text-purple"><i class="bi bi-graph-up"></i></div>
                            <h6 class="mb-0">Results</h6>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="library.php" class="text-decoration-none">
                        <div class="card h-100 text-center p-3 shadow-sm">
                            <div class="dashboard-icon text-brown"><i class="bi bi-book"></i></div>
                            <h6 class="mb-0">Library</h6>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="notifications.php" class="text-decoration-none">
                        <div class="card h-100 text-center p-3 shadow-sm">
                            <div class="dashboard-icon text-pink position-relative">
                                <i class="bi bi-bell"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    3
                                </span>
                            </div>
                            <h6 class="mb-0">Notifications</h6>
                        </div>
                    </a>
                </div>

                <!-- Make Payment Modal -->
                <div class="modal fade" id="makePaymentModal" tabindex="-1" aria-labelledby="makePaymentModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form class="modal-content" method="post" action="savePayment.php">
                            <div class="modal-header bg-warning text-dark">
                                <h5 class="modal-title" id="makePaymentModalLabel"><i class="bi bi-credit-card"></i> Make Payment</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="reg_no" value="<?php echo htmlspecialchars($session_id); ?>">
                                <div class="mb-3">
                                    <label for="amount_paid" class="form-label">Amount (Kshs)</label>
                                    <input type="number" class="form-control" id="amount_paid" name="amount_paid" min="1" required>
                                </div>
                                <div class="mb-3">
                                    <label for="mode_payment" class="form-label">Mode of Payment</label>
                                    <select class="form-select" id="mode_payment" name="mode_payment" required>
                                        <option value="" selected disabled>Select Mode</option>
                                        <option value="Mpesa">Mpesa</option>
                                        <option value="Bank">Bank</option>
                                        <option value="Cash">Cash</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="code" class="form-label">Transaction Code</label>
                                    <input type="text" class="form-control" id="code" name="code" required>
                                </div>
                                <div class="mb-3">
                                    <label for="date" class="form-label">Date of Payment</label>
                                    <input type="date" class="form-control" id="date" name="date" value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="bi bi-x-circle"></i> Cancel
                                </button>
                                <button type="submit" class="btn btn-warning text-dark">
                                    <i class="bi bi-save"></i> Submit Payment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            <!-- Quick Stats Row -->
            <div class="row g-4 mb-4">
                <!-- Course Progress -->
                <div class="col-12 col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-book me-2"></i>Semester Progress</h5>
                        </div>
                        <div class="card-body">

                            <h6> <strong>Course: </strong><?php echo htmlspecialchars($student_data['course_name']); ?></h6>
                            <?php
                            // Semester progress calculation
                            $semester_no = isset($student_data['semester']) ? (int)$student_data['semester'] : 1; // current semester number
                            $total_semesters = isset($student_data['total_semesters']) ? (int)$student_data['total_semesters'] : 4; // total semesters
                            $semester_start = isset($student_data['semester_start']) ? $student_data['semester_start'] : null; // semester start date from DB

                            // If not in DB, set manually for demo:
                            if (!$semester_start) {
                                $semester_start = date('Y-m-01', strtotime('-1 month'));
                            }
                            $semester_length_months = 3;

                            // Calculate progress
                            $start = new DateTime($semester_start);
                            $now = new DateTime();
                            $diff = $start->diff($now);
                            $months_passed = ($diff->y * 12) + $diff->m + ($diff->d / 30); // fractional months
                            $progress = min(100, max(0, round(($months_passed / $semester_length_months) * 100)));
                            if ($progress > 100) $progress = 100;
                            ?>
                            <div class="progress mt-3 mb-2 course-progress">
                                <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $progress; ?>%;"
                                    aria-valuenow="<?php echo $progress; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <small class="text-muted">
                                    Semester <?php echo $semester_no . ' of ' . $total_semesters; ?>
                                </small>
                                <small class="text-muted">
                                    <?php echo $progress; ?>% Complete
                                </small>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <div class="text-center">
                                    <h5 class="mb-0">12</h5>
                                    <small class="text-muted">Courses</small>
                                </div>
                                <div class="text-center">
                                    <h5 class="mb-0">8</h5>
                                    <small class="text-muted">Completed</small>
                                </div>
                                <div class="text-center">
                                    <h5 class="mb-0">3.8</h5>
                                    <small class="text-muted">GPA</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Events -->
                <div class="col-12 col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="bi bi-calendar-event me-2"></i>Upcoming Events</h5>
                            <a href="timetable.php" class="btn btn-sm btn-outline-success">View All</a>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">Mathematics Exam</h6>
                                        <small class="text-muted">Tomorrow, 9:00 AM</small>
                                    </div>
                                    <span class="badge bg-danger">Exam</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">Science Lab</h6>
                                        <small class="text-muted">Wed, 2:00 PM</small>
                                    </div>
                                    <span class="badge bg-primary">Lab</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">Fee Payment Deadline</h6>
                                        <small class="text-muted">Fri, 5:00 PM</small>
                                    </div>
                                    <span class="badge bg-warning text-dark">Deadline</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="row g-4">
                <div class="col-12 col-lg-8">
                    <div class="card shadow">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="bi bi-clock-history"></i> Recent Activities</h5>
                            <a href="finance.php" class="btn btn-outline-success btn-sm">View All</a>
                        </div>
                        <div class="card-body recent-activity p-0">
                            <ul class="list-group list-group-flush">
                                <?php
                                $query = "
                                SELECT payments.*, student.fname, student.sname
                                FROM payments
                                JOIN student ON payments.reg_no = student.reg_no
                                WHERE student.reg_no = '$session_id'
                                AND payments.payStatus = 'approved'
                                ORDER BY payments.date DESC
                                LIMIT 5";
                                $result = mysqli_query($dbcon, $query);
                                if ($result && mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
                                        echo '<span><i class="bi bi-credit-card text-success"></i> Payment of <b>Kshs ' . number_format($row['amount_paid'], 2) . '</b> via <b>' . htmlspecialchars($row['mode_payment']) . '</b></span>';
                                        echo '<span class="badge bg-light text-secondary"><i class="bi bi-calendar-event"></i> ' . htmlspecialchars($row['date']) . '</span>';
                                        echo '</li>';
                                    }
                                } else {
                                    echo '<li class="list-group-item text-center text-muted">No recent activities found.</li>';
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Quick Announcements -->
                <div class="col-12 col-lg-4">
                    <div class="card shadow">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="bi bi-megaphone"></i> Announcements</h5>
                            <a href="#" class="btn btn-outline-success btn-sm">View All</a>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <h6 class="mb-1">Semester Exams Schedule</h6>
                                    <small class="text-muted">Posted 2 days ago</small>
                                    <p class="mt-2 mb-0">The exam timetable for the current semester has been published...</p>
                                </li>
                                <li class="list-group-item">
                                    <h6 class="mb-1">Library Closure Notice</h6>
                                    <small class="text-muted">Posted 1 week ago</small>
                                    <p class="mt-2 mb-0">The library will be closed this Saturday for maintenance...</p>
                                </li>
                            </ul>
                        </div>
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
        // Toggle sidebar on mobile
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });

        // Close sidebar when clicking the close button
        document.getElementById('closeSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.remove('show');
        });

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