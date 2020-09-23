<?php

session_start();
$redirect = 0;
require("lib/connect.php");
include("lib/htm.php");

if($_POST['yeahType'] == 'post') {
	$type = 1;
	$stmt = $db->prepare("SELECT COUNT(*), owner FROM posts WHERE post_id = ? AND deleted = 0");
} else {
	$type = 2;
	$stmt = $db->prepare("SELECT COUNT(*), owner FROM comments WHERE id = ?");
}
$stmt->bind_param('i', $_POST['post']);
$stmt->execute();
if($stmt->error) {
	exit("error");
}
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if($row['COUNT(*)'] == 0) {
	exit("error");
}

if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {} else {
	exit("error");
}

if($row['owner'] == $_SESSION['ciiverseid']) {
	exit("error");
} else {
	if($_POST['remove'] < 1) {
		#This will check if the user already yeahed the post.
		$stmt = $db->prepare("SELECT COUNT(*) FROM yeahs WHERE post_id = ? AND owner = ? AND type = ?");
		$stmt->bind_param('iss', $_POST['post'], $_SESSION['ciiverseid'], $_POST['yeahType']);
		$stmt->execute();
		if($stmt->error) {
			exit("error");
		}
		$yResult = $stmt->get_result();
		$yRow = $yResult->fetch_assoc(); 
		if($yRow['COUNT(*)'] > 0) {
			exit("error");
		}

		#Inserts the "Yeah!" into the database
		$stmt = $db->prepare("INSERT INTO yeahs (post_id, type, owner) VALUES (?, ?, ?)");
		$stmt->bind_param('iss', $_POST['post'], $_POST['yeahType'], $_SESSION['ciiverseid']);
		$stmt->execute();
		if($stmt->error) {
			exit("error");
		}

		#This will check if the user you're sending a Yeah! to hates yeah notifs. If that is true, it will not send a yeah notif to the user.
		$stmt = $db->prepare("SELECT hates_yeah_notifs FROM users WHERE ciiverseid = ?");
		$stmt->bind_param('s', $row['owner']);
		$stmt->execute();
		$fResult = $stmt->get_result();
		$fRow = $fResult->fetch_assoc();

		if($fRow['hates_yeah_notifs'] == 0) {
			$stmt = $db->prepare("SELECT COUNT(*) FROM notifs WHERE notif_to = ? AND notif_by = ? AND type = ? AND post_id = ?");
			$stmt->bind_param('ssii', $row['owner'], $_SESSION['ciiverseid'], $type, $_POST['post']);
			$stmt->execute();
			$cResult = $stmt->get_result();
			$cRow = $cResult->fetch_assoc();

			if($cRow['COUNT(*)'] == 0) {
				$stmt = $db->prepare("INSERT INTO notifs (notif_to, notif_by, type, post_id) VALUES (?, ?, ?, ?)");
				$stmt->bind_param('ssii', $row['owner'], $_SESSION['ciiverseid'], $type, $_POST['post']);
				$stmt->execute();
			}
		}
	} else {
		$stmt = $db->prepare("DELETE FROM yeahs WHERE post_id = ? AND owner = ?");
		$stmt->bind_param('is', $_POST['post'], $_SESSION['ciiverseid']);
		$stmt->execute();
		if($stmt->error) {
			exit("error");
		}

		if($_POST['yeahType'] == 'post') {
			$stmt = $db->prepare("SELECT feeling FROM posts WHERE post_id = ?");
		} else {
			$stmt = $db->prepare("SELECT feeling FROM comments WHERE post_id = ?");
		}
		$stmt->bind_param('i', $_POST['post']);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_assoc();

		echo print_yeah($row['feeling']);
	}
}

?>