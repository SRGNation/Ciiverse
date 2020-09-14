<?php

$did_delete = 0;
session_start();
if(isset($_GET['id'])) {
$redirect = '/database/delete_post.php?id='.$_GET['id'];
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

if(!isset($_GET['delete'])) {
$get_post = $db->query("SELECT * FROM posts WHERE post_id = ".mysqli_real_escape_string($db,$_GET['id']));

$get_comments = $db->query("SELECT * FROM comments WHERE post_id = ".mysqli_real_escape_string($db,$_GET['id']));
$get_yeahs = $db->query("SELECT * FROM yeahs WHERE post_id = ".mysqli_real_escape_string($db,$_GET['id'])." AND type='post'");

$yeah_count = mysqli_num_rows($get_yeahs);
$comment_count = mysqli_num_rows($get_comments);

} else {

if($_COOKIE['csrf_token'] !== $_GET['csrftoken']) {
	exit();
}

$db->query("DELETE FROM posts WHERE post_id = ".mysqli_real_escape_string($db,$_GET['delete']));
$db->query("DELETE FROM yeahs WHERE post_id = ".mysqli_real_escape_string($db,$_GET['delete'])." AND type = 'post'");
$db->query("DELETE FROM comments WHERE post_id = ".mysqli_real_escape_string($db,$_GET['delete']));
$did_delete = 1;

}

?>

<html>
	<head>
		<title>Delete Post - Staff Panel</title>
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
			<?php 
				if($did_delete == 1) {
					echo '</div>
						<div class="alert alert-danger">Post deleted succesfully!</div>
						</div>
						</body>
						</html>';
					exit();
				}
			?>
		<a href="edit_post.php?id=<?php echo $_GET['id'] ?>"><< Bring me back to safety!</a>
	</div>
	<div class="panel panel-danger">
	<div class="panel-heading">Delete Post</div>
	<div class="panel-body">
	<div class="alert alert-info">The following will be deleted:</div>
	<p>The post (Obviously)</p>
	<p>Comments <span class="badge"><?php echo $comment_count; ?></span></p>
	<p>Yeahs <span class="badge"><?php echo $yeah_count; ?></span></p>
	<a class="btn btn-danger" href="delete_post.php?delete=<?php echo $_GET['id']; ?>&csrftoken=<?php echo $_COOKIE['csrf_token']; ?>">Delete Post</a>
	</div>
	</div>
	</div>
	</body>
</html>