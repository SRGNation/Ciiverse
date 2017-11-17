<?php

require('lib/connect.php');
include('lib/menu.php');
session_start();

if(isset($_SESSION['ciiverseid'])) { 

$cvid = $_SESSION['ciiverseid'];

$sql = "SELECT is_owner FROM users WHERE ciiverseid = '$cvid' ";
$result = mysqli_query($db,$sql);
$row = mysqli_fetch_array($result,MYSQLI_ASSOC);

$is_owner = $row['is_owner'];

}

#Use Cedar xddd

 ?>
<html>
<head>
<title>Ciiverse - Community list</title>
<link rel="stylesheet" href="offdevice.css"></link>
<link rel="shortcut icon" href="icon.png" />
<script async src="https://www.google-analytics.com/analytics.js"></script>
<script src="js/complete-en.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
</head>
<body>
<div id="wrapper">
<div id="sub-body">
  <?php
  if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
  echo form_top_bar($_SESSION['ciiverseid'], $_SESSION['nickname'], $_SESSION['pfp'], 'communities');
  } else { 
  echo ftbnli('communities'); }

        ?>
        <!-- <menu id="global-menu">
          <li id="global-menu-logo"><h1><a href="/"><img src="ciiverse.png" alt="Miiverse" width="165" height="30"></a></h1></li>
           <li id="global-menu-list">
            <ul>
             <?php 
             if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
               echo '<li id="global-menu-mymenu"><a href="/users/profile.php?ciiverseid='. $_SESSION['ciiverseid'] .'"><span class="icon-container '; if($is_owner == 'true') {echo "official-user";} echo '"><img src="'.$_SESSION['pfp']. '"></span><span>User Page</span></a></li>';
             }

             ?>
             <li id="global-menu-community" class="selected"><a href="/" class="symbol"><span>Communities</span></a></li>
                   <?php 
                  if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                  echo '<li id="global-menu-news"><a class="symbol" href="/notifications.php"><span class="badge" style="display: none;"></span></a></li>';
                }
                ?>
<li id="global-menu-my-menu"><button class="symbol js-open-global-my-menu open-global-my-menu"></button>

                <menu id="global-my-menu" class="invisible none">

                  <li>
    <?php
    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) { } else {
    echo '<a href="register" class="symbol my-menu-guide"><span>Sign Up</span></a>';
    }
    ?>                  </li>

<li>
  <?php 
  if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) { } else {
    echo '<a href="login" class="symbol my-menu-guide"><span>Sign In</span></a>';
  }
    ?>
                  </li>
                      <?php 
  if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    echo '<li><a href="/users/edit_profile.php" class="symbol my-menu-profile-setting"><span>Edit Profile</span></a></li>	
	<li><a class="symbol my-menu-info" href="/changelog"><span>Ciiverse Changelog</span></a></li>';
  }
    ?>
                      <?php 
  if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    echo '<li><a href="/login/logout.php" class="symbol my-menu-guide"><span>Log Out</span></a></li>';
  }
    ?> -->
                </menu>
              </li>
            </ul>
          </li>
        </menu>
      </div>
    <div id="main-body">
      <div class="body-content" id="community-top">
        <div class="community-top-sidebar">
      <div id="identified-user-banner">
      <a href="/posts/verified.php" data-pjax="#body" class="list-button us">
        <span class="title">Get the latest news here!</span>
        <span class="text">Posts from Verified Users</span>
      </a>
    </div>
  </div>
      <div class="community-main">
        <h3 class="community-title symbol">Game communities</h3>
        <div>
          <ul class="list community-list community-card-list test-hot-communities">
          <li id="community" class="trigger test-community-list-item " data-href="/communities/1" tabindex="0">
  <img src="https://d3esbfg30x759i.cloudfront.net/cnj/zlCfzRAwCC0sfbJLk6" class="community-list-cover">
  <div class="community-list-body">
  <span class="icon-container"><img src="https://d3esbfg30x759i.cloudfront.net/cip/zlCfzRAwCC0sfbJLk6" class="icon"></span>
  <div class="body">
      <a class="title" href="/communities?cid=1" tabindex="-1">New Super Luigi U</a>
        <span class="platform-tag"><img src="https://d13ph7xrk1ee39.cloudfront.net/img/platform-tag-wiiu.png?1FkN4AaoGMfiRidwKs0h3w"></span>
      <span class="text">Wii U Games</span>
  </div>
</ul>
  
  </div>
</li>
</div>
        </div>
      </div>

    </div>
        </div>
</body>
</html>