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
	$stmt = $db->prepare("UPDATE users SET favorite_post = null WHERE ciiverseid = ?");
	$stmt->bind_param('s', $_SESSION['ciiverseid']);
	$stmt->execute();

	header('location: /edit/profile');	
} else {
	exit('Stop being cringe...');
}
