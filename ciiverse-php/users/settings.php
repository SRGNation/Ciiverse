<?php

$redirect = 0;
session_start();
require('../lib/connect.php');

#This will check if you're a Mod or an Admin. If you're not, then it will display that message.
if($user['user_type'] < 2) {
	exit("Frick off.");
}

$ciiverseid = mysqli_real_escape_string($db,$_POST['ciiverseid']);

#This will check if the user exists.
$usr = $db->query("SELECT * FROM users WHERE ciiverseid = '$ciiverseid' ");

if(mysqli_num_rows($usr) == 0) {
	exit("User doesn't exist.");
}

#This will check if the user you're editing is greater than or equal to your user type. This is to prevent Admins from editing other Admin's accounts.
$users = mysqli_fetch_array($usr);

if($users['user_type'] >= $user['user_type']) {
	exit("An error occured.");
}

#This will actually edit the user.
if(isset($_POST['is_disabled'])) {
	$db->query("UPDATE users SET user_type = 0 WHERE ciiverseid = '$ciiverseid' ");
} else {
	if($users['user_type'] == 0) {
	$db->query("UPDATE users SET user_type = 1 WHERE ciiverseid = '$ciiverseid' ");
	} else {
	$db->query("UPDATE users SET user_type = ".$users['user_type']." WHERE ciiverseid = '$ciiverseid' ");
	}
}

if(isset($_POST['can_post_images'])) {
	$db->query("UPDATE users SET can_post_images = 1 WHERE ciiverseid = '$ciiverseid' ");
} else {
	$db->query("UPDATE users SET can_post_images = 0 WHERE ciiverseid = '$ciiverseid' ");
}

header("location: /users/manage_user.php?cvid=$ciiverseid");

 ?>