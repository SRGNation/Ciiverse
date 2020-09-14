<?php 
session_start();
$redirect = 0;
require('lib/connect.php');
require('lib/users.php');
include('lib/htm.php');

if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) { } else {
	exit('You are not logged in. You need to log in to add a tag.');
}

if(account_deleted($_SESSION['ciiverseid'])) {
	exit('An error occured. Please try logging back in and try again.');
}

if($_SERVER["REQUEST_METHOD"] == "POST") {

	$stmt = $db->prepare("SELECT COUNT(*) FROM profile_tags WHERE owner = ?");
	$stmt->bind_param('s', $_SESSION['ciiverseid']);
	$stmt->execute();

	$result = $stmt->get_result();
	$row = $result->fetch_assoc();

	if($row['COUNT(*)'] > 19) {
		exit('You can only create 20 profile tags at a time');
	}

	if($_POST['csrf_token'] !== $_COOKIE['csrf_token']) {
		exit('CSRF Check Failed');
	}

	if(empty($_POST['tag_name'])) {
		exit('Tag name can\'t be empty');
	}

	if(empty($_POST['tag_content'])) {
		exit('Tag text can\'t be empty');
	}

	$stmt = $db->prepare("INSERT INTO profile_tags (owner, tag_name, tag_content) VALUES (?, ?, ?)");
	$stmt->bind_param('sss', $_SESSION['ciiverseid'], $_POST['tag_name'], $_POST['tag_content']);
	$stmt->execute();

	header('location: /userdata/list');
}