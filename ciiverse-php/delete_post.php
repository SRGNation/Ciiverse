<?php 

/*
posts.php
delete_post.php
users/settings.php
users/manage_user.php
users/purge_yeahs.php
profile.php
communities.php
*/

session_start();
$redirect = 0;
require("lib/connect.php");
include("lib/users.php");

if(account_deleted($_SESSION['ciiverseid'])) {
	exit("An error occured. Please try logging back in and try again.");
}

$pid = $_GET['pid'];

$sequal = "SELECT posts.owner, posts.deleted, users.user_type, users.user_level FROM posts, users WHERE posts.post_id = '".mysqli_real_escape_string($db,$pid)."' AND users.ciiverseid = posts.owner";
$result = mysqli_query($db,$sequal);
$row = mysqli_fetch_array($result);

if($row['deleted'] > 0) {
	exit('An error occured.');
}

if($row['owner'] == $_SESSION['ciiverseid']) {
	$db->query("UPDATE posts SET deleted = 1 WHERE post_id = ".mysqli_real_escape_string($db,$pid)." ");
	header('location: /post/'.$pid);
}

if($row['user_level'] >= $user['user_level']) {	
	exit('An error occured.');
} else {
	if($user['user_type'] > 4 || $user['user_type'] < 2) {
		$delete_type = 3;
	} else {
		$delete_type = $user['user_type'];
	}

	$db->query("UPDATE posts SET deleted = ".$delete_type." WHERE post_id = ".mysqli_real_escape_string($db,$pid)." ");
	header('location: /post/'.$pid);
}

?>