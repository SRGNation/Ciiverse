<?php 

session_start();
$redirect = 0;
require("../lib/connect.php");
require("../lib/users.php");

if(account_deleted($_SESSION['ciiverseid'])) {
	exit("An error occured. Please try logging back in and try again.");
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$content = mysqli_real_escape_string($db,$_POST['body']);
	$cvid = $_SESSION['ciiverseid'];
	$pid = mysqli_real_escape_string($db,$_POST['pid']);
	$ip = $_SERVER['REMOTE_ADDR'];
	$feeling = mysqli_real_escape_string($db,$_POST['feeling_id']);

	if(empty($content)) {
		die("Comment can't be empty.");
	}

	if(strlen($content) > 0 && strlen(trim($content)) == 0) {
	exit('Comment can\'t only contain spaces.');
	}

	if(strlen($content) > 1000) {
	$err = "Comments can't be more than 1000 characters long.";
	}

	$stmt = $db->prepare('SELECT COUNT(*) FROM comments WHERE owner = ? AND date_time > NOW() - INTERVAL 15 SECOND');
	$stmt->bind_param('s', $_SESSION['ciiverseid']);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();
	if($row['COUNT(*)'] > 0) {
	    $err = 'You\'re making too many comments in quick succession. Please try again in a moment.';
	}

	$memz = mysqli_query($db,"SELECT * FROM posts WHERE post_id='$pid' ");

	$count = mysqli_num_rows($memz);

	if($count == 0) {
		exit("The post you're trying to comment on doesn't exist.");
	} else {
		if (!isset($err)) {
		$sqli = "INSERT INTO comments (post_id, content, owner, ip, feeling) VALUES ('$pid', '$content', '$cvid', '$ip', '$feeling')";

		$posts = mysqli_fetch_array($memz);

		if($_SESSION['ciiverseid'] !== $posts['owner']) {
			$db->query("INSERT INTO notifs (notif_to, notif_by, post_id, type) VALUES ('".$posts['owner']."', '".$_SESSION['ciiverseid']."', ".$posts['post_id'].", 4)");
		} else {
			$get_pp = $db->query("SELECT * FROM comments WHERE post_id = $pid");

			while($epic = mysqli_fetch_array($get_pp)) {
				$chk_notif = $db->query("SELECT * FROM notifs WHERE notif_to = '".$epic['owner']."' AND notif_by = '".$posts['owner']."' AND post_id = $pid AND type = 4 AND rd_notif = 0");

				if(mysqli_num_rows($chk_notif) == 0) {
					if($epic['owner'] !== $_SESSION['ciiverseid']) { 
					$db->query("INSERT INTO notifs (notif_to, notif_by, post_id, type) VALUES ('".$epic['owner']."', '".$_SESSION['ciiverseid']."', ".$posts['post_id'].", 4)");
					}
				} 
			}
		}

		$db->query($sqli);

		
	} else {
		exit($err);
	}

		header("location: ../post/$pid");
	}

} else { 
	echo "Heck off...";
}

?>