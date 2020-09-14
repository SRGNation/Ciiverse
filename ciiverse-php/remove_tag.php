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

if(!isset($_GET['id']) || empty($_GET['id'])) {
	exit('Please specify a tag id.');
}

$stmt = $db->prepare("SELECT COUNT(*) FROM profile_tags WHERE id = ? AND owner = ?");
$stmt->bind_param('is', $_GET['id'], $_SESSION['ciiverseid']);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

if($row['COUNT(*)'] > 0) {
	$stmt = $db->prepare("DELETE FROM profile_tags WHERE id = ?");
	$stmt->bind_param('i', $_GET['id']);
	$stmt->execute();

	header('location: /userdata/list');
} else {
	exit('This tag either doesn\'t exist or it isn\'t owned by you.');
}