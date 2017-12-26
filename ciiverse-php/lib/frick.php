<?php

   #frick.php is pretty much connect.php but they are only for logins.

   error_reporting(E_ALL);

   function rip() {
      http_response_code(503);
      exit('Ciiverse is having trouble connecting to the database right now. Please come back later.');
   }

   define('DB_SERVER', 'localhost');
   define('DB_USERNAME', 'root');
   define('DB_PASSWORD', '');
   define('DB_DATABASE', 'ciiverse');
   $db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);

   if(!$db) {
      rip();
   }
?>