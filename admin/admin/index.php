<?php

include('header.php');

?>




<div class="container-fluid" style="margin-top: 80px;">
	<?php include('menubar.php') ?>

	<div class="col-md-1"></div>
</div>

<!-- <div class="container-fluid">
	<div class="col-md-2"></div>
	<div class="col-md-8">
		<div class="panel panel-inverse">
			<div class="panel-body">
				<h2>
					SCHOOL MANAGEMENT SYSTEM
				</h2>


			</div>
		</div>
	</div>
	<div class="col-md-2"></div>
</div> -->

<?php include('scripts.php'); ?>

<script>
	let menuicn = document.querySelector(".menuicn");
	let nav = document.querySelector(".navcontainer");

	menuicn.addEventListener("click", () => {
		nav.classList.toggle("navclose");
	})
</script>


</body>

</html>