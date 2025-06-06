<?php
include('header.php');
include('dbconnect.php');
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <div class="position-fixed top-0 end-0 p-3" style="z-index: 1100">
                <div id="msgToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body" id="msgToastBody">
                            
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            </div>
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="bi bi-person-plus-fill"></i> Add Lecturer</h4>
                </div>
                <div class="card-body">
                    <form method="post" autocomplete="off" id="addLecturerForm">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="fname" class="form-label">Firstname</label>
                                <input type="text" name="fname" class="form-control" id="fname" required>
                            </div>
                            <div class="col-md-6">
                                <label for="sname" class="form-label">Secondname</label>
                                <input type="text" name="sname" class="form-control" id="sname" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" id="email" required>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="tel" name="phone" class="form-control" id="phone" maxlength="10" minlength="10" required>
                            </div>
                            <div class="col-md-6">
                                <label for="department" class="form-label">Department</label>
                                <select name="department" class="form-select" id="department" required>
                                    <option value="" selected disabled>Select Department</option>
                                    <?php
                                    $query = mysqli_query($dbcon, "SELECT * FROM department");
                                    while ($row = mysqli_fetch_assoc($query)) {
                                        echo "<option value='" . $row['deptId'] . "'>" . htmlspecialchars($row['deptName']) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="gender" class="form-label">Gender</label>
                                <select name="gender" class="form-select" id="gender" required>
                                    <option value="" selected disabled>Select</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Default Password</label>
                                <input type="password" class="form-control" value="123456" readonly>
                                <small class="text-muted"><i class="bi bi-info-circle"></i> Lecturer's default password is 123456</small>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-success w-100" id="saveBtn">
                                <span id="btnText"><i class="bi bi-save"></i> Add Lecturer</span>
                                <span id="btnSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <a href="index.php" class="btn btn-primary">
                        <i class="bi bi-house"></i> Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
$('#addLecturerForm').on('submit', function(e) {
    e.preventDefault();
    $('#saveBtn').attr('disabled', true);
    $('#btnText').addClass('d-none');
    $('#btnSpinner').removeClass('d-none');
    $.ajax({
        url: 'saveLecturer.php',
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response) {
            let toastEl = document.getElementById('msgToast');
            let toastBody = document.getElementById('msgToastBody');
            if(response.success) {
                toastEl.classList.remove('text-bg-danger');
                toastEl.classList.add('text-bg-success');
                toastBody.innerHTML = '<i class="bi bi-check-circle"></i> ' + response.success;
                $('#addLecturerForm')[0].reset();
            } else if(response.error) {
                toastEl.classList.remove('text-bg-success');
                toastEl.classList.add('text-bg-danger');
                toastBody.innerHTML = '<i class="bi bi-exclamation-triangle"></i> ' + response.error;
            }
            let toast = new bootstrap.Toast(toastEl);
            toast.show();
        },
        error: function() {
            let toastEl = document.getElementById('msgToast');
            let toastBody = document.getElementById('msgToastBody');
            toastEl.classList.remove('text-bg-success');
            toastEl.classList.add('text-bg-danger');
            toastBody.innerHTML = '<i class="bi bi-exclamation-triangle"></i> An error occurred. Please try again.';
            let toast = new bootstrap.Toast(toastEl);
            toast.show();
        },
        complete: function() {
            $('#saveBtn').attr('disabled', false);
            $('#btnText').removeClass('d-none');
            $('#btnSpinner').addClass('d-none');
        }
    });
});
</script>
</body>
</html>