<?php require "includes/head.php"; ?>

<body>
  <!-- Sidenav -->
  <?php require "includes/sidebar.php"; ?>
  <!-- Main content -->
  <div class="main-content" id="panel">
    <!-- Topnav -->
    <?php require "includes/navigation.php"; ?>
    <!-- Header -->
    <div class="header bg-primary pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">
              <h6 class="h2 text-white d-inline-block mb-0">Add User</h6>
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home"></i></a></li>
                  <li class="breadcrumb-item"><a href="users.php">All Users</a></li>
                </ol>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Page content -->
    <div class="container mt--6">
      <div class="row justify-content-center">
        <!-- Light table -->
        <div class="col-xl-10">
          <div class="card">
            <div class="card-body">
              <div class="row justify-content-between">
                <div class="col-md-6">
                  <h6 class="heading-small text-muted mb-3">User information</h6>
                </div>
                <div class="col-md-6 text-right">
                  <?php
                    if (isset($_POST['create_user'])) {
                      $user_firstname = escapeString($_POST['user_firstname']);
                      $user_lastname = escapeString($_POST['user_lastname']);
                      $user_role = $_POST['user_role'];
                      $username_add = escapeString($_POST['username']);
                      $user_email = escapeString($_POST['user_email']);
                      $user_password = $_POST['user_password'];

                      // error array
                      $error = [
                        'firstname' => '',
                        'lastname' => '',
                        'username' => '',
                        'email' => '',
                        'password' => '',
                      ];

                      if(empty($user_firstname)) {
                        $error['firstname'] = 'First Name cannot be empty';
                      }
                      if(empty($user_lastname)) {
                        $error['lastname'] = 'Last Name cannot be empty';
                      }
                      if(empty($user_email)) {
                        $error['email'] = 'Email cannot be empty';
                      }
                      if(emailExists($user_email)) {
                        $error['email'] = 'Email already exists. <a href="login.php"> Please Login.</a>';
                      }
                      if(strlen($username_add) < 3) {
                        $error['username'] = 'Username must include at least 3 characters!';
                      }
                      if(usernameExists($username_add)) {
                        $error['username'] = 'Username already exists. Pick another one!';
                      }
                      if(!preg_match("/[`'\"~!@# $*()<>,:;{}\|]/", $user_password)) {
                        $error['password'] = "Password must include at least one of these special characters !@#%&£";
                      }
                      if(!preg_match("#[0-9]+#", $user_password)) {
                        $error['password'] = "Password must include at least one number!";
                      }
                      if(!preg_match("#[a-zA-Z]+#", $user_password)) {
                        $error['password'] = "Password must include at least one letter!";
                      }
                      if(strlen($user_password) < 6) {
                        $error['password'] = "Password must include at least 6 characters!";
                      }
                      foreach($error as $key => $value) {
                        if(empty($value)) {
                          unset($error[$key]);
                        }
                      }
                      if(empty($error)) {
                        // call add user function
                        insertUser($user_firstname, $user_lastname, $username_add, $user_email, $user_password, $user_role);
                      }
                    }
                  ?>
                </div>
              </div>
              <form action="" method="post" enctype="multipart/form-data">
                <div class="pl-lg-4">
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group mt-3 mb-0">
                        <label class="form-control-label" for="user_firstname">First name</label>
                        <input type="text" class="form-control" placeholder="First name" name="user_firstname" value="<?= isset($user_firstname) ? $user_firstname : ''; ?>">
                      </div>
											<h5 class="text-lead text-warning"><?= isset($error['firstname']) ? $error['firstname'] : ''; ?></h5>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group mt-3 mb-0">
                        <label class="form-control-label" for="user_lastname">Last name</label>
                        <input type="text" class="form-control" placeholder="Last name" name="user_lastname" value="<?= isset($user_lastname) ? $user_lastname : ''; ?>">
                      </div>
											<h5 class="text-lead text-warning"><?= isset($error['lastname']) ? $error['lastname'] : ''; ?></h5>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group mt-3 mb-0">
                        <label class="form-control-label" for="username">Username</label>
                        <input type="text" class="form-control" placeholder="Username" name="username" value="<?= isset($username_add) ? $username_add : ''; ?>">
                      </div>
											<h5 class="text-lead text-warning"><?= isset($error['username']) ? $error['username'] : ''; ?></h5>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group  mt-3 mb-0">
                        <label class="form-control-label" for="user_email">Email address</label>
                        <input type="email" class="form-control" placeholder="Email" name="user_email" value="<?= isset($user_email) ? $user_email : ''; ?>">
                      </div>
											<h5 class="text-lead text-warning"><?= isset($error['email']) ? $error['email'] : ''; ?></h5>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group mt-3 mb-0">
                        <label class="form-control-label" for="user_password">Password</label>
                        <input type="password" class="form-control" placeholder="Password" name="user_password">
                      </div>
											<h5 class="text-lead text-warning"><?= isset($error['password']) ? $error['password'] : ''; ?></h5>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group mt-3 mb-0">
                        <label class="form-control-label" for="user_role">Role</label>
                        <select name="user_role" class="form-control">
                          <option value="2">Select role</option>
                          <?php showAllRoles(); ?>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row justify-content-end mt-5">
                    <div class="col-lg-3">
                      <div class="form-group">
                        <input type="submit" name="create_user" Value="Create User" class="col btn btn-primary">
                      </div>
                    </div>
                  </div>

                </div>

              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer -->
<?php require "includes/footer.php"; ?>