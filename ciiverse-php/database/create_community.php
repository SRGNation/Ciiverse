<?php

session_start();
$redirect = 0;
require('../lib/connect.php');

if(!$_SESSION['loggedin']) {
	exit('Please log in to continue.');
}

if($user['has_db_access'] == 0) {
	exit('Not authorized fagit.');
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	if($_COOKIE['csrf_token'] !== $_POST['csrftoken']) {
		exit();
	}

	$id = $_POST['community_id'];
	$name = mysqli_real_escape_string($db,$_POST['community_name']);
	if(isset($_POST['rd_only'])) {
		$read_only = 'true';
	} else {
		$read_only = 'false';
	}
	if(isset($_POST['deleted'])) {
		$deleted = 1;
	} else {
		$deleted = 0;
	}
	if(isset($_POST['featured'])) {
		$featured = 1;
	} else {
		$featured = 0;
	}
	$description = mysqli_real_escape_string($db,$_POST['description']);
	$icon = mysqli_real_escape_string($db,$_POST['icon']);
	$banner = mysqli_real_escape_string($db,$_POST['banner']);
	$type = mysqli_real_escape_string($db,$_POST['comm_type']);

	$check_id = $db->query("SELECT * FROM communities WHERE id = $id");

	if(mysqli_num_rows($check_id) == 0) {
		$db->query("INSERT INTO communities (id, community_name, rd_oly, deleted, featured, community_picture, community_banner, comm_desc, type) VALUES ($id, '$name', '$read_only', $deleted, $featured, '$icon', '$banner', '$description', '$type')");
		header('location: /database/edit_community.php?id='.$id);
	} else {
		$err = 'Couldn\'t create community because a community with that ID already exists.';
	}
}

?>
<html>
	<head>
		<title>Create Community - Staff Panel</title>
    	<meta charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    	<link rel="shortcut icon" href="/img/icon.png">
    	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	</head>
	<body>
		<div class="container">
			<div class="page-header">
				<h1>Staff Panel</h1>
			</div>
			<?php 
				if(isset($err)) {
					echo '<div class="alert alert-danger"><strong>Error:</strong> '.$err.'</div>';
				}
			?>
			<div class="panel panel-default">
				<div class="panel-heading">Create Community</div>
				<div class="panel-body">
					<form action="create_community.php" method="post">
						<div class="form-group">
							<label for="community_id">Community ID</label>
							<input class="form-control" type="text" name="community_id" placeholder="Community ID goes here.">
						</div>
						<div class="form-group">
							<label for="community_name">Community Name</label>
							<input class="form-control" type="text" name="community_name" placeholder="Community Name goes here.">
						</div>
						<div class="checkbox">
							<label><input type="checkbox" name="rd_only"> Is Read Only</label>
						</div>
						<div class="checkbox">
							<label><input type="checkbox" name="deleted"> Is Deleted</label>
						</div>
						<div class="checkbox">
							<label><input type="checkbox" name="featured"> Is Featured</label>
						</div>
						<div class="form-group">
						<label for="comm_type">Community Type:</label>
  							<select class="form-control" name="comm_type">
   	 							<option value="0">0 - General Community</option>
    							<option value="1">1 - Announcement Community</option>
    							<option value="2">2 - Game Community 3ds</option>
    							<option value="3">3 - Game Community Wii U</option>
    							<option value="4">4 - Game Community 3ds/Wii U</option>
  							</select>
  						</div>
						<div class="form-group">
							<label for="description">Community Description</label>
							<textarea class="form-control" rows="4" name="description" placeholder="Community description goes here."></textarea>
						</div>
						<div class="form-group">
							<label for="icon">Community Icon</label>
							<input class="form-control" type="text" name="icon" placeholder="Community Icon goes here.">
						</div>
						<div class="form-group">
							<label for="banner">Community Banner</label>
							<input class="form-control" type="text" name="banner" placeholder="Community Banner goes here.">
						</div>
						<input type="hidden" name="csrftoken" value="<?php echo $_COOKIE['csrf_token']; ?>">
						<input class="btn btn-primary" type="submit" value="Create">
					</form>
				</div>
			</div>
		</div>
	</body>
</html>