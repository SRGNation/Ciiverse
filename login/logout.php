<?php 
session_start();

$_SESSION['loggedin'] = false;
$_SESSION['ciiverseid'] = '';
header("location: /");

?>