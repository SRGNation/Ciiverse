<?php

   error_reporting(E_ALL);
   
   $discord_webhook = '';

   #This is just a tiny feature I made so you're able to enable/disable custom profile links.
   $allow_url_avatars = true;

   function rip() {
      http_response_code(503);
      exit('Ciiverse is having trouble connecting to the database right now. Please come back later.');
   }

   #If the redirect variable is set to 0 then it won't redirect at all.
   if(isset($_COOKIE['login_magic']) && $redirect !== '0') {
      if(!isset($_SESSION['loggedin'])) {
      header('location: /login/login.php?token='.$_COOKIE['login_magic'].'&redirect='.$redirect);
      }
   } else {
      $_SESSION['ciiverseid'] = '';
      $_SESSION['loggedin'] = false;
   }

   define('DB_SERVER', 'localhost');
   define('DB_USERNAME', 'root');
   define('DB_PASSWORD', '');
   define('DB_DATABASE', 'ciiverse');
   $db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);

   if(!$db) {
      rip();
   }

   if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 'true') {
   $query = $db->query("SELECT * FROM users WHERE ciiverseid = '".$_SESSION['ciiverseid']."'");
   $user = mysqli_fetch_array($query);

   if($user['user_type'] < 1) {
      header('location: /login/logout.php?csrftoken='.$_COOKIE['csrf_token']);
         }
}

   $memo_title = "This is a test.";
   $memo_content = "Hello World is a boring statement to say.";

   @$db->query('SET time_zone = "-4:00";') || rip();
   date_default_timezone_set('America/New_York');

   /*
   include('lib/users.php');
   echo '
   <head>
   <title>Ciiverse</title>
   <link rel="shortcut icon" href="/img/icon.png" />
   </head>
   Ciiverse has ended as of 1/28/2018. Thank you for using the service!<br>';
   if($_SESSION['loggedin'] == true) {
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