<?php

include('session.php');
include('dbconnect.php');
$query = mysqli_query($dbcon, "SELECT * FROM userlogin WHERE staffid = '$session_id'") or die(mysqli_error($dbcon));
$row = mysqli_fetch_array($query);

// count courses
$course_query = mysqli_query($dbcon, "SELECT COUNT(*) as total_courses FROM courses") or die(mysqli_error($dbcon));
$course_row = mysqli_fetch_array($course_query);
$total_courses = $course_row['total_courses'];

// count students
$student_query = mysqli_query($dbcon, "SELECT COUNT(*) as total_students FROM student") or die(mysqli_error($dbcon));
$student_row = mysqli_fetch_array($student_query);
$total_students = $student_row['total_students'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Lecturer Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body {
            background: #f8f9fa;
        }

        .sidebar {
            min-height: 100vh;
            background: #343a40;
            color: #fff;
            transition: left 0.3s;
        }

        .sidebar a {
            color: #adb5bd;
            text-decoration: none;
        }

        .sidebar a.active,
        .sidebar a:hover {
            color: #fff;
            background: #495057;
            border-radius: 5px;
        }

        .profile-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 10px;
        }

        /* Offcanvas sidebar for mobile */
        @media (max-width: 991.98px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: -250px;
                width: 250px;
                z-index: 1045;
                height: 100vh;
                transition: left 0.3s;
            }
            .sidebar.show {
                left: 0;
            }
            .sidebar-backdrop {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background: rgba(0,0,0,0.5);
                z-index: 1040;
            }
            .sidebar-backdrop.show {
                display: block;
            }
        }
    </style>
</head>

<body>
    <!-- Mobile Sidebar Toggle Button -->
    <button class="btn btn-dark d-lg-none m-3" id="sidebarToggle">
        <i class="bi bi-list"></i>
    </button>
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <!-- Sidebar -->
            <nav id="sidebar" class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 sidebar d-flex flex-column align-items-center py-3">
                <button class="btn btn-outline-light d-lg-none align-self-end mb-3" id="sidebarClose" aria-label="Close sidebar">
                    <i class="bi bi-x-lg"></i>
                </button>
                <img src="https://ui-avatars.com/api/?name=Lecturer&background=6c757d&color=fff" alt="Profile" class="profile-img">
                <h5 class="mt-2"><?php echo $row['surname'] . " " . $row['othernames']; ?></h5>
                <ul class="nav nav-pills flex-column mb-auto w-100 mt-4">
                    <li class="nav-item">
                        <a href="#" class="nav-link active">
                            <i class="bi bi-house"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="#" class="nav-link">
                            <i class="bi bi-journal-text"></i> Courses
                        </a>
                    </li>
                    <li>
                        <a href="students.php" class="nav-link">
                            <i class="bi bi-people"></i> Students
                        </a>
                    </li>
                    <li>
                        <a href="#" class="nav-link">
                            <i class="bi bi-calendar"></i> Schedule
                        </a>
                    </li>
                    <li>
                        <a href="#" class="nav-link">
                            <i class="bi bi-gear"></i> Settings
                        </a>
                    </li>
                    <li>
                        <a href="logout.php" class="nav-link text-danger">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- Main Content -->
            <main class="col py-3">
                <div class="container">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2>Welcome, Lecturer!</h2>
                        <button class="btn btn-primary">Add New Course</button>
                    </div>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">Total Courses</h5>
                                    <p class="card-text display-6"><?php echo $total_courses; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">Total Students</h5>
                                    <p class="card-text display-6"><?php echo $total_students; ?></p>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <!-- Example Table -->
                    <div class="mt-5">
                        <h4>Recent Courses</h4>
                        <div class="table-responsive">
                            <table class="table table-striped align-middle">
                                <thead>
                                    <tr>
                                        <th>Course Name</th>
                                        <th>Code</th>
                                        <th>Enrolled</th>
                                        <th>Next Class</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Introduction to Programming</td>
                                        <td>CS101</td>
                                        <td>30</td>
                                        <td>2025-07-01</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary">View</button>
                                            <button class="btn btn-sm btn-outline-secondary">Edit</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Database Systems</td>
                                        <td>CS202</td>
                                        <td>25</td>
                                        <td>2025-07-03</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary">View</button>
                                            <button class="btn btn-sm btn-outline-secondary">Edit</button>
                                        </td>
                                    </tr>
                                    <!-- More rows as needed -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <!-- Bootstrap JS and Icons -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar toggle for mobile
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarClose = document.getElementById('sidebarClose');
        const sidebarBackdrop = document.getElementById('sidebarBackdrop');

        function openSidebar() {
            sidebar.classList.add('show');
            sidebarBackdrop.classList.add('show');
        }
        function closeSidebar() {
            sidebar.classList.remove('show');
            sidebarBackdrop.classList.remove('show');
        }

        sidebarToggle.addEventListener('click', openSidebar);
        sidebarClose.addEventListener('click', closeSidebar);
        sidebarBackdrop.addEventListener('click', closeSidebar);

        // Optional: Close sidebar on resize to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 992) {
                closeSidebar();
            }
        });
    </script>
</body>
</html>