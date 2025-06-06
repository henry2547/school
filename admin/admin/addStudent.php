<?php
include('header.php');
include('dbconnect.php');
?>

<div class="container py-4">
	<div class="row justify-content-center">
		<div class="col-lg-8">
			<ul class="list-group mb-3" id="myinfo">
				<li class="list-group-item" id="mylist"></li>
			</ul>
			<div class="card shadow">
				<div class="card-header bg-success text-white">
					<h4 class="mb-0"><i class="bi bi-person-plus-fill"></i> Enter Student's Details</h4>
				</div>

				<div class="card-body">
					<form id="saveStudent" method="post" autocomplete="off">
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
								<label for="course" class="form-label">Select Course</label>
								<select name="course" class="form-select" id="course" required>
									<option disabled selected>Select course</option>
									<?php
									$sql = "SELECT * FROM courses";
									$result = $dbcon->query($sql);
									if ($result->num_rows > 0) {
										while ($row = $result->fetch_assoc()) {
											echo "<option value='" . $row['courseId'] . "'>" . $row['course_name'] . "</option>";
										}
									}
									?>
								</select>
							</div>
							<div class="col-md-6">
								<label for="county" class="form-label">County</label>
								<select class="form-select" id="county" name="county" onchange="populateSubcounties()" required>
									<option value="">Select County</option>
									<?php
									$counties = [
										'Baringo' => ['Baringo Central', 'Baringo North', 'East Pokot', 'Koibatek', 'Marigat', 'Mochongoi', 'Mogotio', 'Tiaty'],
										'Bomet' => ['Bomet Central', 'Chepalungu', 'Konoin', 'Sotik'],
										'Bungoma' => ['Bumula', 'Bungoma Central', 'Bungoma East', 'Bungoma North', 'Bungoma South', 'Kanduyi', 'Kimilili', 'Mount Elgon', 'Sirisia', 'Tongaren', 'Webuye East'],
										'Busia' => ['Budalangi', 'Bunyala', 'Butula', 'Nambale', 'Samia', 'Tesio', 'Matayos'],
										'Elgeyo Marakwet' => ['Keiyo North', 'Keiyo South', 'Marakwet East', 'Marakwet West'],
										'Embu' => ['Manyatta', 'Mbeere North', 'Mbeere South'],
										'Garissa' => ['Balambala', 'Dadaab', 'Fafi', 'Garissa Township', 'Hulugho', 'Ijara', 'Lagdera'],
										'Homa Bay' => ['Homa Bay Town', 'Kasipul', 'Kendu Bay', 'Karachuonyo', 'Kabondo Kasipul', 'Ndhiwa', 'Rachuonyo North', 'Rangwe', 'Suba'],
										'Isiolo' => ['Isiolo North', 'Isiolo South', 'Merti'],
										'Kajiado' => ['Isinya', 'Kajiado Central', 'Kajiado East', 'Kajiado North', 'Loitokitok', 'Magadi', 'Ngong'],
										'Kakamega' => ['Butere', 'Kakamega Central', 'Kakamega East', 'Kakamega North', 'Kakamega South', 'Khwisero', 'Lugari', 'Lurambi', 'Malava', 'Matungu', 'Mumias', 'Navakholo'],
										'Kericho' => ['Ainamoi', 'Belgut', 'Bureti', 'Kipkelion East', 'Kipkelion West', 'Soin/Sigowet'],
										'Kiambu' => ['Gatundu North', 'Gatundu South', 'Githunguri', 'Juja', 'Kabete', 'Kiambaa', 'Kiambu Town', 'Kikuyu', 'Limuru', 'Ruiru', 'Thika Town'],
										'Kilifi' => ['Ganze', 'Kaloleni', 'Kilifi North', 'Kilifi South', 'Malindi', 'Magarini', 'Rabai'],
										'Kirinyaga' => ['Gichugu', 'Kirinyaga Central', 'Kirinyaga East', 'Kirinyaga West', 'Mwea East', 'Mwea West'],
										'Kisii' => ['Bobasi', 'Bomachoge Borabu', 'Bomachoge Chache', 'Kisii Central', 'Kitutu Chache North', 'Kitutu Chache South', 'Nyamache', 'Sameta'],
										'Kisumu' => ['Kisumu Central', 'Kisumu East', 'Kisumu West', 'Nyakach', 'Nyando', 'Seme'],
										'Kitui' => ['Central Kitui', 'Kyuso', 'Lower Yatta', 'Mwingi Central', 'Mwingi North', 'Mwingi West', 'Mutito', 'Mutomo', 'Tseikuru'],
										'Kwale' => ['Kinango', 'Lunga Lunga', 'Matuga', 'Msambweni', 'Samburu', 'Kwale Town'],
										'Laikipia' => ['Laikipia Central', 'Laikipia East', 'Laikipia North', 'Laikipia West'],
										'Lamu' => ['Lamu East', 'Lamu West'],
										'Machakos' => ['Athi River', 'Kangundo', 'Kathiani', 'Machakos Town', 'Masinga', 'Matungulu', 'Mavoko', 'Mwala', 'Yatta'],
										'Makueni' => ['Kaiti', 'Kibwezi East', 'Kibwezi West', 'Kilome', 'Makueni', 'Mbooni East', 'Mbooni West'],
										'Mandera' => ['Banisa', 'Lafey', 'Mandera Central', 'Mandera East', 'Mandera North', 'Mandera South'],
										'Marsabit' => ['Laisamis', 'Loiyangalani', 'Marsabit Central', 'Moyale', 'North Horr'],
										'Meru' => ['Buuri', 'Igembe Central', 'Igembe North', 'Igembe South', 'Imenti Central', 'Imenti North', 'Imenti South', 'Meru Central', 'Meru North', 'Tigania East', 'Tigania West'],
										'Migori' => ['Awendo', 'Kuria East', 'Kuria West', 'Migori East', 'Migori West', 'Nyatike', 'Rongo', 'Uriri'],
										'Mombasa' => ['Changamwe', 'Jomvu', 'Kisauni', 'Likoni', 'Mvita', 'Nyali'],
										'Murang\'a' => ['Gatanga', 'Gatundu South', 'Kahuro', 'Kandara', 'Kangema', 'Kigumo', 'Kiharu', 'Mathioya', 'Murang\'a East', 'Murang\'a South'],
										'Nairobi' => ['Dagoretti North', 'Dagoretti South', 'Embakasi Central', 'Embakasi East', 'Embakasi North', 'Embakasi South', 'Embakasi West', 'Kamukunji', 'Kasarani', 'Kibra', 'Lang\'ata', 'Mathare', 'Nairobi Central', 'Roysambu', 'Ruaraka', 'Starehe', 'Westlands'],
										'Nakuru' => ['Gilgil', 'Kuresoi North', 'Kuresoi South', 'Molo', 'Naivasha', 'Nakuru East', 'Nakuru North', 'Nakuru West', 'Njoro', 'Rongai', 'Subukia'],
										'Nandi' => ['Aldai', 'Chesumei', 'Emgwen', 'Mosop', 'Nandi Central', 'Nandi East', 'Nandi Hills', 'Tindiret'],
										'Narok' => ['Emurua Dikir', 'Kilgoris', 'Kilgoris Central', 'Loita', 'Mai Mahiu', 'Narok North', 'Narok South', 'Narok West', 'Narok East'],
										'Nyamira' => ['Borabu', 'Manga', 'Masaba North', 'Masaba South', 'Nyamira North', 'Nyamira South'],
										'Nyandarua' => ['Kinangop', 'Kipipiri', 'Ndaragwa', 'Ol Kalou', 'Ol Jorok', 'Ol Joro Orok', 'Ol-Kalou', 'Ol-Kalou Central'],
										'Nyeri' => ['Kieni East', 'Kieni West', 'Mathira East', 'Mathira West', 'Mukurweini', 'Nyeri Central', 'Othaya', 'Tetu'],
										'Samburu' => ['Samburu Central', 'Samburu East', 'Samburu North', 'Samburu West'],
										'Siaya' => ['Alego Usonga', 'Gem', 'Rarieda', 'Rarieda South', 'Ugenya', 'Ugunja'],
										'Taita Taveta' => ['Mwatate', 'Taita', 'Voi', 'Wundanyi'],
										'Tana River' => ['Garsen', 'Hola', 'Bura'],
										'Tharaka Nithi' => ['Chuka', 'Maara', 'Tharaka'],
										'Trans Nzoia' => ['Cherang\'any', 'Kwanza', 'Saboti', 'Endebess', 'Kiminini'],
										'Turkana' => ['Turkana Central', 'Turkana East', 'Turkana North', 'Turkana South', 'Turkana West'],
										'Uasin Gishu' => ['Ainabkoi', 'Kapseret', 'Kesses', 'Moiben', 'Soy', 'Tarakwa', 'Tinderet'],
										'Vihiga' => ['Emuhaya', 'Hamisi', 'Luanda', 'Sabatia', 'Vihiga Central'],
										'Wajir' => ['Eldas', 'Tarbaj', 'Wajir East', 'Wajir North', 'Wajir South', 'Wajir West'],
										'West Pokot' => ['Central Pokot', 'North Pokot', 'South Pokot', 'West Pokot Central'],
									];
									foreach ($counties as $county => $subcounties) {
										echo "<option value='{$county}'>{$county}</option>";
									}
									?>
								</select>
							</div>
							<div class="col-md-6">
								<label for="subcounty" class="form-label">Subcounty</label>
								<select class="form-select" id="subcounty" name="subcounty" required>
									<option value="">Select Subcounty</option>
								</select>
							</div>
							<div class="col-md-6">
								<label for="gender" class="form-label">Select Gender</label>
								<select class="form-select" name="gender" id="gender" required>
									<option selected value="">Select</option>
									<option value="male">Male</option>
									<option value="female">Female</option>
								</select>
							</div>
							<div class="col-md-6">
								<label for="pwd" class="form-label">Password</label>
								<input type="password" readonly name="pwd" value="123456" class="form-control" id="pwd">
								<small class="text-muted"><i class="bi bi-info-circle"></i> Default password is 123456</small>
							</div>
						</div>
						<div class="mt-4">
							<button type="submit" name="save_case" class="btn btn-success w-100">
								<i class="bi bi-save"></i> Save and Continue
							</button>
						</div>
					</form>
				</div>

				<!-- home button -->
				<div class="card-footer text-center">
					<a href="index.php" class="btn btn-outline-primary">
						<i class="bi bi-house"></i> Home
					</a>
				</div>

			</div>
		</div>
	</div>
</div>

<!-- Bootstrap Toast for Success Message -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1100">
	<div id="successToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
		<div class="d-flex">
			<div class="toast-body">
				<i class="bi bi-check-circle-fill"></i> Student Saved Successfully!
			</div>
			<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
		</div>
	</div>
</div>

<?php include('scripts.php'); ?>

<script type="text/javascript">
	$(document).on('submit', '#saveStudent', function(event) {
		event.preventDefault();
		$(".list-group-item").remove();
		var formData = $(this).serialize();
		$.ajax({
			url: 'saveStudent.php',
			type: 'post',
			data: formData,
			dataType: 'JSON',
			success: function(response) {
				if (response.error) {
					// Optionally show error toast or alert
					console.log(response.error);
				} else {
					// Show Bootstrap 5 Toast
					var toastEl = document.getElementById('successToast');
					var toast = new bootstrap.Toast(toastEl);
					toast.show();
					setTimeout(function() {
						window.location = 'addStudent.php';
					}, 3000);
				}
			}
		});
	});

	// JavaScript function to populate subcounties based on selected county
	function populateSubcounties() {
		var selectedCounty = document.getElementById("county").value;
		var subcountySelect = document.getElementById("subcounty");
		subcountySelect.innerHTML = '<option value="">Select Subcounty</option>';
		if (selectedCounty !== "") {
			var subcounties = <?php echo json_encode($counties); ?>;
			var countySubcounties = subcounties[selectedCounty];
			countySubcounties.forEach(function(subcounty) {
				var option = document.createElement("option");
				option.text = subcounty;
				option.value = subcounty;
				subcountySelect.appendChild(option);
			});
		}
	}
</script>
</body>

</html>