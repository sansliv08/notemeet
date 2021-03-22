<?php $first_part = getUrlPart(); ?>

<div class="nav-wrapper">
	<ul class="nav nav-pills nav-fill flex-column flex-sm-row" id="tabs-icons-text" role="tablist">

		
			<li class="nav-item flex-grow-0">
				<a class="nav-link mb-sm-3 mb-md-0 <?= $first_part=="profile.php" ? "active" : "noactive"; ?>" href="profile.php"><i class="fab fa-ello mr-2"></i>Timeline</a>
			</li>
			<li class="nav-item flex-grow-0">
				<a class="nav-link mb-sm-3 mb-md-0 <?= $first_part=="about.php" ? "active" : "noactive"; ?>" href="about.php"><i class="ni ni-circle-08 mr-2"></i>About</a>
			</li>
			<li class="nav-item flex-grow-0">
				<a class="nav-link mb-sm-3 mb-md-0 <?= $first_part=="friends.php" ? "active" : "noactive"; ?>" href="friends.php"><i class="ni ni-camera-compact mr-2"></i>Friends</a>
			</li>
			<?php if(amI()) : ?>
				<?php if(!profileExists($user_id)) : ?>
					<li class="nav-item flex-grow-0 ml-auto">
						<a class="nav-link mb-sm-3 mb-md-0 active" href="create.php">
							<i class="fas fa-edit mr-2"></i>
							Create Profile
						</a>
					</li>
				<?php else : ?>
				<li class="nav-item flex-grow-0 ml-auto">
					<a class="nav-link mb-sm-3 mb-md-0 <?= $first_part=="edit.php" ? "active" : "noactive"; ?>" href="edit.php">
						<i class="fas fa-edit mr-2"></i>
						Edit Profile
					</a>
				</li>
				<?php endif; ?>
			<?php endif; ?>
		
	</ul>
</div>