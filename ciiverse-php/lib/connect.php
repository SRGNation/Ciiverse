<?php

   error_reporting(E_ALL);

    /* exit("
      <head>
      <link rel=\"shortcut icon\" href=\"/img/icon.png\" />
      <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no\">
      <title>The Big Meme</title>
      </head>
      <body>
      <h2>Ciiverse Sucks lol.</h2>
      <p>Hey dudes you should try Pearl it's the best Miiverse clone ever and it's much better than Closedverse, Cedar, Grape, Openverse, Ciiverse, Charlieverse, New Miiverse, Miiverse NEO, Exverse, Wolfverse, Anarchyverse, Discordverse, Reverse, Anarchyverse 2, Hentaiverse, Mokeverse, Oasis, Nanoverse, Cyuuverse 2, Miiversing, Funverse, Ajarverse, Yosaverse, Florianverse, Speedverse, Cosmicverse, Grandverse, Retardverse, Archiverse, Memeverse, Darkverse, Miiverse Reloaded, Deckverse, Xiiverse, Snoopyverse, Pocket, Anonyverse, Furendverse, MOTHERverse, MVHaven, Mooverse, Gayverse, Cowverse, Niiverse, Miicord, Waluigiverse, Swedeverse 2.0, Cloververse, Wiiverse, Leafverse, Newverse, Angularverse, oss-miiverse, Gnarlyverse, Discoverse, Neoverse, Worldverse, Universe, Petitverse, Yourverse, abcverse, Freeverse, Nokoverse, Nextverse, Friendsverse, Miintendo, Miiverseplus, Ultimateverse, Squareverse, Smashverse, Russiaverse, Riiverse, Weeblyverse, Ourverse, and Winverse COMBINED!</p>
         <p>All jokes aside I really do hope Pearl succeeds it's a pretty neat concept and I would like to see it become a real thing.</p>
      </body>
   "); */

   function rip() {
      http_response_code(503);
      exit('Ciiverse is having trouble connecting to the database right now. Please come back later.');
   }

   //Standard database login stuff.
   const DB_SERVER = 'localhost';
   const DB_USERNAME = 'root';
   const DB_PASSWORD = '';
   const DB_DATABASE = 'ciiverse';

   //This is for image uploading. If you leave this empty, it will just use Reverb's account to upload images.
   const CLOUDINARY_NAME = null;
   const CLOUDINARY_KEY = null;
   const CLOUDINARY_PRESET = null;

   //This is for if you want your instance to have ReCaptcha support. Optional, but highly reccomended.
   const RECAPTCHA_KEY = null;
   const RECAPTCHA_SECRET = null;

   //This is for Discord Webhook support.
   const DISCORD_WEBHOOK = null;

   $db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);

   if(!$db) {
      rip();
   }

   if(isset($_COOKIE['login_magic'])) {
      if(!isset($_SESSION['loggedin'])) {
      //header('location: /login/login.php?token='.$_COOKIE['login_magic'].'&redirect='.$redirect);

         $hash_token = hash('sha512', $_COOKIE['login_magic']);
         $find_token = $db->query("SELECT * FROM sessions WHERE token = '$hash_token'");
         $session_data = mysqli_fetch_array($find_token);

         if(mysqli_num_rows($find_token) !== 0) {
            $get_user = $db->query("SELECT user_type FROM users WHERE ciiverseid = '".$session_data['owner']."'");
            $chk_disabled = mysqli_fetch_array($get_user);

            if($chk_disabled['user_type'] !== 0) {
               $_SESSION['loggedin'] = true;
               $_SESSION['ciiverseid'] = $session_data['owner'];
               $_SESSION['pfp'] = null;
               $_SESSION['nickname'] = null;
               $db->query("UPDATE users SET ip = '".$_SERVER['REMOTE_ADDR']."' WHERE ciiverseid = '".$session_data['owner']."'");
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
   $query = $db->query("SELECT * FROM users WHERE ciiverseid = '".$_SESSION['ciiverseid']."'");
   $user = mysqli_fetch_array($query);

   if($user['user_type'] < 1) {
      setcookie('login_magic', '', time() - 3600, '/');
      setcookie('csrf_token', '', time() - 3600, '/');
   }
   }

   $memo_title = "What is Ciiverse?";
   $memo_content = "Ciiverse is an open source Miiverse clone created by SRGNation. I wouldn't reccomend using it for your 431243125th Miiverse clone rehost, because it is still extremely unsecure. However, this version adds a few more features and fixes up a few things to make it less confusing. Kind of inspired by PF2M releasing a slightly fixed up version of Openverse, and also for Ciiverse's 3rd anniversary of existing...";

   date_default_timezone_set('America/Phoenix');

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