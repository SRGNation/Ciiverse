<?php 

require("lib/connect.php");

session_start();

 if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
	   $ciiverseid = mysqli_real_escape_string($db,$_SESSION['ciiverseid']);
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

$sql = "SELECT is_owner FROM users WHERE ciiverseid = '".mysqli_real_escape_string($db,$cvid)."' ";
$result = mysqli_query($db,$sql);
$row = mysqli_fetch_array($result,MYSQLI_ASSOC);

$is_owner = $row['is_owner'];

}

$pid = $_GET['pid'];

$sequal = "SELECT owner FROM posts WHERE post_id = '".mysqli_real_escape_string($db,$pid)."' ";
$result = mysqli_query($db,$sequal);
$row = mysqli_fetch_array($result);

if($_SESSION['ciiverseid'] == $row['owner']) {
	mysqli_query($db,"DELETE FROM posts WHERE post_id = '".mysqli_real_escape_string($db,$pid)."' ");
	echo "Deleted.";
} else {
	if($is_owner == 'true') {
		mysqli_query($db,"DELETE FROM posts WHERE post_id = '".mysqli_real_escape_string($db,$pid)."' ");
		echo "Deleted.";
	} else {
		echo "Only admins can delete other people's posts.";
	}
}

?>