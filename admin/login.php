<?php
session_start(); 
include('header.php');
include('dbconnect.php');
if(isset($_SESSION['staffid'])){
    if($_SESSION['role']=='Admin'){
        header("Location: admin/");
    }
}
?>

<div class="container min-vh-100 d-flex align-items-center justify-content-center">
    <div class="col-12 col-sm-8 col-md-6 col-lg-5">
        <div class="card shadow">
            <div class="card-header bg-success text-white text-center">
                <h4 class="mb-0">Please Login Here</h4>
            </div>
            <div class="card-body">
                <form action="logincheck.php" method="post" id="login-form" autocomplete="off">
                    <div class="form-group">
                        <label for="un">Admin Number</label>
                        <input type="text" class="form-control" name="username" id="un" placeholder="Enter ID number" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="pwd">Password</label>
                        <input type="password" class="form-control" name="pwd" id="pwd" placeholder="Enter password" required>
                    </div>
                    <?php if(isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger text-center">
                            <?php 
                                echo $_SESSION['error']; 
                                unset($_SESSION['error']);
                            ?>
                        </div>
                    <?php endif; ?>
                    <button type="submit" name="login" class="btn btn-success btn-block mt-3">
                        <span class="glyphicon glyphicon-check"></span> Login
                    </button>
                </form>
            </div>
        </div>
        <!-- Loading overlay -->
        <div class="loading-overlay" id="loading-overlay" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(255,255,255,0.7);z-index:9999;align-items:center;justify-content:center;">
            <div class="spinner-border text-success" role="status" style="width:3rem;height:3rem;">
                <span class="sr-only"></span>
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