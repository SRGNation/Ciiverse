<?php 

session_start();
require('../lib/connect.php');

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

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        if(!preg_match('/^[a-zA-Z0-9_-]+$/', $ciiverseid)) {
            $err = 'Ciiverse ID\'s can only contain letters, numbers, dashes, and underscores.';
        }

        if(empty($ciiverseid && $nickname && $password && $password_retype)) {
            $err = 'The needed feilds are empty.';
        }

        if(!empty(RECAPTCHA_SECRET)) {
            $ch = curl_init('https://www.google.com/recaptcha/api/siteverify');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['secret' => RECAPTCHA_SECRET, 'response' => $_POST['g-recaptcha-response'], 'remoteip' => $_SERVER['REMOTE_ADDR']]));
            $response = curl_exec($ch);
            curl_close($ch);
            $responseJSON = json_decode($response);
            if($responseJSON->success == 0) {
                $err = 'The ReCAPTCHA was not solved correctly.';
            }
        }

        $sql = "SELECT id FROM users WHERE ciiverseid = '$ciiverseid' ";
        $result = mysqli_query($db,$sql);
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
        $count = mysqli_num_rows($result);

        if($count !== 1) {

	    if(!isset($err)) {
            if(AUTO_IMAGE_PERMISSIONS) {
                $imagePerms = 1;
            } else {
                $imagePerms = 0;
            }

            $stmt = $db->prepare('INSERT INTO users (nickname, ciiverseid, password, can_post_images, user_type) VALUES (?, ?, ?, ?, 1)');
            $stmt->bind_param('sssi', $nickname, $ciiverseid, $password_hash, $imagePerms);
            $stmt->execute();

            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randstring = '';
            for ($i = 0; $i < 60; $i++) {
                $randstring .= $characters[rand(0, $charactersLength - 1)];
            }
            
            $token = $randstring;
            $token_hash = hash('sha512', $token);

            $stmt = $db->prepare('INSERT INTO sessions (token, owner, ip) VALUES (?, ?, ?)');
            $stmt->bind_param('sss', $token_hash, $ciiverseid, $_SERVER['REMOTE_ADDR']);
            $stmt->execute();

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

            //header('location: /login/login.php?token='.$token);

            }

        } else {
            $err = 'This Ciiverse ID already exists, please choose another one.';
        }
}

?>

<html>
<head>
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
    <?php if(!empty(RECAPTCHA_KEY)) { ?><br><br>
        <script src="https://www.google.com/recaptcha/api.js"></script>
        <div class="g-recaptcha" style="display:inline-block" data-sitekey="<?=htmlspecialchars(RECAPTCHA_KEY)?>"></div>
    <?php } ?>
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