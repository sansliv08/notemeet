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
		<div class="container-fluid mt--6">
			<div class="row justify-content-center">
				<div class="col-xl-8 order-xl-1 mt--4">
					<!-- Profile nav -->
					<?php require "includes/profile_nav.php"; ?>

					<div class="card rounded-top shadow">

						<div class="card-header">
							<div class="row align-items-center">
								<div class="col-8">
									<h3 class="mb-0">Create your profile </h3>
								</div>
								<div class="col-4 text-right">
									<a href="settings.php" class="btn btn-sm btn-primary">Settings</a>
								</div>
							</div>
						</div>
						<div class="card-body">
							<!-- Insert User Basic Info -->
							<div class="row">
								<div class="col">
									<h6 class="heading-small text-muted mb-4">Basic Info</h6>
								</div>
								<div class="col text-md-right">
									<?php insertUserProfile(); ?>
								</div>
							</div>
							<form action="" method="post" enctype="multipart/form-data">
								<div class="pl-lg-4">
									<div class="row">
										<div class="col-lg-6">
											<div class="form-group">
												<label class="form-control-label" for="user_profilepic">Profile Picture</label>
												<div class="custom-file">
													<input type="file" class="custom-file-input" name="user_profilepic">
													<label class="custom-file-label" for="user_profilepic">Choose file</label>
												</div>
											</div>
										</div>
										<div class="col-lg-6">
											<div class="form-group">
												<label class="form-control-label" for="user_coverpic">Cover Picture</label>
												<div class="custom-file">
													<input type="file" class="custom-file-input" name="user_coverpic">
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
													<input class="form-control datepicker" placeholder="Select date" type="text" name="user_birth" value="<?= date('m/d/Y', strtotime('now')); ?>" required>
												</div>
											</div>
										</div>
										<div class="col-lg-6">
											<div class="form-group">
												<label class="form-control-label" for="user_gender">Gender</label>
												<select name="user_gender" class="form-control" required>
													<option value="-1">Select</option>
													<option value="1">Female</option>
													<option value="2">Male</option>
												</select>
											</div>
										</div>
									</div>
								</div>
								<div class="pl-lg-4">
									<div class="form-group">
										<label class="form-control-label" for="user_aboutme">About Me</label>
										<textarea rows="4" class="form-control" name="user_aboutme" placeholder="A few words about you..."></textarea>
									</div>
								</div>

								<hr class="my-4" />
								<!-- Address -->
								<div class="row">
									<div class="col">
										<h6 class="heading-small text-muted mb-4">Address</h6>
									</div>
								</div>
								<div class="pl-lg-4">
									<div class="row">
										<div class="col-6">
											<div class="form-group">
												<label class="form-control-label" for="user_country_id">Country</label>
												<select name="user_country_id" class="form-control" required>
													<option value="-1">Select country</option>
													<?php showAllOptionCountries(); ?>
												</select>
											</div>
										</div>
										<div class="col-6">
											<div class="form-group">
												<label class="form-control-label" for="user_city">City</label>
												<input type="text" name="user_city" class="form-control" placeholder="City" required>
											</div>
										</div>
									</div>
								</div>
								<div class="text-right">
									<div class="form-group">
										<input type="submit" class="btn btn-primary btn-sm px-4" name="insert_profile" value="Save">
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<!-- Footer -->
<?php require "includes/footer.php"; ?>