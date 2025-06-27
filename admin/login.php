<?php
session_start();
include('header.php');
include('dbconnect.php');
if (isset($_SESSION['staffid'])) {
    if ($_SESSION['role'] == 'Admin') {
        header("Location: admin/");
    }
}
?>


<div class="container min-vh-100 d-flex align-items-center justify-content-center">
    <div class="col-12 col-sm-8 col-md-6 col-lg-5">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-success text-white text-center rounded-top">
                <h4 class="mb-0">
                    <i class="bi bi-sign-in-alt me-2"></i>Please Login Here
                </h4>
            </div>
            <div class="card-body p-4">
                <form action="logincheck.php" method="post" id="login-form" autocomplete="off">
                    <div class="mb-3">
                        <label for="un" class="form-label">
                            <i class="bi bi-person-badge me-1 text-success"></i>Admin Number
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                            <input type="text" class="form-control" name="username" id="un" placeholder="Enter ID number" required autofocus>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="pwd" class="form-label">
                            <i class="bi bi-key me-1 text-success"></i>Password
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                            <input type="password" class="form-control" name="pwd" id="pwd" placeholder="Enter password" required>
                        </div>
                    </div>
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger text-center">
                            <?php
                            echo $_SESSION['error'];
                            unset($_SESSION['error']);
                            ?>
                        </div>
                    <?php endif; ?>
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" name="login" class="btn btn-success btn-lg">
                            <i class="bi bi-sign-in-alt me-1"></i>Login
                        </button>
                    </div>
                </form>
            </div>
            
        </div>
        <!-- Loading overlay -->
        <div class="loading-overlay" id="loading-overlay" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(255,255,255,0.7);z-index:9999;align-items:center;justify-content:center;">
            <div class="spinner-border text-success" role="status" style="width:3rem;height:3rem;">
                <span class="visually-hidden"></span>
            </div>
        </div>
    </div>
</div>

<script>
    // Show loading overlay on form submit
    document.getElementById('login-form').addEventListener('submit', function() {
        document.getElementById('loading-overlay').style.display = 'flex';
    });
</script>
</body>

</html>