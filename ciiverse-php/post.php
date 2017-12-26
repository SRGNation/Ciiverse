<?php 

session_start();
$redirect = 0;
require('lib/connect.php');
require('lib/users.php');

if(account_deleted($_SESSION['ciiverseid'])) {
	exit("An error occured. Please try logging back in and try again.");
}

if($_SERVER["REQUEST_METHOD"] == "POST") {

$ip = $_SERVER['REMOTE_ADDR'];

$a = "SELECT * FROM communities WHERE id = ".$_POST['communityid']." AND deleted = 0";
$b = mysqli_query($db,$a);
$d = mysqli_fetch_array($b);

$c = mysqli_num_rows($b);

if($c !== 1) {
	die("The community you're trying to post in doesn't exist.");
}

if($_SESSION['ciiverseid'] !== '124598Dom' && $user['user_type'] < 3 && $d['id'] == 56) {
	die("You can't post in a read only community.");
}

if($d['rd_oly'] == 'true' && $user['user_type'] < 3) {
	die("You can't post in a read only community.");
}

if(strlen($_POST['makepost']) > 400 && $user['user_type'] < 2) {
	exit("Posts can't be more than 400 characters long.");
}

if(empty($_POST['makepost'])) {
	exit('Post can\'t be empty');
}

$content = mysqli_real_escape_string($db,$_POST['makepost']);
$cid = mysqli_real_escape_string($db,$_POST['communityid']);
$cvid = mysqli_real_escape_string($db,$_SESSION['ciiverseid']);
$screenshot = mysqli_real_escape_string($db,$_POST['screenshot']);
$feeling = mysqli_real_escape_string($db,$_POST['feeling_id']);

if(isset($_POST['screenshot']) && $user['can_post_images'] == 0) {
	exit("You don't have the permission to post images.");
}

if(strpos($screenshot, ".png") || strpos($screenshot, ".gif") || strpos($screenshot, ".jpeg") || strpos($screenshot, ".tiff") || strpos($screenshot, ".bmp") || strpos($screenshot, ".jpg")) {
	$accepted_scr = 1;
} else {
	if(!empty($screenshot)) {
	exit("Invalid image.");
	}
}

$sql_post = "INSERT INTO posts (community_id, content, owner, ip, screenshot, feeling) VALUES ('$cid', '$content', '$cvid', '$ip', '$screenshot', '$feeling')";
mysqli_query($db,$sql_post);

header("location: ../communities/$cid");

} else { die("You havent posted anything yet."); }

?>