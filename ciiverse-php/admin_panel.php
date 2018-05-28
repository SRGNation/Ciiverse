<?php 

session_start();
$redirect = '/admin_panel.php';
require('lib/connect.php');

if($user['user_level'] < 1) {
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
	<link rel="shortcut icon" href="/img/icon.png">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
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
	<form action="users/delete_acc.php" method="post">
	<p>Delete User</p>
	<p style="color: red;">WARNING: This can <b>NOT</b> be undone.<br></p>
	<input type="text" maxlength="32" name="cvid" placeholder="Ciiverse ID">
	<input type="hidden" name="csrf_token" value="<?php echo $_COOKIE['csrf_token']; ?>">
	<br>
	<br>
	<input type="submit" value="Delete">
	</form>
	<br>
	<br>
	<form action="users/purge_yeahs.php" type="get">
	<p>Purge yeahs</p>
	<p style="color: red;">WARNING: This is permanent.<br></p>
	<input type="text" maxlength="32" name="cvid" placeholder="Ciiverse ID">
	<br>
	<br>
	<input type="submit" value="Purge">
	</form>
	<br>
	<br>
	<form action="undelete_post.php" type="get">
	<p>Undelete a post.</p>
	<p>Yes, surprisingly, you can undelete someones post if you deleted it on accident or something.<br></p>
	<input type="text" maxlength="11" name="post_id" placeholder="Post ID">
	<br>
	<br>
	<input type="submit" value="Undelete">
	</form>
	<br>
	<p>Stats:</p>
	<p>Users: <?php echo $users; ?></p>
	<p>Posts: <?php echo $posts; ?></p>
	<p>Comments: <?php echo $comments; ?></p>
	<p>Communities: <?php echo $communities; ?></p>
	<p>Favorite Communities: <?php echo $favorites; ?></p>
	<p>Yeahs: <?php echo $yeahs; ?></p>
	<p>Follows: <?php echo $follows; ?></p>
	<p>Notifs: <?php echo $notifs; ?></p>
</body>
</html>