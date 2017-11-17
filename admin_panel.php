<?php 

session_start();
require('lib/connect.php');

if(isset($_SESSION['ciiverseid'])) { 

$cvid = mysqli_real_escape_string($db,$_SESSION['ciiverseid']);

$sql = "SELECT is_owner FROM users WHERE ciiverseid = '$cvid' ";
$result = mysqli_query($db,$sql);
$ses_row = mysqli_fetch_array($result,MYSQLI_ASSOC);

$is_owner = $ses_row['is_owner'];

}

if($is_owner !== 'true') {
	die("You are not authorized to perform this action. Sorry.");
}

?>

<html>
<head>
	<title>Admin Panel</title>
	<link rel="shortcut icon" href="icon.png">
</head>
<body>
	<h2>Admin Panel</h2>
	<form action="users/manage_user.php" type="get">
	<p>Manage User<br></p>
	<input type="text" maxlength="32" name="cvid" placeholder="Ciiverse ID">
	<br>
	<br>
	<input type="submit" value="Go!">
	</form>
</body>
</html>