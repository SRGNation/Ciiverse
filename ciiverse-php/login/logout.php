<?php 
session_start();

if($_GET['csrftoken'] == $_COOKIE['csrf_token']) {
	$_SESSION['loggedin'] = false;
	$_SESSION['ciiverseid'] = '';
	setcookie('login_magic', '', time() - 3600, '/');
	setcookie('csrf_token', '', time() - 3600, '/');
	header("location: /");
}

?>