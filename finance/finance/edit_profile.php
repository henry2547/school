<?php
include('session.php');
include('dbconnect.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Profile</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .profile-panel {
      margin-top: 40px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
      border-radius: 10px;
      background: #fff;
      padding: 2rem;
    }
    .form-label i {
      margin-right: 8px;
      color: #0d6efd;
    }
    .home-btn {
      position: absolute;
      top: 20px;
      right: 40px;
    }
  </style>
</head>
<body>
  <div class="container">
    <a href="index.php" class="btn btn-outline-primary home-btn">
      <i class="fa fa-home"></i> Home
    </a>
    <div class="profile-panel mx-auto col-lg-7 col-md-9 col-12">
      <div class="mb-4 text-center">
        <h3>
          <i class="fa fa-user-edit"></i> Edit Profile
        </h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb justify-content-center">
            <li class="breadcrumb-item"><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Profile</li>
          </ol>
        </nav>
      </div>
      <?php
        $query = mysqli_query($dbcon, "SELECT * FROM userlogin WHERE staffid = '$session_id'") or die(mysqli_error($dbcon));
        $row = mysqli_fetch_array($query);
      ?>
      <form id="editProfileForm" method="post" action="update_profile.php" onsubmit="return validatePasswords()">
        <div class="mb-3">
          <label class="form-label"><i class="fa fa-id-badge"></i> Staff ID</label>
          <input type="text" class="form-control" readonly value="<?php echo $row['staffid'];?>" name="staffid">
        </div>
        <div class="mb-3">
          <label class="form-label"><i class="fa fa-user"></i> First name</label>
          <input type="text" class="form-control" value="<?php echo $row['surname'];?>" name="fname" required>
        </div>
        <div class="mb-3">
          <label class="form-label"><i class="fa fa-user"></i> Second name</label>
          <input type="text" class="form-control" value="<?php echo $row['othernames'];?>" name="sname" required>
        </div>
        <div class="mb-3">
          <label class="form-label"><i class="fa fa-key"></i> New Password</label>
          <input type="password" class="form-control" id="password" name="password" placeholder="Type new password">
        </div>
        <div class="mb-3">
          <label class="form-label"><i class="fa fa-key"></i> Confirm New Password</label>
          <input type="password" class="form-control" id="cfmPassword" name="confirm_password" placeholder="Reenter new password">
        </div>
        <hr>
        <div class="mb-3">
          <label class="form-label"><i class="fa fa-lock"></i> Enter Old Password to confirm changes</label>
          <input type="password" class="form-control" name="old_password" placeholder="Type old password" required>
        </div>
        <div class="d-flex justify-content-between">
          <button class="btn btn-primary" type="submit">
            <i class="fa fa-save"></i> Save Changes
          </button>
          <button class="btn btn-secondary" type="reset">
            <i class="fa fa-eraser"></i> Clear
          </button>
        </div>
      </form>
    </div>
  </div>
  <!-- Bootstrap 5 JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function validatePasswords() {
      var pass1 = document.getElementById("password").value;
      var pass2 = document.getElementById("cfmPassword").value;
      if (pass1 !== pass2) {
        alert("Passwords do not match");
        document.getElementById("password").style.borderColor = "#E34234";
        document.getElementById("cfmPassword").style.borderColor = "#E34234";
        return false;
      }
      return true;
    }
  </script>
</body>
</html>
<?php
// Close the database connection
mysqli_close($dbcon);