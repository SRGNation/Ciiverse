<?php 

session_start();
$redirect = '/admin_panel.php';
require('lib/connect.php');

if($user['user_type'] < 2) {
	exit("You are not authorized to perform this action. Sorry.");
}

$query = $db->query("SELECT * FROM users");
$users = mysqli_num_rows($query);

$query = $db->query("SELECT * FROM posts");
$posts = mysqli_num_rows($query);

$query = $db->query("SELECT * FROM comments");
$comments = mysqli_num_rows($query);

$query = $db->query("SELECT * FROM communities");
$communities = mysqli_num_rows($query);

$query = $db->query("SELECT * FROM yeahs");
$yeahs = mysqli_num_rows($query);

?>

<html>
<head>
	<title>Admin Panel</title>
	<link rel="shortcut icon" href="/img/icon.png">
</head>
<body>
	<p>Yes I know the admin panel sucks big fat penile but I basically but this together in like 2 minutes.</p>
	<h2>Admin Panel</h2>
	<form action="users/manage_user.php" type="get">
	<p>Manage Account<br></p>
	<input type="text" maxlength="32" name="cvid" placeholder="Ciiverse ID">
	<br>
	<br>
	<input type="submit" value="Go!">
	</form>
	<br>
	<br>
	<form action="users/delete_acc.php" type="get">
	<p>Delete User<br></p>
	<input type="text" maxlength="32" name="cvid" placeholder="Ciiverse ID">
	<br>
	<br>
	<input type="submit" value="Delete">
	</form>
	<br>
	<p>Stats:</p>
	<p>Users: <?php echo $users; ?></p>
	<p>Posts: <?php echo $posts; ?></p>
	<p>Comments: <?php echo $comments; ?></p>
	<p>Communities: <?php echo $communities; ?></p>
	<p>Yeahs: <?php echo $yeahs; ?></p>
</body>
</html>