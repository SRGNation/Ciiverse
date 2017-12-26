<?php

   error_reporting(E_ALL);

   function rip() {
      http_response_code(503);
      exit('Ciiverse is having trouble connecting to the database right now. Please come back later.');
   }

   #If the redirect variable is set to 0 then it won't redirect at all.
   if(isset($_COOKIE['login_magic']) && $redirect !== '0') {
      if(!isset($_SESSION['loggedin'])) {
      header('location: /login/login.php?token='.$_COOKIE['login_magic'].'&redirect='.$redirect);
      }
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
      header('location: /login/logout.php');
         }
}

   @$db->query('SET time_zone = "-5:00";') || rip();
   date_default_timezone_set('America/New_York');

?>