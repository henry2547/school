<?php
require("../dbconnect.php");
include("session.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Finance Statement</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .dashboard-icon { font-size: 2rem; }
        .table thead th { vertical-align: middle; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm mb-4">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="bi bi-mortarboard"></i> Student Portal
            </a>
            <div class="d-flex align-items-center ms-auto">
                <a href="profile.php" class="btn btn-outline-light btn-sm me-2" title="Profile">
                    <i class="bi bi-person"></i>
                </a>
                <a href="../logout.php" class="btn btn-outline-light btn-sm" title="Logout">
                    <i class="bi bi-box-arrow-right"></i>
                </a>
            </div>
        </div>
    </nav>

    <div class="container pb-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow mb-4">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-receipt"></i> Finance Statement</h5>
                        <a href="index.php" class="btn btn-outline-light btn-sm">
                            <i class="bi bi-arrow-left"></i> Dashboard
                        </a>
                    </div>
                    <div class="card-body">
                        <?php
                        // Fetch student and course info
                        $q = mysqli_query($dbcon, "SELECT student.*, courses.* 
                            FROM student
                            JOIN courses ON student.courseId = courses.courseId
                            WHERE student.reg_no = '$session_id' AND student.studentStatus = 'approved'");
                        if ($row = mysqli_fetch_assoc($q)) {
                        ?>
                        <div class="mb-4">
                            <h6 class="mb-1"><i class="bi bi-person"></i> <?php echo htmlspecialchars($row['fname'] . ' ' . $row['sname']); ?></h6>
                            <div><i class="bi bi-person-badge"></i> Reg No: <b><?php echo htmlspecialchars($row['reg_no']); ?></b></div>
                            <div><i class="bi bi-book"></i> Course: <b><?php echo htmlspecialchars($row['course_name']); ?></b></div>
                            <div><i class="bi bi-cash-stack"></i> Total Fee: <b>Kshs <?php echo number_format($row['fee'], 2); ?></b></div>
                        </div>
                        <?php } ?>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-success">
                                    <tr>
                                        <th>S/N</th>
                                        <th>Transaction Code</th>
                                        <th>Amount Paid</th>
                                        <th>Mode</th>
                                        <th>Date</th>
                                        <th>Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sn = 0;
                                    $amount_paid = 0;
                                    $total_fee = isset($row['fee']) ? (int)$row['fee'] : 0;
                                    $payments = mysqli_query($dbcon, "SELECT * FROM payments WHERE reg_no = '$session_id' AND payStatus = 'approved' ORDER BY date ASC");
                                    if ($payments && mysqli_num_rows($payments) > 0) {
                                        while ($pay = mysqli_fetch_assoc($payments)) {
                                            $sn++;
                                            $current_paid = (int)$pay['amount_paid'];
                                            $amount_paid += $current_paid;
                                            $balance = $total_fee - $amount_paid;
                                            echo '<tr>';
                                            echo '<td>' . $sn . '</td>';
                                            echo '<td><span class="badge bg-primary"><i class="bi bi-hash"></i> ' . htmlspecialchars($pay['code']) . '</span></td>';
                                            echo '<td><span class="fw-bold text-success">Kshs ' . number_format($current_paid, 2) . '</span></td>';
                                            echo '<td><span class="badge bg-info text-dark"><i class="bi bi-credit-card"></i> ' . htmlspecialchars($pay['mode_payment']) . '</span></td>';
                                            echo '<td><span class="text-secondary"><i class="bi bi-calendar-event"></i> ' . htmlspecialchars($pay['date']) . '</span></td>';
                                            echo '<td><span class="fw-bold ' . ($balance > 0 ? 'text-danger' : 'text-success') . '">Kshs ' . number_format($balance, 2) . '</span></td>';
                                            echo '</tr>';
                                        }
                                        echo '<tr class="table-warning">';
                                        echo '<td colspan="2" class="fw-bold text-end">Total Paid:</td>';
                                        echo '<td class="fw-bold text-success">Kshs ' . number_format($amount_paid, 2) . '</td>';
                                        echo '<td colspan="2" class="fw-bold text-end">Balance:</td>';
                                        echo '<td class="fw-bold ' . (($total_fee - $amount_paid) > 0 ? 'text-danger' : 'text-success') . '">Kshs ' . number_format($total_fee - $amount_paid, 2) . '</td>';
                                        echo '</tr>';
                                    } else {
                                        echo '<tr><td colspan="6" class="text-center text-muted">No payment records found.</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-end mt-3">
                            <button onclick="window_print()" class="btn btn-info">
                                <i class="bi bi-printer"></i> Print Statement
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="text-center py-3 mt-4 bg-white border-top">
        <small>&copy; <?php echo date('Y'); ?> School Management System. All rights reserved.</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
    function window_print() {
        var _head = $('head').clone();
        var _p = $('.card').clone();
        var _html = $('<div>');
        _html.append("<head>" + _head.html() + "</head>");
        _html.append("<h3 class='text-center'>SCHOOL MANAGEMENT SYSTEM</h3>");
        _html.append(_p);
        var nw = window.open("", "_blank", "width=900,height=800");
        nw.document.write(_html.html());
        nw.document.close();
        nw.print();
        setTimeout(() => { nw.close(); }, 500);
    }
    </script>
</body>
</html>