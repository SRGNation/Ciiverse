<?php 

session_start();
$redirect = '/admin_panel.php';
require('lib/connect.php');

if($user['user_level'] < 1) {
	exit("You are not authorized to perform this action. Sorry.");
}

$query = $db->query("SELECT * FROM users");
$users = mysqli_num_rows($query);

$query = $db->query("SELECT * FROM posts WHERE deleted = 0");
$posts = mysqli_num_rows($query);

$query = $db->query("SELECT * FROM comments");
$comments = mysqli_num_rows($query);

$query = $db->query("SELECT * FROM communities");
$communities = mysqli_num_rows($query);

$query = $db->query("SELECT * FROM yeahs");
$yeahs = mysqli_num_rows($query);

$query = $db->query("SELECT * FROM favorite_communities");
$favorites = mysqli_num_rows($query);

$query = $db->query("SELECT * FROM notifs");
$notifs = mysqli_num_rows($query);

$query = $db->query("SELECT * FROM follows");
$follows = mysqli_num_rows($query);

?>

<html>
<head>
	<title>Admin Panel</title>
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
			<h1>Admin Panel</h1>
			<p>Yes, I took the time to make the Admin Panel look nice and fancy. I made this while learning bootstrap. I hope you like it!</p>
		</div>
	<div class="well well-sm">
		<form action="users/manage_user.php" type="get">
		<h4>Manage Account</h4>
		<input type="text" maxlength="32" class="form-control" name="cvid" placeholder="Ciiverse ID">
		<br>
		<input type="submit" class="btn btn-primary" value="Go!">
		</form>
	</div>
	<div class="well well-sm">
		<form action="users/delete_acc.php" method="post">
		<h4>Delete User</h4>
	  	<div class="alert alert-danger">
    	<strong>Warning!</strong> This can <strong>NOT</strong> be undone! 
  		</div>
		<input type="text" maxlength="32" name="cvid" class="form-control" placeholder="Ciiverse ID">
		<input type="hidden" name="csrf_token" value="<?php echo $_COOKIE['csrf_token']; ?>">
		<br>
		<input type="submit" class="btn btn-danger" value="Delete">
		</form>
	</div>
	<div class="well well-sm">
	<form action="users/purge_yeahs.php" type="get">
	<h4>Purge yeahs</h4>
	<div class="alert alert-danger">
    <strong>Warning!</strong> This can <strong>NOT</strong> be undone! 
  	</div>
	<input type="text" maxlength="32" name="cvid" class="form-control" placeholder="Ciiverse ID">
	<br>
	<input type="submit" class="btn btn-danger" value="Purge">
	</form>
	</div>
	<div class="well well-sm">
	<form action="undelete_post.php" type="get">
	<h4>Undelete a post.</h4>
  	<div class="alert alert-info">
    Yes! Surprisingly, you can undelete someone post if you deleted it on accident.
  	</div>
	<input type="text" maxlength="11" class="form-control" name="post_id" placeholder="Post ID">
	<br>
	<input type="submit" class="btn btn-primary" value="Undelete">
	</form>
	</div>
	<div class="well well-sm">
	<h2>Stats</h2>
	<p>Users <span class="badge"><?php echo $users; ?></span></p>
	<p>Posts <span class="badge"><?php echo $posts; ?></span></p>
	<p>Comments <span class="badge"><?php echo $comments; ?></span></p>
	<p>Communities <span class="badge"><?php echo $communities; ?></span></p>
	<p>Favorite Communities <span class="badge"><?php echo $favorites; ?></span></p>
	<p>Yeahs <span class="badge"><?php echo $yeahs; ?></span></p>
	<p>Follows <span class="badge"><?php echo $follows; ?></span></p>
	<p>Notifs <span class="badge"><?php echo $notifs; ?></span></p>
	</div>
	</div>
	</div>
</body>
</html>