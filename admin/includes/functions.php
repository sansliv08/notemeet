<?php

function redirect($location) {
	header("Location: " . $location);
}

function confirmResult($result){
	global $connection;
	if(!$result) {
		die("Query Failed: " . mysqli_error($connection));
	}
}

function escapeString($string) {
	global $connection;
	return mysqli_real_escape_string($connection, trim($string));
}

function query($query) {
	global $connection;
	$result = mysqli_query($connection, $query);
	confirmResult($result);
	return $result;
}

// URLs
function getUrlPart() {
	// components[3] = profile.php
	$directoryURI = $_SERVER['REQUEST_URI'];
	$path = parse_url($directoryURI, PHP_URL_PATH);
	$components = explode('/', $path);
	global $url_part;
	$url_part = $components[3];

	return $url_part;
}

function paramUrl() {
	$query = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
	parse_str($query, $result);

	return http_build_query($result);
}

// SHOW DATETIME - AGO
function pluralize($count, $text) {
	return $count . ( ( $count == 1 ) ? ( " $text" ) : ( " ${text}s" ) );
}

function ago($datetime) {
	date_default_timezone_set("Europe/London");
	$date = new DateTime($datetime);
	$now = new DateTime('now');
	$interval =  $now->diff($date);
	$suffix = ($interval->invert ? 'ago' : '');
	if ( $v = $interval->y >= 1 ) {
		return pluralize( $interval->y, 'year' ) . " " . $suffix;
	}
    if ( $v = $interval->m >= 1 ) {
		return pluralize( $interval->m, 'month' ) . " " . $suffix;
	}
    if ( $v = $interval->d >= 1 ) {
		return pluralize( $interval->d, 'day' ) . " " . $suffix;
	}
    if ( $v = $interval->h >= 1 ) {
		return pluralize( $interval->h, 'hour' ) . " " . $suffix;
	}
    if ( $v = $interval->i >= 1 )  {
		return pluralize( $interval->i, 'minute' ) . " " . $suffix;
	}
	return pluralize( $interval->s, 'second' ) . " " . $suffix;
}

// USERS
function showAllUsers($current_page, $records_per_page) {

	$result= query("SELECT *, u.id AS `user_id`, u.created_at AS firstlogin FROM users u JOIN `role` r ON u.role_id = r.id LEFT JOIN `profile` p ON u.id = p.user_id ORDER BY u.id DESC LIMIT $current_page, $records_per_page");

	while($row = mysqli_fetch_assoc($result)) {
		$user_id = $row['user_id'];
		$username = $row['username'];
		$user_firstname = $row['firstname'];
		$user_lastname = $row['lastname'];
		$user_email = $row['email'];
		$user_role = ucwords($row['role']);
		$user_firstlogin = $row['firstlogin'];
		$user_profilepic = $row['profilepic'];

		echo "<tr>";
		echo "<td>$user_id</td>";
		echo "<td><a href='profile.php?id=$user_id' class='avatar avatar-sm rounded-circle'>";
		if(!empty($user_profilepic)) {
			echo "<img alt='' src='assets/img/users/$username/profile/$user_profilepic'>";
		} else {
			echo "<img src='assets/img/users/_default/user.png' alt='' class='img-fluid'>";
		}
		echo "</a></td>";
		echo "<td>$username</td>";
		echo "<td>$user_firstname $user_lastname</td>";
		echo "<td>$user_email</td>";
		echo "<td>$user_role</td>";
		echo "<td>$user_firstlogin</td>";
		echo "<td><a href='users.php?delete=$user_id' class='btn btn-sm btn-danger rounded-circle'><i class='fas fa-trash'></i></a></td>";
		echo "<td><a href='edit_user.php?edit=$user_id' class='btn btn-sm btn-info rounded-circle'><i class='fas fa-pen'></i></a></td>";
		echo "</tr>";
	}
}

function showAllRoles($id = -1) {
    $result = query("SELECT * FROM `role`");

    while($row = mysqli_fetch_assoc($result)) {
        $role_id = $row['id'];
        $role_name = ucwords($row['role']);

        if ($role_id == $id) {
            echo "<option selected value='$role_id'>$role_name</option>";
        } else {
            echo "<option value='$role_id'>$role_name</option>";
        }
    }
}

function insertUser($user_firstname, $user_lastname, $username, $user_email, $user_password, $user_role) {
	$password_protected = password_hash($user_password, PASSWORD_ARGON2I);

	query("INSERT INTO users (firstname, lastname, username, email, password, role_id, created_at) VALUES ('$user_firstname', '$user_lastname', '$username', '$user_email', '$password_protected', '$user_role', now())");

	echo "<p class='mb-3 font-weight-bold'>User Created! <a href='users.php' class='btn btn-sm btn-primary ml-3'>View all users</a></p>";
}

function deleteUser() {
	if (isset($_GET['delete'])) {
		$user_id_delete = $_GET['delete'];

		query("DELETE FROM users WHERE id = $user_id_delete");

		//redirect("users.php");
		echo "<p class='text-white font-weight-bold mb-0'>User has been delected!</p>";
	}
}
function deleteAccount() {
	if(isset($_GET['deleteaccount'])) {
		$user_id_delete = $_GET['deleteaccount'];

		query("DELETE FROM users WHERE id = $user_id_delete");

		session_destroy();
		redirect("../login.php");
	}
}

// by admin
function updateUserByAdmin($user_id, $new_user_firstname, $new_user_lastname, $new_username, $new_user_email, $new_user_password, $new_user_roleid) {

	$password_protected = password_hash($new_user_password, PASSWORD_ARGON2I);

	query("UPDATE users SET firstname='$new_user_firstname', lastname='$new_user_lastname', username= '$new_username', email='$new_user_email', `password` = '$password_protected', role_id = '$new_user_roleid', updated_at = now() WHERE id = '$user_id'");
	
	echo "<p class='mb-4 font-weight-bold'>User data updated with success!</p>";
	// redirect("users.php");
}

// by a member - can not change username and role
function updateUserData($user_id, $new_user_firstname, $new_user_lastname, $new_user_email) {
	query("UPDATE users SET firstname='$new_user_firstname', lastname='$new_user_lastname', email='$new_user_email', updated_at = now() WHERE id = $user_id");
	
	echo "<p class='mb-0 font-weight-bold'>User data updated with success!</p>";
	// redirect("profile.php");
}

function changePassword($new_password) {
	$user_id = $_SESSION['user_id'];

	// reset the password
	$password_protected = password_hash($new_password, PASSWORD_ARGON2I);

	query("UPDATE users SET `password` = '$password_protected', updated_at = now() WHERE id = '$user_id'");

	echo "Password changed!";
				
}


// AUTENTICATION
function user_Login() {
	if (isset($_POST['user_login'])) {

		$username = escapeString($_POST['username']);
		$password = $_POST['password'];

		$result = query("SELECT * FROM users WHERE username = '$username' LIMIT 1");

		if (mysqli_num_rows($result) !=0) {
			while ($row = mysqli_fetch_assoc($result)) {
				$db_password = $row['password'];
				$db_user_firstname = $row['firstname'];
				$db_user_lastname = $row['lastname'];
				$db_username = $row['username'];
				$db_user_id = $row['id'];
				$db_user_role = $row['role_id'];

				if (password_verify($password, $db_password)) {
					$_SESSION['firstname'] = $db_user_firstname;
					$_SESSION['lastname'] = $db_user_lastname;
					$_SESSION['username'] = $db_username;
					$_SESSION['user_id'] = $db_user_id;
					$_SESSION['user_role'] = $db_user_role;
					$_SESSION['loggedin'] = true;

					redirect("admin");

				} else {
					echo "Wrong credentials!";
				}
			}
		} else {
			echo "User not found";
		}
	}
}

function isLoggedIn() {
	if (isset($_SESSION['loggedin'])) {
		global $user_id;
		$user_id = $_SESSION['user_id'];
		global $firstname;
		$firstname = $_SESSION['firstname'];
		global $lastname;
		$lastname = $_SESSION['lastname'];
		global $username;
		$username = $_SESSION['username'];
		query("UPDATE users SET lastlogin = now() WHERE id = $user_id");
		return true;
	} 
	return false;
}

function userNameInSession() {
	$user_id_s = $_SESSION['user_id'];
	$result = query("SELECT firstname, lastname FROM users WHERE id = '$user_id_s' LIMIT 1");
	while($row = mysqli_fetch_assoc($result)) {
		$firstname = $row['firstname'];
		$lastname = $row['lastname'];

		$fistlastname_s = $firstname . " " . $lastname;
	}
	return $fistlastname_s;
}

function isAdmin() {
	if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === '1') {
		return true;
	}
	return false;
}

function emailExists($email) {
	$result = query("SELECT email from users WHERE email = '$email' LIMIT 1");

	if (mysqli_num_rows($result) > 0) {
		return true;
	}
	return false;
}

function usernameExists($username) {
	$result = query("SELECT username from users WHERE username = '$username' LIMIT 1");

	if (mysqli_num_rows($result) > 0) {
		return true;
	}
	return false;
}

function register_user($firstname, $lastname, $username, $user_email, $user_password) {
	global $connection;
	$protected_password = password_hash($user_password, PASSWORD_ARGON2I);

	$query = "INSERT INTO users (firstname, lastname, username, email, password, role_id, created_at) ";
	$query .= "VALUES (?, ?, ?, ?, ?, '3', now())";

	$stmt = mysqli_prepare($connection, $query);
	mysqli_stmt_bind_param($stmt, "sssss", $firstname, $lastname, $username, $user_email, $protected_password);
	mysqli_stmt_execute($stmt);
	confirmResult($stmt);
	mysqli_stmt_close($stmt);

	echo "User created!";
}

// Get User ID
function getUserId() {
	global $user_id;
	$user_id = $_SESSION['user_id'];
	global $firstname;
	$firstname = $_SESSION['firstname'];
	global $lastname;
	$lastname = $_SESSION['lastname'];
	global $username;
	$username = $_SESSION['username'];
	
	if (isset($_GET['id'])) {
		global $user_id;
		$user_id = $_GET['id'];

		$result = query("SELECT * FROM users WHERE id = '$user_id' LIMIT  1");
		while($row = mysqli_fetch_assoc($result)) {
		$firstname = $row['firstname'];
		$lastname = $row['lastname'];
		$username = $row['username'];
		}
	}
}

// Show Profile
function showProfileCard() {
	global $user_id;
	$result = query("SELECT * FROM `profile` p JOIN `location` l ON p.pk_location = l.id JOIN country coun ON l.pk_countryid = coun.id JOIN city c ON l.pk_cityid = c.id WHERE p.user_id=$user_id");

	while($row = mysqli_fetch_assoc($result)) {
		$user_city = $row['city'];
		$user_country = $row['country'];

		echo "<div class='h5 font-weight-300'><i class='ni location_pin mr-2'></i>$user_city, $user_country</div>";
	}
}

function userExistsInEducation($user_id) {
	$result = query("SELECT * from user_education WHERE user_id = '$user_id' LIMIT 1");

	if (mysqli_num_rows($result) > 0) {
		return true;
	}
	return false;
}

function showBasicInfo($user_id) {
	$result = query("SELECT * FROM `profile` p JOIN users u ON p.user_id = u.id WHERE p.user_id = $user_id"); 
	if(mysqli_num_rows($result) > 0 ) {
		while($row = mysqli_fetch_assoc($result)) {
			$user_aboutme = $row['aboutme'];
			$user_gender = $row['gender'];
			$user_birth = $row['birthdate'];
			?>
				<div class="pl-lg-4">
					<div class="row">
						<div class="col">
							<?php
								if(!empty($user_aboutme)) {
									echo "<label class='form-control-label'>About Me</label>";
									echo "<p class='description'>$user_aboutme</p>";
								} else {
									echo "<p class='description'><u>Edit your profile</u> and write some details about you.</p>";
								}
							?>
						</div>
					</div>
				</div>
				
				<hr class="my-4" />

				<h6 class="heading-small text-muted mb-4">Basic Info</h6>
				<div class="pl-lg-4">
					<?php
						if($user_gender != 0 || $user_birth != 000-00-00) { 
						?>
							<div class="row">
								<div class="col-lg-6">
									<label class="form-control-label">Gender</label>
									<?php
										if($user_gender == 1) {
											echo "<p class='description text-capitalize'><i class='fas fa-female text-muted mr-3'></i>Female</p>";
										} elseif($user_gender == 2) {
											echo "<p class='description text-capitalize'><i class='fas fa-male text-muted mr-3'></i>Male</p>";
										}
									?>
								</div>
								<div class="col-lg-6">
									<label class="form-control-label">Brithday of Date</label>
									<p class="description"><i class="fas fa-birthday-cake text-muted mr-3"></i><?= $user_birth; ?></p>
								</div>
							</div>
						<?php
						} else {
							echo "<p class='description'><u>Edit your profile</u> and add your gender and brithday date.</p>";
						}
					?>
				</div>
			<?php
		}
	}
}

function showEducation($user_id) {
	global $connection;

	if (userExistsInEducation($user_id)) {
		$query = "SELECT *, cour.name AS coursename, s.name AS schoolname FROM user_education ue ";
		$query .= "JOIN course cour ON ue.course_id = cour.id ";
		$query .= "JOIN school s ON cour.school_id = s.id LEFT JOIN coursedetails cd ON cour.cd_id = cd.id ";
		$query .= "WHERE ue.user_id = $user_id";

		$result = mysqli_query($connection, $query);
		confirmResult($result);

		if (mysqli_num_rows($result) > 0 ) {
			while($row = mysqli_fetch_assoc($result)) {
			$course = $row['coursename'];
			$school = $row['schoolname'];
			$sdate = $row['startdate'];
			$fdate = $row['finishdate'];
			$cd_id = $row['cd_id'];
			$course_description = $row['description'];
			?>
				<div class="pl-lg-4">
					<div class="row">
						<div class="col-lg-6">
							<label class="form-control-label">Course</label>
							<p class="description mb-0"><i class="fas fa-graduation-cap text-muted mr-2"></i> <?= $course; ?></p>
							<p class="description"><i class="fas fa-university text-muted mr-3"></i>Studied at <strong><?= $school; ?></strong></p>
						</div>
						<div class="col-lg-6">
							<label class="form-control-label"></label>
							<?php
								if(!$cd_id == 0 && !($sdate == "0000" || $fdate == "0000")) { 
									echo "<p class='description mb-0 mt-1'><i class='fas fa-calendar-alt text-muted mr-3'></i>$sdate - $fdate</p>";
								} elseif($sdate !== "0000") {
									echo "<p class='description mb-0 mt-1'><i class='fas fa-calendar-alt text-muted mr-3'></i>Started at $sdate</p>";
								} elseif ($fdate !== "0000") {
									echo "<p class='description mb-0 mt-1'><i class='fas fa-calendar-alt text-muted mr-3'></i>Finished at $fdate</p>";
								}
							?>
							<p class="description pl-4"> <?= $course_description; ?></p>
						</div>
					</div>
				</div>
			<?php
			}
		}
	} else {
		echo "<p class='description'><u>Edit your profile</u> and add information about your education.</p>";
	}
}

function showLocation($user_id) {
	global $connection;

	$query = "SELECT * FROM `profile` p JOIN users u ON p.user_id = u.id ";
	$query .= "LEFT JOIN `location` l ON p.pk_location = l.id JOIN country coun ON l.pk_countryid = coun.id JOIN city c ON l.pk_cityid = c.id ";
	$query .= "WHERE p.user_id = $user_id";

	$result = mysqli_query($connection, $query);
	confirmResult($result);

	if(mysqli_num_rows($result) > 0 ) {

		while($row = mysqli_fetch_assoc($result)) {
			$user_email = $row['email'];

			$location_id = $row['pk_location'];
			$user_city = $row['city'];
			$user_country = $row['country'];
			
			if(!empty($location_id)) {
				?>
					<div class="pl-lg-4">
						<div class="row">
							<div class="col-lg-6">
								<label class="form-control-label">Email</label>
								<p class="description"><i class="fas fa-envelope text-muted mr-3"></i><?= $user_email; ?></p>
							</div>
							<div class="col-lg-6">
								<label class="form-control-label">Address</label>
								<p class="description"><i class="fas fa-map-marker-alt  text-muted mr-3"></i><?= $user_city . ", ". $user_country; ?></p>
							</div>
						</div>
					</div>
				<?php
			} 
		}
	} else {
		echo "<p class='description'><u>Edit your profile</u> and add your address.</p>";
	}
}

function profileExists($user_id) {
	$result = query("SELECT user_id from `profile` WHERE user_id = '$user_id'");
	if(mysqli_num_rows($result) > 0) {
		return true;
	}
	return false;
}

function amI() {
	global $user_id;
	$user_id_s = $_SESSION['user_id'];
	if($user_id_s == $user_id) {
		return true;
	}
	return false;
}

function showProfilePictureInSession() {
	$user_id_s = $_SESSION['user_id'];
	$username_s = $_SESSION['username'];
	
	if(!profileExists($user_id_s)) {
		echo "<img alt='' class='rounded-circle' src='assets/img/users/_default/user.png'>";
	} else {
		$result = query("SELECT profilepic FROM `profile` WHERE user_id = '$user_id_s' LIMIT 1");
			while($row = mysqli_fetch_assoc($result)) {
			$user_profilepic = $row['profilepic'];

				if(!empty($user_profilepic)) { 
					echo "<img alt='' class='rounded-circle' src='assets/img/users/$username_s/profile/$user_profilepic'>";
				} 
				else {
					echo "<img alt='' class='rounded-circle' src='assets/img/users/_default/user.png'>";
				}
			}
	}
}

function showProfilePicture($user_id, $username) {
	if(!profileExists($user_id)) {
		echo "<img alt='' class='rounded-circle' src='assets/img/users/_default/user.png'>";
	} else {

		$result = query("SELECT profilepic FROM `profile` WHERE user_id = '$user_id' LIMIT 1");
			while($row = mysqli_fetch_assoc($result)) {
			$user_profilepic = $row['profilepic'];

				if(!empty($user_profilepic)) { 
					echo "<img alt='' class='rounded-circle' src='assets/img/users/$username/profile/$user_profilepic'>";
				} 
				else {
					echo "<img alt='' class='rounded-circle' src='assets/img/users/_default/user.png'>";
				}
			}
	}
}

function showCoverPicture() {
	global $user_id;
	$result = query("SELECT coverpic FROM `profile` WHERE user_id='$user_id' LIMIT 1");

	while($row = mysqli_fetch_assoc($result)) {
    $user_coverpic = $row['coverpic'];

		echo $user_coverpic;
	}
}

function showAllOptionCountries($id = -1) {

	$result = query("SELECT * FROM country");

    while($row = mysqli_fetch_assoc($result)) {
        $country_id = $row['id'];
        $country_name = $row['country'];

        if ($country_id == $id) {
            echo "<option selected value='$country_id'>$country_name</option>";
        }else{
            echo "<option value='$country_id'>$country_name</option>";
        }
    }
}

// Edit Profile

function cityExists($user_city) {
	$result = query("SELECT city from city WHERE city = '$user_city' LIMIT 1");

	if(mysqli_num_rows($result) > 0) {
		return true;
	}
	return false;
}

function insertUserProfile() {
	global $connection; 
	$user_id = $_SESSION['user_id'];
	$username = $_SESSION['username'];

	if (isset($_POST['insert_profile'])) {
		// basic info
		$user_birth = date('Y-m-d', strtotime(escapeString($_POST['user_birth'])));
		$user_gender = $_POST['user_gender'];
		$user_aboutme = escapeString($_POST['user_aboutme']);

		$user_profilepic = $_FILES['user_profilepic']['name'];
		$user_profilepic_temp = $_FILES['user_profilepic']['tmp_name'];
		$user_coverpic = $_FILES['user_coverpic']['name'];
		$user_coverpic_temp = $_FILES['user_coverpic']['tmp_name'];

		$path_profilepic = "./assets/img/users/$username/profile";
		if(!mkdir($path_profilepic, 0777, true)) {
			die('Failed to create folders...');
		}
		$path_coverpic = "./assets/img/users/$username/cover";
		if(!mkdir($path_coverpic, 0777, true)) {
			die('Failed to create folders...');
		}
		move_uploaded_file($user_profilepic_temp, "$path_profilepic/$user_profilepic");
		move_uploaded_file($user_coverpic_temp, "$path_coverpic/$user_coverpic");

		// address
		$user_country_id = $_POST['user_country_id'];
		$user_city = escapeString($_POST['user_city']);

		if(!cityExists($user_city)) {
			query("INSERT INTO city (city) VALUES ('$user_city')");
		}		

		$result = query("SELECT * FROM `location` WHERE pk_countryid = '$user_country_id' AND pk_cityid = (SELECT id FROM city WHERE city='$user_city')");
		if(!mysqli_num_rows($result) > 0) {
			query("INSERT INTO `location` (pk_cityid, pk_countryid) VALUES ((SELECT id FROM city WHERE city='$user_city' LIMIT 1), '$user_country_id')");
		}

		$query = "INSERT INTO `profile` (birthdate, gender, aboutme, profilepic, coverpic, created_at, user_id, pk_location) ";
		$query .= "VALUES ('$user_birth', '$user_gender', '$user_aboutme', '$user_profilepic', '$user_coverpic', now(), '$user_id', ";
		$query .= "(SELECT id FROM `location` WHERE pk_countryid = '$user_country_id' AND pk_cityid = (SELECT id FROM city WHERE city='$user_city' LIMIT 1)))";

		$result = mysqli_query($connection, $query);
		confirmResult($result);

		echo "<p class='font-weight-bold'>Basic and Address Info Created! <a href='about.php' class='btn btn-sm btn-primary ml-3'>View your profile</a></p>";
	}
}

function schoolExists($school_name) {
	$result = query("SELECT `name` from school WHERE `name` = '$school_name' LIMIT 1");

	if(mysqli_num_rows($result) > 0) {
		return true;
	}
	return false;
}

function insertEducation() {
	global $connection;
	$user_id = $_SESSION['user_id'];

	if (isset($_POST['insert_education'])) {
		$course_name = escapeString($_POST['user_course']);
		$school_name = escapeString($_POST['course_school']);
		$course_sd = escapeString($_POST['course_sd']);
		$course_fd = escapeString($_POST['course_fd']);
		$course_descrip = escapeString($_POST['course_descrip']);

		if(!schoolExists($school_name)) {
			query("INSERT INTO school (`name`) VALUE ('$school_name')");
		}

		query("INSERT INTO course (`name`, school_id) VALUES ('$course_name', (SELECT id FROM school WHERE `name` ='$school_name' LIMIT 1))");

		$course_id = mysqli_insert_id($connection);

		if(!empty($course_sd || $course_fd || $course_descrip)) {
			query("INSERT INTO coursedetails (startdate, finishdate, `description`) VALUES ('$course_sd', '$course_fd', '$course_descrip')");

			$cd_id = mysqli_insert_id($connection);

			query("UPDATE course SET cd_id = '$cd_id' WHERE id = '$course_id'");
		}

		query("INSERT INTO user_education (user_id, course_id) VALUES ('$user_id', '$course_id')");

		echo "<p class='font-weight-bold'>Education Into Created! <a href='about.php' class='btn btn-sm btn-primary ml-3 px-4'>View your profile</a></p>";
	}
}


// UPDATE PROFILE

function updateProfile() {
	global $connection;
	$user_id = $_SESSION['user_id'];
	$username = $_SESSION['username'];

	if(isset($_POST['update_profile'])) {
		$new_user_profilepic = $_FILES['user_profilepic']['name'];
		$new_user_profilepic_temp = $_FILES['user_profilepic']['tmp_name'];
		$new_user_profilepic_current = $_POST['user_profilepic_current'];

		$new_user_coverpic = $_FILES['user_coverpic']['name'];
		$new_user_coverpic_temp = $_FILES['user_coverpic']['tmp_name'];
		$new_user_coverpic_current = $_POST['user_coverpic_current'];

		$new_user_birth = date('Y-m-d', strtotime(escapeString($_POST['user_birth'])));
		$new_user_gender = $_POST['user_gender'];
		$new_user_aboutme = escapeString($_POST['user_aboutme']);

		$new_user_country_id = $_POST['user_country_id'];
		$user_city = escapeString($_POST['user_city']);

		// profile and education join
		$query = "UPDATE `profile` p SET ";
		$query .= "p.birthdate = '$new_user_birth', p.gender = '$new_user_gender', p.aboutme = '$new_user_aboutme', ";
		
		if(!empty($new_user_profilepic)) {
			if(file_exists("./assets/img/users/$username/profile/" . $new_user_profilepic_current)) {
				unlink("./assets/img/users/$username/profile/" . $new_user_profilepic_current);
			}
			move_uploaded_file($new_user_profilepic_temp, "./assets/img/users/$username/profile/$new_user_profilepic");
			$query.= "p.profilepic = '$new_user_profilepic', ";
		}

		if(!empty($new_user_coverpic)) {
			if(file_exists("./assets/img/users/$username/cover/" . $new_user_coverpic_current)) {
				unlink("./assets/img/users/$username/cover/" . $new_user_coverpic_current);
			}
			move_uploaded_file($new_user_coverpic_temp, "./assets/img/users/$username/cover/$new_user_coverpic");
			$query .= "p.coverpic = '$new_user_coverpic', ";
		}

		$query .= "p.updated_at = now(), ";
		
		if(!empty($new_user_country_id || $user_city)) {
			// address
			if(!cityExists($user_city)) {
				query("INSERT INTO city (city) VALUES ('$user_city')");
			}		
			$result = query("SELECT * FROM `location` l WHERE l.pk_countryid = '$new_user_country_id' AND l.pk_cityid = (SELECT c.id FROM city c WHERE c.city='$user_city')");
			if(!mysqli_num_rows($result) > 0) {
				query("INSERT INTO `location` (pk_cityid, pk_countryid) VALUES ((SELECT c.id FROM city c WHERE c.city='$user_city' LIMIT 1), '$new_user_country_id')");
			}
			$query .= "p.pk_location = (SELECT l.id FROM `location` l WHERE l.pk_countryid = '$new_user_country_id' AND l.pk_cityid = (SELECT c.id FROM city c WHERE c.city='$user_city' LIMIT 1)) ";
		} 
		
		$query .= "WHERE p.user_id = '$user_id'";

		$result = mysqli_query($connection, $query);
		confirmResult($result);

		// db cleaning
		query("DELETE FROM city WHERE id NOT IN (SELECT l.pk_cityid FROM `location` l)");
		query("DELETE FROM `location` WHERE id NOT IN (SELECT p.pk_location FROM `profile` p)");

		// echo "<p class='mb-4 font-weight-bold'>Profile data updated with success!</p>";
		redirect("edit.php");
	}
}

function updateCourse() {
	global $connection;

	if(isset($_GET['edit'])) {
		$course_id = $_GET['edit'];

		if(isset($_POST['update_course'])) {
			$new_user_course = escapeString($_POST['user_course']);
			$new_course_school = escapeString($_POST['course_school']);
			$new_course_sd = escapeString($_POST['course_sd']);
			$new_course_fd = escapeString($_POST['course_fd']);
			$new_course_descrip = escapeString($_POST['course_descrip']);

			$query = "UPDATE course c ";

			if(courseDetailsExists($course_id)) {
				$query .= "LEFT JOIN coursedetails cd ON c.cd_id = cd.id ";
				$query .= "SET cd.startdate = '$new_course_sd', cd.finishdate = '$new_course_fd', cd.description = '$new_course_descrip', ";
			} else {
				if(!empty($new_course_sd || $new_course_fd || $new_course_descrip)) {
					query("INSERT INTO coursedetails (startdate, finishdate, `description`) VALUES ('$new_course_sd', '$new_course_fd', '$new_course_descrip')");
					$cd_id = mysqli_insert_id($connection);

					$query .= "SET c.cd_id = '$cd_id', ";
				} else {
					$query .= "SET ";
				}
			}

			if(!schoolExists($new_course_school)) {
				query("INSERT INTO school (`name`) VALUE ('$new_course_school')");
				$query .= "c.school_id = (SELECT id FROM school WHERE `name` = '$new_course_school'), ";
			}

			$query .= "c.name = '$new_user_course' ";

			$query .= "WHERE c.id = '$course_id' ";

			$result = mysqli_query($connection, $query);
			confirmResult($result);

			// db cleaning
			query("DELETE FROM school WHERE id NOT IN (SELECT c.school_id FROM course c)");

			echo " Course has been updated!";

		} else {
			$result= query("SELECT *, cour.name AS course_name, s.name AS school_name FROM course cour JOIN school s ON cour.school_id = s.id LEFT JOIN coursedetails cd ON cour.cd_id = cd.id WHERE cour.id = '$course_id' ");

			if(mysqli_num_rows($result) > 0) {
				while($row = mysqli_fetch_assoc($result)) {
					$course = $row['course_name'];
					$school = $row['school_name'];
					$sdate = $row['startdate'];
					$fdate = $row['finishdate'];
					$course_description = $row['description'];
					?>
						<div class="bg-lighter rounded px-4 py-3">
							<h6 class="heading-small text-primary font-weight-bold mb-3">Edit your <?= $course; ?> course</h6>
							<form action="" method="post" enctype="multipart/form-data">
								<div class="pl-lg-4">
									<div class="form-group">
										<label class="form-control-label" for="user_course">Course Name</label>
										<input type="text" name="user_course" class="form-control" placeholder="Course Name" value="<?= $course; ?>">
									</div>
								</div>
								<div class="pl-lg-4">
									<div class="row">
										<div class="col-6">
											<div class="form-group">
												<label class="form-control-label" for="course_school">School Name</label>
												<input type="text" name="course_school" class="form-control" placeholder="School Name" value="<?= $school; ?>">
											</div>
										</div>
										<div class="col-3">
											<div class="form-group">
												<label class="form-control-label" for="course_sd">Start Date</label>
												<div class="input-group">
													<div class="input-group-prepend">
															<span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
													</div>
													<input class="form-control datepicker" placeholder="Select date" type="text" name="course_sd" <?= $fdate!="0000" ? 'value='. $sdate : "" ; ?>>
												</div>
											</div>
										</div>
										<div class="col-3">
											<div class="form-group">
												<label class="form-control-label" for="course_fd">Finish Date</label>
												<div class="input-group">
													<div class="input-group-prepend">
															<span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
													</div>
													<input class="form-control datepicker" placeholder="Select date" type="text" name="course_fd" <?= $fdate!="0000" ? 'value='. $fdate : "" ; ?>>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="pl-lg-4">
									<div class="form-group">
										<label class="form-control-label" for="course_descrip">Description</label>
										<textarea rows="4" class="form-control" name="course_descrip" placeholder="A few words about your course..."><?= $course_description; ?></textarea>
									</div>
								</div>

								<div class="text-right">
									<div class="form-group mb-0">
										<input type="submit" class="btn btn-primary btn-sm px-4" name="update_course" value="Save">
									</div>
								</div>
							</form>
						</div>
					<?php	
				}
			}
		}
	}
}

function courseDetailsExists($course_id) {
	$result = query("SELECT * from course WHERE id = '$course_id' LIMIT 1");

	if(mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		$cd_id = $row['cd_id'];
		if(!empty($cd_id)) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}

function showAllEducations($user_id) {
	$result= query("SELECT *, cour.id AS course_id, cour.name AS course_name, s.name AS school_name FROM user_education ue JOIN course cour ON ue.course_id = cour.id JOIN school s ON cour.school_id = s.id WHERE ue.user_id = '$user_id' ");

	if(mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			$course_id = $row['course_id'];
			$course_name = $row['course_name'];
			$school_name = $row['school_name'];
			
			echo "<tr>";
			echo "<td>$course_id</td>";
			echo "<td>$course_name</td>";
			echo "<td>$school_name</td>";
			echo "<td><a href='edit.php?delete=$course_id' class='btn btn-sm btn-danger rounded-circle'><i class='fas fa-trash'></i></a></td>";
			echo "<td><a href='edit.php?edit=$course_id#editpost' class='btn btn-sm btn-success rounded-circle'><i class='fas fa-pen'></i></a></td>";
			echo "</tr>";
		}
	}
}

function deleteEducation() {
	if (isset($_GET['delete'])) {
		$course_id = $_GET['delete'];

		query("DELETE cour, cd FROM course cour INNER JOIN coursedetails cd ON cd.id = cour.cd_id WHERE cour.id =' $course_id' ");
		
		// echo "<script>alert('Education has been delected!')</script>";
		redirect("edit.php");
	}
}

// POSTS
function insertPost() {
	global $connection;
	global $user_id;
	$user_id_s = $_SESSION['user_id'];
	$username = $_SESSION['username'];

	if(isset($_POST['insert_post'])) {
		$post_content = escapeString($_POST['post_content']);

		if(strlen($post_content) > 255) {
			die('FAILED!!! Your post has more than 255 characters!');
		}
		if(empty($post_content)) {
			die('FAILED!!! Your post is empty!');
		}

		$post_img = $_FILES['post_img']['name'];
		$post_img_temp = $_FILES['post_img']['tmp_name'];

		$path_post_img = "./assets/img/users/$username/posts";
		if(!$path_post_img) {
			mkdir($path_post_img, 0777, true);
		}

		move_uploaded_file($post_img_temp, "$path_post_img/$post_img");

		if($user_id_s == $user_id) {
			query("INSERT INTO posts (body, `image`, created_at, user_id, profile_uid) VALUES ('$post_content', '$post_img', now(), '$user_id_s','$user_id')");
		} elseif($user_id_s !== $user_id) {
			query("INSERT INTO posts (body, `image`, created_at, user_id, profile_uid, approved) VALUES ('$post_content', '$post_img', now(), '$user_id_s','$user_id', 0)");
		}
		$post_id = mysqli_insert_id($connection);

		// insert post-tag relation
		if(!empty($_POST['tag_id'])) {
			$tag_id = $_POST['tag_id'];
			print_r($tag_id);
			for($i = 0; $i < count($tag_id); $i++){
				query("INSERT INTO post_tag_relation (post_id, tag_id) VALUES ('$post_id', '$tag_id[$i]')");
				// echo "post and relation inserted";
			}
		}
		// insert notification - post
		insertNotify($user_id_s, $user_id, 1, $post_id, null);

		//change
		echo "Post Created!";
	}
}

function PostExistsInTagRelation($post_id) {
	$result = query("SELECT * from post_tag_relation WHERE post_id = '$post_id' LIMIT 1");

	if (mysqli_num_rows($result) > 0) {
		return true;
	}
	return false;
}

function updatePost() {
	global $connection;
	$username = $_SESSION['username'];

	if(isset($_POST['update_post'])) {
		$post_id = $_POST['post_id'];
		$new_post_content = escapeString($_POST['post_content']);

		$post_img = $_FILES['post_img']['name'];
        $post_img_temp = $_FILES['post_img']['tmp_name'];
		$post_img_current = $_POST['post_img_current'];
		

		$query = "UPDATE posts SET ";
		if(!empty($new_post_content)) {
			$query .= "body = '$new_post_content', ";
		}
		if (!empty($post_img)) {
            if (file_exists("./assets/img/users/$username/posts/" . $post_img_current)) {
            unlink("./assets/img/users/$username/posts/" . $post_img_current);
            }
            move_uploaded_file($post_img_temp, "./assets/img/users/$username/posts/$post_img");
            $query .= "image = '$post_img', ";
		} 
		$query .= "updated_at = now() WHERE id = '$post_id'";

		$result = mysqli_query($connection, $query);
		confirmResult($result);

			// insert post-tag relation

			if(PostExistsInTagRelation($post_id)) {
				// delete all relation
				query("DELETE FROM post_tag_relation WHERE post_id = '$post_id'");
			}
				// after insert again
				if(!empty($_POST['tag_id'])) {
					$tag_id = $_POST['tag_id'];
					
					for($i = 0; $i < count($tag_id); $i++){
						query("INSERT INTO post_tag_relation (post_id, tag_id) VALUES ('$post_id', '$tag_id[$i]')");
						// echo "post and relation inserted";
					}
				}
			
		// echo "<script>alert('Your post has been updated!')</script>";
		redirect("post.php?id=$post_id");
    } 
}


function deletePost() {
	$user_id_s = $_SESSION['user_id'];
	$username = $_SESSION['username'];
	
	if(isset($_POST['delete_post'])) {
		$post_id = $_POST['post_id'];
		$sender_id = $_POST['sender_id'];
        $post_img = $_POST['post_img'];

		query("DELETE FROM posts WHERE id = $post_id");
        if (file_exists("./assets/img/users/$username/posts/" . $post_img)) {
            unlink("./assets/img/users/$username/posts/" . $post_img);
		}

		if(notifyExists($sender_id, $user_id_s, 1, $post_id, null)) {
			deleteNotify($sender_id, $user_id_s, 1, $post_id, null);
		}
		
		header("Location: profile.php");
    }
}

function approvePost() {
	$user_id_s = $_SESSION['user_id'];

	if(isset($_POST['approve_post'])) {
		$post_id = $_POST['post_id'];
		$sender_id = $_POST['sender_id'];

		query("UPDATE posts SET approved = 1, updated_at = now() WHERE id = '$post_id'");

		if(notifyExists($sender_id, $user_id_s, 1, $post_id, null)) {
			deleteNotify($sender_id, $user_id_s, 1, $post_id, null);
		}

		// echo "Post approved";
		redirect("profile.php");
	}
}

// TAGS
function insertTag() {
	if(isset($_POST['create_tag'])) {
		$tag_name = escapeString($_POST['tag_name']);

		if(empty($tag_name)) {
			echo "Tag name should not be empty";
		} else {
			query("INSERT INTO tags (`name`) VALUE ('$tag_name')");
			echo "Tag created!";
		}
	}
}

function showTags($current_page, $records_per_page) {
	$result= query("SELECT * FROM tags LIMIT $current_page, $records_per_page");

	while($row = mysqli_fetch_assoc($result)) {
		$tag_id = $row['id'];
		$tag_name = $row['name'];

		echo "<tr>";
		echo "<td>$tag_id</td>";
		echo "<td>$tag_name</td>";
		echo "<td><a href='tags.php?delete=$tag_id' class='btn btn-sm btn-danger rounded-circle'><i class='fas fa-trash'></i></a></td>";
		echo "<td><a href='tags.php?edit=$tag_id' class='btn btn-sm btn-success rounded-circle'><i class='fas fa-pen'></i></a></td>";
		echo "</tr>";
	}
}

function deleteTag() {
	if(isset($_GET['delete'])) {
		$tag_id_delete = $_GET['delete'];
		query("DELETE FROM tags WHERE id = '$tag_id_delete'");
		echo "<p class='text-white mb-0'>Tag has been delected!</p>";
	}
}

function showButtonsTags() {

	$result= query("SELECT * FROM tags");

	if(mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			$tag_id = $row['id'];
			$tag_name = $row['name'];
	
			echo "<a href='index.php?tagid=$tag_id'>";
			echo "<button type='button' class='btn btn-sm btn-outline-secondary px-3 mx-2'>$tag_name</button>";
			echo "</a>";
		}
	}
}

function updateTag() {
	if(isset($_GET['edit'])) {
		$tag_id = $_GET['edit'];

		if(isset($_POST['update_tag'])) {
			$new_tag_name = escapeString($_POST['tag_name']);

			query("UPDATE tags SET `name` = '$new_tag_name' WHERE id = '$tag_id' ");
			echo " Tag has been updated!";

		} else {
			$result = query("SELECT * FROM tags WHERE id = '$tag_id'");
			while($row = mysqli_fetch_assoc($result)) {
				$tag_name = $row['name'];
				?>
				<div class="card">
					<div class="card-body">
						<form action="" method="post">
							<h6 class="heading-small text-muted mb-3">Edit Tag</h6>
							<div class="row align-items-center">
								<div class="col-lg-9">
									<div class="form-group mb-2">
									<input type="text" class="form-control" placeholder="Tag name" name="tag_name" value="<?= $tag_name; ?>">
									</div>
								</div>
								<div class="col-lg-3">
									<div class="form-group mb-2">
									<input type="submit" name="update_tag" Value="Update" class="col btn btn-primary">
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
				<?php	
			}
		}
	}
}

function showTagsInProfile($post_id) {
	$result = query("SELECT * FROM post_tag_relation ptr JOIN tags t ON ptr.tag_id = t.id WHERE ptr.post_id = '$post_id'");
	while($row = mysqli_fetch_assoc($result)) {
		$tag_name = $row['name'];

		echo "<span class='badge badge-pill badge-primary shadow-bg text-white ml-2'>$tag_name</span>";
	}
}

function showTagOption($id = -1) {
	$result = query("SELECT * FROM tags WHERE id = $id");
	while($row = mysqli_fetch_assoc($result)) {
		$tag_id = $row['id'];
		$tag_name = $row['name'];

		if($tag_id == $id) {
			echo "<option selected class='mb-2' value='$tag_id'>$tag_name</option>";
		} else {
			echo "<option class='mb-2' value='$tag_id'>$tag_name</option>";
		}
	}
}

function showTagsOptionExcept($id) {
	$result = query("SELECT * FROM tags WHERE id != $id");
	while($row = mysqli_fetch_assoc($result)) {
		$tag_id = $row['id'];
		$tag_name = $row['name'];

		if($tag_id == $id) {
			echo "<option selected class='mb-2' value='$tag_id'>$tag_name</option>";
		} else {
			echo "<option class='mb-2' value='$tag_id'>$tag_name</option>";
		}
	}
}

function showAllTagsOptions() {
	$result = query("SELECT * FROM tags");
	while($row = mysqli_fetch_assoc($result)) {
		$tag_id = $row['id'];
		$tag_name = $row['name'];
		echo "<option class='mb-2' value='$tag_id'>$tag_name</option>";
	}
}

// COMMENTS
function insertComment() {
	$user_id_s = $_SESSION['user_id'];

	if(isset($_POST['insert_comment'])) {
		$post_id = $_POST['post_id'];
		$parent_commentid = $_POST['parent_commentid'];
		$comment = escapeString($_POST['comment_post']);

		if(!empty($comment)) {
			query("INSERT INTO comments (post_id, user_id, parent_id, content) VALUES ('$post_id', '$user_id_s', '$parent_commentid', '$comment')");
			if(empty($parent_commentid)) {
				$receiver_id = $_POST['receiver_uid'];
				// insert notification - comment the post
				insertNotify($user_id_s, $receiver_id, 2, $post_id, $parent_commentid);
			} else {
				$result = query("SELECT user_id FROM comments WHERE id = '$parent_commentid' LIMIT 1");
				$row = mysqli_fetch_assoc($result);
				$user_id_coment = $row['user_id'];
				// insert notification - respond a comment
				insertNotify($user_id_s, $user_id_coment, 2, $post_id, $parent_commentid);
			}
			echo "<script>alert('Comment has been inserted!')</script>";
		}
	}
}

// SHOW COMMENTS
function displayComments(array $comments, $level = 0) {
	foreach($comments as $info) {
		$user_id = $info['userid'];
		$firstname = $info['firstname'];
		$lastname = $info['lastname'];
		$username = $info['username'];
		$comment_id = $info['comment_id'];
		$comment_updated_at = $info['comment_updated'];
		$comment_content = $info['content'];
		$post_id = $info['post_id'];
		$parent_id = $info['parent_id'];
		?>
		<div class="row">
		<?= str_repeat("<div class='col-auto px-4'></div>", $level); ?>
			<div class="col-auto pr-2">
				<?php if($user_id !== $_SESSION['user_id']) {
					echo "<a href='profile.php?id=$user_id' class='avatar avatar-sm rounded-circle'>";
				} else {
					echo "<a href='profile.php' class='avatar avatar-sm rounded-circle'>";
				}
				showProfilePicture($user_id, $username);
				?>
				</a>
			</div>
			<div class="col pl-0 pr-5">
				<div class="card d-inline-block bg-lighter mb-0">
					<div class="row mr-0 align-items-center">
						<div class="col-auto">
							<div class="card-body py-1 px-3">
								<p class="text-sm font-weight-bold mb-0"><?= $firstname . " " . $lastname; ?></p>
								<p class="text-sm mb-0"><?= $comment_content; ?></p>
							</div>
						</div>
						<div class="col-auto px-0">
							<?php if($level == 0) : ?>
								<span class="position-absolute ml--3">
									<a class="btn btn-primary btn-sm rounded-circle" data-toggle="collapse" href="#insertComment<?= $comment_id; ?>" role="button" aria-expanded="false" aria-controls="insertComment">
										<i class="fas fa-reply"></i>
									</a>
								</span>
							<?php endif; ?>
							<!-- Dropdown button to posts -->
							<?php require "includes/comment_dropdown.php"; ?>
						</div>
						
					</div>
				</div>
				<p class="text-xs text-muted mb-2">
					<?= ago($comment_updated_at); ?>
				</p>
				<div class="collapse mb-3" id="insertComment<?= $comment_id; ?>">
					<!-- Insert a comment -->
					<form action="" method="POST" enctype="multipart/form-data">
						<div class="row align-items-center">										
							<div class="col-auto pr-2">
								<div class="avatar avatar-xs rounded-circle mt-1">
									<?php showProfilePictureInSession(); ?>
								</div>
							</div>
							<div class="col pl-0">
								<div class="input-group">
									<input type="hidden" name="post_id" value="<?= $post_id; ?>">
									<input type="hidden" name="parent_commentid" value="<?= $comment_id; ?>">
									<input type="text" class="form-control form-control-sm text-xs col-sm col-md-6" name="comment_post" placeholder="Reply the comment...">
									<div class="input-group-append">
										<button class="btn btn-primary btn-sm btn-icon" type="submit" name="insert_comment"><i class="fas fa-paper-plane"></i></button>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>

		</div>
		<?php
		if (!empty($info['childs'])) {
			displayComments($info['childs'], $level + 1);
		}
	}
}

function showComment($post_id) {
	$result = query("SELECT *, c.created_at AS comment_created, c.updated_at AS comment_updated, c.id AS comment_id, u.id AS userid FROM comments c LEFT JOIN `profile` p ON c.user_id = p.user_id JOIN users u ON c.user_id = u.id WHERE c.post_id = '$post_id' ORDER BY comment_created");
	$comments = array();
	while($row = mysqli_fetch_assoc($result)) {
		$row['childs'] = array();
		$comments[$row['comment_id']] = $row;
	}

	foreach ($comments as $k => &$value) {
		if($value['parent_id'] != 0) {
			$comments[$value['parent_id']]['childs'][] =& $value;
		}
	}
	unset($value);

	foreach($comments as $k => $value) {
		if($value['parent_id'] != 0) {
			unset($comments[$k]);
		}
	}

	displayComments($comments);
}

function showUsersCommented($post_id) {
	$result = query("SELECT DISTINCT c.user_id, u.firstname, u.lastname FROM comments c JOIN users u ON c.user_id = u.id WHERE c.post_id = '$post_id' ORDER BY c.created_at");
	while($row = mysqli_fetch_assoc($result)) {
		$firstname = $row['firstname'];
		$lastname = $row['lastname'];
		echo "<p class='text-sm mb-0 px-3'>$firstname " ."$lastname</p>";
	}
}

function deleteComment() {
	if(isset($_POST['delete_comment'])) {
		$comment_id = $_POST['comment_id'];
		$parent_id = $_POST['parent_id'];

		if($parent_id == 0) {
			query("DELETE FROM comments WHERE id = $comment_id OR parent_id = $comment_id");
		} else {
			query("DELETE FROM comments WHERE id = $comment_id");
		}
		echo "<script>alert('Comment has been deleted!')</script>";
		// header("Location: profile.php");
    }
}

function updateComment() {
	if(isset($_POST['update_comment'])) {
		$comment_id = $_POST['comment_id'];
		$new_comment_content = escapeString($_POST['comment_content']);

		query("UPDATE comments SET content = '$new_comment_content', updated_at = now() WHERE id = '$comment_id'");

		echo "<script>alert('Your comment has been updated!')</script>";
    } 
}


// LIKES
function insertOrDeleteLike() {
	$user_id_s = $_SESSION['user_id'];

	if(isset($_POST['change_like'])) {
		$post_id = $_POST['post_id'];
		$receiver_id = $_POST['receiver_uid'];

		$result = query("SELECT * FROM likes WHERE post_id = '$post_id' AND user_id='$user_id_s'");
		if(!mysqli_num_rows($result) > 0) {
			query("INSERT INTO likes (post_id, user_id) VALUES ('$post_id', '$user_id_s')");
			// insert like notification
			insertNotify($user_id_s, $receiver_id, 3, $post_id, null);
		} else {
			query("DELETE FROM likes WHERE post_id = '$post_id' AND user_id='$user_id_s'");
			// delete like notification
			deleteNotify($user_id_s, $receiver_id, 3, $post_id, null);
		}
	}
}

function showWhoLikes($post_id) {
	$result = query("SELECT * FROM likes l JOIN users u ON l.user_id = u.id JOIN `profile` p ON l.user_id = p.user_id WHERE l.post_id = '$post_id' LIMIT 3");
	while($row = mysqli_fetch_assoc($result)) {
		$user_like = $row['user_id'];
		$username = $row['username'];
		$user_profilepic = $row['profilepic'];
		
		if($user_like !== $_SESSION['user_id']) {
			echo "<a href='profile.php?id=$user_like' class='avatar avatar-sm rounded-circle'>";
		} else {
			echo "<a href='profile.php' class='avatar avatar-sm rounded-circle'>";
		}
		echo "<img alt='' src='assets/img/users/$username/profile/$user_profilepic'>";
		echo "</a>";
	}
}

function WhoLikesExists($post_id) {
	$result = query("SELECT * FROM likes l JOIN users u ON l.user_id = u.id JOIN `profile` p ON l.user_id = p.user_id WHERE l.post_id = '$post_id' LIMIT 3");
	if(mysqli_num_rows($result) > 0) {
		return true;
		echo mysqli_num_rows($result);
	}
	return false;
}

function showUsersLiked($post_id) {
	$result = query("SELECT l.user_id, u.firstname, u.lastname FROM likes l JOIN users u ON l.user_id = u.id WHERE l.post_id = '$post_id' ORDER BY l.created_at");
	while($row = mysqli_fetch_assoc($result)) {
		$firstname = $row['firstname'];
		$lastname = $row['lastname'];
		echo "<p class='text-sm mb-0 px-3'>$firstname " ."$lastname</p>";
	}
}

// RELATIONSHIP
function friendsExists($user_id) {
	$result = query("SELECT * FROM `relationship` r WHERE (r.user1_id = '$user_id' OR r.user2_id = '$user_id') AND r.status = 1");
	if(mysqli_num_rows($result) > 0) {
		return true;
	}
	return false;
}

function checkFriendship($user_id) {
	$user_id_s = $_SESSION['user_id'];
	$result = query("SELECT * FROM `relationship` WHERE (`user1_id` = '$user_id' AND `user2_id` = '$user_id_s') OR (`user1_id` = '$user_id_s' AND `user2_id` = '$user_id') AND `status` = 1 LIMIT 1 ");
	if(mysqli_num_rows($result) > 0) {
		return true;
	}
	return false;
}

function sendRequestExists($user_id) {
	$user_id_s = $_SESSION['user_id'];

	$result = query("SELECT * FROM `relationship` WHERE user1_id = $user_id_s AND user2_id = $user_id AND `status` = 0 AND action_usersid = $user_id_s LIMIT 1");
	if(mysqli_num_rows($result) > 0) {
		return true;
	}
	return false;
}

function sendFriendRequest() {
	$user_id_s = $_SESSION['user_id'];

	if(isset($_POST['send_request'])) {
		$friend_id = $_POST['friend_id'];

		if(!sendRequestExists($friend_id)) {
			query("INSERT INTO `relationship` (user1_id, user2_id, `status`, action_usersid) VALUES ('$user_id_s', '$friend_id', 0, '$user_id_s')");

			redirect("profile.php?id=$friend_id");

		}
	}
}

function receiveRequestExists($user_id) {
	$user_id_s = $_SESSION['user_id'];

	$result = query("SELECT * FROM `relationship` WHERE user1_id = $user_id AND user2_id = $user_id_s AND `status` = 0 AND action_usersid != $user_id_s LIMIT 1");
	if(mysqli_num_rows($result) > 0) {
		return true;
	}
	return false;
}

function friendsButton($user_id) {
	if(!checkFriendship($user_id) && !sendRequestExists($user_id)) { ?>
        <!-- Send a Friend Request -->
        <form action="profile.php" method="POST">
            <input type="hidden" name="friend_id" value="<?= $user_id; ?>">
            <button type="submit" name="send_request" class="btn btn-sm btn-info"><i class="fas fa-user-plus mr-2"></i>Add Friend</button>
        </form>
    <?php
    } elseif(sendRequestExists($user_id)) { ?>
    <!-- Cancel Sending Friend Request -->
        <button type="button" class="btn btn-sm btn-info active" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user-plus mr-2"></i>Request Sent</button>
        <div class="dropdown-menu">
            <form action="" method="POST">
                <input type="hidden" name="friend_id" value="<?= $user_id; ?>">
                <button class="dropdown-item" type="submit" name="delete_request"><i class="fas fa-user-times mr-3"></i>Delete Request</button>
            </form>
        </div>
    <?php
    } elseif(receiveRequestExists($user_id)) { ?>
    <!-- Accept Pending Friend Request -->
        <button type="button" class="btn btn-sm btn-info active" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user-plus mr-2"></i>Respond</button>
        <div class="dropdown-menu">
            <form action="" method="POST">
                <input type="hidden" name="friend_id" value="<?= $user_id; ?>">
                <button class="dropdown-item" type="submit" name="accept_friend"><i class="fas fa-user-check mr-3"></i>Accept</button>
                <button class="dropdown-item" type="submit" name="delete_request"><i class="fas fa-user-times mr-3"></i>Delete Request</button>
            </form>
        </div>
    <?php
    } else {
    ?>
        <button type="button" class="btn btn-sm btn-info" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user-check mr-2"></i>Friends</button>
        <div class="dropdown-menu">
            <form action="" method="POST">
                <input type="hidden" name="friend_id" value="<?= $user_id; ?>">
                <button class="dropdown-item" type="submit" name="delete_friendship"><i class="fas fa-user-times mr-3"></i>UnFriend</button>
            </form>
        </div>
    <?php
    }
}

function showRequests() {
	$user_id_s = $_SESSION['user_id'];
	$result = query("SELECT * FROM `relationship` WHERE (user1_id = '$user_id_s' OR user2_id = '$user_id_s') AND action_usersid != '$user_id_s' AND isread = 0 ORDER BY created_at desc");

	while($row = mysqli_fetch_assoc($result)) {
		$user1_id = $row['user1_id'];
		$user2_id = $row['user2_id'];
		$status = $row['status'];
		$created_at = $row['created_at'];

		if(mysqli_num_rows($result) > 0) {
			if($status == 0) {
				$pendrequest = query("SELECT * FROM users WHERE id = '$user1_id'");
				$row = mysqli_fetch_assoc($pendrequest);
				$firstname = $row['firstname'];
				$lastname = $row['lastname'];
				$username = $row['username'];

				if($pendrequest) {
				?>
				<!-- List group -->
				<div class="list-group list-group-flush">
					<div class="card-body border-bottom list-group-item-action py-2">
						<div class="row align-items-center">
							<div class="col-auto">
								<!-- Avatar -->
								<a href="profile.php?id=<?= $user1_id; ?>" class="avatar avatar-lg rounded-circle">
									<?php showProfilePicture($user1_id, $username); ?>
								</a>
							</div>
							<div class="col ml--2">
								<h4 class="mb-0">
									<a href="profile.php?id=<?= $user1_id; ?>"><?= $firstname . " " . $lastname; ?></a>
								</h4>
								<p class="text-sm text-dark font-weight-normal mb-0">sent you a friend request.</p>
								<p class="text-sm text-muted mb-0"><small><?= ago($created_at); ?></small></p>
							</div>
							<div class="col-auto">
								<form action="" method="POST">
									<input type="hidden" name="friend_id" value="<?= $user1_id; ?>">
									<button class="btn btn-sm btn-info" type="submit" name="accept_friend" data-toggle="tooltip" data-placement="bottom" title="Accept"><i class="fas fa-user-check"></i></button>
									<button class="btn btn-sm btn-info" type="submit" name="delete_request" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="fas fa-user-times"></i></button>
								</form>
							</div>
						</div>
					</div>
				</div>
				<?php
				}
			} elseif ($status == 1) {
				$acceptrequest = query("SELECT * FROM users WHERE id = '$user2_id'");
				$row = mysqli_fetch_assoc($acceptrequest);
				$firstname = $row['firstname'];
				$lastname = $row['lastname'];
				$username = $row['username'];

				if($acceptrequest) {
				?>
				<!-- List group -->
				<div class="list-group list-group-flush">
					<div class="card-body border-bottom list-group-item-action py-3">
						<div class="row align-items-center">
							<div class="col-auto">
								<!-- Avatar -->
								<a href="profile.php?id=<?= $user2_id; ?>" class="avatar avatar-lg rounded-circle">
									<?php showProfilePicture($user2_id, $username); ?>
								</a>
							</div>
							<div class="col ml--2">
								<h4 class="mb-0">
									<a href="profile.php?id=<?= $user2_id; ?>"><?= $firstname . " " . $lastname; ?></a>
								</h4>
								<p class="text-sm text-dark font-weight-normal mb-0">accepted your friend request.</p>
								<p class="text-sm text-muted mb-0"><small><?= ago($created_at); ?></small></p>
							</div>
							<div class="col-auto pl-md-0">
								<form action="" method="POST">
									<input type="hidden" name="friend_id" value="<?= $user2_id; ?>">
									<button type="submit" name="read_friend" class="close" data-toggle="tooltip" data-placement="bottom" title="Close">
										<span>&times;</span>
									</button>
								</form>
							</div>
						</div>
					</div>
				</div>
				<?php
				}
			}
		}

	}
}

function acceptFriendRequest() {
	$user_id_s = $_SESSION['user_id'];
	$paramid = paramUrl();

	if(isset($_POST['accept_friend'])) {
		$friend_id = $_POST['friend_id'];

		query("UPDATE `relationship` SET `status` = 1, action_usersid = '$user_id_s', created_at = now() WHERE user1_id = '$friend_id' AND user2_id= '$user_id_s'");

		if($paramid == "id=$friend_id") {
			redirect("profile.php?id=$friend_id");
		}
	}
}

function deleteFriendRequest() {
	$user_id_s = $_SESSION['user_id'];
	$paramid = paramUrl();

	if(isset($_POST['delete_request'])) {
		$friend_id = $_POST['friend_id'];

		query("DELETE FROM `relationship` WHERE (user1_id = '$user_id_s' AND user2_id = '$friend_id') OR (user1_id = '$friend_id' AND user2_id = '$user_id_s') AND `status` = '0' LIMIT 1");
		
		if(!empty($paramid)) {
			redirect("profile.php?id=$friend_id");
		}
	}
}

function readFriendRequest() {
	$user_id_s = $_SESSION['user_id'];
	$paramid = paramUrl();

	if(isset($_POST['read_friend'])) {
		$friend_id = $_POST['friend_id'];

		query("UPDATE `relationship` SET isread = '1' WHERE user1_id = '$user_id_s' AND user2_id= '$friend_id'");

		if($paramid == "id=$friend_id") {
			redirect("profile.php?id=$friend_id");
		}
	}
}

function deleteFriendship() {
	$user_id_s = $_SESSION['user_id'];
	$paramid = paramUrl();

	if(isset($_POST['delete_friendship'])) {
		$friend_id = $_POST['friend_id'];

		if(checkFriendship($friend_id)){
			query("DELETE FROM `relationship` WHERE (`user1_id` = '$friend_id' AND `user2_id` = '$user_id_s') OR (`user1_id` = '$user_id_s' AND `user2_id` = '$friend_id') AND `status` = 1 LIMIT 1 ");
			
			redirect("profile.php?id=$friend_id");
		}
	}
}

// NOTIFICATIONS
function notifyExists($sender_id, $receiver_id, $type, $src_id, $psrc_id) {
	$result = query("SELECT * FROM notifications WHERE sender_id = '$sender_id' AND receiver_id = '$receiver_id' AND `type` = '$type' AND src_id = '$src_id' AND psrc_id = '$psrc_id'");
	if(mysqli_num_rows($result) > 0) {
		return true;
	}
	return false;
}

function insertNotify($sender_id, $receiver_id, $type, $src_id, $psrc_id) {
	if($sender_id != $receiver_id) {
		query("INSERT INTO notifications (sender_id, receiver_id, `type`, src_id, psrc_id) VALUES ('$sender_id', '$receiver_id', '$type', '$src_id', '$psrc_id' )");
		// echo "notification inserted";
	}
}

function deleteNotify($sender_id, $receiver_id, $type, $src_id, $psrc_id) {
	query("DELETE FROM notifications WHERE sender_id = '$sender_id' AND receiver_id = '$receiver_id' AND `type` = '$type' AND src_id = '$src_id' AND psrc_id = '$psrc_id'");
	// echo "notification deleted";
}

function deleteEachNotify() {
	if(isset($_POST['delete_notify'])) {
		$notify_id = $_POST['notify_id'];

		query("DELETE FROM notifications WHERE id = '$notify_id'");
		// echo "notification deleted";
	}
}

function showNotifications() {
	$user_id_s = $_SESSION['user_id'];

	$result = query("SELECT *, n.id AS notify_id, n.created_at AS notify_created FROM notifications n JOIN users u ON n.sender_id = u.id WHERE n.receiver_id ='$user_id_s' ORDER BY notify_created desc");

	while($row = mysqli_fetch_assoc($result)) {
		$firstname = $row['firstname'];
		$lastname = $row ['lastname'];
		$username = $row['username'];
		$notify_id = $row['notify_id'];
		$sender_id = $row['sender_id'];
		$type = $row['type'];
		$post_id = $row['src_id'];
		$p_comment = $row['psrc_id'];
		$notify_created = $row['notify_created'];

		if(mysqli_num_rows($result) > 0) {
		?>
		<div class="list-group list-group-flush">
			<div class="card-body border-bottom list-group-item-action py-2">
				<div class="row align-items-center">
					<?php
						if($type == 1) {
						?>
							<div class="col-auto">
								<!-- Avatar -->
								<a href="post.php?id=<?= $post_id; ?>" class="avatar avatar-lg rounded-circle">
									<?php showProfilePicture($sender_id, $username); ?>
									<span class="position-absolute ml--3">
										<span class="badge badge-lg badge-circle bg-success position-absolute mt-1 ml--4">
											<i class="fas fa-file text-sm text-white"></i>
										</span>
									</span>
								</a>
							</div>
							<div class="col-5 pr-md-0 ml--2">
								<h4 class="mb-0">
									<a href="profile.php?id=<?= $sender_id; ?>"><?= $firstname . " " . $lastname; ?></a>
								</h4>
								<p class="text-sm text-dark font-weight-normal mb-0">posted in your profile.</p>
								<p class="text-sm text-muted mb-0"><small><?= ago($notify_created); ?></small></p>
							</div>
							<div class="col-2 col-md-auto ml-md-3 ml-lg-3 flex-wrap">
								<form action="" method="POST">
									<input type="hidden" name="post_id" value="<?= $post_id; ?>">
									<input type="hidden" name="sender_id" value="<?= $sender_id; ?>">
									<button class="btn btn-sm btn-info mb-1" type="submit" name="approve_post" data-toggle="tooltip" data-placement="bottom" title="Approve"><i class="fas fa-check"></i></button>
									<button class="btn btn-sm btn-info mb-1" type="submit" name="delete_post" data-toggle="tooltip" data-placement="bottom" title="Remove"><i class="fas fa-ban"></i></button>
								</form>
							</div>
							
						<?php
						} elseif($type == 2) {
						?>
							<div class="col-auto">
								<!-- Avatar -->
								<a href="post.php?id=<?= $post_id; ?>" class="avatar avatar-lg rounded-circle">
									<?php showProfilePicture($sender_id, $username);
										if($p_comment == 0) {
											echo "<span class='position-absolute ml--3'><span class='badge badge-lg badge-circle bg-yellow position-absolute mt-1 ml--4'>";
											echo "<i class='fas fa-comment text-sm text-white'></i>";
										} else {
											echo "<span class='position-absolute ml--3'><span class='badge badge-lg badge-circle bg-info position-absolute mt-1 ml--4'>";
											echo "<i class='fas fa-reply text-sm text-white'></i>";
										}
										?>
										</span>
									</span>

								</a>
							</div>
							<div class="col ml--2">
								<h4 class="mb-0">
									<a href="profile.php?id=<?= $sender_id; ?>"><?= $firstname . " " . $lastname; ?></a>
								</h4>
								<p class="text-sm text-dark font-weight-normal mb-0">
									<?php
										if($p_comment == 0) {
											echo "commented your post.";
										} else {
											echo "replied your comment.";
										}
									?>
								</p>
								<p class="text-sm text-muted mb-0"><small><?= ago($notify_created); ?></small></p>
							</div>
						<?php
						} elseif ($type == 3) {
						?>
							<div class="col-auto ">
								<!-- Avatar -->
								<a href="post.php?id=<?= $post_id; ?>" class="avatar avatar-lg rounded-circle">
									<?php showProfilePicture($sender_id, $username); ?>
									<span class="position-absolute ml--3">
										<span class="badge badge-lg badge-circle bg-warning position-absolute mt-1 ml--4">
											<i class="fas fa-thumbs-up text-sm text-white"></i>
										</span>
									</span>
								</a>
							</div>
							<div class="col ml--2">
								<h4 class="mb-0">
									<a href="profile.php?id=<?= $sender_id; ?>"><?= $firstname . " " . $lastname; ?></a>
								</h4>
								<p class="text-sm text-dark font-weight-normal mb-0">liked a post you published.</p>
								<p class="text-sm text-muted mb-0"><small><?= ago($notify_created); ?></small></p>
							</div>
							<?php
						}
						?>
							<div class="col-auto pl-md-0">
								<form action="" method="POST">
									<input type="hidden" name="notify_id" value="<?= $notify_id; ?>">
									<button type="submit" name="delete_notify" class="close" data-toggle="tooltip" data-placement="bottom" title="Close and Remove">
										<span>&times;</span>
									</button>
								</form>
							</div>
						</div>
					</div>
				</div>
			<?php
		}
	}
}

// COUNTERS
function countFriends() {
	global $user_id;
	$result = query("SELECT count(*) As total FROM `relationship` r WHERE (r.user1_id = '$user_id' OR r.user2_id = '$user_id') AND r.status = '1' ");
	$row = mysqli_fetch_assoc($result);
	return $row['total'];
}

function countPosts() {
	global $user_id;
	$result = query("SELECT count(*) As total FROM posts");
	$row = mysqli_fetch_assoc($result);
	return $row['total'];
}

function countPostsOneUser() {
	global $user_id;
	$result = query("SELECT count(*) As total FROM posts WHERE profile_uid = '$user_id' AND approved = 1");
	$row = mysqli_fetch_assoc($result);
	return $row['total'];
}

function countPostsMyAndFriends() {
	global $user_id;
	$result = query("SELECT count(*) As total FROM posts p JOIN `relationship` r ON p.profile_uid = r.user1_id WHERE (r.user1_id = $user_id OR r.user2_id = $user_id) AND r.status = 1 AND p.approved = 1");
	$row = mysqli_fetch_assoc($result);
	return $row['total'];
}

function countComments($post_id) {
	$result = query("SELECT count(*) As total FROM comments WHERE post_id = $post_id");
	$row = mysqli_fetch_assoc($result);
	return $row['total'];
}

function countLikes($post_id) {
	$totalfriends = query("SELECT count(*) As total FROM likes WHERE post_id = $post_id");
	$row = mysqli_fetch_assoc($totalfriends);
	$numberOfLikes =  $row['total'];

	$photoslikes = query("SELECT count(*) as total FROM likes l JOIN users u ON l.user_id = u.id JOIN `profile` p ON l.user_id = p.user_id WHERE post_id = '$post_id' LIMIT 3");
	$row = mysqli_fetch_assoc($photoslikes);
	$numberOfPhotos = $row['total'];

	return $numberOfLikes - $numberOfPhotos;
}

function countPendingRequests() {
	$user_id_s = $_SESSION['user_id'];
	$result = query("SELECT count(*) AS total FROM `relationship` WHERE (user1_id = '$user_id_s' OR user2_id = '$user_id_s') AND action_usersid != '$user_id_s' AND isread = 0 ");
	$row = mysqli_fetch_assoc($result);
	return $row['total'];
}

function countNotifications() {
	$user_id_s = $_SESSION['user_id'];
	$result = query("SELECT count(*) AS total FROM notifications WHERE receiver_id = $user_id_s");
	$row = mysqli_fetch_assoc($result);
	return $row['total'];
}

function countUsers() {
	$result = query("SELECT count(*) AS total FROM users");
	$row = mysqli_fetch_assoc($result);
	return $row['total'];
}

function countTags() {
	$result = query("SELECT count(*) AS total FROM tags");
	$row = mysqli_fetch_assoc($result);
	return $row['total'];
}

// ADMIN - dashboard
function lastNewUser() {
	$result = query("SELECT * FROM users ORDER BY id desc LIMIT 1");
	$row = mysqli_fetch_assoc($result);
	echo date('j F, Y', strtotime($row['created_at'])) . " by " . "<a class='h4 text-success' href='profile.php?id=" .$row['id']."'>" . $row['firstname'] . " " . $row['lastname'] . "</a>" ;
}

function lastNewPost() {
	$result = query("SELECT *, p.id AS post_id, p.created_at AS post_creat FROM posts p JOIN users u ON p.user_id = u.id ORDER BY p.id desc LIMIT 1");
	$row = mysqli_fetch_assoc($result);
	echo date('j F, Y', strtotime($row['post_creat'])) . " by " . "<a class='h4 text-success' href='post.php?id=" .$row['post_id']."'>" . $row['firstname'] . " " . $row['lastname'] . "</a>" ;
}

// PAGINATION
function getPage() {
	$page = 0;

	if (isset($_GET['page'])) {
		$page = $_GET['page'];
		return $page;
	}
	return $page;
}

function getCurrentPage($records_per_page) {
	$page = getPage();

	if ($page == 1 || $page == "") {
		$current_page = 0;
	} else {
	$current_page = ($page * $records_per_page - $records_per_page);
	}
	return $current_page;
}

?>