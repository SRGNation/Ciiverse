<?php

session_start();
$redirect = '/';
require('lib/connect.php');
include('lib/htm.php');
include('lib/users.php');

$cinfo = $db->query("SELECT id, community_picture, community_name, community_banner, type FROM communities WHERE rd_oly = 'false' AND deleted = 0");

$featured = $db->query("SELECT * FROM communities WHERE featured = 1");

if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {

  $favorite_communities = $db->query("SELECT * FROM favorite_communities WHERE owner = '".$_SESSION['ciiverseid']."' ORDER BY id DESC LIMIT 8");
  $favorites_count = mysqli_num_rows($favorite_communities);

}

 ?>
<html>
<head>
<?php 

formHeaders('Communities - Ciiverse');

?>
</head>
<body>
<div id="wrapper" <?php if(!$_SESSION['loggedin']) { echo 'class="guest"'; } ?>>
<div id="sub-body">
  <?php
  if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
  echo form_top_bar($_SESSION['ciiverseid'], $_SESSION['nickname'], $_SESSION['pfp'], 'communities');
  } else { 
  echo ftbnli('communities'); }

        ?>
      </div>
    <div id="main-body">
      <div class="community-top-sidebar">
<div class="post-list-outline index-memo">
<h2 class="label"><?=MEMO_TITLE?></h2><p style="width: 90%; display: inine-block; padding: 10px;"><?=MEMO_CONTENT?></p>
</div>
</div>
      <div class="body-content" id="community-top">
        <div class="community-top-sidebar">
  </div>
      <div class="community-main">
      <?php 
       if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
        echo '<h3 class="community-title symbol community-favorite-title">Favorite Communities</h3>';
        if($favorites_count !== 0) {
        echo '<div class="card" id="community-favorite"><ul>';
        while($favorites = mysqli_fetch_array($favorite_communities)) {
          echo '<li><a class="icon-container" href="/communities/'.$favorites['community_id'].'"><img class="icon" src="'.community_info($favorites['community_id'],'icon').'"></a></li>';
        }
echo '<li class="read-more">
<a href="/communities/favorites" class="favorite-community-link symbol"><span class="symbol-label">Show More</span></a>
</li>
</ul>
</div>';
} else {
        echo '<div class="no-content no-content-favorites" id="community-favorite"><ul>
        <p>Tap the ☆ button on a community\'s page to have it show up as a favorite <br>community here.</p>
<li class="read-more">
<a href="/communities/favorites" class="favorite-community-link symbol"><span class="symbol-label">Show More</span></a>
</li>
</ul>
</div>';
      }
    }
      ?>
        <h3 class="community-title symbol">Featured Communities</h3>
        <div>
           <ul class="list community-list community-card-list test-hot-communities">
             <?php 

        while($infoc = mysqli_fetch_array($featured)) {
        echo '<li id="community" class="trigger test-community-list-item " data-href="/communities/'.$infoc['id'].'" tabindex="0">
    <img src="'.$infoc['community_banner'].'" class="community-list-cover">
  <div class="community-list-body">
  <span class="icon-container"><img src="'.$infoc['community_picture'].'" class="icon"></span>
  <div class="body">
      <a class="title" href="/communities/'.$infoc['id'].'" tabindex="-1">'.$infoc['community_name'].'</a>';
      if($infoc['type'] == 0) {
        echo '<span class="text">General Community</span>';
      }elseif($infoc['type'] == 1) {
        echo '<span class="text">Announcement Community</span>';
      }elseif($infoc['type'] == 2) {
        echo '
        <span class="platform-tag"><img src="/img/platform-tag-3ds.png"></span>
        <span class="text">3DS Games</span>';
      }elseif($infoc['type'] == 3) {
        echo '
        <span class="platform-tag"><img src="/img/platform-tag-wiiu.png"></span>
        <span class="text">Wii U Games</span>';
      }elseif($infoc['type'] == 4) {
        echo '
        <span class="platform-tag"><img src="/img/platform-tag-wiiu-3ds.png"></span>
        <span class="text">Wii U Games・3DS Games</span>';
      }
      echo '</div></div></li>'; } ?>
           </ul>
        </div>

        <h3 class="community-title symbol">All Communities</h3>
        <div>
          <ul class="list community-list community-card-list test-hot-communities">
        <?php 

        while($infoc = mysqli_fetch_array($cinfo)) {
        echo '<li id="community" class="trigger test-community-list-item " data-href="/communities/'.$infoc['id'].'" tabindex="0">
  <div class="community-list-body">
  <span class="icon-container"><img src="'.$infoc['community_picture'].'" class="icon"></span>
  <div class="body">
      <a class="title" href="/communities/'.$infoc['id'].'" tabindex="-1">'.$infoc['community_name'].'</a>';
      if($infoc['type'] == 0) {
        echo '<span class="text">General Community</span>';
      }elseif($infoc['type'] == 1) {
        echo '<span class="text">Announcement Community</span>';
      }elseif($infoc['type'] == 2) {
        echo '
        <span class="platform-tag"><img src="/img/platform-tag-3ds.png"></span>
        <span class="text">3DS Games</span>';
      }elseif($infoc['type'] == 3) {
        echo '
        <span class="platform-tag"><img src="/img/platform-tag-wiiu.png"></span>
        <span class="text">Wii U Games</span>';
      }elseif($infoc['type'] == 4) {
        echo '
        <span class="platform-tag"><img src="/img/platform-tag-wiiu-3ds.png"></span>
        <span class="text">Wii U Games・3DS Games</span>';
      }
      echo '</div></div></li>'; } 
      ?>
  </div>
  </ul>
  </div>
</li>
</div>  
        </div>
  <?php 
    printFooter();
    ?>
      </div>
    </div> 
        </div>
</body>
</html>