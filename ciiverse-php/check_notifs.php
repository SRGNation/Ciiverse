<?php 
session_start();
require("lib/connect.php");

if (!empty($_SESSION['loggedin'])) {

	$get_notifs = $db->query("SELECT * FROM notifs WHERE notif_to = '".$_SESSION['ciiverseid']."' AND rd_notif = 0");
	$notif_count = mysqli_num_rows($get_notifs);

