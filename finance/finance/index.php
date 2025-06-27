<?php
session_start();
include("dbconnect.php");

// Optionally, check if user is logged in as finance manager
// if (!isset($_SESSION['finance_manager'])) { header("Location: ../login.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Finance Manager Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }

        .dashboard-card {
            min-height: 140px;
            border-radius: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .dashboard-icon {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .table thead th {
            vertical-align: middle;
        }
    </style>

</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm mb-4">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">
                <i class="bi bi-cash-coin"></i> Finance Manager Panel
            </a>
            <div class="d-flex align-items-center ms-auto">
                <!-- edit profile -->
                <a href="edit_profile.php" class="btn btn-outline-light btn-sm me-2" title="Edit Profile">
                    <i class="bi bi-person-fill"></i>
                </a>
                <a href="logout.php" class="btn btn-outline-light btn-sm" title="Logout">
                    <i class="bi bi-box-arrow-right"></i>
                </a>
            </div>
        </div>
    </nav>


    <div class="container pb-4">

        <!-- Dashboard Cards -->
        <div class="row g-4 mb-4">
            <!-- Total Students -->
            <div class="col-12 col-md-3">
                <div class="card dashboard-card text-center p-3">
                    <div class="dashboard-icon text-primary"><i class="bi bi-people"></i></div>
                    <h6 class="mb-1">Total Students</h6>
                    <h4>
                        <?php
                        $res = mysqli_query($dbcon, "SELECT COUNT(*) as total FROM student WHERE studentStatus='approved'");
                        $row = mysqli_fetch_assoc($res);
                        echo $row['total'];
                        ?>
                    </h4>
                </div>
            </div>
            <!-- Total Collected -->
            <div class="col-12 col-md-3">
                <div class="card dashboard-card text-center p-3">
                    <div class="dashboard-icon text-success"><i class="bi bi-wallet2"></i></div>
                    <h6 class="mb-1">Total Collected</h6>
                    <h4>
                        <?php
                        $res = mysqli_query($dbcon, "SELECT SUM(amount_paid) as total FROM payments WHERE payStatus='approved'");
                        $row = mysqli_fetch_assoc($res);
                        echo "Kshs " . number_format($row['total'] ?? 0, 2);
                        ?>
                    </h4>
                </div>
            </div>
            <!-- Pending Payments -->
            <div class="col-12 col-md-3">
                <div class="card dashboard-card text-center p-3">
                    <div class="dashboard-icon text-warning"><i class="bi bi-hourglass-split"></i></div>
                    <h6 class="mb-1">Pending Payments</h6>
                    <h4>
                        <?php
                        $res = mysqli_query($dbcon, "SELECT COUNT(*) as total FROM payments WHERE payStatus='pending'");
                        $row = mysqli_fetch_assoc($res);
                        echo $row['total'];
                        ?>
                    </h4>
                </div>
            </div>
            <!-- Total Courses -->
            <div class="col-12 col-md-3">
                <div class="card dashboard-card text-center p-3">
                    <div class="dashboard-icon text-info"><i class="bi bi-book"></i></div>
                    <h6 class="mb-1">Courses</h6>
                    <h4>
                        <?php
                        $res = mysqli_query($dbcon, "SELECT COUNT(*) as total FROM courses");
                        $row = mysqli_fetch_assoc($res);
                        echo $row['total'];
                        ?>
                    </h4>
                </div>
            </div>
        </div>


        <!-- Analytics & Charts -->
        <div class="row mb-4">
            <div class="col-12 col-lg-6 mb-3">
                <div class="card shadow">
                    <div class="card-header bg-info text-white">
                        <i class="bi bi-bar-chart"></i> Monthly Collections
                    </div>
                    <div class="card-body">
                        <canvas id="collectionsChart" height="120"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6 mb-3 h-100">
                <div class="card shadow">
                    <div class="card-header bg-warning text-dark">
                        <i class="bi bi-donut-chart"></i> Payment Mode Breakdown
                    </div>
                    <div class="card-body">
                        <canvas id="modeChart" height="60"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fee Defaulters -->
        <div class="card shadow mb-4">
            <div class="card-header bg-danger text-white">
                <i class="bi bi-exclamation-circle"></i> Fee Defaulters
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-danger">
                            <tr>
                                <th>#</th>
                                <th>Reg No</th>
                                <th>Name</th>
                                <th>Course</th>
                                <th>Total Fee</th>
                                <th>Total Paid</th>
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sn = 0;
                            $defaulters = mysqli_query($dbcon, "
                                SELECT s.reg_no, s.fname, s.sname, c.course_name, c.fee,
                                    IFNULL(SUM(p.amount_paid),0) as total_paid
                                FROM student s
                                JOIN courses c ON s.courseId = c.courseId
                                LEFT JOIN payments p ON s.reg_no = p.reg_no AND p.payStatus='approved'
                                WHERE s.studentStatus='approved'
                                GROUP BY s.reg_no
                                HAVING (c.fee - IFNULL(SUM(p.amount_paid),0)) > 0
                                ORDER BY (c.fee - IFNULL(SUM(p.amount_paid),0)) DESC
                            ");
                            if ($defaulters && mysqli_num_rows($defaulters) > 0) {
                                while ($row = mysqli_fetch_assoc($defaulters)) {
                                    $sn++;
                                    $balance = $row['fee'] - $row['total_paid'];
                                    echo "<tr>";
                                    echo "<td>{$sn}</td>";
                                    echo "<td>" . htmlspecialchars($row['reg_no']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['fname'] . ' ' . $row['sname']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['course_name']) . "</td>";
                                    echo "<td>Kshs " . number_format($row['fee'], 2) . "</td>";
                                    echo "<td>Kshs " . number_format($row['total_paid'], 2) . "</td>";
                                    echo "<td class='fw-bold text-danger'>Kshs " . number_format($balance, 2) . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7' class='text-center text-muted'>No defaulters found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Finance Reports -->
        <div class="card shadow mb-4">
            <div class="card-header bg-secondary text-white">
                <i class="bi bi-file-earmark-bar-graph"></i> Finance Reports
            </div>
            <div class="card-body">
                <a href="export_report.php?type=all_payments" class="btn btn-outline-success btn-sm mb-2">
                    <i class="bi bi-download"></i> Download All Payments (CSV)
                </a>
                <a href="export_report.php?type=defaulters" class="btn btn-outline-danger btn-sm mb-2">
                    <i class="bi bi-download"></i> Download Defaulters (CSV)
                </a>
                <a href="export_report.php?type=summary" class="btn btn-outline-primary btn-sm mb-2">
                    <i class="bi bi-download"></i> Download Summary (CSV)
                </a>
                <span class="text-muted ms-2">More reports available in the Reports section.</span>
            </div>
        </div>

        <!-- Fee Structure Management -->
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <span><i class="bi bi-gear"></i> Fee Structure Management</span>
                <a href="edit_fee_structure.php" class="btn btn-light btn-sm"><i class="bi bi-pencil"></i> Edit</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th>Course</th>
                                <th>Current Fee</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $courses = mysqli_query($dbcon, "SELECT * FROM courses");
                            if ($courses && mysqli_num_rows($courses) > 0) {
                                while ($row = mysqli_fetch_assoc($courses)) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['course_name']) . "</td>";
                                    echo "<td>Kshs " . number_format($row['fee'], 2) . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='2' class='text-center text-muted'>No courses found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Receipts Management -->
        <div class="card shadow mb-4">
            <div class="card-header bg-success text-white">
                <i class="bi bi-receipt-cutoff"></i> Receipts Management
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-success">
                            <tr>
                                <th>#</th>
                                <th>Reg No</th>
                                <th>Name</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Receipt</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sn = 0;
                            $receipts = mysqli_query($dbcon, "
                                SELECT payments.*, student.fname, student.sname
                                FROM payments
                                JOIN student ON payments.reg_no = student.reg_no
                                WHERE payments.payStatus='approved'
                                ORDER BY payments.date DESC LIMIT 10
                            ");
                            if ($receipts && mysqli_num_rows($receipts) > 0) {
                                while ($row = mysqli_fetch_assoc($receipts)) {
                                    $sn++;
                                    echo "<tr>";
                                    echo "<td>{$sn}</td>";
                                    echo "<td>" . htmlspecialchars($row['reg_no']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['fname'] . ' ' . $row['sname']) . "</td>";
                                    echo "<td>Kshs " . number_format($row['amount_paid'], 2) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                                    echo "<td>
                                        <a href='print_receipt.php?id=" . $row['paymentId'] . "' class='btn btn-outline-info btn-sm' target='_blank'>
                                            <i class='bi bi-printer'></i> Print
                                        </a>
                                    </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center text-muted'>No receipts found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Payments Table -->
        <div class="card shadow mb-4">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-receipt"></i> All Payments</h5>
                <form class="d-flex" method="get">
                    <input class="form-control form-control-sm me-2" type="search" name="search" placeholder="Search RegNo/Code" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button class="btn btn-light btn-sm" type="submit"><i class="bi bi-search"></i></button>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-success">
                            <tr>
                                <th>#</th>
                                <th>Reg No</th>
                                <th>Name</th>
                                <th>Course</th>
                                <th>Total Paid</th>
                                <th>Last Payment</th>
                                <th>Status</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sn = 0;
                            $search = isset($_GET['search']) ? mysqli_real_escape_string($dbcon, $_GET['search']) : '';
                            $sql = "SELECT 
                                    student.reg_no, 
                                    student.fname, 
                                    student.sname, 
                                    courses.course_name,
                                    SUM(payments.amount_paid) as total_paid,
                                    MAX(payments.date) as last_payment_date,
                                    MAX(payments.payStatus) as last_status
                                FROM payments
                                JOIN student ON payments.reg_no = student.reg_no
                                JOIN courses ON student.courseId = courses.courseId";
                            if ($search) {
                                $sql .= " WHERE student.reg_no LIKE '%$search%' OR payments.code LIKE '%$search%'";
                            }
                            $sql .= " GROUP BY payments.reg_no ORDER BY last_payment_date DESC";
                            $result = mysqli_query($dbcon, $sql);
                            if ($result && mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $sn++;
                                    echo "<tr>";
                                    echo "<td>{$sn}</td>";
                                    echo "<td>" . htmlspecialchars($row['reg_no']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['fname'] . ' ' . $row['sname']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['course_name']) . "</td>";
                                    echo "<td><span class='fw-bold text-success'>Kshs " . number_format($row['total_paid'], 2) . "</span></td>";
                                    echo "<td><span class='text-secondary'>" . htmlspecialchars($row['last_payment_date']) . "</span></td>";
                                    echo "<td>";
                                    if ($row['last_status'] == 'approved') {
                                        echo "<span class='badge bg-success'>Approved</span>";
                                    } elseif ($row['last_status'] == 'pending') {
                                        echo "<span class='badge bg-warning text-dark'>Pending</span>";
                                    } else {
                                        echo "<span class='badge bg-danger'>Rejected</span>";
                                    }
                                    echo "</td>";
                                    echo "<td>
                                        <a href='payment_details.php?reg_no=" . urlencode($row['reg_no']) . "' class='btn btn-info btn-sm'>
                                            <i class='bi bi-eye'></i> View
                                        </a>
                                      </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8' class='text-center text-muted'>No payment records found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <footer class="text-center py-3 mt-4 bg-white border-top">
        <small>&copy; <?php echo date('Y'); ?> School Management System. All rights reserved.</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        <?php
        // Monthly collections data for chart
        $months = [];
        $collections = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $months[] = date('M Y', strtotime("-$i months"));
            $q = mysqli_query($dbcon, "SELECT SUM(amount_paid) as total FROM payments WHERE payStatus='approved' AND DATE_FORMAT(date, '%Y-%m')='$month'");
            $row = mysqli_fetch_assoc($q);
            $collections[] = (float)($row['total'] ?? 0);
        }
        ?>
        const ctx1 = document.getElementById('collectionsChart').getContext('2d');
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($months); ?>,
                datasets: [{
                    label: 'Collections (Kshs)',
                    data: <?php echo json_encode($collections); ?>,
                    backgroundColor: 'rgba(25, 135, 84, 0.7)'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Payment mode breakdown
        <?php
        $modes = ['Mpesa', 'Bank', 'Cash'];
        $modeCounts = [];
        foreach ($modes as $mode) {
            $q = mysqli_query($dbcon, "SELECT SUM(amount_paid) as total FROM payments WHERE payStatus='approved' AND mode_payment='$mode'");
            $row = mysqli_fetch_assoc($q);
            $modeCounts[] = (float)($row['total'] ?? 0);
        }
        ?>
        const ctx2 = document.getElementById('modeChart').getContext('2d');
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($modes); ?>,
                datasets: [{
                    data: <?php echo json_encode($modeCounts); ?>,
                    backgroundColor: [
                        'rgba(13, 138, 188, 0.7)',
                        'rgba(255, 193, 7, 0.7)',
                        'rgba(40, 167, 69, 0.7)'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</body>

</html>