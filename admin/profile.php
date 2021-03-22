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

					<!-- Insert post -->
					<?php require "includes/insert_post.php"; ?>

					<?php updatePost(); ?>

					<?php insertComment(); ?>

					<?php updateComment(); ?>

					<?php insertOrDeleteLike(); ?>

					<!-- Show Your Posts -->
					<div class="card-columns">
						<?php
						global $user_id;

						$records_per_page = 10;
						$current_page = getCurrentPage($records_per_page);

						$result = query("SELECT *, p.id AS post_id, p.created_at AS post_creat FROM posts p JOIN users u ON p.user_id = u.id WHERE p.profile_uid = $user_id AND p.approved = 1 ORDER BY p.id DESC LIMIT $current_page, $records_per_page");

						if(mysqli_num_rows($result) > 0) {
							while($row = mysqli_fetch_assoc($result)) {
								$post_id = $row['post_id'];
								$post_img = $row['image'];
								$post_content = $row['body'];
								$post_created = $row['post_creat'];
								$userid_postby = $row['user_id'];
								$firstname = $row['firstname'];
								$lastname = $row['lastname'];
								$username = $row['username'];
								?>
									<div class="card rounded-top shadow">
										<div class="card-header pb-0">
											<div class="row justify-content-between">
												<div class="col-auto">
													<a href ="profile.php<?= $userid_postby !== $_SESSION['user_id'] ? "?id=$userid_postby" : "" ; ?>" class="avatar rounded-circle">
														<?php showProfilePicture($userid_postby, $username);  ?>
													</a>
												</div>
												<div class="col pl-0">
													<a href ="profile.php<?= $userid_postby !== $_SESSION['user_id'] ? "?id=$userid_postby" : "" ; ?>" class="font-weight-bold text-gray-dark mb-0">
														<?= $firstname . " " . $lastname; ?>
													</a>
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
						?>
					</div>
					<!-- Pagination -->
					<?php 
						$numberOfPostsinProfile = countPostsOneUser();
						$count = ceil($numberOfPostsinProfile / $records_per_page);
						$page = getPage();

						if($count > 1) { ?>
							<div class="row justify-content-center">
								<nav aria-label="...">
								<ul class="pagination pagination-lg">
									<?php
									for ($i = 1; $i <= $count; $i++) : ?>
										<li class="page-item <?= $page == $i || ( !isset($_GET['page']) && $i == 1) ? 'active' : '' ?>">
										<a class="page-link" href='profile.php?page=<?= $i; ?>'><?= $i; ?></a>
										</li>
									<?php endfor; ?>
								</ul>
								</nav>
							</div>
							<?php
						} elseif($count = 0 && amI()) { 
							?>
								<div class="card rounded-top mb-6 shadow">
									<div class="card-body">
										<p class="h4 text-center text-primary mb-0"><u>Add your first post in your timeline!</u></p>
										<p class="description text-center mb-0">Then your posts will appear here.</p>
									</div>
								</div>
							<?php
							
						}
					?>
				</div>
			</div>

<!-- Footer -->
<?php require "includes/footer.php"; ?>

<!-- Scripts of Modals -->
<?php require "includes/scripts.php"; ?>