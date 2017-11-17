<?php 
Require('../lib/frick.php');
session_start();

if($_SESSION['loggedin'] !== 'true') { 
if($_SERVER["REQUEST_METHOD"] == "POST") {
$ciiverseid = $_POST['ciiverseid'];
$password = $_POST['password'];

$password = hash('sha512', $password);

$sql = "SELECT user_token FROM users WHERE ciiverseid = '".mysqli_real_escape_string($db,$ciiverseid)."' AND password = '".mysqli_real_escape_string($db,$password)."' ";
$result = mysqli_query($db,$sql);
$row = mysqli_fetch_array($result);

$count = mysqli_num_rows($result);

      if($count == 1) {

        header("location: /login/login.php?token=".$row['user_token']);

         } else {
        $incorrect = true;
      }

}
} else {
die("You're already logged in lol.");
}

?>

<html>
<title>Login to Ciiverse</title>
<link rel="shortcut icon" href="../icon.png">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link rel="stylesheet" href="../offdevice.css" type="text/css">
<link rel="stylesheet" href="../ciiverse.css" type="text/css">
<link rel="stylesheet" href="login_style.css" type="text/css">
</head>
<body>
    <div id="wrapper">
    <div id="main-body">
<div align="center" <h3 class="sign_in_text">Sign in<p></p>
</div>
<div align="center">
<br>
<form action="index.php" method="post">
    <div class="hb-container hb-l-inside-half hb-mg-top-none">
    <div class="auth-input-double">
    <label>
        <input type="text" name="ciiverseid" placeholder="Ciiverse ID" maxlength="32" />
    </label>
    <div style="margin-bottom:20px">
    <label>
        <input type="password" name="password" placeholder="Password" maxlength="32" />
    </label>
</div>
    <?php if(isset($incorrect) && $incorrect == true) {
        echo "<p style='color: red'>Ciiverse ID or Password is incorrect :(</p> ";
    } ?>
</div>
    </div>
<input type="submit" class="black-button apply-button" name="login" value="Login" />
</form>
</div>
    </div>
        </div>
    		</div>


</body></html>