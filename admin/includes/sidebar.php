	<nav class="sidenav navbar navbar-vertical  fixed-left  navbar-expand-xs navbar-light bg-white" id="sidenav-main">
		<div class="scrollbar-inner">
			<!-- Brand -->
			<div class="sidenav-header  d-flex  align-items-center">
				<a class="navbar-brand pr-0" href="index.php">
					<span class="h1 text-primary"><i class="fab fa-ello mr-2"></i>NoteMeet</span>
				</a>
				<div class=" ml-auto ">
					<!-- Sidenav toggler -->
					<div class="sidenav-toggler d-none d-xl-block" data-action="sidenav-unpin" data-target="#sidenav-main">
						<div class="sidenav-toggler-inner">
							<i class="sidenav-toggler-line"></i>
							<i class="sidenav-toggler-line"></i>
							<i class="sidenav-toggler-line"></i>
						</div>
					</div>
				</div>
			</div>
			<div class="navbar-inner">
				<!-- Collapse -->
				<div class="collapse navbar-collapse" id="sidenav-collapse-main">
					<!-- Nav items -->
					<ul class="navbar-nav">
						<li class="nav-item">
							<a class="nav-link" href="index.php" role="button" aria-controls="navbar-dashboards">
								<i class="far fa-newspaper text-primary"></i>
								<span class="nav-link-text">News Feed</span>
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="profile.php" role="button" aria-controls="navbar-dashboards">
								<i class="fas fa-portrait text-success"></i>
								<span class="nav-link-text">My Profile</span>
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="friends.php" role="button" aria-controls="navbar-dashboards">
								<i class="fas fa-user-friends text-warning"></i>
								<span class="nav-link-text">My Friends</span>
							</a>
						</li>

						<?php if(isAdmin()): ?>

							<hr class="my-4">
							<h6 class="navbar-heading pl-4 text-muted">
								<span class="docs-normal">Management</span>
							</h6>
							<li class="nav-item">
								<a class="nav-link" href="admin.php" role="button" aria-controls="navbar-dashboards">
									<i class="fas fa-tachometer-alt text-default"></i>
									<span class="nav-link-text">Dashboard</span>
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#navbar-users" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-examples">
									<i class="fas fa-user text-info"></i>
									<span class="nav-link-text">Users</span>
								</a>
								<div class="collapse" id="navbar-users">
									<ul class="nav nav-sm flex-column">
										<li class="nav-item">
											<a href="users.php" class="nav-link">
												<span class="sidenav-mini-icon"> ALL </span>
												<span class="sidenav-normal"> All Users </span>
											</a>
										</li>
										<li class="nav-item">
											<a href="add_user.php" class="nav-link">
												<span class="sidenav-mini-icon"> + 1 </span>
												<span class="sidenav-normal"> Add User </span>
											</a>
										</li>
									</ul>
								</div>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="tags.php" role="button" aria-controls="navbar-dashboards">
									<i class="fas fa-tags text-gray"></i>
									<span class="nav-link-text">Tags</span>
								</a>
							</li>
						<?php endif; ?>
						<!-- <li class="nav-item">
							<a class="nav-link" href="https://demos.creative-tim.com/impact-design-system-pro/docs/getting-started/quick-start/">
								<i class="ni ni-spaceship"></i>
								<span class="nav-link-text">Getting started</span>
							</a>
						</li> -->
						<li class="nav-item">
							<a class="nav-link" href="https://demos.creative-tim.com/impact-design-system-pro/docs/dashboard/alerts/">
								<i class="ni ni-ui-04"></i>
								<span class="nav-link-text">Components</span>
							</a>
						</li>
						<!-- <li class="nav-item">
							<a class="nav-link" href="https://demos.creative-tim.com/impact-design-system-pro/docs/plugins/charts/">
								<i class="ni ni-chart-pie-35"></i>
								<span class="nav-link-text">Plugins</span>
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link active active-pro" href="https://www.creative-tim.com/product/impact-design-system-pro" target="_blank">
								<i class="ni ni-send text-primary"></i>
								<span class="nav-link-text">Upgrade to PRO</span>
							</a>
						</li>  -->
					</ul>
				</div>
			</div>
		</div>
	</nav>