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
	$nickname = $_SESSION['nickname'];
	$pfp = $_SESSION['pfp'];
	$cvid = $_SESSION['ciiverseid'];
	$pid = mysqli_real_escape_string($db,$_POST['pid']);
	$ip = $_SERVER['REMOTE_ADDR'];

	if(empty($content)) {
		die("Post cannot be empty.");
	}

	$memz = mysqli_query($db,"SELECT post_id FROM posts WHERE post_id='$pid' ");

	$count = mysqli_num_rows($memz);

	if($count == 0) {
		die("The post you're trying to comment in doesn't exist.");
	} else {
		$sql = mysqli_query($db,"SELECT * FROM comments WHERE post_id='$pid' ");
		$cout = mysqli_num_rows($sql);

		$noc = $cout + 1;

		$sqli = "INSERT INTO comments (post_id, content, owner, ip) VALUES ('$pid', '$content', '$cvid', '$ip')";
		mysqli_query($db,$sqli);

		mysqli_query($db,"UPDATE posts SET comments='$noc' WHERE post_id='$pid' ");

		header("location: ../post/$pid");
	}

} else { 
	echo "You didn't post anything yet.";
}

?>