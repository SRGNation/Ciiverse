<?php
   require_once('config.php');
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

   date_default_timezone_set($db->real_escape_string(TIMEZONE));

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