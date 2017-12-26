<?php 

session_start();
$redirect = '/sql_query.php';
require("lib/connect.php");

$ciiverseid = $_GET['cvid'];

if($user['user_type'] < 4) {
	die("You are not authorized to perform this action. Sorry :(");
}

if($_SERVER["REQUEST_METHOD"] == "POST") {

$sql = $_POST['sql'];
mysqli_query($db,$sql);

}

?>

<html>
<head>
<title>Do a MySQL query</title>
</head>
<body>
<h2>MySQL query.</h2>
<form action="/sql_query.php" method="post">
<input type="text" name="sql">
<input type="submit">
</form>
</body>
</html>