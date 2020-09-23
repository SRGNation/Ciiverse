<?php 

session_start();
$redirect = 0;
require('lib/connect.php');
require('lib/users.php');
include('lib/htm.php');

if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) { } else {
	$err = "You are not logged in. You need to log in to post.";
}

if(account_deleted($_SESSION['ciiverseid'])) {
	$err = "An error occured. Please try logging back in and try again.";
}

if($_SERVER["REQUEST_METHOD"] == "POST") {

if($_POST['csrf_token'] !== $_COOKIE['csrf_token']) {
	$err = "CSRF check failed.";
}

$ip = $_SERVER['REMOTE_ADDR'];

$stmt = $db->prepare("SELECT COUNT(*), id, rd_oly FROM communities WHERE id = ? AND deleted = 0");
$stmt->bind_param('i', $_POST['communityid']);
$stmt->execute();
if($stmt->error) 
{
	$err = "An error occured while trying to find a community.";
}
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if($row['COUNT(*)'] == 0) {
	$err = "The community you're trying to post in doesn't exist.";
}

if($_SESSION['ciiverseid'] !== '124598Dom' && $user['user_type'] < 3 && $row['id'] == 56) {
	$err = "You can't post in a read only community.";
}

if($row['rd_oly'] == 'true' && $user['user_level'] < 6) {
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

$content = $_POST['makepost'];
$normie = 'HAHAHAAHAHAHA NORM1e LOOK AT MEME IM SO FUNNY LOololololololololololololololololololololololololhguishaoigugheuwnqg.';
$content = str_replace('normie', $normie, $content);

$stmt = $db->prepare('SELECT COUNT(*) FROM posts WHERE owner = ? AND date_time > NOW() - INTERVAL 15 SECOND');
$stmt->bind_param('s', $_SESSION['ciiverseid']);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
if($row['COUNT(*)'] > 0) {
    $err = 'You\'re making too many posts in quick succession. Please try again in a moment.';
}

if(isset($_FILES['screenshot'])) { 
	$img = $_FILES['screenshot'];
} else {
	$img = null;
}

if(!empty($img['name'])) {
	if($user['can_post_images'] == 1) {
		$filename = $img['tmp_name'];
		
		$image = uploadImage($filename);
		if ($image == 1) {
			$err = 'Image upload failed.';
		}
	} else {
		$err = 'You don\'t have the permission to post images.';
	}
} else {
	$image = null;
}

if(!isset($err)) {
	$stmt = $db->prepare("INSERT INTO posts (community_id, content, owner, ip, screenshot, web_url, feeling) VALUES (?, ?, ?, ?, ?, ?, ?)");
	$stmt->bind_param('isssssi', $_POST['communityid'], $content, $_SESSION['ciiverseid'], $ip, $image, $_POST['url'], $_POST['feeling_id']);
	$stmt->execute();
	if($stmt->error) {
		exit("<script type='text/javascript'> alert(\"An error occured while trying to insert the post into the database.\"); </script>"); 
	}
} else {
	exit("<script type='text/javascript'> alert(\"".$err."\"); </script>"); 
}

$stmt = $db->prepare("SELECT post_id FROM posts WHERE owner = ? ORDER BY post_id DESC LIMIT 1");
$stmt->bind_param('s', $_SESSION['ciiverseid']);
$stmt->execute();
if($stmt->error)
{
	exit("<script type='text/javascript'> alert(\"An error occured while trying to find your latest post.\"); </script>"); 
}
$result = $stmt->get_result();
$post = $result->fetch_assoc();

printPost($post['post_id'],0);

} else { exit("Your str*ight lol."); }

?>