<?php
require("../../dbconnect.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Track inventory</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="responsive.css">
</head>

<body>

    <!-- for header part -->
    <header>

        <div class="logosec">
            <div class="logo"></div>
            <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210182541/Untitled-design-(30).png" class="icn menuicn" id="menuicn" alt="menu-icon">
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
        <div class="navcontainer">

            <nav class="nav">
                <div class="nav-upper-options">

                    <div class="nav-option option1">
                        <a href="../index.php">
                            <img src="https://cdn-icons-png.freepik.com/256/4517/4517933.png?semt=ais_hybrid" class="nav-img" alt="home">
                        </a>
                        <h3> Home</h3>
                    </div>

                    

                    <div class="nav-option option3">
                        <a href="view_products.php">
                            <img src="https://cdn-icons-png.freepik.com/256/4401/4401717.png?semt=ais_hybrid" class="nav-img" alt="products">
                        </a>
                        <h3>View Product</h3>
                    </div>

                    <div class="nav-option option4">
                        <a href="low_stock.php">
                            <img src="https://cdn-icons-png.freepik.com/256/8358/8358824.png?semt=ais_hybrid" class="nav-img" alt="category">
                        </a>
                        <h3>Low stock</h3>
                    </div>



                </div>
            </nav>

        </div>

        <div class="main">

            <div class="box-container">

                

                <div class="box box2">

                    <div class="text">

                        <h2 class="topic">Total Orders</h2>

                        <h2 class="topic-heading">
                            <?php
                            // Assuming $dbcon is your database connection variable
                            $select_sum_payment = "SELECT COUNT(*) AS total_cart FROM cart;";
                            $result = mysqli_query($dbcon, $select_sum_payment);

                            if ($result) {
                                // Fetch the result as an associative array
                                $row = mysqli_fetch_assoc($result);

                                // Access the total_amount column from the result
                                $total_cart = $row['total_cart'];

                                // Output the total amount
                                echo $total_cart;
                            } else {
                                // Query execution failed
                                echo "Error: " . mysqli_error($dbcon);
                            }
                            ?>
                        </h2>

                    </div>


                    <img src="https://cdn-icons-png.freepik.com/256/6052/6052680.png?semt=ais_hybrid" alt="Orders">
                </div>

                <div class="box box3">
                    <div class="text">

                        <h2 class="topic">All products</h2>

                        <h2 class="topic-heading">
                            <?php
                            // Assuming $dbcon is your database connection variable
                            $select_sum_payment = "SELECT COUNT(*) AS total_products FROM inventory;";
                            $result = mysqli_query($dbcon, $select_sum_payment);

                            if ($result) {
                                // Fetch the result as an associative array
                                $row = mysqli_fetch_assoc($result);

                                // Access the total_amount column from the result
                                $total_products = $row['total_products'];

                                // Output the total amount
                                echo $total_products;
                            } else {
                                // Query execution failed
                                echo "Error: " . mysqli_error($dbcon);
                            }
                            ?>
                        </h2>

                    </div>

                    <img src="https://cdn-icons-png.freepik.com/256/5365/5365428.png?semt=ais_hybrid" alt="All Products">
                </div>

                <div class="box box4">
                    <div class="text">

                        <h2 class="topic">Low stock</h2>

                        <h2 class="topic-heading">
                            <?php
                            // Assuming $dbcon is your database connection variable
                            $select_sum_payment = "SELECT COUNT(*) AS low_stock FROM inventory WHERE RemainingItems <= 5;";
                            $result = mysqli_query($dbcon, $select_sum_payment);

                            if ($result) {
                                // Fetch the result as an associative array
                                $row = mysqli_fetch_assoc($result);

                                // Access the total_amount column from the result
                                $low_stock = $row['low_stock'];

                                // Output the total amount
                                echo $low_stock;
                            } else {
                                // Query execution failed
                                echo "Error: " . mysqli_error($dbcon);
                            }
                            ?>
                        </h2>

                    </div>

                    <img src="https://cdn-icons-png.freepik.com/256/8358/8358824.png?semt=ais_hybrid" alt="Low stock">
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