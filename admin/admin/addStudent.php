<?php

include('header.php');
include('dbconnect.php');

?>


<div class="container-fluid">


	<?php include('menubar.php') ?>
	<?php // include('menubar1.php');


	?>
	<div class="container-fluid">

		<div class="col-md-2"></div>
		<div class="col-md-8">
			<ul class="list-group" id="myinfo">

				<li class="list-group-item" id="mylist"></li>

			</ul>
			<div class="panel panel-success">
				<div class="panel-heading">

					<h3 class="panel-title">Enter Student's Details</h3>
				</div>
				<div class="panel-body">





					<div class="container-fluid">
						<form class="form-horizontal" id="saveStudent" method="post" role="form">

							<div class="form-row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="">Firstname:</label>
										<input type="text" name="fname" class="form-control" id="fname" required="">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="">Secondname:</label>
										<input type="text" name="sname" class="form-control" id="sname" required="">
									</div>
								</div>
							</div>

							<div class="form-row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="">Email:</label>
										<input type="email" name="email" class="form-control" id="email" required="">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="">Phone:</label>
										<input type="tel" name="phone" class="form-control" id="phone" maxlength="10" minlength="10" required>
									</div>
								</div>

								<div class="col-md-6">
									<div class="form-group">
										<label for="">Select course:</label>
										<select name="course" class="form-control">
											<option disabled selected>Select course</option>
											<?php
											require_once "dbconnect.php";
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
								</div>


							</div>

							<div class="form-row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="">County:</label>
										<select class="form-control" id="county" name="county" onchange="populateSubcounties(this.value)">
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
								</div>

							</div>

							<div class="form-row">

								<div class="col-md-6">
									<div class="form-group">
										<label for="subcounty" class="form-label">Subcounty</label>
										<select class="form-control" id="subcounty" name="subcounty">
											<option value="">Select Subcounty</option>
										</select>
									</div>
								</div>

								<div class="form-row">

									<div class="col-md-6">
										<div class="form-group">
											<label for="">Select Gender:</label>
											<select class="form-control" name="gender" id="gender">
												<option selected="selected" value="">Select</option>

												<option value="male"> Male </option>
												<option value="female"> Female </option>

											</select>
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label for="">Password:</label>

											<input type="password" readonly="" name="pwd" value="123456" class="form-control" id="pwd" autofocus="">
										</div>
									</div>



								</div>


							</div>
							<div class="form-group">
								<button type="submit" name="save_case" class="btn btn-success form-control">Save and Continue
									<span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-2"></div>
	</div>

	<?php include('scripts.php'); ?>

	<script type="text/javascript">
		$(document).on('submit', '#saveStudent', function(event) {

			event.preventDefault();

			// This removes the error messages from the page
			$(".list-group-item").remove();

			var formData = $(this).serialize();

			$.ajax({
				url: 'saveStudent.php',
				type: 'post',
				data: formData,
				dataType: 'JSON',

				success: function(response) {

					if (response.error) {

						console.log(response.error);
					} else {

						Swal.fire({
							position: 'top-end',
							icon: 'success',
							title: 'Student Saved',
							showConfirmButton: false,
							timer: 3000
						});


						setTimeout(function() {
							window.location = 'addStudent.php';
						}, 3000);


					}

				}


			});



		});
	</script>

	<script>
		// JavaScript function to populate subcounties based on selected county
		function populateSubcounties() {
			// Get the selected county
			var selectedCounty = document.getElementById("county").value;

			// Get the select element for subcounties
			var subcountySelect = document.getElementById("subcounty");

			// Clear existing options
			subcountySelect.innerHTML = '<option value="">Select Subcounty</option>';

			// Populate subcounties based on selected county
			if (selectedCounty !== "") {
				var subcounties = <?php echo json_encode($counties); ?>;
				var countySubcounties = subcounties[selectedCounty];

				// Add options to the subcounty select element
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