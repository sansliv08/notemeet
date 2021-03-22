<?php $url_part = getUrlPart(); ?>

<div>
    <div class="position-absolute mt-2" style="right: 1.5rem">
        <p class="mb-0"><small><?php showTagsInProfile($post_id); ?></small></p>
    </div>
    <?php if(!empty($post_img)) :?>
        <img class="card-img rounded-0 border-bottom" src="assets/img/users/<?= $username; ?>/posts/<?= $post_img; ?>" alt="">
    <?php endif; ?>
</div>
<div class="card-body">
    <p class="mt-2 mb-0"><?= $post_content; ?></p>
</div>
<?php 
    if($url_part !== "search.php") {
        ?>
            <div class="card-body border-bottom py-0">
                <div class="row justify-content-between">
                    <div class="col-auto d-flex align-items-center">
                        <!-- LIKES -->
                        <?php $numberOfLikes = countLikes($post_id); ?>
                        <div class="avatar-group d-inline-block">
                            <?php showWhoLikes($post_id); ?>
                        </div>
                        <div class="d-inline-block">
                            <p class="text-gray mb-0 <?= WhoLikesExists($post_id) ? 'ml-2' : ''; ?>" class="btn btn-secondary" data-toggle="tooltip" data-placement="bottom" data-html="true" title="<?php showUsersLiked($post_id); ?>">
                                <small>
                                <?php 
                                    if(!WhoLikesExists($post_id)) {
                                        echo "$numberOfLikes";
                                    } elseif(WhoLikesExists($post_id) && $numberOfLikes == 0) {
                                        echo "";
                                    } elseif(WhoLikesExists($post_id) && $numberOfLikes >= 1) {
                                        echo " + $numberOfLikes";
                                    }
                                    echo $numberOfLikes == 1 ? ' Like' : ' Likes';
                                ?>
                                </small>
                            </p>
                        </div>
                    </div>
                    <div class="col-auto d-flex align-items-center justify-content-end">
                        <!-- COMMENTS -->
                        <?php $numberOfComments = countComments($post_id);?>
                        <?php if($numberOfComments > 0) : ?>
                            <a data-toggle="collapse" href="#showComments<?= $post_id; ?>" role="button" aria-expanded="true" aria-controls="showComments">
                                <p class="text-gray mb-0" class="btn btn-secondary" data-toggle="tooltip" data-placement="bottom" data-html="true" title="<?php showUsersCommented($post_id); ?>">
                                    <small><?= $numberOfComments; ?> <?= $numberOfComments == 1 ? 'Comment' : 'Comments'; ?></small>
                                </p>
                            </a>
                        <?php elseif($numberOfComments == 0) : ?>
                            <p class="text-gray mb-0" class="btn btn-secondary" data-toggle="tooltip" data-placement="bottom" data-html="true" title="<?php showUsersCommented($post_id); ?>">
                                <small><?= $numberOfComments; ?> <?= $numberOfComments == 1 ? 'Comment' : 'Comments'; ?></small>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="row justify-content-between px-3">
                    <!-- Insert a like -->
                    <form action="" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="post_id" value="<?= $post_id; ?>">
                        <input type="hidden" name="receiver_uid" value="<?= $userid_postby; ?>">
                        <button class="btn btn-icon btn-2 btn-secondary btn-sm px-3" type="submit" name="change_like">
                            <span class="btn-inner--icon"><i class="fas fa-thumbs-up mr-2"></i>Like</span>
                        </button>
                    </form>
                    <!-- <button class="btn btn-icon btn-2 btn-secondary btn-sm px-3" type="button">
                        <span class="btn-inner--icon"><i class="fas fa-share mr-2"></i>Share</span>
                    </button> -->
                </div>
            </div>
            <div class="card-footer border-top-0 pt-0">
                <!-- Show comments -->
                <div class="collapse show" id="showComments<?= $post_id; ?>">
                    <?php showComment($post_id); ?>
                </div>

                    <!-- Insert a comment -->
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="row align-items-center">										
                            <div class="col-auto pr-2">
                                <div class="avatar rounded-circle mt-1">
                                    <?php showProfilePictureInSession(); ?>
                                </div>
                            </div>
                            <div class="col pl-0">
                                <div class="input-group">
                                    <input type="hidden" name="post_id" value="<?= $post_id; ?>">
                                    <input type="hidden" name="parent_commentid">
                                    <input type="hidden" name="receiver_uid" value="<?= $userid_postby; ?>">
                                    <input type="text" class="form-control" name="comment_post" placeholder="Write a comment...">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary btn-icon" type="submit" name="insert_comment"><i class="fas fa-paper-plane"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
            </div>
        <?php
    }
?>