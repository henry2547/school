<?php
require("dbconnect.php");
include("session.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Student portal</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="responsive.css">



    <style>
        .pay,
        .receipt {
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            max-width: 600px;
        }

        input[type=text],
        input[type=number],
        select {
            width: 50%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
        }

        .print {
            background-color: #3d3d3d;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
        }

        input[type=submit] {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type=submit]:hover {
            background-color: #45a049;
        }

        input[type=text]:focus,
        input[type=number]:focus,
        select:focus {
            outline: none;
            border: 1px solid #4CAF50;
        }
    </style>

</head>

<body>

    <!-- for header part -->
    <header>

        <div class="logosec">
            <div class="logo"></div>
            <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210182541/Untitled-design-(30).png" class="icn menuicn" id="menuicn" alt="menu-icon">
            <h2>School management system</h2>
        </div>

        <div class="searchbar">
            <input type="text" placeholder="Search">
            <div class="searchbtn">
                <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210180758/Untitled-design-(28).png" class="icn srchicn" alt="search-icon">
            </div>
        </div>

        <div class="message">
            <div class="circle"></div>
            <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210183322/8.png" class="icn" alt="">
            <div class="dp">
                <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210180014/profile-removebg-preview.png" class="dpicn" alt="dp">
            </div>
        </div>

    </header>

    <div class="main-container">

        <?php include("nav.php") ?>

        <div class="main">

            <div class="box-container">

                <div class="box box2">
                    <div class="text">

                        <h2 class="topic">Total fee</h2>

                        <h2 class="topic-heading">
                            <img src="https://cdn-icons-png.freepik.com/256/2953/2953423.png?semt=ais_hybrid" alt="fee">
                            <?php
                            // Assuming $dbcon is your database connection variable
                            $select_sum_payment = "SELECT student.*, courses.* 
								FROM student
								JOIN courses ON student.courseId = courses.courseId
								WHERE student.studentStatus = 'approved'
                                AND student.reg_no = '$session_id'";

                            $result = mysqli_query($dbcon, $select_sum_payment);

                            if ($result) {
                                // Fetch the result as an associative array
                                $row = mysqli_fetch_assoc($result);

                                // Access the total_amount column from the result
                                $fee = number_format($row['fee'], 2);

                                // Output the total amount
                                echo "<h3>$fee</h3>";
                            } else {
                                // Query execution failed
                                echo "Error: " . mysqli_error($dbcon);
                            }
                            ?>
                        </h2>

                    </div>
                </div>

                <div class="box box3">
                    <div class="text">

                        <h2 class="topic">Amount paid</h2>

                        <h2 class="topic-heading">

                            <img src="https://cdn-icons-png.freepik.com/256/7377/7377382.png?semt=ais_hybrid" alt="amount paid">

                            <?php
                            // Assuming $dbcon is your database connection variable
                            $session_id = mysqli_real_escape_string($dbcon, $session_id); // Sanitize input to prevent SQL injection

                            // Define the query to fetch payment details
                            $query = "
                            SELECT payments.amount_paid
                            FROM payments
                            JOIN student ON payments.reg_no = student.reg_no
                            WHERE student.reg_no = '$session_id'";

                            // Execute the query
                            $result = mysqli_query($dbcon, $query);

                            if ($result) {
                                $total_amount_paid = 0; // Initialize total amount paid

                                // Fetch each row and accumulate the total amount
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $total_amount_paid += (int)$row['amount_paid'];
                                }
                                $formatTotal = number_format($total_amount_paid, 2);

                                // Output the total amount
                                echo "<h3>$formatTotal</h3>";
                            } else {
                                // Query execution failed
                                echo "Error: " . mysqli_error($dbcon);
                            }
                            ?>

                        </h2>

                    </div>


                </div>

                <div class="box box3">
                    <div class="text">

                        <h2 class="topic">Fee balance</h2>

                        <h2 class="topic-heading">
                            <img src="https://cdn-icons-png.freepik.com/256/2021/2021686.png?semt=ais_hybrid" alt="fee balance">

                            <?php
                            // Assuming $dbcon is your database connection variable
                            $session_id = mysqli_real_escape_string($dbcon, $session_id); // Sanitize input to prevent SQL injection

                            // Define the query to fetch payment details and fee
                            $query = "
                            SELECT payments.amount_paid, courses.fee
                            FROM payments
                            JOIN student ON payments.reg_no = student.reg_no
                            JOIN courses ON student.courseId = courses.courseId
                            WHERE student.reg_no = '$session_id'";

                            // Execute the query
                            $result = mysqli_query($dbcon, $query);

                            if ($result) {
                                $total_amount_paid = 0; // Initialize total amount paid
                                $fee = 0; // Initialize fee

                                // Fetch the data
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $total_amount_paid += (int)$row['amount_paid'];

                                    // Assuming fee should be taken from courses table, and it's constant for a student
                                    // You can set fee only once assuming it is constant for all rows.
                                    if ($fee === 0) {
                                        $fee = (int)$row['fee'];
                                    }
                                }

                                // Calculate the balance
                                $balance = $fee - $total_amount_paid;
                                $formatBalance = number_format($balance, 2);

                                // Output the balance
                                echo "<h3>$formatBalance</h3>";
                            } else {
                                // Query execution failed
                                echo "Error: " . mysqli_error($dbcon);
                            }
                            ?>



                        </h2>

                    </div>


                </div>



            </div>

            <div class="report-container">
                <div class="report-header">
                    <h1 class="recent-Articles">Make your paymet here</h1>


                </div>


                <div class="pay">

                    <form action="savePayment.php" method="post">
                        <label for="">Enter amount you paid:</label><br>
                        <input type="number" name="amount" required id="amount" oninput="calculateTotal()"><br>

                        <label for="">Select semester:</label><br>
                        <select name="semester" class="form-control">
                            <option disabled selected></option>
                            <?php
                            require_once "dbconnect.php";
                            $sql = "SELECT * FROM semester";
                            $result = $dbcon->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row['semesterId'] . "'>" . $row['sem_name'] . "</option>";
                                }
                            }
                            ?>
                        </select><br>
                        <!-- field for the total fee -->
                        <?php
                        // Assuming $dbcon is your database connection variable
                        $select_sum_payment = "SELECT student.*, courses.* 
								FROM student
								JOIN courses ON student.courseId = courses.courseId
								WHERE student.studentStatus = 'approved'
                                AND student.reg_no = '$session_id'";

                        $result = mysqli_query($dbcon, $select_sum_payment);

                        if ($result) {
                            // Fetch the result as an associative array
                            $row = mysqli_fetch_assoc($result);

                            // Access the total_amount column from the result
                            $fee = $row['fee'];
                        } else {
                            // Query execution failed
                            echo "Error: " . mysqli_error($dbcon);
                        }
                        ?>

                        <input type="hidden" name="fee" value="<?php echo $fee ?>" id="fee"><br>
                        <input type="hidden" name="reg_no" id="reg_no" value="<?php echo $session_id ?>">


                        <label for="code">Transaction code:</label><br>
                        <input type="text" name="code" required id="code" maxlength="10" minlength="10"><br>

                        <label for="mode">Mode of payment:</label><br>
                        <select name="mode" id="mode">
                            <option selected disabled></option>
                            <option value="Mpesa">Mpesa</option>
                            <option value="Bank">Bank</option>
                            <option value="Paypal">Paypal</option>
                        </select><br>

                        <label for="balance">Remaining fee balance:</label><br>
                        <input type="number" name="balance" readonly id="balance"><br>

                        <input type="submit" value="Make payment"><br>

                        <?php
                        if (isset($_SESSION['message'])) {
                            echo "<span> " . $_SESSION['message'] . " </span><br>";
                            unset($_SESSION['message']);
                        }
                        ?>

                    </form>

                </div>

                <a href="#" onClick="window_print()" class="print" style="margin-left: 230px;"><i></i> Print</a>

                <div id="outprint">

                    <div class="receipt">


                        <?php
                        $getStudent = "SELECT student.*, courses.course_name
                        FROM student
                        JOIN courses ON student.courseId = courses.courseId
                        WHERE student.reg_no = '$session_id'";

                        $resultStudent = mysqli_query($dbcon, $getStudent);

                        if ($resultStudent) {
                            $row = mysqli_fetch_assoc($resultStudent);

                            //store the student's information
                            $fname = $row['fname'];
                            $sname = $row['sname'];
                            $email = $row['email'];
                            $phone = $row['phone'];
                            $course_name = $row['course_name'];
                        }



                        // fetch the payment details
                        $fetchPayment = "SELECT * 
                        FROM `payments` 
                        WHERE `reg_no` LIKE '$session_id' 
                        AND `date` BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY);";

                        // Execute the query
                        $resulPayment = mysqli_query($dbcon, $fetchPayment);

                        if ($resulPayment) {
                            $total_balance = 0;
                            $total_amount_paid = 0;

                            $allCodes = [];
                            $modePayments = [];

                            // Fetch each row and sum the balances and amounts
                            while ($row = mysqli_fetch_assoc($resulPayment)) {
                                $total_balance += (float)$row['balance']; // Assuming balance is a numeric column
                                $total_amount_paid += (float)$row['amount_paid']; // Assuming amount_paid is a numeric column


                                //format the balance and amount
                                $formatAmount = number_format($total_amount_paid, 2);
                                $formattedBalance = number_format($total_balance, 2);

                                //calculate the balance
                                $henceBalance = $fee - $total_balance;
                                $formatBalance = number_format($henceBalance, 2);


                                // Collect codes and modes
                                $allCodes[] = $row['code'];
                                $modePayments[] = $row['mode_payment'];
                            }

                            // Join codes and modes into strings separated by new lines or commas
                            $allCodesString = implode(', ', $allCodes);
                            $modePaymentsString = implode(', ', $modePayments);
                        } else {
                            echo "Error fetching data: " . mysqli_error($dbcon);
                        }
                        ?>

                        <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210180014/profile-removebg-preview.png" height="150px" style="margin-left: 200px; margin-right: 200px;" alt="logo" srcset="">
                        <p style="text-align: center;">henrynjue255@gmail.com | +254742735159</p>
                        <h3 style="text-align: center; margin-top: 30px;">Student Payment Receipt</h3>
                        <p style="text-align: center; margin-top: 20px;"><strong>Fullname:</strong> <?php echo $fname . " " . $sname ?></p>
                        <p style="text-align: center;"><strong>Phone number:</strong> <?php echo $phone ?></p>
                        <p style="text-align: center;"><strong>Email:</strong> <?php echo $email ?></p>
                        <p style="text-align: center;"><strong>Course:</strong> <?php echo $course_name ?></p>


                        <p style="margin-top: 30px;"><strong>Amount Paid: Kshs</strong> <?php echo $formatAmount ?></p>
                        <p><strong>Transaction Code:</strong> <?php echo $allCodesString ?></p>
                        <p><strong>Mode of Payment:</strong> <?php echo $modePaymentsString ?></p>
                        <p><strong>Remaining Balance: Kshs</strong> <?php echo $formattedBalance ?></p>

                    </div>


                </div>



            </div>

        </div>

    </div>

    <script src="index.js"></script>
    <?php include('scripts.php') ?>

    <script>
        function calculateTotal() {
            var amountPaid = parseFloat(document.getElementById('amount').value) || 0;
            var fee = parseFloat(document.getElementById('fee').value) || 0;
            var remainingBalance = fee - amountPaid;
            document.getElementById('balance').value = remainingBalance.toFixed(2);
        }

        function validateForm() {
            var amountPaid = parseFloat(document.getElementById('amount').value) || 0;
            var fee = parseFloat(document.getElementById('fee').value) || 0;
            if (amountPaid > fee) {
                alert('Amount paid cannot be greater than the total fee.');
                return false;
            }
            return true;
        }

        function updateReceipt() {
            document.getElementById('receipt-amount').textContent = document.getElementById('amount').value;
            document.getElementById('receipt-code').textContent = document.getElementById('code').value;
            document.getElementById('receipt-mode').textContent = document.getElementById('mode').value;
            document.getElementById('receipt-balance').textContent = document.getElementById('balance').value;
        }

        function downloadReceipt() {
            updateReceipt();
            // Here, you might want to trigger a download or open a new page with receipt details
            alert('Receipt download functionality not implemented.');
        }
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