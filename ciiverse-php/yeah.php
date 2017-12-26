<?php

session_start();
$redirect = 0;
require("lib/connect.php");

$post_id = mysqli_real_escape_string($db,$_GET['post_id']);
$type = mysqli_real_escape_string($db,$_GET['type']);
$remove = $_GET['remove'];

if($type = 'post') {
$post_info = $db->query("SELECT * FROM posts WHERE post_id = $post_id");
} else {
$post_info = $db->query("SELECT * FROM comments WHERE id = $post_id");
}

if(mysqli_num_rows($post_info) == 0) {
	exit("The post you're trying to yeah doesn't exist");
}

$post = mysqli_fetch_array($post_info);

if($post['owner'] == $_SESSION['ciiverseid']) {
	exit("An error occured.");
} else {
	if($remove < 1) {
		#This will check if the user already yeahed the post.
		$check_if = $db->query("SELECT * FROM yeahs WHERE post_id = $post_id AND owner = '".$_SESSION['ciiverseid']."' ");
		if(mysqli_num_rows($check_if) > 0) {
			exit("You already yeahed this post.");
		}

		$db->query("INSERT INTO yeahs (post_id, type, owner) VALUES ($post_id, '$type', '".$_SESSION['ciiverseid']."')");

		#This will update the yeah on the post/comment.
		$no_of_yeahs = $db->query("SELECT * FROM yeahs WHERE post_id = $post_id");
		$num_of_yeahs = mysqli_num_rows($no_of_yeahs);
		$db->query("UPDATE posts SET yeahs = $num_of_yeahs WHERE post_id = $post_id ");

		header("location: /post/$post_id");

	} else {
		$db->query("DELETE FROM yeahs WHERE post_id = $post_id AND owner = '".$_SESSION['ciiverseid']."' ");

		#This will update the yeah on the post/comment.
		$no_of_yeahs = $db->query("SELECT * FROM yeahs WHERE post_id = $post_id");
		$num_of_yeahs = mysqli_num_rows($no_of_yeahs);
		$db->query("UPDATE posts SET yeahs = $num_of_yeahs WHERE post_id = $post_id ");

		header("location: /post/$post_id");
	}
}

?>