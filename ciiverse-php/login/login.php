<?php 

Require('../lib/frick.php');

session_start();

$token = $_GET['token'];

         $ses_sql = "SELECT pfp, nickname, prof_desc, ciiverseid, user_type FROM users WHERE user_token = '".mysqli_real_escape_string($db,$token)."' ";

         $ses_res = $db->query($ses_sql);
         $ses_row = $ses_res->fetch_assoc();

         $count = mysqli_num_rows($ses_res);

         if($ses_row['user_type'] == 0) {
            $dont_update_ip == true;
         }

         if($count == 1) {
         $ciiverseid = $ses_row['ciiverseid'];

 		 $_SESSION['loggedin'] = true;
         $_SESSION['ciiverseid'] = $ciiverseid;

         $pfp = $ses_row['pfp'];
         $nickname = $ses_row['nickname'];
         $prof_desc = $ses_row['prof_desc'];

         $_SESSION['pfp'] = $pfp;
         $_SESSION['nickname'] = $nickname;
         $_SESSION['prof_desc'] = $prof_desc;

         if(empty($_SESSION['pfp'])) {
            $_SESSION['pfp'] = "/img/defult_pfp.png";
         }

         if(!isset($dont_update_ip)) {
            $db->query("UPDATE users SET ip = '".$_SERVER['REMOTE_ADDR']."' WHERE ciiverseid = '".$_SESSION['ciiverseid']."' ");
         }

         if(!isset($_COOKIE['login_magic'])) {
         setcookie('login_magic', $token, time() + (86400 * 30), '/');
         }

         if(!isset($_COOKIE['csrf_token'])) {
            $csrf = "";
            $characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
            $max = count($characters) - 1;
            for ($i = 0; $i < 32; $i++) {
                $rand = mt_rand(0, $max);
                $csrf .= $characters[$rand];
            }

            setcookie('csrf_token', $csrf, time() + (86400 * 30), '/');
         }

         if(isset($_GET['redirect'])) {
         header("location: ".$_GET['redirect']); 
        } else {
            header("location: /");
        }

     } else {
        $_SESSION['loggedin'] = false;
        $_SESSION['ciiverseid'] = '';
        setcookie('login_magic', '', time() - 3600, '/');
        setcookie('csrf_token', '', time() - 3600, '/');
     	header("location: /");
     }

?>