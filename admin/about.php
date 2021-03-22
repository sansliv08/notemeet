<?php require "includes/head.php"; ?>

<body>
	<!-- Sidenav -->
	<?php require "includes/sidebar.php"; ?>
	<!-- Main content -->
	<div class="main-content" id="panel">
		<!-- Topnav -->
		<?php require "includes/navigation.php"; ?>
		
		<!-- Get User ID -->
		<?php getUserId(); ?>
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
						<div class="card-body">
							<?php 
								if(!profileExists($user_id)) {
									if(!amI()) {
									echo "The profile hasn't been created yet!";
									} else {
									echo "Please, create your profile!";
									}
								} else {
									?>
									<h6 class="heading-small text-muted mb-4">Overview</h6>
										<?php showBasicInfo($user_id); ?>

									<hr class="my-4" />
									<h6 class="heading-small text-muted mb-4">Education</h6>
										<?php showEducation($user_id); ?>

									<hr class="my-4" />
									<h6 class="heading-small text-muted mb-4">Contact info</h6>
										<?php showLocation($user_id); ?>
									<?php
								} 
							?>
						</div>
					</div>

				</div>
			</div>
			<!-- Footer -->
<?php require "includes/footer.php"; ?>