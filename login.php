<?php require "includes/header.php"; ?>
<?php
	if (isLoggedIn()) {
		redirect("admin");
	}
?>

<?php user_Login(); ?>
	<!-- Main content -->
	<div class="main-content">
		<!-- Header -->
		<div class="header bg-gradient-primary py-6 py-lg-6 pt-lg-6">
			<div class="container">
				<div class="header-body text-center mb-7">
					<div class="row justify-content-center">
						<div class="col-xl-5 col-lg-6 col-md-8 px-5">
							<h1 class="text-white display-2 mb-4 font-weight-bolder"><i class="fab fa-ello mr-3"></i>NoteMeet</h1>
							<h1 class="text-white">Welcome!</h1>
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
							<div class="text-center mb-4">
								<small>Login with credentials</small>
							</div>
							<form action="" method="post">
								<div class="form-group mb-3">
									<div class="input-group input-group-merge input-group-alternative">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="ni ni-single-02"></i></span>
										</div>
										<input class="form-control" placeholder="Username" type="text" name="username">
									</div>
								</div>
								<div class="form-group">
									<div class="input-group input-group-merge input-group-alternative">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
										</div>
										<input class="form-control" placeholder="Password" type="password" name="password">
									</div>
								</div>
								<div class="text-center">
									<input type="submit" class="btn btn-primary my-2" name="user_login" value="Login">
								</div>
							</form>
						</div>
					</div>
					<div class="row mt-3">
						<div class="col-6">
							<a href="forgot.php" class="text-gray"><small>Forgot password?</small></a>
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