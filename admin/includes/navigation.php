<?php $fistlastname_s = userNameInSession(); ?>
<?php $url_part = getUrlPart(); ?>

<nav class="navbar navbar-top navbar-expand navbar-dark bg-primary border-bottom sticky-top">
	<div class="container-fluid">
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<a href="index.php" class="display-2 text-white mr-3"><i class="fab fa-ello"></i></a>
			<!-- Search form -->
			<form action="search.php" method="post" class="navbar-search navbar-search-light form-inline mr-sm-3" id="navbar-search-main">
				<div class="form-group mb-0">
					<div class="input-group input-group-alternative input-group-merge">
						<button type="submit" name="submit_search" class="btn btn-sm input-group-prepend">
							<span class="input-group-text"><i class="fas fa-search"></i></span>
						</button>
						<input type="hidden" name="s_type" value="<?= isset($_POST['submit_search']) && $_POST['s_type'] == "user" ? "user" : "post"; ?>">
						<input class="form-control" placeholder="Search" type="text" name="search" value="<?= isset($_POST['search']) ? $_POST['search'] : "" ; ?>">
					</div>
				</div>
				<button type="button" class="close" data-action="search-close" data-target="#navbar-search-main" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
				<div class="form-group ml-4 mb-0">
					<?php 
						if($url_part == "search.php") {
						?>
							<div class="btn-group btn-group-toggle" data-toggle="buttons">
								<label class="btn btn-secondary btn-sm" for="s_type">
									<input type="radio" name="s_type" value="user" <?= isset($_POST['submit_search']) && $_POST['s_type'] == "user" ? "checked" : ""; ?>> Users
								</label>
								<label class="btn btn-secondary btn-sm active" for="s_type">
									<input type="radio" name="s_type" value="post" <?= isset($_POST['submit_search']) && $_POST['s_type'] == "post" ? "checked" : ""; ?>> Posts
								</label>
							</div>
						<?php
						}
					?>
				</div>
			</form>

			<!-- Navbar links -->
			<ul class="navbar-nav align-items-center  ml-md-auto ">
				<li class="nav-item d-xl-none">
					<!-- Sidenav toggler -->
					<div class="pr-3 sidenav-toggler sidenav-toggler-dark" data-action="sidenav-pin" data-target="#sidenav-main">
						<div class="sidenav-toggler-inner">
							<i class="sidenav-toggler-line"></i>
							<i class="sidenav-toggler-line"></i>
							<i class="sidenav-toggler-line"></i>
						</div>
					</div>
				</li>
				<li class="nav-item d-sm-none">
					<a class="nav-link" href="#" data-action="search-show" data-target="#navbar-search-main">
						<i class="ni ni-zoom-split-in"></i>
					</a>
				</li>
				<!-- Friend Requests -->
				<li class="nav-item dropdown">
						<a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="fas fa-users"></i>
							<?php
							$numberOfRequests = countPendingRequests();
							if(!empty($numberOfRequests)) {
								echo "<span class='badge badge-circle bg-danger position-absolute mt--2 ml--2 font-weight-bolder'>$numberOfRequests</span>";
							}
							?>
						</a>
					<div class="dropdown-menu dropdown-menu-xl  dropdown-menu-right  py-0 overflow-hidden">
						<!-- Dropdown header -->
						<div class="border-bottom px-3 py-2">
							<h6 class="text-sm text-muted m-0">You have <strong class="text-primary"><?= $numberOfRequests; ?></strong> <?= $numberOfRequests == 1 ? 'friend request' : 'friend requests'; ?>.</h6>
						</div>
						<div class="overflow-auto" style="max-height: 435px">
							<?php showRequests(); ?>
						</div>
						<!-- View all -->
						<!-- <a href="#!" class="dropdown-item text-center text-primary font-weight-bold py-3">View all</a> -->
					</div>
				</li>
				<!-- Notifications -->
				<li class="nav-item dropdown">
					<a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="ni ni-bell-55"></i>
						<?php
							$numberOfNotify = countNotifications();
							if(!empty($numberOfNotify)) {
								echo "<span class='badge badge-circle bg-danger position-absolute mt--2 ml--2 font-weight-bolder'>$numberOfNotify</span>";
							}
						?>
					</a>
					<div class="dropdown-menu dropdown-menu-xl dropdown-menu-right py-0 overflow-hidden" >
						<!-- Dropdown header -->
						<div class="border-bottom px-3 py-2">
							<h6 class="text-sm text-muted m-0">You have <strong class="text-primary"><?= $numberOfNotify; ?></strong> <?= $numberOfNotify == 1 ? 'notification' : 'notifications'; ?>.</h6>
						</div>
						<div class="overflow-auto" style="max-height: 435px">
							<?php showNotifications(); ?>
						</div>
						<!-- View all -->
						<!-- <a href="#!" class="dropdown-item text-center text-primary font-weight-bold py-3">View all</a> -->
					</div>
				</li>
			</ul>
			<ul class="navbar-nav align-items-center  ml-auto ml-md-0 ">
				<li class="nav-item dropdown">
					<a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<div class="media align-items-center">
							<span class="avatar avatar-sm rounded-circle">
								<?php showProfilePictureInSession(); ?>
							</span>
							<div class="media-body ml-2 d-none d-lg-block">
								<span class="mb-0 text-sm font-weight-bold"><?= $fistlastname_s; ?></span>
							</div>
						</div>
					</a>
					<div class="dropdown-menu  dropdown-menu-right ">
						<div class="dropdown-header noti-title">
							<h6 class="text-overflow m-0">Welcome!</h6>
						</div>
						<a href="profile.php" class="dropdown-item">
							<i class="ni ni-single-02"></i>
							<span>My profile</span>
						</a>
						<a href="settings.php" class="dropdown-item">
							<i class="ni ni-settings-gear-65"></i>
							<span>Settings</span>
						</a>
						<div class="dropdown-divider"></div>
						<a href="../logout.php" class="dropdown-item">
							<i class="ni ni-user-run"></i>
							<span>Logout</span>
						</a>
					</div>
				</li>
			</ul>
		</div>
	</div>
</nav>