<?php
   //Standard database login stuff. Required.
   const DB_SERVER = 'localhost';
   const DB_USERNAME = 'root';
   const DB_PASSWORD = '';
   const DB_DATABASE = 'ciiverse';

   //This is for image uploading. If you leave this empty, it will just use Reverb's account to upload images. Optional.
   const CLOUDINARY_NAME = null;
   const CLOUDINARY_KEY = null;
   const CLOUDINARY_PRESET = null;

   //This is for if you want your instance to have ReCAPTCHA support. Optional, but highly reccomended.
   const RECAPTCHA_KEY = null;
   const RECAPTCHA_SECRET = null;

   //This is for Discord Webhook support. There is a function called post_to_discord() that I never used for Ciiverse, so uhh... Why not? Optional.
   const DISCORD_WEBHOOK = null;

   //This is basically the about page for Ciiverse.
   $memo_title = "What is Ciiverse?";
   $memo_content = "Ciiverse is an open source Miiverse clone created by SRGNation. I wouldn't reccomend using it for your 431243125th Miiverse clone rehost, because it is still extremely unsecure. However, this version adds a few more features and fixes up a few things to make it less confusing. Kind of inspired by PF2M releasing a slightly fixed up version of Openverse, and also for Ciiverse's 3rd anniversary of existing...";

   error_reporting(E_ALL);

   function showError($err) 
   {
      echo '<p>'.$err.'</p>';
      exit('<p>If this error keeps happening, contact the webmaster of this site.</p>');
   }

   function rip() {
      http_response_code(500);
      showError('Ciiverse is having trouble connecting to the database right now. Please come back later.');
   }

   $db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);

   if(!$db) {
      rip();
   }

   if(isset($_COOKIE['login_magic'])) {
      if(!isset($_SESSION['loggedin'])) {
         $hash_token = hash('sha512', $_COOKIE['login_magic']);

         $stmt = $db->prepare("SELECT id, owner FROM sessions WHERE token = ?");
         $stmt->bind_param('s', $hash_token);
         $stmt->execute();
         if($stmt->error)
         {
            showError('An error occured while trying to get your token.');
         }
         $result = $stmt->get_result();
         $sesData = $result->fetch_assoc();

         if($result->num_rows > 0) {
            $stmt = $db->prepare("SELECT user_type FROM users WHERE ciiverseid = ?");
            $stmt->bind_param('s', $sesData['owner']);
            $stmt->execute();
            if($stmt->error)
            {
               showError('An error occured while trying to check if the user was disabled.');
            }
            $result = $stmt->get_result();
            $isDisabled = $result->fetch_assoc();

            if($isDisabled['user_type'] !== 0) {
               $_SESSION['loggedin'] = true;
               $_SESSION['ciiverseid'] = $sesData['owner'];
               $_SESSION['pfp'] = null;
               $_SESSION['nickname'] = null;
               $stmt = $db->prepare("UPDATE users SET ip = ? WHERE ciiverseid = ?");
               $stmt->bind_param('ss', $_SERVER['REMOTE_ADDR'], $sesData['owner']);
               $stmt->execute();
               if($stmt->error)
               {
                  showError('An error occured while trying to update the user\'s ip address.');
               }
            } else {
               setcookie('login_magic', '', time() - 3600, '/');
               setcookie('csrf_token', '', time() - 3600, '/');
            }
         } else {
            setcookie('login_magic', '', time() - 3600, '/');
            setcookie('csrf_token', '', time() - 3600, '/');
         }
      }
   } else {
      $_SESSION['ciiverseid'] = '';
      $_SESSION['loggedin'] = false;
   }

   if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 'true') {
      $stmt = $db->prepare("SELECT * FROM users WHERE ciiverseid = ?");
      $stmt->bind_param('s', $_SESSION['ciiverseid']);
      $stmt->execute();
      if($stmt->error)
      {
         showError('An error occured while trying to get the user data.');
      }
      $result = $stmt->get_result();
      $user = $result->fetch_assoc();

      if($user['user_type'] < 1) {
         setcookie('login_magic', '', time() - 3600, '/');
         setcookie('csrf_token', '', time() - 3600, '/');
      }
   }

   date_default_timezone_set('America/Phoenix');

   /*
   include('lib/users.php');
   echo '
   <head>
   <title>Ciiverse</title>
   <link rel="shortcut icon" href="/img/icon.png" />
   </head>
   Ciiverse has ended as of 1/28/2018. Thank you for using the service!<br>';
   if($_SESSION['loggedin']) {
      echo '<img src="'.user_pfp($_SESSION['ciiverseid'],1).'"></img>';
   } else {
      echo 'If you already have a Ciiverse account, you can still see your profile picture if you <a href="/login">sign in!</a>';
   }
   echo '<br><br><h3>Why did it you end it though?</h3>
   <p>Because it sucked and every feature added here is also on every other Miiverse clone ever. I also really didn\'t want to work on it anymore, and there are a bunch of other Miiverse clones out there.</p>
   <h3>Will we get to save our posts?</h3>
   <p>No, but I could maybe make the entire database downloadable. But not right now cuz i\'m lazy.</p>
   <h3>How the hecc do I contact you?</h3>
   <p>You can contact me from discord, if you have it. <b>SRGNation#3309</b></p>
   <h3>Are you planning on making another social media site or Miiverse clone or something?</h3>
   <p>Maybe I can bring Ciiverse back when I finally have the motivation to work on it again and make new and original features.</p>';
   exit();
   */

?>