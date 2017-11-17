<?php 

require("../lib/connect.php");
session_start();

if(isset($_SESSION['ciiverseid'])) { 

		$cvid = $_SESSION['ciiverseid'];

		$sql = "SELECT is_owner FROM users WHERE ciiverseid = '$cvid' ";
		$result = mysqli_query($db,$sql);
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);

		$is_owner = $row['is_owner'];
	}

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

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$content = mysqli_real_escape_string($db,$_POST['body']);
	$nickname = $_SESSION['nickname'];
	$pfp = $_SESSION['pfp'];
	$cvid = $_SESSION['ciiverseid'];
	$pid = mysqli_real_escape_string($db,$_POST['pid']);
	$ip = mysqli_real_escape_string($_SERVER['REMOTE_ADDR']);

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

		$sqli = "INSERT INTO comments (post_id, content, owner, owner_nickname, owner_pfp, is_verified, ip) VALUES ('$pid', '$content', '$cvid', '$nickname', '$pfp', '$is_owner', '$ip')";
		mysqli_query($db,$sqli);

		mysqli_query($db,"UPDATE posts SET comments='$noc' WHERE post_id='$pid' ");

		header("location: ../post/$pid");
	}

} else { 
	echo "You didn't post anything yet.";
}

?>