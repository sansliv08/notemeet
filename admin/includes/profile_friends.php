<div class="card">
	<!-- Card header -->
	<div class="card-header justify-content-between">
		<div class="row">
			<div class="col">
				<!-- Title -->
				<a href="friends.php">
					<h5 class="h3 mb-0">Friends</h5>
				</a>
			</div>
			<div class="col text-right">
			<?php 
				$numberOfFriends = countFriends();
				echo "<p class='mb-0'>$numberOfFriends</p>";
			?>
			</div>
		</div>

	</div>
	<!-- Card body -->
	<div class="card-body d-flex flex-wrap">
		<?php

			// Primeira versão de mostrar os friends
			$user_id_s = $_SESSION['user_id'];
			$result = query("SELECT * FROM `relationship` r WHERE (r.user1_id = '$user_id' OR r.user2_id = '$user_id') AND r.status = 1 LIMIT 6");

			while($row = mysqli_fetch_array($result)) { 
				$user1_id = $row['user1_id'];
				$user2_id = $row['user2_id'];
			
				if(mysqli_num_rows($result) > 0) {
					if($user1_id == $user_id) {
						$friends = query("SELECT * FROM users u LEFT JOIN `profile` p on u.id = p.user_id WHERE u.id = $user2_id");
						if($friends) {
							$row = mysqli_fetch_assoc($friends);
							$firstname = $row['firstname'];
							$lastname = $row['lastname'];
							$username = $row['username'];
							$img = $row['profilepic'];

								echo "<div class='col-4 text-center'>";
								if($user_id_s == $user2_id){
									echo "<a href='profile.php'>";	
								} else {
									echo "<a href='profile.php?id=$user2_id'>";	
								}
								if(!empty($img)) {
									echo "<img src='assets/img/users/$username/profile/$img' alt='' class='img-fluid rounded-circle'>";
								} else {
									echo "<img src='assets/img/users/_default/user.png' alt='' class='img-fluid'>";
								}
								echo "<p class='text-sm'>$firstname " . "$lastname</p>";
								echo "</a>";
								echo "</div>";
						}
					} elseif ($user2_id == $user_id) {
						$friends = query("SELECT * FROM users u LEFT JOIN `profile` p on u.id = p.user_id WHERE u.id = $user1_id");
						if($friends) {
							$row = mysqli_fetch_assoc($friends);
							$firstname = $row['firstname'];
							$lastname = $row['lastname'];
							$username = $row['username'];
							$img = $row['profilepic'];

								echo "<div class='col-4 text-center'>";
								if($user_id_s == $user1_id){
									echo "<a href='profile.php'>";	
								} else {
									echo "<a href='profile.php?id=$user1_id'>";	
								}
								if(!empty($img)) {
									echo "<img src='assets/img/users/$username/profile/$img' alt='' class='img-fluid rounded-circle'>";
								} else {
									echo "<img src='assets/img/users/_default/user.png' alt='' class='img-fluid'>";
								}
								echo "<p class='test-sm'>$firstname " . "$lastname</p>";
								echo "</a>";
								echo "</div>";
						}
					}
				}
			}	
			// Get User ID
			getUserId();
		?>
	</div>
</div>