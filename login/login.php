<?php 

Require('../lib/frick.php');

session_start();

$token = $_GET['token'];

         $ses_sql = "SELECT pfp, nickname, prof_desc, ciiverseid FROM users WHERE user_token = '".mysqli_real_escape_string($db,$token)."' ";

         $ses_res = $db->query($ses_sql);
         $ses_row = $ses_res->fetch_assoc();

         $count = mysqli_num_rows($ses_res);

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
       
         header("location: ..");
     } else {
     	die("Couldn't find a user with that token.");
     }

?>