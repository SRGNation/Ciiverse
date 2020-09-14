<?php 
session_start();
$redirect = 0;
require('lib/connect.php');
require('lib/users.php');
include('lib/htm.php');

if(account_deleted($_SESSION['ciiverseid'])) {
	$err = "An error occured. Please try logging back in and try again.";
}

if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) { } else {
	$err = "You are not logged in. You need to log in.";
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
	$stmt = $db->prepare("SELECT screenshot FROM posts WHERE post_id = ? AND deleted = 0");
	$stmt->bind_param('s', $_POST['id']);
	$stmt->execute();

	$result = $stmt->get_result();
	$row = $result->fetch_assoc();

	if(empty($row['screenshot']))
	{
		exit('This post doesn\'t have an image.');
	}

	$stmt = $db->prepare("UPDATE users SET favorite_post = ? WHERE ciiverseid = ?");
	$stmt->bind_param('is', $_POST['id'], $_SESSION['ciiverseid']);
	$stmt->execute();

	header('location: /post/'.$_POST['id']);	
} else {
	exit('Stop being cringe...');
}
