<?php require "includes/head.php"; ?>

<body>
  <!-- Sidenav -->
  <?php require "includes/sidebar.php"; ?>
  <!-- Main content -->
  <div class="main-content" id="panel">
    <!-- Topnav -->
    <?php require "includes/navigation.php"; ?>
    <!-- Header -->
    <!-- Header -->
    <div class="header bg-primary pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-md-6">
              <h6 class="h2 text-white d-inline-block mb-0">All Users</h6>
              <a href="add_user.php" class="btn btn-sm btn-neutral ml-4">Add User</a>
            </div>
            <div class="col-md-6 text-md-right">
              <!-- Delete a User -->
              <?php deleteUser(); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Page content -->
    <div class="container-fluid mt--6">
      <div class="row">
        <!-- Light table -->
        <div class="col">
          <div class="card">
            <!-- Light table -->
            <div class="table-responsive rounded-top">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col" class="sort">Id</th>
                    <th scope="col" class="sort">Foto</th>
                    <th scope="col" class="sort">Username</th>
                    <th scope="col" class="sort">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col" class="sort">Role</th>
                    <th scope="col" class="sort">First Login</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody class="list">
                  <?php 
                  $records_per_page = 5;
                  $current_page = getCurrentPage($records_per_page);

                  showAllUsers($current_page, $records_per_page); ?>
                </tbody>
              </table>
            </div>
            <!-- Card footer -->
            <?php 
            $numberOfUsers = countUsers();
            $count = ceil($numberOfUsers / $records_per_page);
            $page = getPage();
            ?>
            <?php if($count > 1) : ?>
              <div class="card-footer py-4">
                <nav aria-label="...">
                  <ul class="pagination pagination-sm justify-content-end mb-0">
                    <?php
                      for ($i = 1; $i <= $count; $i++) : ?>
                        <li class="page-item <?= $page == $i || ( !isset($_GET['page']) && $i == 1) ? 'active' : '' ?>">
                          <a class="page-link" href='users.php?page=<?= $i; ?>'><?= $i; ?></a>
                        </li>
                      <?php endfor; ?>
                  </ul>
                </nav>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <!-- Footer -->
<?php require "includes/footer.php"; ?>