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

				<div class="col-xl-8 order-xl-1 mt--4 mb-5">

					<?php updatePost(); ?>

					<?php insertComment(); ?>

					<?php updateComment(); ?>

					<?php insertOrDeleteLike(); ?>

					<!-- Show Your Posts -->
					<?php
                    global $user_id;

                    if (isset($_GET['id'])) {
                        $post_id = $_GET['id'];

                        $result = query("SELECT *, u.firstname AS firstname_postby, u.lastname AS lastname_postby, u.username AS username_postby, u1.firstname AS firstname_u1, u1.lastname AS lastname_u1, u1.username AS username_u1, p.id AS post_id, p.created_at AS post_creat FROM posts p JOIN `relationship` r ON p.profile_uid = r.user1_id JOIN users u ON p.user_id = u.id JOIN users u1 ON r.user1_id = u1.id WHERE p.id = $post_id AND p.approved = 1 LIMIT 1");

                        while($row = mysqli_fetch_assoc($result)) {
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

                            if(mysqli_num_rows($result) > 0) {
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
					?>
				</div>
			</div>

<!-- Footer -->
<?php require "includes/footer.php"; ?>

<!-- Scripts of Modals -->
<?php require "includes/scripts.php"; ?>