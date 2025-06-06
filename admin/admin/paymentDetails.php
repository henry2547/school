<?php
include('dbconnect.php');
include('header.php');

$reg_no = $_GET['reg_no'];
?>

<div class="container py-4">

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="viewFinance.php" class="btn btn-success">
                    <i class="bi bi-arrow-left"></i> Return Home
                </a>
                <button onclick="window_print()" class="btn btn-info">
                    <i class="bi bi-printer"></i> Print
                </button>
            </div>
            <div class="card shadow" id="outprint">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="bi bi-person-lines-fill"></i> Student Payment Details</h4>
                </div>
                <div class="card-body">
                    <!-- Student Info -->
                    <?php
                    $query = mysqli_query($dbcon, "SELECT DISTINCT student.*, courses.*
                        FROM student
                        JOIN courses ON student.courseId = courses.courseId
                        WHERE student.studentStatus = 'approved'
                        AND student.reg_no = '$reg_no'
                        GROUP BY student.reg_no");

                    if ($row = mysqli_fetch_array($query)) {
                    ?>
                        <table class="table table-bordered mb-4">
                            <tbody>
                                <tr>
                                    <th width="180">Reg No</th>
                                    <td><?php echo $row['reg_no'] ?></td>
                                </tr>
                                <tr>
                                    <th>Firstname</th>
                                    <td><?php echo $row['fname'] ?></td>
                                </tr>
                                <tr>
                                    <th>Secondname</th>
                                    <td><?php echo $row['sname'] ?></td>
                                </tr>
                                <tr>
                                    <th>Email address</th>
                                    <td><?php echo $row['email'] ?></td>
                                </tr>
                                <tr>
                                    <th>Phone number</th>
                                    <td><?php echo $row['phone'] ?></td>
                                </tr>
                                <tr>
                                    <th>Registration Date</th>
                                    <td><?php echo $row['regDate'] ?></td>
                                </tr>
                                <tr>
                                    <th>County</th>
                                    <td><?php echo $row['county'] ?></td>
                                </tr>
                                <tr>
                                    <th>Subcounty</th>
                                    <td><?php echo $row['subcounty'] ?></td>
                                </tr>
                                <tr>
                                    <th>Taking Course</th>
                                    <td><strong><?php echo $row['course_name'] ?></strong></td>
                                </tr>
                                <tr>
                                    <th>Course Fee</th>
                                    <td><strong>Kshs <?php echo number_format($row['fee'], 2) ?></strong></td>
                                </tr>
                            </tbody>
                        </table>
                    <?php } else {
                        echo '<div class="alert alert-warning">Student not found or not approved.</div>';
                    } ?>

                                        
                    <!-- Payment Transactions -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle shadow-sm">
                            <thead class="table-success">
                                <tr>
                                    <th>S/N</th>
                                    <th>Transaction code</th>
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
                                $total_fee = 0;
                                $query = mysqli_query($dbcon, "SELECT student.*, payments.*, courses.*
                                    FROM student
                                    JOIN payments ON student.reg_no = payments.reg_no
                                    JOIN courses ON student.courseId = courses.courseId
                                    WHERE student.reg_no = '$reg_no'
                                    ORDER BY payments.date ASC");
                                while ($row = mysqli_fetch_array($query)) {
                                    $sn++;
                                    $current_amount_paid = (int)$row['amount_paid'];
                                    $amount_paid += $current_amount_paid;
                                    $fee = (int)$row['fee'];
                                    $total_fee = $fee;
                                    $balance = $total_fee - $amount_paid;
                                    $convert_current_amount_paid = number_format($current_amount_paid, 2);
                                    $convert_balance = number_format($balance, 2);
                                    $mode_payment = $row['mode_payment'];
                                    $code = $row['code'];
                                    $payment_date = $row['date'];
                                ?>
                                    <tr>
                                        <td><?php echo $sn; ?></td>
                                        <td>
                                            <span class="badge bg-primary"><i class="bi bi-hash"></i> <?php echo $code ?></span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-success">Kshs <?php echo htmlspecialchars($convert_current_amount_paid); ?></span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info text-dark"><i class="bi bi-credit-card"></i> <?php echo $mode_payment ?></span>
                                        </td>
                                        <td>
                                            <span class="text-secondary"><i class="bi bi-calendar-event"></i> <?php echo $payment_date ?></span>
                                        </td>
                                        <td>
                                            <span class="fw-bold <?php echo ($balance > 0) ? 'text-danger' : 'text-success'; ?>">
                                                Kshs <?php echo htmlspecialchars($convert_balance); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <tr class="table-warning">
                                    <td colspan="2" class="fw-bold text-end">Total Paid:</td>
                                    <td class="fw-bold text-success">Kshs <?php echo htmlspecialchars(number_format($amount_paid, 2)); ?></td>
                                    <td colspan="2" class="fw-bold text-end">Balance:</td>
                                    <td class="fw-bold <?php echo (($total_fee - $amount_paid) > 0) ? 'text-danger' : 'text-success'; ?>">
                                        Kshs <?php echo htmlspecialchars(number_format($total_fee - $amount_paid, 2)); ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    

                </div>
            </div>
            <div class="text-center mt-3">
                <a href="viewFinance.php" class="btn btn-success">
                    <i class="bi bi-arrow-left"></i> Return Home
                </a>
            </div>
        </div>
    </div>
</div>

<?php include('scripts.php'); ?>

<script type="text/javascript">
function window_print() {
    var _head = $('head').clone();
    var _p = $('#outprint').clone();
    var _html = $('<div>');
    _html.append("<head>" + _head.html() + "</head>");
    _html.append("<h3 class='text-center'>SCHOOL MANAGEMENT SYSTEM</h3>");
    _html.append(_p);
    var nw = window.open("", "_blank", "width=900,height=800");
    nw.document.write(_html.html());
    nw.document.close();
    nw.print();
    setTimeout(() => {
        nw.close();
    }, 500);
}
</script>
</body>
</html>