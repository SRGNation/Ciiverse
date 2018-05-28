<?php 

session_start();
$redirect = 0;
include("../lib/connect.php");

$ciiverseid = mysqli_real_escape_string($db,$_GET['cvid']);

if($user['user_level'] < 1) {
	die("You are not authorized to perform this action.");
}

$sql = "SELECT * FROM users WHERE ciiverseid = '$ciiverseid' ";
$res = mysqli_query($db,$sql);
$row = mysqli_fetch_array($res);

if($row['user_level'] >= $user['user_level']) {
die("An error occured.");
}

$count = mysqli_num_rows($res);

if($count == 0) {
	die("Ciiverse ID doesn't exist");
} else {
	$db->query("DELETE FROM yeahs WHERE owner = '$ciiverseid' ");
}


echo "Purged the yeahs of $ciiverseid";

?>