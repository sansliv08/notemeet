<div class="dropdown">
    <a class="btn btn-sm btn-icon-only text-light mr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-ellipsis-v"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">	
        <a class="dropdown-item text-body" type="button" href="post.php?id=<?= $post_id; ?>">
            <i class="fas fa-eye"></i>
            <span>View Post</span>
        </a>
        <?php if($userid_postby == $_SESSION['user_id']) : ?>
            <!-- <a class="dropdown-item" type="button" data-toggle="modal" data-target="#editModal" data-id="<?= $post_id; ?>" data-img="<?= $post_img; ?>" data-postcontent="<?= $post_content; ?>"> -->
                
            <a class="dropdown-item text-body" type="button" href="edit_post.php?id=<?= $post_id; ?>">
            <i class="fas fa-edit"></i>
                <span>Edit Post</span>
            </a>
            <a class="dropdown-item" type="button" data-toggle="modal" data-target="#deleteModal" data-id="<?= $post_id; ?>" data-img="<?= $post_img; ?>">
                <i class="fas fa-trash"></i>
                <span>Delete Post</span>
            </a>
        <?php elseif(amI() || isAdmin()) : ?>
            <a class="dropdown-item" type="button" data-toggle="modal" data-target="#deleteModal" data-id="<?= $post_id; ?>" data-img="<?= $post_img; ?>">
                <i class="fas fa-trash"></i>
                <span>Delete Post</span>
            </a>
        <?php endif; ?>
    </div>
</div>


<!-- Modal Edit Post  ---- Sem efeito -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Post</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="modal-body text-left">
                    <div class="media align-items-center mb-3">
                        <span class="avatar rounded-circle">
                        <?php showProfilePictureInSession(); ?>
                        </span>
                        <div class="media-body ml-3">
                            <span class="mb-0 font-weight-bold"><?= $_SESSION['firstname'] . " " . $_SESSION['lastname']; ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" id="postcontent" name="post_content" rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <img alt="" class="card-img rounded-0 mb-2" src="assets/img/users/<?= $username; ?>/posts/<?= $post_img; ?>">
                        <label class="form-control-label" for="tag_id">Change image</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="post_img">
                            <input type="hidden" id="postimg" name="post_img_current">
                            <label class="custom-file-label" for="post_img">Choose file</label>
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-control-label" for="tag_id">Change tags</label>
                        <select multiple name="tag_id[]" class="form-control">
                            <option class="mb-2" value="-1">Select Tags</option>
                            <?php showAllTagsOptions(-1); ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <input type="hidden" id="postid" name="post_id">
                    <input type="submit" class="btn btn-primary" name="update_post" value="Save">
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Delete Post -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Post</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-left mb-0">
                    Are you sure that you want to delete this post?
                </p>
            </div>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <input type="hidden" id="postid" name="post_id">
                    <input type="hidden" id="postimg" name="post_img">
                    <input type="hidden" name="sender_id">
                    <input type="submit" class="btn btn-primary" name="delete_post" value="Delete">
                </div>
            </form>
        </div>
    </div>
</div>

