<?php 
Require('../lib/connect.php');
session_start();

if(isset($_COOKIE['login_magic'])) {
    exit("You're already logged in pp head.");
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
$ciiverseid = $_POST['ciiverseid'];
$password = $_POST['password'];

$sql = "SELECT user_type FROM users WHERE ciiverseid = '".mysqli_real_escape_string($db,$ciiverseid)."'";
$result = mysqli_query($db,$sql);
$row = mysqli_fetch_array($result);

$count = mysqli_num_rows($result);

      if($count == 1) {

        $find_password = $db->query("SELECT password FROM users WHERE ciiverseid = '".mysqli_real_escape_string($db,$ciiverseid)."'");
        $user_pass = mysqli_fetch_array($find_password);

        if(!password_verify($_POST['password'], $user_pass['password'])) {

            $sha512_pass = hash('sha512', $_POST['password']);

            if($user_pass['password'] !== $sha512_pass) {
                $err = 'Password doesn\'t match.';
            } else {
                $new_pass = password_hash($password, PASSWORD_DEFAULT);
                $db->query("UPDATE users SET password = '$new_pass' WHERE ciiverseid = '".mysqli_real_escape_string($db,$ciiverseid)."'");
            }
        }

        if($row['user_type'] < 1) {
            $err = 'This user has been disabled.';
        }

        if(!isset($err)) {
        //header("location: /login/login.php?token=".$row['user_token']);
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randstring = '';
        for ($i = 0; $i < 60; $i++) {
            $randstring .= $characters[rand(0, $charactersLength - 1)];
        }
            
        $token = $randstring;
        $token_hash = hash('sha512', $token);

        $db->query("INSERT INTO sessions (token, owner, ip) VALUES ('$token_hash', '$ciiverseid', '".$_SERVER['REMOTE_ADDR']."')");
        $db->query("UPDATE users SET ip = '".$_SERVER['REMOTE_ADDR']."' WHERE ciiverseid = '$ciiverseid'");

        $_SESSION['loggedin'] = true;
        $_SESSION['ciiverseid'] = $ciiverseid;
        $_SESSION['pfp'] = null;
        $_SESSION['nickname'] = null;
        setcookie('login_magic', $token, time() + (86400 * 90), '/');

        $csrf = "";
        $characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
        $max = count($characters) - 1;
        for ($i = 0; $i < 32; $i++) {
            $rand = mt_rand(0, $max);
            $csrf .= $characters[$rand];
        }

        setcookie('csrf_token', $csrf, time() + (86400 * 90), '/');
        header("location: /");

        }

         } else {
        $err = 'That user doesn\'t exist.';
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