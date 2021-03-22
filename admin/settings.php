<?php require "includes/head.php"; ?>
<body>
	<!-- Sidenav -->
	<?php require "includes/sidebar.php"; ?>
	<!-- Main content -->
	<div class="main-content" id="panel">
		<!-- Topnav -->
		<?php require "includes/navigation.php"; ?>
		<!-- Header -->
		<div class="header bg-primary pb-6">
			<div class="container-fluid">
				<div class="header-body">
					<div class="row align-items-center py-4">
						<div class="col-lg-6 col-7">
							<h6 class="h2 text-white d-inline-block mb-0">Settings</h6>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Page content -->
		<div class="container mt--6">
			<div class="row justify-content-center">
				<!-- Light table -->
				<div class="col-xl-10">
					<div class="card">
						<div class="card-header">
							<div class="row">
								<div class="col-6">
									<h3 class="mb-0">General</h3>
								</div>
								<div class="col-6 text-right">
									<!-- Update User Data -->
									<?php
										$user_id = $_SESSION['user_id'];

										$result = query("SELECT * FROM users WHERE id='$user_id' LIMIT 1");
										while ($row = mysqli_fetch_assoc($result)) {
											$user_firstname = $row['firstname'];
											$user_lastname = $row['lastname'];
											$user_email = $row['email'];

											if (isset($_POST['update_user'])) {
												$new_user_firstname = escapeString($_POST['first_name']);
												$new_user_lastname = escapeString($_POST['last_name']);
												$new_user_email = escapeString($_POST['email']);

												$error1 = [
													'email' => '',
												];
												if($new_user_email !== $user_email) {
													if(empty($new_user_email)) {
														$error1['email'] = 'Email cannot be empty';
													}
													if(emailExists($new_user_email)) {
														$error1['email'] = 'Email already exists.';
													}
												}
												foreach($error1 as $key => $value) {
													if(empty($value)) {
														unset($error1[$key]);
													}
												}
												if(empty($error1)) {
												// call update user function
												updateUserData($user_id, $new_user_firstname, $new_user_lastname, $new_user_email);
												}
											}	
											?>
								</div>
							</div>
						</div>
						<div class="card-body">
							<form action="" method="post" enctype="multipart/form-data">
								<h6 class="heading-small text-muted mb-4">User information</h6>
								<div class="pl-lg-4">
									<div class="row">
										<div class="col-lg-6">
											<div class="form-group">
												<label class="form-control-label" for="first_name">First name</label>
												<input value="<?= $user_firstname; ?>" type="text" name="first_name" class="form-control">
											</div>
										</div>
										<div class="col-lg-6">
											<div class="form-group">
												<label class="form-control-label" for="last_name">Last name</label>
												<input value="<?= $user_lastname; ?>" type="text" name="last_name" class="form-control">
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col">
											<div class="form-group mb-0">
												<label class="form-control-label" for="email">Email address</label>
												<input value="<?= isset($new_user_email) ? $new_user_email : $user_email; ?>" type="email" name="email" class="form-control">
											</div>
											<h5 class="text-lead text-warning"><?= isset($error1['email']) ? $error1['email'] : ''; ?></h5>
										</div>
									</div>
									<div class="text-right">
										<div class="form-group mb-0">
											<input type="submit" class="btn btn-primary btn-sm px-4" name="update_user" value="Save">
										</div>
									</div>
								</div>
							</form>
							<?php
										}
							?>
						</div>
					</div>
					<div class="card">
						<div class="card-header">
							<div class="row">
								<div class="col-6">
									<h3 class="mb-0">Change Password</h3>
								</div>
								<div class="col-6 text-right">
									<!-- Change password -->
									<?php
									if(isset($_POST['change_password'])) {
										$current_password = $_POST['current_password'];
										$new_password = $_POST['new_password'];
										$reply_password = $_POST['reply_password'];

										$result = query("SELECT `password` FROM users WHERE id = '$user_id' LIMIT 1");
										if (mysqli_num_rows($result) !=0) {
											$row = mysqli_fetch_assoc($result);
											$db_password = $row['password'];
										}

										$error2 = [
											'current_password' => '',
											'new_password' => '',
											'reply_password' => '',
										];
										if(!password_verify($current_password, $db_password)) {
											$error2['current_password'] = 'Wrong current password!';
										}
										if(!preg_match("/[`'\"~!@# $*()<>,:;{}\|]/", $new_password)) {
											$error2['new_password'] = "Password must include at least one of these special characters !@#%&£";
										}
										if(!preg_match("#[0-9]+#", $new_password)) {
											$error2['new_password'] = "Password must include at least one number!";
										}
										if(!preg_match("#[a-zA-Z]+#", $new_password)) {
											$error2['new_password'] = "Password must include at least one letter!";
										}
										if(strlen($new_password) < 6) {
											$error2['new_password'] = "Password must include at least 6 characters!";
										}
										if(password_verify($new_password, $db_password)) {
											$error2['new_password'] = "Passwords are the same!";
										}
										if($new_password !== $reply_password) {
											$error2['reply_password'] = "Passwods do not match!";
										}
										foreach($error2 as $key => $value) {
											if(empty($value)) {
												unset($error2[$key]);
											}
										}
										if(empty($error2)) {
											// call changePassord function
											changePassword($new_password);
										}
									}
								?>
								</div>
							</div>
						</div>
						<div class="card-body">
							<form action="" method="post" enctype="multipart/form-data">
								<div class="row justify-content-center">
									<div class="col-sm">
										<div class="form-group mb-0">
											<label class="form-control-label" for="current_password">Current Password</label>
											<input type="password" name="current_password" class="form-control" placeholder="Current Password">
										</div>
										<h5 class="text-lead text-info"><?= isset($error2["current_password"]) ? $error2["current_password"] : ""; ?></h5>
									</div>
								</div>
								<div class="row">
									<div class="col-sm">
										<div class="form-group mt-3 mb-0">
											<label class="form-control-label" for="new_password">New Password</label>
											<input type="password" name="new_password" class="form-control" placeholder="New Password">
										</div>
										<h5 class="text-lead text-info"><?= isset($error2['new_password']) ? $error2['new_password'] : ''; ?></h5>
									</div>
									<div class="col-sm">
										<div class="form-group mt-3 mb-0">
											<label class="form-control-label" for="reply_password">Reply Password</label>
											<input type="password" name="reply_password" class="form-control" placeholder="Reply Password">
										</div>
										<h5 class="text-lead text-info"><?= isset($error2['reply_password']) ? $error2['reply_password'] : ''; ?></h5>
									</div>
								</div>
								<div class="row justify-content-between">
									<div class="col">
										<a href="../forgot.php" class="text-gray"><small>Forgot password?</small></a>
									</div>
									<div class="col text-right">
										<div class="form-group mb-0">
											<input type="submit" class="btn btn-primary btn-sm px-4" name="change_password" value="Save">
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
					<div class="card">
						<div class="card-header">
							<div class="row">
								<div class="col-6">
									<h3 class="mb-0">Delete your account</h3>
								</div>
							</div>
						</div>
						<div class="card-body text-center">
							<form action="" method="post">
								<div class="form-group mb-0">
									<button type="button" class="btn btn-lg btn-danger" data-toggle="modal" data-target="#deleteUserModal">
										DELETE
									</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>

<!-- Footer -->
<?php require "includes/footer.php"; ?>



<!-- Modal Delete User -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel">Delete your account</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="modal-body">
			<p class="text-left mb-0">
				Are you sure that you want to <span class="font-weight-bolder text-danger">delete your account</span>?
			</p>
			<p class="text-left mb-0">This will delete your account <u>forever</u>.</p>
		</div>
		<div class="modal-footer">
			<a href="settings.php?deleteaccount=<?= $_SESSION["user_id"]; ?>">
				<button class="btn btn-lg btn-danger">Delete</button>
			</a>
		</div>
		</div>
	</div>
</div>