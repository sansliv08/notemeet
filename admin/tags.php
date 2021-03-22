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
              <h6 class="h2 text-white d-inline-block mb-0">All Tags</h6>
            </div>
            <div class="col-md-6 text-md-right">              
              <?php deleteTag(); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Page content -->
    <div class="container-fluid mt--6">
      <div class="row justify-content-center">
        <!-- Light table -->
        <div class="col-md-6 col-sm">
          
          <!-- Insert new Tag -->
          <div class="card">
            <div class="card-body">
              <div class="row justify-content-between">
                <div class="col-6">
                  <h6 class="heading-small text-muted mb-3">Add a Tag</h6>
                </div>
                <div class="col-6 text-right">
                  <?php insertTag(); ?>
                </div>
              </div>
              <form action="" method="post">
                <div class="row align-items-center">
                  <div class="col-lg-9">
                    <div class="form-group mb-2">
                      <input type="text" class="form-control" placeholder="Tag name" name="tag_name">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group mb-2">
                      <input type="submit" name="create_tag" Value="Create" class="col btn btn-primary">
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <!-- Update Tag -->
          <?php updateTag(); ?>

          <!-- Show Tags -->
          <div class="card">
            <!-- Light table -->
            <div class="table-responsive rounded-top">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col" class="sort">Id</th>
                    <th scope="col" class="sort">Tag Title</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody class="list">
                  <?php 
                  $records_per_page = 10;
                  $current_page = getCurrentPage($records_per_page);

                  showTags($current_page, $records_per_page); ?>
                </tbody>
              </table>
            </div>
            <!-- Card footer -->
            <?php 
            $numberOfTags = countTags();
            $count = ceil($numberOfTags / $records_per_page);
            $page = getPage();
            ?>
            <?php if($count > 1) : ?>
              <div class="card-footer py-4">
                <nav aria-label="...">
                  <ul class="pagination pagination-sm justify-content-center mb-0">
                    <?php
                      for ($i = 1; $i <= $count; $i++) : ?>
                        <li class="page-item <?= $page == $i || ( !isset($_GET['page']) && $i == 1) ? 'active' : '' ?>">
                          <a class="page-link" href='tags.php?page=<?= $i; ?>'><?= $i; ?></a>
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