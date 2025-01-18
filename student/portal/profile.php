<?php
require("dbconnect.php");

include("session.php");
include("style.php");



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
  $county = $row['county'];
  $sub = $row['subcounty'];
  $gender = $row['gender'];
}

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

    <?php include("nav.php") ?>

    <div class="main">

      <div class="container">
        <div class="profile">
          <h2>My account details</h2>

          <p><strong>Fullname: </strong><?php echo $fname . " " . $sname ?></p>
          <p><strong>Email:</strong> <?php echo $email ?></p>
          <p><strong>Phone: </strong><?php echo $phone ?></p>
          <p><strong>Course: </strong><?php echo $course_name ?></p>
          <p><strong>County: </strong><?php echo $county ?></p>
          <p><strong>Sub-county: </strong><?php echo $sub ?></p>
          <p><strong>Gender: </strong><?php echo $gender ?></p>


          <button type="submit" class="edit-profile">Edit</button>


        </div>
      </div>

    </div>

  </div>

  <script src="index.js"></script>
</body>

</html>