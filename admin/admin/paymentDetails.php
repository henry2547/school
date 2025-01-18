<?php
//require_once('session_login.php');
include('dbconnect.php');
include('header.php');

$reg_no = $_GET['reg_no'];

?>


<br />
<div class="container-fluid">
    <?php include('menubar.php'); ?>


    <div class="col-md-1"></div>



    <div class="col-md-8">
        <a href="#" onClick="window_print()" class="btn btn-info" style="margin-bottom:20px"><i class="icon-print icon-large"></i> Print</a>

        </script>

        <div class="panel panel-success" id="outprint">

            <div class="panel panel-success">

                <div class="panel-heading">
                    <h3 class="panel-title">Student Details</h3>
                </div>

                <div class="panel-body">
                    <br />

                    <div class="panel panel-success">
                        <div class="panel-heading">

                            <h3 class="panel-title"> <span class="glyphicon glyphicon-user" aria-hidden="true"></span> Student information</h3>
                        </div>

                        <div class="panel-body">
                            <?php
                            $query = mysqli_query($dbcon, "SELECT DISTINCT student.*, courses.*
								FROM student
								JOIN courses ON student.courseId = courses.courseId
								WHERE student.studentStatus = 'approved'
                                AND student.reg_no = '$reg_no'
                                GROUP BY student.reg_no");

                            while ($row = mysqli_fetch_array($query)) {
                            ?>
                                <table class="table">
                                    <tr>
                                        <td width="160px">Regno:</td>
                                        <td> <input type="text" value="<?php echo $row['reg_no'] ?>" readonly=""> </td>
                                    </tr>
                                    <tr>
                                        <td>Firstname:</td>
                                        <td><?php echo $row['fname'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>Secondname:</td>
                                        <td><?php echo $row['sname'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>Email address:</td>
                                        <td><?php echo $row['email'] ?></td>
                                    </tr>

                                    <tr>
                                        <td>Phone number:</td>
                                        <td><?php echo $row['phone'] ?></td>
                                    </tr>

                                    <tr>
                                        <td>Registration Date:</td>
                                        <td><?php echo $row['regDate'] ?></td>
                                    </tr>

                                    <tr>
                                        <td>County:</td>
                                        <td><?php echo $row['county'] ?></td>

                                    </tr>
                                    <tr>
                                        <td>Subcounty:</td>
                                        <td><?php echo $row['subcounty'] ?></td>

                                    </tr>

                                    <hr>

                                    <tr>
                                        <td><b>Taking Course:</b></td>
                                        <td><b><?php echo $row['course_name'] ?></b></td>

                                    </tr>


                                    <tr>
                                        <td><b>Course fee:</b></td>
                                        <td><b>Kshs <?php echo number_format($row['fee'], 2) ?></b></td>

                                    </tr>



                                <?php
                            }
                                ?>

                                </table>

                        </div>

                    </div>




                </div>

                <!-- different invoices -->

                <div id="trans-table">
                    <table class="table table-bordered table-hover" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Payment details</th>
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // The serial number variable
                            $sn = 0;
                            $amount_paid = 0; // Initialize total amount paid
                            $total_fee = 0; // Initialize total fee
                            $query = mysqli_query($dbcon, "SELECT student.*, payments.*, courses.*
                                            FROM student
                                            JOIN payments ON student.reg_no = payments.reg_no
                                            JOIN courses ON student.courseId = courses.courseId
                                            WHERE student.reg_no = '$reg_no'");

                            // Fetch all rows and calculate totals
                            while ($row = mysqli_fetch_array($query)) {
                                $sn++;

                                // Update amounts
                                $current_amount_paid = (int)$row['amount_paid'];
                                $amount_paid += $current_amount_paid; // Cumulative total amount paid
                                $fee = (int)$row['fee'];
                                $total_fee = $fee; // Assuming one fee per course
                                $balance = $total_fee - $amount_paid;

                                // Convert the data into two decimal places
                                $convert_fee = number_format($total_fee, 2);
                                $convert_current_amount_paid = number_format($current_amount_paid, 2);
                                $convert_amount_paid = number_format($amount_paid, 2);
                                $convert_balance = number_format($balance, 2);

                                //other payment details
                                $mode_payment = $row['mode_payment'];
                                $code = $row['code'];
                                $payment_date = $row['date'];
                            ?>
                                <tr>
                                    <td><?php echo $sn; ?></td>
                                   <td>
                                        <p><b>Transaction code: </b><?php echo $code ?></p>
                                        <p><b>Mode of payment: </b><?php echo $mode_payment ?></p>
                                        <p><b>Amount paid: </b> Kshs <?php echo htmlspecialchars($convert_current_amount_paid); ?></p>
                                        <p><b>Date of payment: </b><?php echo $payment_date ?></p>
                                    </td>
                                    <td>Kshs <?php echo htmlspecialchars($convert_balance); ?></td>
                                </tr>
                            <?php } ?>

                            <!-- Total Row -->
                            <tr>
                                
                                <td colspan="2"> <strong>Total Amount Paid:</strong> Kshs <?php echo htmlspecialchars(number_format($amount_paid, 2)); ?></td>
                                <td><strong>Balance Kshs:</strong> <?php echo htmlspecialchars(number_format($total_fee - $amount_paid, 2)); ?></td> <!-- Remaining Balance -->
                            </tr>
                        </tbody>
                    </table>
                </div>





            </div>


        </div>

        <center>
            <a href="viewFinance.php" class="btn btn-success">Return Home
                <span class="glyphicon glyphicon-backward" aria-hidden="true"></span>
            </a>
        </center>



    </div>


    <?php include('scripts.php'); ?>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#myTable-trans').DataTable();
        });
    </script>


    <script type="text/javascript">
        function window_print() {
            var _head = $('head').clone();
            var _p = $('#outprint').clone();
            var _html = $('<div>')
            _html.append("<head>" + _head.html() + "</head>")
            _html.append("<h3 class='text-center'>SCHOOL MANAGEMENT SYSTEM</h3>")
            _html.append(_p)
            console.log(_html.html())
            var nw = window.open("", "_blank", "width:900;height:800")
            nw.document.write(_html.html())
            nw.document.close()
            nw.print()
            setTimeout(() => {
                nw.close()
            }, 500);
        }
    </script>

    </body>

    </html>