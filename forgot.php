<?php require "includes/header.php"; ?>
<?php require "classes/Config.php"; ?>

<?php
	use PHPMailer\PHPMailer\PHPMailer;
	require 'vendor/autoload.php';
	require 'vendor/phpmailer/phpmailer/src/PHPMailer.php'
?>

<?php
	if(isset($_POST['recover_password'])) {
		$email = $_POST['user_email'];

		if (emailExists($email)) {
			$token = bin2hex(openssl_random_pseudo_bytes(50));

			$stmt = mysqli_prepare($connection, "UPDATE users SET token='$token' WHERE email=?");
			mysqli_stmt_bind_param($stmt, "s", $email);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);

			// Mail Settings
			$mail = new PHPMailer();
			$mail->isSMTP();
			$mail->Host = Config::$SMTP_HOST;
			$mail->Username = Config::$SMTP_USERNAME;
			$mail->Password = Config::$SMTP_PASSWORD;
			$mail->Port = Config::$SMTP_PORT;
			$mail->SMTPSecure = 'tls';
			$mail->SMTPAuth = true;
			$mail->isHTML(true);
			$mail->Charset = 'UTF-8';
			
			$mail->setFrom("santosliv08@gmail.com", "Lara Santos"); // quem envia
			$mail->addAddress($email); //email adicionado para recuperar

			$mail->Subject = "Recover Password";
			$mail->Body = "<p>Please click to reset your password <a href='http://localhost:80/sntest/reset.php?email=" . $email . "&token=" . $token . "'>RESET</a></p>";

			if($mail->Send()){
				echo "Email sent!";
			} else {
					echo "Fail";
			}
		} else {
			echo "Email not found!";
		}
	}
?>
	<!-- Main content -->
	<div class="main-content">
		<!-- Header -->
		<div class="header bg-gradient-primary py-7 py-lg-7 pt-lg-7">
			<div class="container">
				<div class="header-body text-center mb-7">
					<div class="row justify-content-center">
						<div class="col-xl-5 col-lg-6 col-md-8 px-5">
							<h1 class="text-white display-1 mb-0"><i class="ni ni-lock-circle-open"></i></h1>
							<!-- <p class="text-lead text-white">Trouble Logging In?</p> -->
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
							<div class="text-center mb-4 px-3">
								<small>Enter your email and we'll send you a link to get back into your account.</small>
							</div>
							<form action="" method="post">
								<div class="form-group mb-3">
									<div class="input-group input-group-merge input-group-alternative">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="ni ni-email-83"></i></span>
										</div>
										<input class="form-control" placeholder="Email" type="email" name="user_email">
									</div>
								</div>
								<div class="text-center">
									<input type="submit" class="btn btn-primary mt-3" name="recover_password" value="Send">
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