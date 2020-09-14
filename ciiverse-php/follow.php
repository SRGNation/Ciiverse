<?php 
$redirect = 0;
session_start();
include("lib/connect.php");
include("lib/users.php");

$user_following = mysqli_real_escape_string($db,$_POST['UserID']);
$type = mysqli_real_escape_string($db,$_POST['followType']);

if($_SESSION['ciiverseid'] == $user_following) {
	exit("error");
}

if(!$_SESSION['loggedin']) {
	exit("error");
}

if($type == 'follow') {

	$chk = $db->query("SELECT * FROM follows WHERE follow_to = '$user_following' AND follow_by = '".$_SESSION['ciiverseid']."'");
	$is_already = mysqli_num_rows($chk);

	if($is_already !== 0) {
		exit("error");
	}

	$db->query("INSERT INTO follows (follow_to, follow_by) VALUES ('".$user_following."', '".$_SESSION['ciiverseid']."')");
	$db->query("INSERT INTO notifs (notif_to, notif_by, type) VALUES ('".$user_following."', '".$_SESSION['ciiverseid']."', 3)");
} else {
	$chk = $db->query("SELECT * FROM follows WHERE follow_to = '$user_following' AND follow_by = '".$_SESSION['ciiverseid']."'");
	$is_already = mysqli_num_rows($chk);

	if($is_already == 0) {
		exit("error");
	}

	$db->query("DELETE FROM follows WHERE follow_to = '$user_following' AND follow_by = '".$_SESSION['ciiverseid']."'");
}

 ?>