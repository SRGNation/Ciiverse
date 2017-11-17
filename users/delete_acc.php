<?php 

include("../lib/connect.php");

session_start();

$ciiverseid = $_GET['cvid'];

if(isset($_SESSION['ciiverseid'])) { 

$cvid = $_SESSION['ciiverseid'];

$sql = "SELECT is_owner FROM users WHERE ciiverseid = '$cvid' ";
$result = mysqli_query($db,$sql);
$ses_row = mysqli_fetch_array($result,MYSQLI_ASSOC);

$is_owner = $ses_row['is_owner'];

}

if($is_owner !== 'true') {
	die("You are not authorized to perform this action. Sorry :(");
}

$sql = "SELECT * FROM users WHERE ciiverseid = '$ciiverseid' ";
$res = mysqli_query($db,$sql);
$row = mysqli_fetch_array($res);

if($row['is_owner'] == 'true') {
die("You can't delete another admin's account.");
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