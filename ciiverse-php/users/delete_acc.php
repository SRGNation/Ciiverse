<?php 

session_start();
$redirect = 0;
include("../lib/connect.php");

$ciiverseid = mysqli_real_escape_string($db,$_GET['cvid']);

if($user['user_type'] < 2) {
	die("You are not authorized to perform this action. Sorry :(");
}

$sql = "SELECT * FROM users WHERE ciiverseid = '$ciiverseid' ";
$res = mysqli_query($db,$sql);
$row = mysqli_fetch_array($res);

if($row['user_type'] >= $user['user_type']) {
die("An error occured.");
}

$count = mysqli_num_rows($res);

if($count == 0) {
die("The Ciiverse ID you're trying to delete doesn't exist");
} else {
$sql1 = "DELETE FROM users WHERE ciiverseid = '$ciiverseid' ";
$sql2 = "DELETE FROM posts WHERE owner = '$ciiverseid'";
$sql3 = "DELETE FROM comments WHERE owner = '$ciiverseid' ";

mysqli_query($db,$sql1);
mysqli_query($db,$sql2);
mysqli_query($db,$sql3);

echo "Deleted the user $ciiverseid";

}

?>