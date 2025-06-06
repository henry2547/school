<?php
include("dbconnect.php");

$reg_no = isset($_GET['reg_no']) ? mysqli_real_escape_string($dbcon, $_GET['reg_no']) : '';

if (!$reg_no) {
    echo "<div class='alert alert-danger m-4'>No student selected.</div>";
    exit();
}

// Fetch student and course info
$studentQ = mysqli_query($dbcon, "SELECT student.*, courses.course_name, courses.fee 
    FROM student 
    JOIN courses ON student.courseId = courses.courseId 
    WHERE student.reg_no = '$reg_no'");
$student = mysqli_fetch_assoc($studentQ);

if (!$student) {
    echo "<div class='alert alert-danger m-4'>Student not found.</div>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Payment Details - <?php echo htmlspecialchars($reg_no); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="index.php" class="btn btn-success">
                <i class="bi bi-arrow-left"></i> Back to Panel
            </a>
            <div>
                <button onclick="window.print()" class="btn btn-info me-2">
                    <i class="bi bi-printer"></i> Print
                </button>
                <form method="post" action="" class="d-inline">
                    <button type="submit" name="approve_all" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Approve All Payments
                    </button>
                </form>
            </div>
        </div>

        <?php
            // Approve all payments for this student if requested
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['approve_all'])) {
                $update = mysqli_query($dbcon, "UPDATE payments SET payStatus='approved' WHERE reg_no='$reg_no' AND payStatus!='approved'");
                echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                let alert = document.createElement('div');
                alert.className = 'alert alert-success mt-3';
                alert.innerHTML = '<i class=\"bi bi-check-circle\"></i> All payments approved successfully!';
                document.querySelector('.card').prepend(alert);
                setTimeout(() => { alert.remove(); }, 3000);
            });
            </script>";
                // Refresh payment data after approval
                $payments = mysqli_query($dbcon, "SELECT * FROM payments WHERE reg_no = '$reg_no' ORDER BY date ASC");
            }
        ?>
        <div class="card shadow mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-person-lines-fill"></i> Student Payment Details</h5>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h6 class="mb-1"><i class="bi bi-person"></i> <?php echo htmlspecialchars($student['fname'] . ' ' . $student['sname']); ?></h6>
                    <div><i class="bi bi-person-badge"></i> Reg No: <b><?php echo htmlspecialchars($student['reg_no']); ?></b></div>
                    <div><i class="bi bi-book"></i> Course: <b><?php echo htmlspecialchars($student['course_name']); ?></b></div>
                    <div><i class="bi bi-cash-stack"></i> Total Fee: <b>Kshs <?php echo number_format($student['fee'], 2); ?></b></div>
                    <div><i class="bi bi-envelope"></i> Email: <b><?php echo htmlspecialchars($student['email']); ?></b></div>
                    <div><i class="bi bi-telephone"></i> Phone: <b><?php echo htmlspecialchars($student['phone']); ?></b></div>
                    <div><i class="bi bi-geo-alt"></i> County: <b><?php echo htmlspecialchars($student['county']); ?></b></div>
                    <div><i class="bi bi-geo"></i> Sub-county: <b><?php echo htmlspecialchars($student['subcounty']); ?></b></div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle shadow-sm">
                        <thead class="table-success">
                            <tr>
                                <th>#</th>
                                <th>Transaction Code</th>
                                <th>Amount Paid</th>
                                <th>Mode</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sn = 0;
                            $amount_paid = 0;
                            $total_fee = (int)$student['fee'];
                            $payments = mysqli_query($dbcon, "SELECT * FROM payments WHERE reg_no = '$reg_no' ORDER BY date ASC");
                            if ($payments && mysqli_num_rows($payments) > 0) {
                                while ($pay = mysqli_fetch_assoc($payments)) {
                                    $sn++;
                                    $current_paid = (int)$pay['amount_paid'];
                                    $amount_paid += $current_paid;
                                    $balance = $total_fee - $amount_paid;
                                    echo "<tr>";
                                    echo "<td>{$sn}</td>";
                                    echo "<td><span class='badge bg-primary'><i class='bi bi-hash'></i> " . htmlspecialchars($pay['code']) . "</span></td>";
                                    echo "<td><span class='fw-bold text-success'>Kshs " . number_format($current_paid, 2) . "</span></td>";
                                    echo "<td><span class='badge bg-info text-dark'><i class='bi bi-credit-card'></i> " . htmlspecialchars($pay['mode_payment']) . "</span></td>";
                                    echo "<td><span class='text-secondary'><i class='bi bi-calendar-event'></i> " . htmlspecialchars($pay['date']) . "</span></td>";
                                    echo "<td>";
                                    if ($pay['payStatus'] == 'approved') {
                                        echo "<span class='badge bg-success'>Approved</span>";
                                    } elseif ($pay['payStatus'] == 'pending') {
                                        echo "<span class='badge bg-warning text-dark'>Pending</span>";
                                    } else {
                                        echo "<span class='badge bg-danger'>Rejected</span>";
                                    }
                                    echo "</td>";
                                    echo "<td><span class='fw-bold " . ($balance > 0 ? 'text-danger' : 'text-success') . "'>Kshs " . number_format($balance, 2) . "</span></td>";
                                    echo "</tr>";
                                }
                                echo "<tr class='table-warning'>";
                                echo "<td colspan='2' class='fw-bold text-end'>Total Paid:</td>";
                                echo "<td class='fw-bold text-success'>Kshs " . number_format($amount_paid, 2) . "</td>";
                                echo "<td colspan='3' class='fw-bold text-end'>Balance:</td>";
                                echo "<td class='fw-bold " . (($total_fee - $amount_paid) > 0 ? 'text-danger' : 'text-success') . "'>Kshs " . number_format($total_fee - $amount_paid, 2) . "</td>";
                                echo "</tr>";
                            } else {
                                echo "<tr><td colspan='7' class='text-center text-muted'>No payment records found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>