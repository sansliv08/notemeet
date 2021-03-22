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
		</div>
		<!-- Page content -->
		<div class="container mt--6">
			<div class="row justify-content-center">
				<!-- <div class="col-xl-4 order-xl-2 mt--2">
				</div> -->

				<div class="col-xl-8 order-xl-1 mt--4 mb-5">

                    <?php 
                    if (isset($_GET['id'])) {
                        global $connection;
                        $post_id = $_GET['id'];

                        $result = query("SELECT * FROM posts p WHERE p.id = $post_id");

                        $row = mysqli_fetch_assoc($result);
                            $post_content = $row['body'];
                            $post_img = $row['image'];
                            ?>
                            <div class="card rounded-top shadow">
                                <div class="card-header">
                                    <h5 class="modal-title">Edit Post</h5>
                                </div>
                                <form action="" method="POST" enctype="multipart/form-data">
                                    <div class="card-body text-left">
                                        <div class="media align-items-center mb-3">
                                            <span class="avatar rounded-circle">
                                            <?php showProfilePictureInSession(); ?>
                                            </span>
                                            <div class="media-body ml-3">
                                                <span class="mb-0 font-weight-bold"><?= $fistlastname_s; ?></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <textarea class="form-control" name="post_content" rows="2"><?= $post_content; ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <img alt="" class="card-img rounded-0 mb-2" src="assets/img/users/<?= $username; ?>/posts/<?= $post_img; ?>">
                                            <label class="form-control-label" for="tag_id">Change image</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" name="post_img">
                                                <input type="hidden" name="post_img_current" value="<?= $post_img; ?>">
                                                <label class="custom-file-label" for="post_img">Choose file</label>
                                            </div>
                                        </div>
                                        <div class="form-group mb-0">
                                            <label class="form-control-label" for="tag_id">Change tags</label>
                                            <select multiple name="tag_id[]" class="form-control">
                                                <option class="mb-2" value="-1">Select Tags</option>
                                                <?php
                                                    if(PostExistsInTagRelation($post_id)) {
                                                        $result = query("SELECT * FROM post_tag_relation WHERE post_id = '$post_id'");
                                                        while($row = mysqli_fetch_assoc($result)) {
                                                            $tag_id = $row['tag_id'];
                                                            showTagOption($tag_id);
                                                        }
                                                        showTagsOptionExcept($tag_id);
                                                    } else {
                                                        showAllTagsOptions();
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="card-footer border-0 pt-0 text-right">
                                        <input type="hidden" name="post_id" value="<?= $post_id; ?>">
                                        <input type="submit" class="btn btn-primary" name="update_post" value="Save">
                                    </div>
                                </form>
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
