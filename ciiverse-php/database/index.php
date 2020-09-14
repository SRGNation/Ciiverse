<?php 

session_start();
$redirect = '/database';
require('../lib/connect.php');

if(!$_SESSION['loggedin']) {
	exit('Please log in to continue.');
}

$query = $db->query("SELECT * FROM communities");
$communities = mysqli_num_rows($query);

$query = $db->query("SELECT * FROM posts");
$posts = mysqli_num_rows($query);

$query = $db->query("SELECT * FROM users");
$users = mysqli_num_rows($query);

$query = $db->query("SELECT * FROM comments");
$comments = mysqli_num_rows($query);

$query = $db->query("SELECT * FROM admin_log");
$admin_log = mysqli_num_rows($query);

?>

<html>
	<head>
		<title>Staff Panel</title>
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

		if($user['has_db_access'] == 0) {
			exit('<p>You do not have staff permissions.</p>');
		}

		?>
		<div class="panel panel-default">
			<div class="panel-heading">Welcome, <?php echo htmlspecialchars($user['nickname']); ?>!</div>
			<div class="panel-body">
				<ul class="list-group">
				<li class="list-group-item"><a href="/database/users.php?1=0&2=30&order=DESC">Users <span class="badge"><?php echo $users; ?></span></a></li>
				<li class="list-group-item"><a href="/database/communities.php?1=0&2=30&order=DESC">Communities <span class="badge"><?php echo $communities; ?></span></a></li>
				<li class="list-group-item"><a href="/database/posts.php?1=0&2=30&order=DESC">Posts <span class="badge"><?php echo $posts; ?></span></a></li>
				<li class="list-group-item"><a href="/database/comments.php?1=0&2=30&order=DESC">Comments <span class="badge"><?php echo $comments; ?></span></a></li>
				</ul>
			</div>
		</div>
	</div>
	</body>
</html>