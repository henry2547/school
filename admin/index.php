<!DOCTYPE html>
<html lang="">

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
	
<div class="col-md-3"></div>
<div class="col-md-6">
	<div class="panel panel-success">
		<div class="panel-heading">
			<h3 class="panel-title">Please Login Here</h3>
		</div>
		<div class="panel-body">
			<form class="form-horizontal" action="logincheck.php" method="post" role="form" id="login-form" >
			  <div class="form-group">
			    <label class="control-label col-sm-2" for="un">Admin Number:</label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" name="username" id="un" placeholder="Enter id number" autofocus="" required="">
			    </div>
			  </div>
			  <div class="form-group">
			    <label class="control-label col-sm-2" for="pwd">Password:</label>
			    <div class="col-sm-10"> 
			      <input type="password" class="form-control" name="pwd" id="pwd" placeholder="Enter password" required="">
			    </div>
			  </div>
			 
			  <div class="form-group"> 
			    <div class="col-sm-offset-2 col-sm-10">
			      <button type="submit" name="login" class="btn btn-default">Login
			      <span class="glyphicon glyphicon-check" ></span>
			      </button>
			    </div>
			  </div>

			  	 <div class="form-group"> 
			    <div class="col-sm-offset-2 col-sm-10">
			     <?php
  		if(isset($_SESSION['error'])){
  			echo "
			
  				<span class='alert alert-danger text-center mt-10'>
			  		".$_SESSION['error']." 
			  	</span>
				
  			";
  			unset($_SESSION['error']);
  		}
  	?>
			    </div>
			  </div>


			</form>
		</div>

		<!-- Loading overlay with loading icon -->
		<div class="loading-overlay" id="loading-overlay">
			<div class="loading-icon"></div>
		</div>

	</div>
</div>
<div class="col-md-3">


 
  	</div>




<script type="text/javascript" src="../packages/assets/js/jquery-3.1.1.min.js"></script>
<script type="text/javascript" src="../packages/assets/js/bootstrap.min.js"></script>
<script>
    // Function to show loading overlay
    function showLoadingOverlay() {
        document.getElementById('loading-overlay').style.display = 'flex';
    }

    // Function to hide loading overlay
    function hideLoadingOverlay() {
        document.getElementById('loading-overlay').style.display = 'none';
    }

    // Attach event listener to form submit
    document.getElementById('login-form').addEventListener('submit', function() {
        // Show loading overlay when form is submitted
        showLoadingOverlay();
    });
</script>



</body>
</html>