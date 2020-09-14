<?php

$limit_1 = $_GET['1'];
$limit_2 = $_GET['2'];
$order = $_GET['order'];

session_start();
$redirect = '/database/admin_log.php?1='.$limit_1.'&2='.$limit_2.'&order='.$order;
require('../lib/connect.php');

if(!$_SESSION['loggedin']) {
	exit('Please log in to continue.');
}

if($user['has_db_access'] == 0) {
	exit('Not authorized fagit.');
}

$query = $db->query("SELECT * FROM admin_log ORDER BY id $order limit $limit_1, $limit_2");
$admin_log = mysqli_num_rows($query);

 ?>

<html>
	<head>
		<title>Ciiverse Database</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	</head>
	<body>
		<body>
		<h2>Admin Logs (Showing: <?php echo $admin_log; ?>)</h2>
		<?php 

		while($list = mysqli_fetch_array($query)) {
			if($list['type'] == 1) {
				echo '<p>'.$list['who_did_it'].' Deleted a post. Post ID: <a href="/database/edit_post.php?id='.$list['post_target'].'">'.$list['post_target'].'</a></p>';
			} elseif ($list['type'] == 2) {
				echo '<p>'.$list['who_did_it'].' Deleted a user. Ciiverse ID: '.$list['user_target'].'</p>';
			}
		}

		?>
</body>
	</body>
</html>