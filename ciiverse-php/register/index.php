<?php 

require('../lib/connect.php');
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST') {

        $ciiverseid = $_POST['ciiverseid'];
        $nickname = $_POST['nickname'];
        $password = $_POST['password'];
        $password_retype = $_POST['password_retype'];

        if(strlen($ciiverseid) > 32) {
            $err = 'Ciiverse ID is too long.';
        }

        if(strlen($nickname) > 32) {
            $err = 'Nickname is too long.';
        }        

        if(strlen($password) > 32) {
            $err = 'Password is too long.';
        }

        if($password !== $password_retype) {
            $err = 'Passwords don\'t match';
        }

        $password_hash = hash('sha512', $password);
        $cvid_hash = hash('sha512', $ciiverseid);

        $token = "$password_hash $cvid_hash";
        $token = str_replace(' ', 'a', $token);

        if(!preg_match('/^[a-zA-Z0-9_-]+$/', $ciiverseid)) {
            $err = 'Ciiverse ID\'s can only contain letters, numbers, dashes, and underscores.';
        }

        if(empty($ciiverseid && $nickname && $password && $password_retype)) {
            $err = 'The needed feilds are empty.';
        }

        $sql = "SELECT id FROM users WHERE ciiverseid = '$ciiverseid' ";
        $result = mysqli_query($db,$sql);
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
        $count = mysqli_num_rows($result);

        if($count !== 1) {

	if(!isset($err)) {
            $sql_register = "INSERT INTO users (nickname, pfp, ciiverseid, password, user_token, user_type) VALUES ('".mysqli_real_escape_string($db,$nickname)."', '".mysqli_real_escape_string($db,$pfp)."', '".mysqli_real_escape_string($db,$ciiverseid)."', '".mysqli_real_escape_string($db,$password_hash)."', '".mysqli_real_escape_string($db,$token)."', 1)";
            mysqli_query($db,$sql_register);

            header("location: /login/login.php?token=$token");
            }

        } else {
            $err = 'This Ciiverse ID already exists, please choose another one.';
        }
}

?>

<html>
<title>Create an account - Ciiverse</title>
<link rel="shortcut icon" href="/img/icon.png">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link rel="stylesheet" href="/offdevice.css" type="text/css">
<link rel="stylesheet" href="/ciiverse.css" type="text/css">
<link rel="stylesheet" href="/login/login_style.css" type="text/css">
</head>
<body>
    <div id="wrapper">
    <div id="main-body">
<div align="center"> 
    <h3 class="sign_in_text">Create an account</h3><p></p>
<div>
<div align="center">
<br>
<form action="index.php" method="post">
    <div class="hb-container hb-l-inside-half hb-mg-top-none">
    <div class="auth-input-double">
    <label>
        <input type="text" name="ciiverseid" placeholder="Ciiverse ID" maxlength="32" />
    </label>
        <label>
        <input type="text" name="nickname" placeholder="Nickname" maxlength="32" />
    </label>
    <label>
        <input type="password" name="password" placeholder="Password" maxlength="32" />
    </label>
    <label>
        <input type="password" name="password_retype" placeholder="Retype Password" maxlength="32" />
    </label>

</div>

<?php 

if(isset($err)) {
    echo '<br><p style="color: red">'.$err.'</p>';
}

?>

<br>
<input type="submit" class="black-button apply-button" name="create" value="Create Account" />
</form>
</div>
    </div>
        </div>
    		</div>


</body></html>