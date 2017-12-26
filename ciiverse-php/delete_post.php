<?php 

session_start();
$redirect = 0;
require("lib/connect.php");
include("lib/users.php");

if(account_deleted($_SESSION['ciiverseid'])) {
	exit("An error occured. Please try logging back in and try again.");
}

$pid = $_GET['pid'];

$sequal = "SELECT posts.owner, users.user_type FROM posts, users WHERE posts.post_id = '".mysqli_real_escape_string($db,$pid)."' AND users.ciiverseid = posts.owner";
$result = mysqli_query($db,$sequal);
$row = mysqli_fetch_array($result);

if($row['owner'] == $_SESSION['ciiverseid']) {
	mysqli_query($db,"DELETE FROM posts WHERE post_id = '".mysqli_real_escape_string($db,$pid)."' ");
	exit('Deleted.');
}

if($user['user_type'] < 2) {
	exit('An error occured.');
} else {
if($row['user_type'] >= $user['user_type']) {
	exit('An error occured.');
} else {
	mysqli_query($db,"DELETE FROM posts WHERE post_id = '".mysqli_real_escape_string($db,$pid)."' ");
	exit('Deleted');
}
}

?>