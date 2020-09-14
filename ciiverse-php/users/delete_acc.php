<?php 

session_start();
$redirect = 0;
include("../lib/connect.php");

$ciiverseid = mysqli_real_escape_string($db,$_POST['cvid']);
$csrftok = $_POST['csrf_token'];

if($_POST['csrf_token'] !== $_COOKIE['csrf_token']) {
	exit();
}

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
die("The Ciiverse ID you're trying to delete doesn't exist");
} else {
$sql1 = "DELETE FROM users WHERE ciiverseid = '$ciiverseid' ";
$sql2 = "DELETE FROM posts WHERE owner = '$ciiverseid'";
$sql3 = "DELETE FROM comments WHERE owner = '$ciiverseid' ";
$sql4 = "DELETE FROM yeahs WHERE owner = '$ciiverseid' ";
$sql5 = "DELETE FROM notifs WHERE notif_to = '$ciiverseid' OR notif_by = '$ciiverseid' ";
$sql6 = "DELETE FROM favorite_communities WHERE owner = '$ciiverseid' ";
$sql7 = "DELETE FROM follows WHERE follow_to = '$ciiverseid' OR follow_by = '$ciiverseid' ";
$sql8 = "DELETE FROM sessions WHERE owner = '$ciiverseid' ";

mysqli_query($db,$sql1);
mysqli_query($db,$sql2);
mysqli_query($db,$sql3);
mysqli_query($db,$sql4);
mysqli_query($db,$sql5);
mysqli_query($db,$sql6);
mysqli_query($db,$sql7);
mysqli_query($db,$sql8);

echo "Deleted the user $ciiverseid";

}

?>