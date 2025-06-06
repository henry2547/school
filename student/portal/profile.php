<?php
require("dbconnect.php");
include("session.php");

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
  <title>Student Profile</title>
  <!-- Bootstrap 5 & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: #f8f9fa;
    }

    .profile-card {
      max-width: 500px;
      margin: 2rem auto;
      border-radius: 1rem;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
    }

    .profile-avatar {
      width: 96px;
      height: 96px;
      border-radius: 50%;
      object-fit: cover;
      margin-top: -48px;
      border: 4px solid #fff;
      background: #e9ecef;
    }

    .edit-profile {
      min-width: 120px;
    }
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
        <a href="changepassword.php" class="btn btn-outline-light btn-sm me-2" title="Change Password">
          <i class="bi bi-key"></i>
        </a>
        <a href="../logout.php" class="btn btn-outline-light btn-sm" title="Logout">
          <i class="bi bi-box-arrow-right"></i>
        </a>
      </div>
    </div>
  </nav>

  <div class="container">
    <div class="card profile-card shadow-sm">
      <div class="card-body text-center">
        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($fname . ' ' . $sname); ?>&background=0D8ABC&color=fff&size=128" alt="Profile" class="profile-avatar shadow">
        <h3 class="mt-3 mb-0"><?php echo htmlspecialchars($fname . " " . $sname); ?></h3>
        <p class="text-muted mb-2"><?php echo htmlspecialchars($course_name); ?></p>
        <hr>
        <div class="row text-start mb-3">
          <div class="col-12 mb-2"><i class="bi bi-envelope"></i> <strong>Email:</strong> <?php echo htmlspecialchars($email); ?></div>
          <div class="col-12 mb-2"><i class="bi bi-telephone"></i> <strong>Phone:</strong> <?php echo htmlspecialchars($phone); ?></div>
          <div class="col-12 mb-2"><i class="bi bi-geo-alt"></i> <strong>County:</strong> <?php echo htmlspecialchars($county); ?></div>
          <div class="col-12 mb-2"><i class="bi bi-geo"></i> <strong>Sub-county:</strong> <?php echo htmlspecialchars($sub); ?></div>
          <div class="col-12 mb-2"><i class="bi bi-gender-ambiguous"></i> <strong>Gender:</strong> <?php echo htmlspecialchars($gender); ?></div>
        </div>

        <a href="#" class="btn btn-primary edit-profile" data-bs-toggle="modal" data-bs-target="#editProfileModal">
          <i class="bi bi-pencil-square"></i> Edit Profile
        </a>

        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1100">
          <div id="msgToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
              <div class="toast-body" id="msgToastBody"></div>
              <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
          </div>
        </div>

        <!-- Edit Profile Modal -->
        <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
          <div class="modal-dialog">

            <form class="modal-content" method="post" action="profile_update.php">
              <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editProfileModalLabel"><i class="bi bi-pencil-square"></i> Edit Profile</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <input type="hidden" name="reg_no" value="<?php echo htmlspecialchars($row['reg_no']); ?>">
                <div class="mb-3">
                  <label for="editFname" class="form-label">First Name</label>
                  <input type="text" class="form-control" id="editFname" name="fname" value="<?php echo htmlspecialchars($fname); ?>" required>
                </div>
                <div class="mb-3">
                  <label for="editSname" class="form-label">Second Name</label>
                  <input type="text" class="form-control" id="editSname" name="sname" value="<?php echo htmlspecialchars($sname); ?>" required>
                </div>
                <div class="mb-3">
                  <label for="editEmail" class="form-label">Email</label>
                  <input type="email" class="form-control" id="editEmail" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                </div>
                <div class="mb-3">
                  <label for="editPhone" class="form-label">Phone</label>
                  <input type="tel" class="form-control" id="editPhone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>
                </div>

                <div class="mb-3">
                  <label for="editGender" class="form-label">Gender</label>
                  <select class="form-select" id="editGender" name="gender" required>
                    <option value="male" <?php if ($gender == 'male') echo 'selected'; ?>>Male</option>
                    <option value="female" <?php if ($gender == 'female') echo 'selected'; ?>>Female</option>
                  </select>
                </div>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                  <i class="bi bi-x-circle"></i> Cancel
                </button>
                <button type="submit" class="btn btn-primary">
                  <i class="bi bi-save"></i> Save Changes
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="text-center mt-4">
      <a href="index.php" class="btn btn-outline-success">
        <i class="bi bi-arrow-left"></i> Back to Dashboard
      </a>
    </div>
  </div>

  <footer class="text-center py-3 mt-4 bg-white border-top">
    <small>&copy; <?php echo date('Y'); ?> School Management System. All rights reserved.</small>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.querySelector('#editProfileModal form').addEventListener('submit', function(e) {
      // Show progress toast
      let toastEl = document.getElementById('msgToast');
      let toastBody = document.getElementById('msgToastBody');
      toastEl.classList.remove('text-bg-danger');
      toastEl.classList.add('text-bg-info');
      toastBody.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Updating profile...';
      let toast = new bootstrap.Toast(toastEl);
      toast.show();
    });
  </script>

</body>

</html>