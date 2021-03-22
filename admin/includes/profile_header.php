<div class="header pb-6 d-flex align-items-center" style="min-height: 500px; background-image: url('assets/img/users/<?= $username; ?>/cover/<?php showCoverPicture(); ?>'); background-size: cover; background-position: center center;">
	<!-- Mask -->
	<span class="mask bg-gradient-default opacity-3"></span>
	<!-- Header container -->
	<?php if(amI()) : ?>
		<div class="container-fluid d-flex align-items-center">
			<div class="row">
				<div class="col">
					<h1 class="display-2 text-white">Hello <?= $fistlastname_s; ?></h1>
					<p class="text-white mt-0 mb-5">This is your profile page. You can see and edit all your data.</p>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>