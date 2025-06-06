<?php
include('session.php');
include('dbconnect.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reports & Analytics</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>
<body>
    <div class="container mt-4">
        <h3 class="mb-4"><i class="bi bi-bar-chart-line"></i> Reports & Analytics</h3>
        <div class="row g-4 mb-4">
            <!-- Total Students -->
            <div class="col-12 col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-people-fill display-5 text-primary"></i>
                        <h6 class="mt-2">Total Students</h6>
                        <h4>
                            <?php
                            $res = mysqli_query($dbcon, "SELECT COUNT(*) as total FROM student");
                            $row = mysqli_fetch_assoc($res);
                            echo $row['total'];
                            ?>
                        </h4>
                    </div>
                </div>
            </div>
            <!-- Total Lecturers -->
            <div class="col-12 col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-person-lines-fill display-5 text-secondary"></i>
                        <h6 class="mt-2">Total Lecturers</h6>
                        <h4>
                            <?php
                            $res = mysqli_query($dbcon, "SELECT COUNT(*) as total FROM lecturer");
                            $row = mysqli_fetch_assoc($res);
                            echo $row['total'];
                            ?>
                        </h4>
                    </div>
                </div>
            </div>
            <!-- Total Courses -->
            <div class="col-12 col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-book display-5 text-info"></i>
                        <h6 class="mt-2">Total Courses</h6>
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
            <!-- Total Fees Collected -->
            <div class="col-12 col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-cash-stack display-5 text-success"></i>
                        <h6 class="mt-2">Total Fees Collected</h6>
                        <h4>
                            <?php
                            $res = mysqli_query($dbcon, "SELECT SUM(amount_paid) as total FROM payments WHERE payStatus='approved'");
                            $row = mysqli_fetch_assoc($res);
                            echo "Kshs " . number_format($row['total'] ?? 0, 2);
                            ?>
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytics & Charts -->
        <div class="row mb-4">
            <div class="col-12 col-lg-6 mb-3">
                <div class="card shadow">
                    <div class="card-header bg-info text-white">
                        <i class="bi bi-bar-chart"></i> Monthly Student Registrations
                    </div>
                    <div class="card-body">
                        <canvas id="studentsChart" height="60"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6 mb-3">
                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <i class="bi bi-bar-chart"></i> Monthly Fees Collection
                    </div>
                    <div class="card-body">
                        <canvas id="feesChart" height="60"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Download Reports -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-secondary text-white">
                <i class="bi bi-download"></i> Download Reports
            </div>
            <div class="card-body">
                <a href="../finance/finance/export_report.php?type=all_payments" class="btn btn-outline-success btn-sm mb-2">
                    <i class="bi bi-download"></i> All Payments (CSV)
                </a>
                <a href="../finance/finance/export_report.php?type=defaulters" class="btn btn-outline-danger btn-sm mb-2">
                    <i class="bi bi-download"></i> Fee Defaulters (CSV)
                </a>
                <a href="../finance/finance/export_report.php?type=summary" class="btn btn-outline-primary btn-sm mb-2">
                    <i class="bi bi-download"></i> Summary (CSV)
                </a>
            </div>
        </div>
    </div>


    <center>
        <a href="index.php" class="btn btn-primary">
        <i class="bi bi-house"></i> Back to Dashboard
    </a>
    </center>

    
    <footer class="text-center mt-4">
        <p>&copy; <?php echo date('Y'); ?> All rights reserved.</p>
    </footer>
    <script>
    <?php
    // Monthly student registrations
    $months = [];
    $studentCounts = [];
    $feeMonths = [];
    $feeCollections = [];
    for ($i = 11; $i >= 0; $i--) {
        $month = date('Y-m', strtotime("-$i months"));
        $months[] = date('M Y', strtotime("-$i months"));
        // Students
        $q = mysqli_query($dbcon, "SELECT COUNT(*) as total FROM student WHERE DATE_FORMAT(regDate, '%Y-%m')='$month'");
        $row = mysqli_fetch_assoc($q);
        $studentCounts[] = (int)($row['total'] ?? 0);
        // Fees
        $q2 = mysqli_query($dbcon, "SELECT SUM(amount_paid) as total FROM payments WHERE payStatus='approved' AND DATE_FORMAT(date, '%Y-%m')='$month'");
        $row2 = mysqli_fetch_assoc($q2);
        $feeCollections[] = (float)($row2['total'] ?? 0);
    }
    ?>
    // Students Chart
    const ctx1 = document.getElementById('studentsChart').getContext('2d');
    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($months); ?>,
            datasets: [{
                label: 'Registrations',
                data: <?php echo json_encode($studentCounts); ?>,
                backgroundColor: 'rgba(13, 110, 253, 0.7)'
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } }
        }
    });
    // Fees Chart
    const ctx2 = document.getElementById('feesChart').getContext('2d');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($months); ?>,
            datasets: [{
                label: 'Fees Collected (Kshs)',
                data: <?php echo json_encode($feeCollections); ?>,
                backgroundColor: 'rgba(25, 135, 84, 0.7)'
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } }
        }
    });
    </script>
</body>
</html>