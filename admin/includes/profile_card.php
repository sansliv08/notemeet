<div class="card card-profile">
	<div class="row justify-content-center">
		<div class="col-lg-3 order-lg-2">
			<div class="card-profile-image">
				<?php showProfilePicture($user_id, $username); ?>
			</div>
		</div>
	</div>
	<div class="card-header rounded-top text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
		<div class="d-flex justify-content-between">
			<?php 
				if(!amI()) {
					friendsButton($user_id);
				}
			?>
			<!-- FUTURAMENTE -->
			<!-- <a href="#" class="btn btn-sm btn-default float-right">Message</a> -->
		</div>
	</div>
	<div class="card-body pt-0">
		<div class="row">
			<div class="col">
				<div class="card-profile-stats d-flex justify-content-between px-4">
					<div>
						<?php $numberOfFriends = countFriends(); ?>
						<span class="heading"><?= $numberOfFriends; ?></span>
						<span class="description"><?= $numberOfFriends == 1 ? 'Friend' : 'Friends'; ?></span>
					</div>
					<div>
						<?php $numberOfPosts = countPostsOneUser(); ?>
						<span class="heading"><?= $numberOfPosts; ?></span>
						<span class="description"><?= $numberOfPosts == 1 ? 'Post' : 'Posts'; ?></span>
					</div>
				</div>
			</div>
		</div>
		<div class="text-center">
			<h5 class='h3'><?= $user_id == $_SESSION['user_id'] ? $fistlastname_s : $firstname . " " . $lastname; ?></h5>
			<?php showProfileCard(); ?>
		</div>
	</div>
</div>