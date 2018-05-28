<?php

session_start();
$redirect = 0;
require("lib/connect.php");
include("lib/htm.php");

/* $post_id = mysqli_real_escape_string($db,$_GET['post_id']);
$type = mysqli_real_escape_string($db,$_GET['type']);
$remove = $_GET['remove']; */

$post_id = mysqli_real_escape_string($db,$_POST['post']);
$type = mysqli_real_escape_string($db,$_POST['yeahType']);
$remove = mysqli_real_escape_string($db,$_POST['remove']);

if($post_id == 2511) {
	exit('error');
}

if($type == 'post') {
$post_info = $db->query("SELECT * FROM posts WHERE post_id = $post_id AND deleted = 0");
} else {
$post_info = $db->query("SELECT * FROM comments WHERE id = $post_id");
}

if(mysqli_num_rows($post_info) == 0) {
	exit("error");
}

if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {} else {
	exit("error");
}

if($_SESSION['ciiverseid'] == 'fuck_001') {
	exit("error");
}

$post = mysqli_fetch_array($post_info);

if($post['owner'] == $_SESSION['ciiverseid']) {
	exit("error");
} else {
	if($remove < 1) {
		#This will check if the user already yeahed the post.
		$check_if = $db->query("SELECT * FROM yeahs WHERE post_id = $post_id AND owner = '".$_SESSION['ciiverseid']."' AND type = '$type' ");
		if(mysqli_num_rows($check_if) > 0) {
			exit("error");
		}

		$db->query("INSERT INTO yeahs (post_id, type, owner) VALUES ($post_id, '$type', '".$_SESSION['ciiverseid']."')");
		$db->query("INSERT INTO notifs (notif_to, notif_by, type, post_id) VALUES ('".$post['owner']."', '".$_SESSION['ciiverseid']."', ".($type == 'post' ? 1 : 2).", $post_id)");

		#This will update the yeah on the post/comment.
		$no_of_yeahs = $db->query("SELECT * FROM yeahs WHERE post_id = $post_id");
		$num_of_yeahs = mysqli_num_rows($no_of_yeahs);
		if($type !== 'post') {
			$db->query("UPDATE comments SET yeahs = $num_of_yeahs WHERE id = $post_id ");
		}
	} else {
		$db->query("DELETE FROM yeahs WHERE post_id = $post_id AND owner = '".$_SESSION['ciiverseid']."' ");;

		if($type == 'post') {
			$get_feeling = $db->query("SELECT feeling FROM posts WHERE post_id = $post_id");
		} else {
			$get_feeling = $db->query("SELECT feeling FROM comments WHERE post_id = $post_id");
		}

		$f = mysqli_fetch_array($get_feeling);

		echo print_yeah($f["feeling"]);
	}
}

?>