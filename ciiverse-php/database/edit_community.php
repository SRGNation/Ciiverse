<?php

session_start();
if(isset($_GET['id'])) {
$redirect = '/database/edit_community.php?id='.$_GET['id'];
} else {
$redirect = 0;	
}
require('../lib/connect.php');

if(!$_SESSION['loggedin']) {
	exit('Please log in to continue.');
}

if($user['has_db_access'] == 0) {
	exit('Not authorized fagit.');
}

if($_SERVER['REQUEST_METHOD'] !== "POST") {
$community_id = $_GET['id'];
$this_post = $db->query("SELECT * FROM communities WHERE id = $community_id");

if(mysqli_num_rows($this_post) == 0) {
	exit("Community not found.");
}

$community = mysqli_fetch_array($this_post);
} else {
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

	$db->query("UPDATE communities SET community_name = '$name', rd_oly = '$read_only', deleted = $deleted, featured = $featured, community_picture = '$icon', community_banner = '$banner', comm_desc = '$description', type = '$type' WHERE id = $id");
	header('location: /database/edit_community.php?id='.$id);
}

?>
<html>
	<head>
		<title>Edit Community - Staff Panel</title>
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
			<div class="panel panel-default">
				<div class="panel-heading">Edit Community. <a href="/communities/<?php echo $community['id']; ?>">View in site.</a></div>
				<div class="panel-body">
					<form action="edit_community.php" method="post">
						<input type="hidden" name="community_id" value="<?php echo $community['id']; ?>">
						<div class="form-group">
							<label for="community_name">Community Name</label>
							<input class="form-control" type="text" name="community_name" placeholder="Community Name goes here." value="<?php echo $community['community_name']; ?>">
						</div>
						<div class="checkbox">
							<label><input type="checkbox" name="rd_only" <?php if($community['rd_oly'] == 'true') {echo 'checked';} ?>> Is Read Only</label>
						</div>
						<div class="checkbox">
							<label><input type="checkbox" name="deleted" <?php if($community['deleted'] == 1) {echo 'checked';} ?>> Is Deleted</label>
						</div>
						<div class="checkbox">
							<label><input type="checkbox" name="featured" <?php if($community['featured'] == 1) {echo 'checked';} ?>> Is Featured</label>
						</div>
						<div class="form-group">
						<label for="comm_type">Community Type:</label>
  							<select class="form-control" name="comm_type">
   	 							<option value="0" <?php if($community['type'] == 0) {echo 'selected';} ?>>0 - General Community</option>
    							<option value="1" <?php if($community['type'] == 1) {echo 'selected';} ?>>1 - Announcement Community</option>
    							<option value="2" <?php if($community['type'] == 2) {echo 'selected';} ?>>2 - Game Community 3ds</option>
    							<option value="3" <?php if($community['type'] == 3) {echo 'selected';} ?>>3 - Game Community Wii U</option>
    							<option value="4" <?php if($community['type'] == 4) {echo 'selected';} ?>>4 - Game Community 3ds/Wii U</option>
  							</select>
  						</div>
						<div class="form-group">
							<label for="description">Community Description</label>
							<textarea class="form-control" rows="4" name="description" placeholder="Community description goes here."><?php echo htmlspecialchars($community['comm_desc']); ?></textarea>
						</div>
						<div class="form-group">
							<label for="icon">Community Icon</label>
							<input class="form-control" type="text" name="icon" placeholder="Community Icon goes here." value="<?php echo htmlspecialchars($community['community_picture']); ?>">
						</div>
						<div class="form-group">
							<label for="banner">Community Banner</label>
							<input class="form-control" type="text" name="banner" placeholder="Community Banner goes here." value="<?php echo htmlspecialchars($community['community_banner']); ?>">
						</div>
						<input type="hidden" name="csrftoken" value="<?php echo $_COOKIE['csrf_token']; ?>">
						<input class="btn btn-primary" type="submit" value="Edit">
						<a class="btn btn-danger" href="delete_community.php?id=<?php echo $community['id']; ?>">Delete Community</a>
					</form>
				</div>
			</div>
	</body>
</html>