<?php 
session_start();

$_SESSION['loggedin'] = false;
$_SESSION['ciiverseid'] = '';
setcookie('login_magic', '', time() - 3600, '/');
header("location: /");

?>