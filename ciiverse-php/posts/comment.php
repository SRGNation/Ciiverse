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

	$memz = mysqli_query($db,"SELECT post_id FROM posts WHERE post_id='$pid' ");

	$count = mysqli_num_rows($memz);

	if($count == 0) {
		exit("The post you're trying to comment in doesn't exist.");
	} else {
		if (!isset($err)) {
		$sqli = "INSERT INTO comments (post_id, content, owner, ip, feeling) VALUES ('$pid', '$content', '$cvid', '$ip', '$feeling')";
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