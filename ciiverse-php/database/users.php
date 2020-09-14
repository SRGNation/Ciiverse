<?php

if(!isset($_GET['search'])) {
$limit_1 = $_GET['1'];
$limit_2 = $_GET['2'];
$order = $_GET['order'];
}

session_start();
require('../lib/connect.php');

if(!$_SESSION['loggedin']) {
	exit('Please log in to continue.');
}

if($user['has_db_access'] == 0) {
	exit('Not authorized fagit.');
}

if(!isset($_GET['search'])) {
$query = $db->query("SELECT * FROM users ORDER BY id $order limit $limit_1, $limit_2");
} else {
$query = $db->query("SELECT * FROM users WHERE ciiverseid LIKE '%".$_GET['search']."%'");
}
$users = mysqli_num_rows($query);

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
			<a href="/database"><< Go back</a>
		</div>
		<a class="btn btn-primary" href="create_user.php"><span class="badge">+</span> Add user</a><br><br>
		<form action="users.php" type="get">
			<div class="input-group">
				<input class="form-control" type="text" name="search" placeholder="Search user by Ciiverse ID">
				<div class="input-group-btn">
					<input class="btn btn-default" type="submit" value="Search">
				</div>
			</div>
		</form>
		<div class="panel panel-default">
		<div class="panel-heading">Users (Showing: <?php echo $users; ?>)</div>
		<div class="panel-body">
			<ul class="list-group">
		<?php 

		while($list = mysqli_fetch_array($query)) {
			echo '<li class="list-group-item"><a href="/database/edit_user.php?id='.$list['id'].'">'.$list['ciiverseid'].' '.($list['user_type'] == 0 ? '<span class="label label-primary">Disabled</span>' : '').'</a></li>';
		}

		?>
			</ul>
		</div>
		</div>
		</div>
</body>
