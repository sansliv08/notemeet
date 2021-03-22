<?php require "includes/head.php"; ?>

<body>
	<!-- Sidenav -->
	<?php require "includes/sidebar.php"; ?>
	<!-- Main content -->
	<div class="main-content" id="panel">
		<!-- Topnav -->
		<?php require "includes/navigation.php"; ?>
		<!-- Header -->
		<?php require "includes/profile_header.php"; ?>
		<!-- Page content -->
		<div class="container mt--6">
			<div class="row">
				<div class="col-xl-4 order-xl-2 mt--2">
					<!-- Profile Card -->
					<?php require "includes/profile_card.php"; ?>
					<!-- Profile friends -->
					<?php 
						if(friendsExists($user_id)) {
							require "includes/profile_friends.php"; 
						}
					?>
				</div>
				<div class="col-xl-8 order-xl-1 mt--4">

					<!-- Profile nav -->
					<?php require "includes/profile_nav.php"; ?>

					<div class="card rounded-top shadow">

						<div class="card-header">
							<div class="row align-items-center">
								<div class="col-8">
									<h3 class="mb-0">Edit your profile </h3>
								</div>
								<div class="col-4 text-right">
									<a href="settings.php" class="btn btn-sm btn-primary">Settings</a>
								</div>
							</div>
						</div>


						<div class="card-body">
							<!-- Update Profile -->
							<?php
								$user_id = $_SESSION['user_id'];
								$result = query("SELECT * FROM `profile` p JOIN `location` l ON p.pk_location = l.id JOIN city c ON l.pk_cityid=c.id WHERE p.user_id = $user_id LIMIT 1");

								while($row = mysqli_fetch_assoc($result)) {
									$user_profilepic = $row['profilepic'];
									$user_coverpic = $row['coverpic'];
									$user_birth = date('m/d/Y', strtotime($row['birthdate']));
									$user_gender = $row['gender'];
									$user_aboutme = $row['aboutme'];
									
									if($user_gender == 1) {
										$user_gender = "Female";
									} elseif($user_gender == 2) {
										$user_gender = "Male";
									}
									$user_country_id = $row['pk_countryid'];
									$user_city = $row['city'];
								?>
									<form action="" method="post" enctype="multipart/form-data">
										<h6 class="heading-small text-muted mb-4">Basic Info</h6>
										<div class="pl-lg-4">
											<div class="row">
												<div class="col-lg-4">
													<div class="form-group">
														<label class="form-control-label d-block" for="user_coverpic">Profile Picture</label>
														<img alt="" class='img-fluid rounded mb-2' src="assets/img/users/<?= $_SESSION['username'];?>/profile/<?= $user_profilepic; ?>">
														<div class="custom-file">
															<input type="file" class="custom-file-input" name="user_profilepic">
															<input type="hidden" name="user_profilepic_current" value="<?= $user_profilepic; ?>">
															<label class="custom-file-label" for="user_profilepic">Choose file</label>
														</div>
													</div>
												</div>
												<div class="col-lg-8">
													<div class="form-group">
														<label class="form-control-label d-block" for="user_coverpic">Cover Picture</label>
														<img alt="" class='img-fluid rounded mb-2' src="assets/img/users/<?= $_SESSION['username'];?>/cover/<?= $user_coverpic; ?>">
														<div class="custom-file">
															<input type="file" class="custom-file-input" name="user_coverpic">
															<input type="hidden" name="user_coverpic_current" value="<?= $user_coverpic; ?>">
															<label class="custom-file-label" for="user_coverpic">Choose file</label>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="pl-lg-4">
											<div class="row">
												<div class="col-lg-6">
												<div class="form-group">
														<label class="form-control-label" for="user_birth">Birthday of Date</label>
														<div class="input-group">
															<div class="input-group-prepend">
																<span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
															</div>
															<input class="form-control datepicker" placeholder="Select date" type="text" name="user_birth" value="<?= $user_birth; ?>">
														</div>
													</div>
												</div>
												<div class="col-lg-6">
													<div class="form-group">
														<label class="form-control-label" for="user_gender">Gender</label>
														<select name="user_gender" class="form-control">

															<option class="selected" ><?= $user_gender; ?></option>
															<?php
																if($user_gender == "Female") {
																	echo "<option value='2'>Male</option>";
																} else {
																	echo "<option value='1'>Female</option>";
																}
															?>
														</select>
													</div>
												</div>
											</div>
										</div>
										<div class="pl-lg-4">
											<div class="form-group">
												<label class="form-control-label" for="user_aboutme">About Me</label>
												<textarea rows="4" class="form-control" name="user_aboutme" placeholder="A few words about you..."><?= $user_aboutme; ?></textarea>
											</div>
										</div>

										<hr class="my-4" />

										<h6 class="heading-small text-muted mb-4">Address</h6>
										<div class="pl-lg-4">
											<div class="row" >
												<div class="col-6">
													<div class="form-group">
														<label class="form-control-label" for="user_country_id">Country</label>
														<select name="user_country_id" class="form-control">
															<option value="-1">Select country</option>
															<?php showAllOptionCountries($user_country_id); ?>
														</select>
													</div>
												</div>
												<div class="col-6">
													<div class="form-group">
														<label class="form-control-label" for="user_city">City</label>
														<input type="text" name="user_city" class="form-control" placeholder="City" value="<?= $user_city; ?>">
													</div>
												</div>
											</div>
										</div>

										<div class="text-right">
											<div class="form-group mb-0">
												<input type="submit" class="btn btn-primary btn-sm px-4" name="update_profile" value="Save">
											</div>
										</div>
									</form>
								<?php
								}
							?>

								<hr class="my-4" id="editpost"/>
							
								<div class="row">
									<div class="col">
										<h6 class="heading-small text-muted">Education</h6>
									</div>
									<div class="col text-md-right">
										<a class="btn btn-sm btn-info px-4 mr-0 mb-3" data-toggle="collapse" href="#add_course" role="button" aria-expanded="false" aria-controls="add_course">
											Add course
										</a>
										<?php insertEducation(); ?>
									</div>
								</div>
								<div id="add_course" class="collapse bg-secondary rounded px-4 py-3">
									<!-- form to insert education -->
									<form action="" method="post" enctype="multipart/form-data">
										<div class="pl-lg-4">
											<div class="form-group">
												<label class="form-control-label" for="user_course">Course Name</label>
												<input type="text" name="user_course" class="form-control" placeholder="Course Name" required>
											</div>
										</div>
										<div class="pl-lg-4">
											<div class="row">
												<div class="col-6">
													<div class="form-group">
														<label class="form-control-label" for="course_school">School Name</label>
														<input type="text" name="course_school" class="form-control" placeholder="School Name" required>
													</div>
												</div>
												<div class="col-3">
													<div class="form-group">
														<label class="form-control-label" for="course_sd">Start Date</label>
														<div class="input-group">
															<div class="input-group-prepend">
																	<span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
															</div>
															<input class="form-control datepicker" placeholder="Select date" type="text" name="course_sd">
														</div>
													</div>
												</div>
												<div class="col-3">
													<div class="form-group">
														<label class="form-control-label" for="course_fd">Finish Date</label>
														<div class="input-group">
															<div class="input-group-prepend">
																	<span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
															</div>
															<input class="form-control datepicker" placeholder="Select date" type="text" name="course_fd">
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="pl-lg-4">
											<div class="form-group">
												<label class="form-control-label" for="course_descrip">Description</label>
												<textarea rows="4" class="form-control" name="course_descrip" placeholder="A few words about your course..."></textarea>
											</div>
										</div>
									
										<div class="text-right">
											<div class="form-group mb-0">
												<input type="submit" class="btn btn-primary btn-sm px-4" name="insert_education" value="Save">
											</div>
										</div>
									</form>
								</div>

								<!-- Update Course-->
								<?php updateCourse(); ?>

								<!-- Show Education -->
								<?php 
								if(userExistsInEducation($user_id)) {
									?>
										<div class="row">
											<div class="col my-4">
												<label class="form-control-label">All your courses</label>
												<div class="table-responsive rounded-top">
													<table class="table align-items-center table-flush border-bottom mb-4">
														<thead class="thead-light">
															<tr>
															<th scope="col" class="sort">Id</th>
															<th scope="col" class="sort">Course Name</th>
															<th scope="col" class="sort">School Name</th>
															<th scope="col"></th>
															<th scope="col"></th>
															</tr>
														</thead>
														<tbody class="list">
															<?php 
															$records_per_page = 10;
															$current_page = getCurrentPage($records_per_page);

															showAllEducations($user_id); ?>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									<?php
								} else {
								echo "<p class='description text-center'><u>Edit your profile</u> and add data about your education.</p>";
								}
								?>
						</div>
					</div>
				</div>
			</div>
			<!-- Footer -->
<?php require "includes/footer.php"; ?>