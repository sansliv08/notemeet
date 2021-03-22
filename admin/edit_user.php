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
              <h6 class="h2 text-white d-inline-block mb-0">Edit User</h6>
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

                    if(isset($_GET['edit'])) {
                      $user_id_edit = $_GET['edit'];

                      $result = query("SELECT * FROM users WHERE id='$user_id_edit' LIMIT 1");
                      while ($row = mysqli_fetch_assoc($result)) {
                        $user_firstname = $row['firstname'];
                        $user_lastname = $row['lastname'];
                        $username_get = $row['username'];
                        $user_email_get = $row['email'];
                        $user_password_old = $row['password'];
                        $user_role_id = $row['role_id'];

                        // Update User data by Admin
                        if(isset($_POST['update_user'])) {
                          $user_id = $_POST['user_id'];
                          $new_user_firstname = escapeString($_POST['user_firstname']);
                          $new_user_lastname = escapeString($_POST['user_lastname']);
                          $new_user_roleid = $_POST['user_role'];
                          $new_username = escapeString($_POST['username']);
                          $new_user_email = escapeString($_POST['user_email']);
                          $new_user_password = $_POST['user_password'];

                          // error array
                          $error = [
                            'username' => '',
                            'email' => '',
                            'password' => '',
                          ];
                          if($new_user_email !== $user_email_get) {
                            if(empty($new_user_email)) {
                              $error['email'] = 'Email cannot be empty';
                            }
                            if(emailExists($new_user_email)) {
                              $error['email'] = 'Email already exists.';
                            }
                          }
                          if($new_username !== $username_get) {
                            if(strlen($new_username) < 3) {
                              $error['username'] = 'Username must include at least 3 characters!';
                            }
                            if(usernameExists($new_username)) {
                              $error['username'] = 'Username already exists. Pick another one!';
                            }
                          }
                          if(!empty($new_user_password)) {
                            if(!preg_match("/[`'\"~!@# $*()<>,:;{}\|]/", $new_user_password)) {
                              $error['password'] = "Password must include at least one of these special characters !@#%&£";
                            }
                            if(!preg_match("#[0-9]+#", $new_user_password)) {
                              $error['password'] = "Password must include at least one number!";
                            }
                            if(strlen($new_user_password) < 6) {
                              $error['password'] = "Password must include at least 6 characters!";
                            }
                          } elseif (empty($new_user_password)) {
                            $new_user_password = $user_password_old;
                          }
                          foreach($error as $key => $value) {
                            if(empty($value)) {
                              unset($error[$key]);
                            }
                          }
                          if(empty($error)) {
                            // call update user function
                            updateUserByAdmin($user_id, $new_user_firstname, $new_user_lastname, $new_username, $new_user_email, $new_user_password, $new_user_roleid);
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
                                  <input type="text" class="form-control" placeholder="First name" name="user_firstname" value="<?= isset($new_user_firstname) ? $new_user_firstname : $user_firstname; ?>">
                                </div>
                                <h5 class="text-lead text-warning"><?= isset($error['firstname']) ? $error['firstname'] : ''; ?></h5>
                              </div>
                              <div class="col-lg-6">
                                <div class="form-group mt-3 mb-0">
                                  <label class="form-control-label" for="user_lastname">Last name</label>
                                  <input type="text" class="form-control" placeholder="Last name" name="user_lastname" value="<?= isset($new_user_lastname) ? $new_user_lastname : $user_lastname; ?>">
                                </div>
                                <h5 class="text-lead text-warning"><?= isset($error['lastname']) ? $error['lastname'] : ''; ?></h5>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-6">
                                <div class="form-group mt-3 mb-0">
                                  <label class="form-control-label" for="username">Username</label>
                                  <input type="text" class="form-control" placeholder="Username" name="username" value="<?= isset($new_username) ? $new_username : $username_get; ?>">
                                </div>
                                <h5 class="text-lead text-warning"><?= isset($error['username']) ? $error['username'] : ''; ?></h5>
                              </div>
                              <div class="col-lg-6">
                                <div class="form-group  mt-3 mb-0">
                                  <label class="form-control-label" for="user_email">Email address</label>
                                  <input type="email" class="form-control" placeholder="Email" name="user_email" value="<?= isset($new_user_email) ? $new_user_email : $user_email_get; ?>">
                                </div>
                                <h5 class="text-lead text-warning"><?= isset($error['email']) ? $error['email'] : ''; ?></h5>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-6">
                                <div class="form-group mt-3 mb-0">
                                  <label class="form-control-label" for="user_password">Password</label>
                                  <input type="password" class="form-control" placeholder="Password" name="user_password">
                                  <input hidden="text" name="user_password_old" value="<?= isset($user_password_old) ? $user_password_old : ''; ?>">
                                </div>
                                <h5 class="text-lead text-warning"><?= isset($error['password']) ? $error['password'] : ''; ?></h5>
                              </div>
                              <div class="col-lg-6">
                                <div class="form-group mt-3 mb-0">
                                  <label class="form-control-label" for="user_role">Role</label>
                                  <select name="user_role" class="form-control">
                                    <option>Select role</option>
                                    <?php showAllRoles(isset($new_user_roleid) ? $new_user_roleid : $user_role_id); ?>
                                  </select>
                                </div>
                              </div>
                            </div>
                            <div class="row justify-content-end mt-5">
                              <div class="col-lg-3">
                                <div class="form-group">
                                  <input type="hidden" name="user_id" value="<?= $user_id_edit; ?>">
                                  <input type="submit" name="update_user" Value="Update User" class="col btn btn-primary">
                                </div>
                              </div>
                            </div>
                          </div>

                        </form>
                        <?php
                      }
                    }
                  ?>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer -->
<?php require "includes/footer.php"; ?>