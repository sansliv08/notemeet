<div class="dropdown position-absolute <?= $level == 0 ? 'ml-3' : 'ml-1'; ?> ">
    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-ellipsis-v"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow bg-white">					
        <?php if($user_id == $_SESSION['user_id']) : ?>
            <a class="dropdown-item" type="button" data-toggle="modal" data-target="#editModalComment" data-id="<?= $comment_id; ?>" data-content="<?= $comment_content; ?>">
                <i class="fas fa-edit"></i>
                <span>Edit</span>
            </a>
            
            <a class="dropdown-item" type="button" data-toggle="modal" data-target="#deleteModalComment" data-id="<?= $comment_id; ?>" data-parentid="<?= $parent_id; ?>">
                <i class="fas fa-trash"></i>
                <span>Delete</span>
            </a>
        <?php elseif(isAdmin() || amI()): ?>
            <a class="dropdown-item" type="button" data-toggle="modal" data-target="#deleteModalComment" data-id="<?= $comment_id; ?>" data-parentid="<?= $parent_id; ?>">
                <i class="fas fa-trash"></i>
                <span>Delete</span>
            </a>
        <?php endif; ?>
    </div>
</div>


<!-- Modal Edit Comment -->
<div class="modal fade" id="editModalComment" tabindex="-1" role="dialog" aria-labelledby="editModalCommentLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="editModalCommentLabel">Edit Comment</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="media align-items-center mb-3">
                <span class="avatar rounded-circle">
                <?php showProfilePictureInSession(); ?>
                </span>
                <div class="media-body ml-3">
                    <span class="mb-0 font-weight-bold"><?= $_SESSION['firstname'] . " " . $_SESSION['lastname']; ?></span>
                </div>
            </div>
            <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group mb-0">
                <textarea class="form-control" id="commentcontent" name="comment_content" rows="2"></textarea>
            </div>
            
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <input type="hidden" id="commentid" name="comment_id">
            <input type="submit" class="btn btn-primary" name="update_comment" value="Save">
        </div>
        </form>
        </div>
    </div>
</div>

<!-- Modal Delete Comment -->
<div class="modal fade" id="deleteModalComment" tabindex="-1" role="dialog" aria-labelledby="deleteModalCommentLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="deleteModalCommentLabel">Delete Comment</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <p class="mb-0">
                Are you sure that you want to delete this comment?
            </p>
        </div>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <input type="hidden" id="commentid" name="comment_id">
                <input type="hidden" id="parentid" name="parent_id">
                <input type="submit" class="btn btn-primary" name="delete_comment" value="Delete">
            </div>
        </form>
        </div>
    </div>
</div>