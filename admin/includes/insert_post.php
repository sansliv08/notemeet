<div class="card rounded-top shadow">
    <div class="card-body">
        <?php insertPost(); ?>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-auto">
                    <a href="profile.php" class="avatar avatar-lg rounded-circle">
                        <?php showProfilePictureInSession(); ?>
                    </a>
                </div>
                <div class="col pl-0">
                    <textarea name="post_content" class="form-control" placeholder="<?= $user_id !== $_SESSION['user_id'] ? "Create a post to your friend" : "Create your post"; ?>" cols="30" rows="2"></textarea>
                </div>
            </div>

            <div class="ml-md-6 bg-secondary rounded mt-3 px-4 py-3">
                <h6 class="heading-small text-muted mb-3">If you want add to your post</h6>
                <div class="row justify-content-between">
                    <div class="col-sm mb-2">
                        <label class="form-control-label" for="post_img">Image</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="post_img">
                            <label class="custom-file-label mb-0" for="post_img">Choose file</label>
                        </div>
                    </div>
                    <div class="col-sm mb-2">
                        <div class="form-group mb-0">
                            <label class="form-control-label" for="tag_id">Tags</label>
                            <select multiple name="tag_id[]" class="form-control" size="1">
                                <option class="mb-2" value="-1">Select Tags</option>
                                <?php showAllTagsOptions(); ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-end">
                <div class="col-auto mt-3">
                    <input type="submit" class="btn btn-primary" name="insert_post" value="Post">
                </div>
            </div>

        </form>
    </div>
</div>