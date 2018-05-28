<?php 

session_start();
$redirect = 0;
require('lib/connect.php');
require('lib/users.php');
include('lib/htm.php');

if(account_deleted($_SESSION['ciiverseid'])) {
	$err = "An error occured. Please try logging back in and try again.";
}

if($_SERVER["REQUEST_METHOD"] == "POST") {

if($_POST['csrf_token'] !== $_COOKIE['csrf_token']) {
	exit();
}

$ip = $_SERVER['REMOTE_ADDR'];

$a = "SELECT * FROM communities WHERE id = ".$_POST['communityid']." AND deleted = 0";
$b = mysqli_query($db,$a);
$d = mysqli_fetch_array($b);

$c = mysqli_num_rows($b);

if($c !== 1) {
	$err = "The community you're trying to post in doesn't exist.";
}

if($_SESSION['ciiverseid'] !== '124598Dom' && $user['user_type'] < 3 && $d['id'] == 56) {
	$err = "You can't post in a read only community.";
}

if($d['rd_oly'] == 'true' && $user['user_level'] < 6) {
	$err = "You can't post in a read only community.";
}

if(strlen($_POST['makepost']) > 1000) {
	$err = "Posts can't be more than 1000 characters long.";
}

if(empty($_POST['makepost'])) {
	$err = 'Post can\'t be empty';
}

if (strlen($_POST['makepost']) > 0 && strlen(trim($_POST['makepost'])) == 0) {
	$err = 'Post can\'t only contain spaces.';
}

$content = mysqli_real_escape_string($db,$_POST['makepost']);
$cid = mysqli_real_escape_string($db,$_POST['communityid']);
$cvid = mysqli_real_escape_string($db,$_SESSION['ciiverseid']);
$screenshot = mysqli_real_escape_string($db,$_POST['screenshot']);
$feeling = mysqli_real_escape_string($db,$_POST['feeling_id']);
$url = mysqli_real_escape_string($db,$_POST['url']);

if(isset($_POST['screenshot']) && $user['can_post_images'] == 0) {
	$err = "You don't have the permission to post images.";
}

$normie = 'HAHAHAAHAHAHA NORM1e LOOK AT MEME IM SO FUNNY LOololololololololololololololololololololololololhguishaoigugheuwnqg.';

$content = str_replace('normie', $normie, $content);

if(!empty($screenshot)) {
	if(!urlimageisvalid($screenshot)) {
		$err = 'Invalid Image';
	}
}

if(!isset($err)) {
$sql_post = "INSERT INTO posts (community_id, content, owner, ip, screenshot, web_url, feeling) VALUES ('$cid', '$content', '$cvid', '$ip', '$screenshot', '$url', '$feeling')";
mysqli_query($db,$sql_post);

/* if($d['rd_oly'] == 'true') {
	$get_post = $db->query("SELECT post_id, content FROM posts WHERE community_id = ".$cid." ORDER BY post_id DESC");
	$post_id = mysqli_fetch_array($get_post);
	$postid = $post_id['post_id'];

	$content = mb_substr($post_id['content'],0,50).'...';

	post_to_discord("NEW UPDATE: $content 
	(Full post at: http://srgciiverse.x10host.com/post/$postid)");
} */
} else {
	echo "<script type='text/javascript'> alert(\"".$err."\"); </script> 
	<p>Redirecting...</p>
	<meta http-equiv=\"refresh\" content=\"2;url=/communities/$cid\" />";
	exit();
}

header("location: ../communities/$cid");

} else { exit("Your gay lol."); }

?>