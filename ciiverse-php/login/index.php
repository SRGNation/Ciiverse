<?php 
Require('../lib/frick.php');
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST") {
$ciiverseid = $_POST['ciiverseid'];
$password = $_POST['password'];

$password = hash('sha512', $password);

$sql = "SELECT user_token, user_type FROM users WHERE ciiverseid = '".mysqli_real_escape_string($db,$ciiverseid)."' AND password = '".mysqli_real_escape_string($db,$password)."' ";
$result = mysqli_query($db,$sql);
$row = mysqli_fetch_array($result);

$count = mysqli_num_rows($result);

      if($count == 1) {

        if($row['user_type'] == 0) {
            $err = 'This user has been disabled.';
        }

        if(!isset($err)) {
        header("location: /login/login.php?token=".$row['user_token']);
        }

         } else {
        $err = 'Ciiverse ID or password is incorrect.';
      }

}

?>

<html>
<title>Login to Ciiverse</title>
<link rel="shortcut icon" href="/img/icon.png">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link rel="stylesheet" href="/offdevice.css" type="text/css">
<link rel="stylesheet" href="/ciiverse.css" type="text/css">
<link rel="stylesheet" href="login_style.css" type="text/css">
</head>
<body>
    <div id="wrapper">
    <div id="main-body">
<div align="center"><h3 class="sign_in_text">Sign in</h3>
<p>If you don't have an account, you can <a href="/register/">sign up</a> here.</p>
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
    <?php if(isset($err)) {
        echo "<p style='color: red'>".$err."</p> ";
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