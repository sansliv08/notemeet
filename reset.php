<?php require "includes/header.php"; ?>

<?php
	if (!isset($_GET['token']) || !isset($_GET['email'])) {
		redirect ('index.php');
	}

	$user_email = $_GET['email'];
	$user_token = $_GET['token'];

	$stmt = mysqli_prepare($connection, "SELECT token FROM users WHERE token=?");
	mysqli_stmt_bind_param($stmt, "s", $user_token);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $db_token);

	mysqli_stmt_store_result($stmt); //aceder aos resultados
	if (mysqli_stmt_num_rows($stmt) > 0) {

		if(isset($_POST['reset_password'])) {
			if($_POST['password'] === $_POST['confirm_password']) {
				$new_password = $_POST['password'];

				$error = [
					'password' => '',
				];
	
				if(!preg_match("/[`'\"~!@# $*()<>,:;{}\|]/", $new_password)) {
					$error['password'] = "Password must include at least one of these special characters !@#%&£";
				}
				if(!preg_match("#[0-9]+#", $new_password)) {
					$error['password'] = "Password must include at least one number!";
				}
				if(!preg_match("#[a-zA-Z]+#", $new_password)) {
					$error['password'] = "Password must include at least one letter!";
				}
				if(strlen($new_password) < 6) {
					$error['password'] = "Password must include at least 6 characters!";
				}
				foreach($error as $key => $value) {
					if(empty($value)) {
						unset($error[$key]);
					}
				}

				if(empty($error)) {
					// reset the password
					$password_protected = password_hash($new_password, PASSWORD_ARGON2I);

					$stmt2 = mysqli_prepare($connection, "UPDATE users SET token='', password='$password_protected' WHERE email=?");
					mysqli_stmt_bind_param($stmt2, "s", $user_email); // substitui
					mysqli_stmt_execute($stmt2);

					if (mysqli_stmt_affected_rows($stmt2) > 0) {
						redirect('login.php');
					}
					mysqli_stmt_close($stmt2);
				}
			}
		}
	} else {
		echo "Expired token";
	}
	mysqli_stmt_close($stmt);
?>

	<!-- Main content -->
	<div class="main-content">
		<!-- Header -->
		<div class="header bg-gradient-primary py-7 py-lg-7 pt-lg-7">
			<div class="container">
				<div class="header-body text-center mb-7">
					<div class="row justify-content-center">
						<div class="col-xl-5 col-lg-6 col-md-8 px-5">
							<h1 class="text-white display-2 mb-0"><i class="ni ni-lock-circle-open"></i></h1>
							<h2 class="text-lead text-white mb-0">Reset your password</h2>
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
		<div class="container mt--9 pb-5 text-gray">
			<div class="row justify-content-center">
				<div class="col-lg-5 col-md-7">
					<div class="card bg-secondary border border-soft mb-0">
						<div class="card-body px-lg-5 py-lg-5">
							<!-- <div class="text-center mb-4 px-3">
								<small>Enter your email and we'll send you a link to get back into your account.</small>
							</div> -->
							<form action="" method="post">
								<div class="form-group">
									<div class="input-group input-group-merge input-group-alternative">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="ni ni-key-25"></i></span>
										</div>
										<input class="form-control" placeholder="Password" type="password" name="password" required>
									</div>
									<h5 class="text-lead text-info"><?= isset($error['password']) ? $error['password'] : ''; ?></h5>
								</div>
								<div class="form-group">
									<div class="input-group input-group-merge input-group-alternative">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="ni ni-key-25"></i></span>
										</div>
										<input class="form-control" placeholder="Confirm Password" type="password" name="confirm_password" required>
									</div>
								</div>
								<div class="text-center">
									<input type="submit" class="btn btn-primary mt-3" name="reset_password" value="Reset">
								</div>
							</form>
						</div>
					</div>
					<div class="row mt-3">
						<div class="col-6">
							<a href="login.php" class="text-gray"><small>Back to Login</small></a>
						</div>
						<div class="col-6 text-right">
							<a href="register.php" class="text-gray"><small>Create new account</small></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<!-- Footer -->
<?php require "includes/footer.php"; ?>