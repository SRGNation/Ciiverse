<?php 

require('../lib/connect.php');

session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST') {

        $ciiverseid = $_POST['ciiverseid'];
        $nickname = $_POST['nickname'];
        $password = $_POST['password'];
        $pfp = $_POST['pfp'];


        $password = hash('sha512', $password);
        $cvid_hash = hash('sha512', $ciiverseid);

        $token = "$password $cvid_hash";
        $token = str_replace(' ', 'a', $token);

        if(empty($ciiverseid & $nickname & $password)) {
            die("The needed feilds are empty, You need to put in a Ciiverse ID, Nickname, and Password to continue");
        }

        $sql = "SELECT id FROM users WHERE ciiverseid = '$ciiverseid' ";
        $result = mysqli_query($db,$sql);
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
        $count = mysqli_num_rows($result);

        if($count !== 1) {

            $sql_register = "INSERT INTO users (nickname, pfp, ciiverseid, password, user_token) VALUES ('".mysqli_real_escape_string($db,$nickname)."', '".mysqli_real_escape_string($db,$pfp)."', '".mysqli_real_escape_string($db,$ciiverseid)."', '".mysqli_real_escape_string($db,$password)."', '".mysqli_real_escape_string($db,$token)."')";
            mysqli_query($db,$sql_register);

            header("location: /login/login.php?token=$token");

        } else {
            echo "This Ciiverse ID already exists, please choose another one.";
        }
}

?>

<html>
<title>Create an account - Ciiverse</title>
<link rel="shortcut icon" href="../icon.png">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link rel="stylesheet" href="../offdevice.css" type="text/css">
<link rel="stylesheet" href="../ciiverse.css" type="text/css">
<link rel="stylesheet" href="../login/login_style.css" type="text/css">
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
</div>

<input type="submit" class="black-button apply-button" name="create" value="Create Account" />
</form>
</div>
    </div>
        </div>
    		</div>


</body></html>
