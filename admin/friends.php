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
				</div>
				<div class="col-xl-8 order-xl-1 mt--4">

					<!-- Profile nav -->
					<?php require "includes/profile_nav.php"; ?>

					<div class="card rounded-top shadow">
						<div class="card-header">
							<div class="row align-items-center">
								<div class="col">
									<h3 class="mb-0">Friends</h3>
								</div>
								<div class="col text-right">
									<a class="btn btn-sm btn-primary px-4" data-toggle="collapse" href="#find_friend" role="button" aria-expanded="false" aria-controls="add_course">Find Friends</a>
								</div>
							</div>
						</div>

						<div id="find_friend" class="collapse card-body">
							<div class="bg-secondary rounded px-4 py-3">
								<form action="search.php" method="post">
									<div class="form-group mb-0">
										<div class="input-group input-group-alternative input-group-merge">
											<button type="submit" name="submit_search" class="btn btn-sm btn-primary input-group-prepend">
												<span class="input-group-text bg-primary text-white"><i class="fas fa-search"></i></span>
											</button>
											<input type="hidden" name="s_type" value="user">
											<input class="form-control pl-4" placeholder="Search by name" type="text" name="search" value="<?= isset($_POST['search']) ? $_POST['search'] : "" ; ?>">
										</div>
									</div>
								</form>
							</div>
						</div>
						<div class="card-body">
							<div class="row d-flex flex-wrap">
								<!-- Show Friends -->
								<?php
									// Segunda versão de mostrar os friends - maravilha de joins
									global $user_id;

									$query = "SELECT *, u1.id AS u1_id, u1.firstname AS u1_firstname, u1.lastname AS u1_lastname, u1.username AS u1_username, ";
									$query .= "u2.id AS u2_id, u2.firstname AS u2_firstname, u2.lastname AS u2_lastname, u2.username AS u2_username FROM users u1 ";
									$query .= "JOIN `relationship` r ON u1.id = r.user1_id JOIN users u2 ON u2.id = r.user2_id ";
									$query .= "WHERE (r.user1_id = $user_id OR r.user2_id = $user_id) AND r.status = 1 ";

									$result = mysqli_query($connection, $query);
									confirmResult($result);

									if(mysqli_num_rows($result) > 0) {
										while($row = mysqli_fetch_array($result)) {
											$u1_id = $row['u1_id'];
											$u1_firstname = $row['u1_firstname'];
											$u1_lastname = $row['u1_lastname'];
											$u1_username = $row['u1_username'];
											$u2_id = $row['u2_id'];
											$u2_firstname = $row['u2_firstname'];
											$u2_lastname = $row['u2_lastname'];
											$u2_username = $row['u2_username'];

											if($u1_id == $user_id) {
												?>
													<div class="col-md-6">
														<div class="card border rounded bg-secondary mb-3">
															<div class="card-body">
																<div class="row align-items-center">
																	<div class="col-auto">
																		<a href ="profile.php<?= $u2_id !== $_SESSION['user_id'] ? "?id=$u2_id" : "" ; ?>" class="avatar rounded-circle">
																			<?php showProfilePicture($u2_id, $u2_username);  ?>
																		</a>
																	</div>
																	<div class="col-4 px-0">
																		<p class="h4 mb-0"><?= $u2_firstname; ?></p>
																		<p class="h4 mb-0"><?= $u2_lastname; ?></p>
																	</div>
																	<div class="col-4 px-0">
																		<?php friendsButton($u2_id); ?>
																	</div>
																</div>
															</div>
														</div>
				
													</div>
												<?php
											} elseif($u2_id == $user_id) {
												?>
													<div class="col-md-6">
														<div class="card border rounded bg-secondary mb-3">
															<div class="card-body">
																<div class="row align-items-center">
																	<div class="col-auto">
																		<a href ="profile.php<?= $u1_id !== $_SESSION['user_id'] ? "?id=$u1_id" : "" ; ?>" class="avatar rounded-circle">
																			<?php showProfilePicture($u1_id, $u1_username);  ?>
																		</a>
																	</div>
																	<div class="col-4 px-0">
																		<p class="h4 mb-0"><?= $u1_firstname; ?></p>
																		<p class="h4 mb-0"><?= $u1_lastname; ?></p>
																	</div>
																	<div class="col-4 px-0">
																		<?php friendsButton($u1_id); ?>
																	</div>
																</div>
															</div>
														</div>
													</div>
												<?php
											}
										}
									} else {
										if(amI()) { ?>
											<div class="col">
												<p class="h4 text-primary mb-0"><u>Find new friends!</u></p>
												<p class="description">Then your friends will appear here.</p>
											</div>
										<?php
										}
									}
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Footer -->
<?php require "includes/footer.php"; ?>