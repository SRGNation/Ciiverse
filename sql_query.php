<?php 

require("lib/connect.php");

session_start();

$ciiverseid = $_GET['cvid'];

if(isset($_SESSION['ciiverseid'])) { 

$cvid = $_SESSION['ciiverseid'];

$sql = "SELECT is_owner FROM users WHERE ciiverseid = '$cvid' ";
$result = mysqli_query($db,$sql);
$ses_row = mysqli_fetch_array($result,MYSQLI_ASSOC);

$is_owner = $ses_row['is_owner'];

}

if($is_owner !== 'true') {
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