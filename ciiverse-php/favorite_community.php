<?php

session_start();
$redirect = 0;
require("lib/connect.php");
$community_id = mysqli_real_escape_string($db,$_GET['cid']);
$action = $_GET['action'];

if($_SESSION['loggedin'] == false) {
	exit("You're not logged in. This error would normally show if you just logged out.");
}

if($action == 'favorite') {

	#Checks if you already favorited the community
	$check = $db->query("SELECT * FROM favorite_communities WHERE community_id = $community_id AND owner = '".$_SESSION['ciiverseid']."' ");
	$favorite = mysqli_num_rows($check);

	if($favorite > 1) {
		exit("You have already favorited this community.");
	}

	#This will check if the community actually exists
	$check = $db->query("SELECT * FROM communities WHERE id = $community_id AND deleted = 0");
	$community = mysqli_fetch_array($check);

	if($community == 0) {
		exit("The community you're trying to favorite doesn't exist or has been deleted.");
	}

	#This will actually add the favorite community
	$db->query("INSERT INTO favorite_communities (community_id, owner) VALUES ($community_id, '".$_SESSION['ciiverseid']."') ");
	header("location: /communities/$community_id");

} else {

	$check = $db->query("SELECT * FROM favorite_communities WHERE community_id = $community_id AND owner = '".$_SESSION['ciiverseid']."' ");
	$favorite = mysqli_num_rows($check);

	if($favorite == 0) {
		exit("You didn't favorite this community.");
	}

	$db->query("DELETE FROM favorite_communities WHERE owner = '".$_SESSION['ciiverseid']."' AND community_id = $community_id");
	header("location: /communities/$community_id");

}

?>