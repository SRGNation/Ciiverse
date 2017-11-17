<?php 

require('../login/IncludesOrSomething/db_login.php');
require('../login/IncludesOrSomething/filter.php');
include('../login/IncludesOrSomething/functions.php');
session_start();

   if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
	   $ciiverseid = $_SESSION['ciiverseid'];
	   $heck = "SELECT ciiverseid FROM users WHERE ciiverseid ='$ciiverseid' ";
	   $use_cedar = mysqli_query($db,$heck);
	   
	   $cont = mysqli_num_rows($use_cedar);
	   
	   if($cont !== 1) {
		   die("Sorry, we couldn't find your Ciiverse ID anywhere in the database so we assumed your account has been deleted :( <br>
		   <a href='/login/logout.php'>Log out.</a>");
	   }
   }

if(isset($_SESSION['ciiverseid'])) { 

$cvid = $_SESSION['ciiverseid'];

$sql = "SELECT is_owner FROM users WHERE ciiverseid = '$cvid' ";
$result = mysqli_query($db,$sql);
$row = mysqli_fetch_array($result,MYSQLI_ASSOC);

$is_owner = $row['is_owner'];

}

if($_SERVER["REQUEST_METHOD"] == "POST") {

$ip = $_SERVER['REMOTE_ADDR'];

$a = "SELECT * FROM communities WHERE id = ".$_POST['communityid'];
$b = mysqli_query($db,$a);
$d = mysqli_fetch_array($b);

$c = mysqli_num_rows($b);

if($c !== 1) {
	die("The community you're trying to post in doesn't exist.");
}

if($d['rd_oly'] == 'true' && $is_owner !== 'true') {
	die("You can't post in a read only community.");
}

$content = mysqli_real_escape_string($db,$_POST['makepost']);
$cid = mysqli_real_escape_string($db,$_POST['communityid']);
$cvid = $_SESSION['ciiverseid'];

$sql = "SELECT nickname, pfp FROM users WHERE ciiverseid = '$cvid' ";
$result = mysqli_query($db,$sql);
$row = mysqli_fetch_array($result);

$nickname = mysqli_real_escape_string($db,$row['nickname']);
$pfp = mysqli_real_escape_string($db,$row['pfp']);

if(empty($pfp)) {
	$pfp = '/defult_pfp.png';
}

if(empty($content)) {
  die("Post cannot be empty.");
}

if(!isset($is_owner)) {
	$is_owner = 'false';
}

$sql_post = "INSERT INTO posts (community_id, content, owner, owner_pfp, owner_nickname, is_verified, ip) VALUES ('$cid', '$content', '$cvid', '$pfp', '$nickname', '$is_owner', '$ip')";
mysqli_query($db,$sql_post);

header("location: ../communities?cid=$cid");

} else { die("You havent posted anything yet."); }

?>