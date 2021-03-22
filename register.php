<?php require "includes/header.php"; ?>
<?php
		if(isset($_POST['user_register'])) {
			$firstname = escapeString($_POST['first_name']);
			$lastname = escapeString($_POST['last_name']);
			$username = escapeString($_POST['username']);
			$user_email = escapeString($_POST['user_email']);
			$user_password = escapeString($_POST['user_password']);

			$error = [
				'firstname' => '',
				'lastname' => '',
				'username' => '',
				'email' => '',
				'password' => '',
			];

			if(empty($firstname)) {
				$error['firstname'] = 'First Name cannot be empty';
			}
			if(empty($lastname)) {
				$error['lastname'] = 'Last Name cannot be empty';
			}
			if(empty($user_email)) {
				$error['email'] = 'Email cannot be empty';
			}
			if(emailExists($user_email)) {
				$error['email'] = 'Email already exists. <a href="login.php"> Please Login.</a>';
			}
			if(strlen($username) < 3) {
				$error['username'] = 'Username must include at least 3 characters!';
			}
			if(usernameExists($username)) {
				$error['username'] = 'Username already exists. Pick another one!';
			}
			if(!preg_match("/[`'\"~!@# $*()<>,:;{}\|]/", $user_password)) {
				$error['password'] = "Password must include at least one of these special characters !@#%&£";
			}
			if(!preg_match("#[0-9]+#", $user_password)) {
				$error['password'] = "Password must include at least one number!";
			}
			if(!preg_match("#[a-zA-Z]+#", $user_password)) {
				$error['password'] = "Password must include at least one letter!";
			}
			if(strlen($user_password) < 6) {
				$error['password'] = "Password must include at least 6 characters!";
			}
			foreach($error as $key => $value) {
				if(empty($value)) {
					unset($error[$key]);
				}
			}

			if(empty($error)) {
				// call register function
				register_user($firstname, $lastname, $username, $user_email, $user_password);
			}
		}
?>
	<!-- Navbar -->
	<!-- <?php require "includes/navigation.php"; ?> -->
	<!-- Main content -->
	<div class="main-content">
		<!-- Header -->
		<div class="header bg-gradient-primary py-6 py-lg-6 pt-lg-6">
			<div class="container">
				<div class="header-body text-center mb-8">
					<div class="row justify-content-center">
						<div class="col-xl-5 col-lg-6 col-md-8 px-5">
							<h1 class="text-white display-1 mb-0"><i class="fab fa-ello"></i></h1>
							<h1 class="text-white">Create an account</h1>
							<!-- <p class="text-lead text-white">Use these awesome forms to login or create new account in your project for free.</p> -->
						</div>
					</div>
				</div>
			</div>
			<div class="separator separator-bottom separator-skew zindex-100">
				<svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
					<polygon class="fill-white" points="2560 0 2560 100 0 100"></polygon>
				</svg>
			</div>
		</div>
		<!-- Page content -->
		<div class="container mt--9 pb-5">
			<!-- Table -->
			<div class="row justify-content-center">
				<div class="col-lg-6 col-md-8">
					<div class="card bg-secondary border border-soft mb-0">
						<div class="card-body px-lg-5 py-lg-5">
							<div class="text-center text-muted mb-4">
								<small>Sign up with credentials</small>
							</div>
							<form action="" method="post" enctype="multipart/form-data">
								<div class="row">
									<div class="col-6">
										<div class="form-group">
											<div class="input-group input-group-merge input-group-alternative mb-2">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="ni ni-hat-3"></i></span>
												</div>
												<input class="form-control" placeholder="First name" type="text" name="first_name" value="<?= isset($firstname) ? $firstname : ''; ?>">
											</div>
											<h5 class="text-lead"><?= isset($error['firstname']) ? $error['firstname'] : ''; ?></h5>
										</div>
									</div>
									<div class="col-6">
										<div class="form-group">
											<div class="input-group input-group-merge input-group-alternative mb-2">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="ni ni-hat-3"></i></span>
												</div>
												<input class="form-control" placeholder="Last name" type="text" name="last_name" value="<?= isset($lastname) ? $lastname : ''; ?>">
											</div>
											<h5 class="text-lead"><?= isset($error['lastname']) ? $error['lastname'] : ''; ?></h5>
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="input-group input-group-merge input-group-alternative mb-2">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="ni ni-single-02"></i></span>
										</div>
										<input class="form-control" placeholder="Username" type="text" name="username" value="<?= isset($username) ? $username : ''; ?>">
									</div>
									<h5 class="text-lead"><?= isset($error['username']) ? $error['username'] : ''; ?></h5>
								</div>
								<div class="form-group">
									<div class="input-group input-group-merge input-group-alternative mb-2">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="ni ni-email-83"></i></span>
										</div>
										<input class="form-control" placeholder="Email" type="email" name="user_email" value="<?= isset($user_email) ? $user_email : ''; ?>">
									</div>
									<h5 class="text-lead"><?= isset($error['email']) ? $error['email'] : ''; ?></h5>
								</div>
								<div class="form-group">
									<div class="input-group input-group-merge input-group-alternative mb-2">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
										</div>
										<input class="form-control" placeholder="Password" type="password" name="user_password">
									</div>
									<h5 class="text-lead"><?= isset($error['password']) ? $error['password'] : ''; ?></h5>
								</div>
								<!-- <div class="text-muted font-italic"><small>password strength: <span class="text-success font-weight-700">strong</span></small></div> -->
								<div class="row my-4">
									<div class="col-12">
										<div class="custom-control custom-control-alternative custom-checkbox">
											<input class="custom-control-input" id="customCheckRegister" type="checkbox" required>
											<label class="custom-control-label" for="customCheckRegister">
												<span class="text-muted">I agree with the <a href="#!">Privacy Policy</a></span>
											</label>
										</div>
									</div>
								</div>
								<div class="text-center">
									<div class="form-group mb-0">
										<input type="submit" class="btn btn-primary mt-2" name="user_register" value="Create account">
								</div>
								</div>
							</form>
						</div>
					</div>
					<div class="row mt-3">
							<div class="col text-center">
								<p class="text-gray">Have an account?<a href="login.php" class="ml-2 font-weight-bold">Login</a></p>
							</div>
						</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Footer -->

<?php require "includes/footer.php"; ?>