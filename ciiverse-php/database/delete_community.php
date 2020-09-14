<?php

$did_delete = 0;
session_start();
if(isset($_GET['id'])) {
$redirect = '/database/delete_community.php?id='.$_GET['id'];
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

$thing_id = mysqli_real_escape_string($db,$_GET['id']);

if(!isset($_GET['delete'])) {
$get_community = $db->query("SELECT * FROM communities WHERE id = ".$thing_id);

$get_post = $db->query("SELECT * FROM posts WHERE community_id = ".$thing_id);
$post_count = mysqli_num_rows($get_post);

$get_fc = $db->query("SELECT * FROM favorite_communities WHERE community_id = ".$thing_id);
$fc_count = mysqli_num_rows($get_fc);

} else {

if($_COOKIE['csrf_token'] !== $_GET['csrftoken']) {
	exit();
}

$db->query("DELETE FROM communities WHERE id = ".mysqli_real_escape_string($db,$_GET['delete']));
$db->query("DELETE FROM posts WHERE community_id = ".mysqli_real_escape_string($db,$_GET['delete']));
$db->query("DELETE FROM favorite_communities WHERE community_id = ".mysqli_real_escape_string($db,$_GET['delete']));
$did_delete = 1;

}

?>
<html>
	<head>
		<title>Delete Community - Staff Panel</title>
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
							<div class="alert alert-danger">Community deleted succesfully!</div>
							</div>
							</body>
							</html>';
						exit();
					}
				?>
				<a href="edit_community.php?id=<?php echo $_GET['id'] ?>"><< Bring me back to safety!</a>
			</div>
			<div class="panel panel-danger">
				<div class="panel-heading">Delete Community</div>
				<div class="panel-body">
					<div class="alert alert-info">The following will be deleted:</div>
					<p>The community (Obviously)</p>
					<p>Posts <span class="badge"><?php echo $post_count; ?></span></p>
					<p>Favorite Communities <span class="badge"><?php echo $fc_count; ?></span></p>
					<a class="btn btn-danger" href="delete_community.php?delete=<?php echo $_GET['id']; ?>&csrftoken=<?php echo $_COOKIE['csrf_token']; ?>">Delete Community</a>
				</div>
			</div>
		</div>
	</body>
</html>