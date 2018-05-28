<?php

session_start();
$redirect = 0;
require('lib/connect.php');

if($user['user_level'] < 1) {
	exit('You\'re not authorized to perform this action.');
} else {
	$post_id = mysqli_real_escape_string($db,$_GET['post_id']);

	$check_posts = $db->query("SELECT * FROM posts WHERE post_id = $post_id AND deleted != 5");

	if(mysqli_num_rows($check_posts) == 0) {
		exit('The post you\'re trying to undelete doesn\'t exist.');
	} else {
		$db->query("UPDATE posts SET deleted = 0 WHERE post_id = $post_id");

		header('location: /post/'.$post_id);
	}
}

?>