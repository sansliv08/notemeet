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
		<div class="header bg-primary pb-6" style="height: 200px; ">
			<div class="container-fluid">
				<div class="header-body">
					<div class="row">
					</div>
				</div>
			</div>
		</div>
		<!-- Page content -->
		<div class="container mt--6">
			<div class="row justify-content-center">

				<div class="col-xl-10 order-xl-1 mt--1">
					<!-- Search Card -->
					<?php
						$user_id_s = $_SESSION['user_id'];

						if(isset($_POST['submit_search'])) {
							$search = escapeString($_POST['search']);
							$s_type = $_POST['s_type'];

							if($s_type == "post") {
								$cond_query = ", p.id AS post_id, p.user_id AS uid_postby, p.created_at AS post_creat, ";
								$cond_query .= "u.firstname AS firstname_postby, u.lastname AS lastname_postby, u.username AS username_postby, ";
								$cond_query .= "u1.id AS u1_id, u1.firstname AS firstname_u1, u1.lastname AS lastname_u1, u1.username AS username_u1 ";

							} else {
								$cond_query = "";
							}
							$query = "SELECT *, u.id AS user_id $cond_query FROM users u ";

							// Users
							if($s_type == "user") {
								$query .= "WHERE u.firstname LIKE '%$search%' OR u.lastname LIKE '%$search%' ";
							}

							// Posts
							if($s_type == "post") {
								$query .= "JOIN posts p ON u.id = p.user_id JOIN users u1 ON p.profile_uid = u1.id ";
								$query .= "WHERE p.body LIKE '%$search%' ORDER BY p.id";
							}

							$result = mysqli_query($connection, $query);
							confirmResult($result);
							$count = mysqli_num_rows($result);
							?>
						<div class="card shadow">
							<div class="card-body text-center">
								<p class="h4 mb-0">Search results for <span class="text-primary font-weight-bolder"><?= $search; ?></span></p>
								<p class="description mb-0">About <?= $count; ?> <?= $count == 1 ? $s_type : $s_type.'s'; ?></p>
								<?php
									if($count == 0) {
										echo "<p class='h3 mt-3 mb-0'>No results! <span class='description lead'>Try again.</span></hp>";
									}
								?>
							</div>
						</div>
						<div class="card-columns">
							<?php
							if(!empty($search) && $count > 0) {
								while ($row = mysqli_fetch_assoc($result)) {
									if($s_type == "user") {
										$u_id = $row['user_id'];
										$firstname = $row['firstname'];
										$lastname = $row['lastname'];
										$username = $row['username'];
										?>
											<div class="card bg-secondary border shadow">
												<div class="card-body">
													<div class="row align-items-center">
														<div class="col-auto">
															<a href ="profile.php<?= $u_id !== $_SESSION['user_id'] ? "?id=$u_id" : "" ; ?>" class="avatar rounded-circle">
																<?php showProfilePicture($u_id, $username);  ?>
															</a>
														</div>
														<div class="col-5">
															<p class="h4 mb-0"><?= $firstname . " " . $lastname; ?></p>
														</div>
														<div class="col-4">
															<?php friendsButton($u_id); ?>
														</div>
													</div>
												</div>
											</div>
										<?php
									} 
									elseif ($s_type == "post") {
										$post_id = $row['post_id'];
										$post_img = $row['image'];
										$post_content = $row['body'];
										$post_created = $row['post_creat'];
										$userid_postby = $row['user_id'];
										$userid_postby = $row['uid_postby'];
										$firstname_postby = $row['firstname_postby'];
										$lastname_postby = $row['lastname_postby'];
										$username_postby = $row['username_postby'];
										$user1_id = $row['u1_id'];
										$firstname_u1 = $row['firstname_u1'];
										$lastname_u1 = $row['lastname_u1'];
										$username_u1 = $row['username_u1'];
										?>
											<div class="card rounded-top shadow">
												<!-- Different Header Post -->
												<div class="card-header pb-0">
													<div class="row justify-content-between">
														<div class="col-auto">
															<a href ="profile.php<?= $userid_postby !== $_SESSION['user_id'] ? "?id=$userid_postby" : "" ; ?>" class="avatar rounded-circle">
																<?php showProfilePicture($userid_postby, $username_postby);  ?>
															</a>
														</div>
														<div class="col pl-0">
															<a href ="profile.php<?= $userid_postby !== $_SESSION['user_id'] ? "?id=$userid_postby" : "" ; ?>" class="font-weight-bold text-gray-dark mb-0">
																<?= $firstname_postby . " " . $lastname_postby; ?>
															</a>
															<!-- When the $userid_postby post in a friend profile -->
															<?php if($userid_postby !== $user1_id) : ?>
																<span>
																	<i class="fas fa-angle-right px-2"></i>
																	<a href ="profile.php<?= $user1_id !== $_SESSION['user_id'] ? "?id=$user1_id" : "" ; ?>" class="font-weight-bold text-gray-dark mb-0">
																		<?= $firstname_u1 . " " . $lastname_u1; ?>
																	</a> 
																</span>
															<?php endif; ?>
															<p class="text-xs text-muted"><?= date('j F, Y', strtotime($post_created)) . ' at ' . date('H:i', strtotime($post_created)); ?></p>
														</div>
														<div class="col-auto text-right">
															<!-- Dropdown button to posts -->
															<?php require "includes/post_dropdown.php"; ?>
														</div>
													</div>
												</div>
												<!-- Content post with all likes and all coments -->
												<?php require "includes/post_body.php"; ?>
											</div>
										<?php
									}
								}
							}
						}
						?>
						</div>
				</div>
			</div>

<!-- Footer -->
<?php require "includes/footer.php"; ?>

<!-- Scripts of Modals -->
<?php require "includes/scripts.php"; ?>