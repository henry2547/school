<?php
require("dbconnect.php");

include("session.php");
include("style.php");
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
                <a href="profile.php">
                    <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210180014/profile-removebg-preview.png" class="dpicn" alt="dp">
                </a>
            </div>
        </div>

    </header>

    <div class="main-container">

       <!-- include the nav -->
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
                    <h1 class="recent-Articles">Recent Activities</h1>
                    <button class="view">View All</button>
                </div>

                


            </div>

        </div>

    </div>

    <script src="index.js"></script>
</body>

</html>