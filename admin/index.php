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

					<!-- Card stats -->
					<div class="row">

					</div>
				</div>
			</div>
		</div>
		<!-- Page content -->
		<div class="container mt--6">
			<div class="row justify-content-center">

				<div class="col-xl-10 order-xl-1 mt--4">
					<!-- Insert post -->
					<?php require "includes/insert_post.php"; ?>

					<div class="row justify-content-center mb-4">
					<?php 
						global $user_id; // In this case $user_id = $user_id_is (login)

						$records_per_page = 20;
						$current_page = getCurrentPage($records_per_page);

						$query = "SELECT *, u.firstname AS firstname_postby, u.lastname AS lastname_postby, u.username AS username_postby, ";
						$query .= "u1.firstname AS firstname_u1, u1.lastname AS lastname_u1, u1.username AS username_u1, ";
						$query .= "p.id AS post_id, p.created_at AS post_creat FROM posts p ";
						$query .= "JOIN `relationship` r ON p.profile_uid = r.user1_id JOIN users u ON p.user_id = u.id JOIN users u1 ON r.user1_id = u1.id ";
						if(isset($_GET['tagid'])) {
							$tag_id = $_GET['tagid'];
							$query .= "JOIN post_tag_relation ptr ON p.id = ptr.post_id ";
							$query .= "WHERE ptr.tag_id = '$tag_id' AND ";
						} else {
							$query .= "WHERE ";
						}
						$query .= "(r.user1_id = $user_id OR r.user2_id = $user_id) AND r.status = 1 ";
						$query .= "AND p.approved = 1 ORDER BY p.id DESC LIMIT $current_page, $records_per_page";

						$result = mysqli_query($connection, $query);
						confirmResult($result);

						if(mysqli_num_rows($result) > 0) { ?>
							<div class="col-auto">
								<a href="index.php">
									<button type='button' class='btn btn-sm btn-outline-secondary px-3 mx-2'>All</button>
								</a>							
								<?php showButtonsTags(); ?>
								<hr class="my-1">
							</div>
						</div>

					<?php updatePost(); ?>

					<?php insertComment(); ?>

					<?php updateComment(); ?>

					<?php insertOrDeleteLike(); ?>

					<!-- Show Your Posts -->
					<div class="card-columns">
					<?php
						while($row = mysqli_fetch_array($result)) {
							$post_id = $row['post_id'];
							$post_img = $row['image'];
							$post_content = $row['body'];
							$post_created = $row['post_creat'];
							$userid_postby = $row['user_id'];
							$firstname_postby = $row['firstname_postby'];
							$lastname_postby = $row['lastname_postby'];
							$username_postby = $row['username_postby'];
							$user1_id = $row['user1_id'];
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
					?>
					</div>
					<!-- Pagination -->
					<?php 
						$numberOfPostsMyAndFriends = countPostsMyAndFriends();
						$count = ceil($numberOfPostsMyAndFriends / $records_per_page);
						$page = getPage(); 
						$numberOfFriends = countFriends();

						if($count > 1) { ?>
							<div class="row justify-content-center">
								<nav aria-label="...">
								<ul class="pagination pagination-lg">
									<?php
									for ($i = 1; $i <= $count; $i++) : ?>
										<li class="page-item <?= $page == $i || ( !isset($_GET['page']) && $i == 1) ? 'active' : '' ?>">
										<a class="page-link" href='index.php?page=<?= $i; ?>'><?= $i; ?></a>
										</li>
									<?php endfor; ?>
								</ul>
								</nav>
							</div>
						<?php 
						
						} elseif($numberOfFriends == 0) { 
							?>
								<div class="card rounded-top mb-6 shadow">
									<div class="card-body text-center">
										<p class="h4 text-primary mb-0"><u>At first, find new friends!</u></p>
										<p class="description">Then you will see their posts here.</p>
										<a href="friends.php" class="btn btn-sm btn-primary px-4">Find Friends</a>
									</div>
								</div>
							<?php
						} else {
							?>
							<div class="row justify-content-center mb-4">
								<div class="col-auto">
									<a href="index.php">
										<button type='button' class='btn btn-sm btn-outline-secondary px-3 mx-2'>All</button>
									</a>							
									<?php showButtonsTags(); ?>
									<hr class="my-1">
								</div>
							</div>
								<div class="card rounded-top mb-6 shadow">
									<div class="card-body text-center">
										<p class="h4 text-primary mb-0"><u>There are no posts in this tag!</u></p>
										<p class="description mb-0">Choose another one.</p>
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