<?php

session_start();
require("lib/connect.php");
include("lib/htm.php");
include("lib/users.php");

if(!isset($_GET['user'])) {
  $favorites = $db->query("SELECT * FROM favorite_communities WHERE owner = '".$_SESSION['ciiverseid']."' ORDER BY id DESC");  
} else {
  $favorites = $db->query("SELECT * FROM favorite_communities WHERE owner = '".mysqli_real_escape_string($db,$_GET['user'])."' ORDER BY id DESC");
}

$fav_count = mysqli_num_rows($favorites);

#Check if user exists or something I DON'T KNWO
if(isset($_GET['user'])) {
  $user_check = $db->query("SELECT * FROM users WHERE ciiverseid = '".mysqli_real_escape_string($db,$_GET['user'])."'");
  $johnnyjohnny = mysqli_fetch_array($user_check);

  if(mysqli_num_rows($user_check) == 0) {
    exit("user doesn't exist");
  }
}

$posts = $db->query("SELECT * FROM posts WHERE owner = '".$_SESSION['ciiverseid']."' AND deleted = 0 ORDER BY post_id DESC");
$comments = $db->query("SELECT * FROM comments WHERE owner = '".$_SESSION['ciiverseid']."' ORDER BY id DESC");
$yeahs = $db->query("SELECT * FROM yeahs WHERE owner = '".$_SESSION['ciiverseid']."' ORDER BY yeah_id DESC");
$profile_tags = $db->query("SELECT * FROM profile_tags WHERE owner = '".$_SESSION['ciiverseid']."' ORDER BY id ASC");

$post_count = mysqli_num_rows($posts);
$reply_count = mysqli_num_rows($comments);
$yeah_count = mysqli_num_rows($yeahs);

?>

<html>
<head>
  <?php
    formHeaders('Favorite Communities - Ciiverse');
    ?>
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
      </div>
      <div id="main-body">
      <div id="sidebar" class="user-sidebar">
          <div class="sidebar-container">
      
    <div id="sidebar-profile-body" class="without-profile-post-image">

      <div class="icon-container <?php echo print_badge($_SESSION['ciiverseid']); ?>">
        <a href="/users/<?php echo $_SESSION['ciiverseid']; ?>">
          <img src="<?php echo user_pfp($_SESSION['ciiverseid'],0); ?>" class="icon">
        </a>

      </div>
      <?php 
        if($user['user_type'] > 1) {
          printOrganization($user['user_type'],0);
        }
      ?>
      <a href="/users/<?php echo $_SESSION['ciiverseid']; ?>" class="nick-name"><?php echo $user['nickname']?></a>
      <p class="id-name"><?php echo $_SESSION['ciiverseid']; ?></p>
      </div>
    </div> 
    <div class="sidebar-setting sidebar-container">
  <div class="sidebar-post-menu">
    <a href="/users/<?php echo $_SESSION['ciiverseid']; ?>" class="sidebar-menu-post with-count symbol <?php if($page == 1) {echo'selected';} ?>">
      <span>All posts</span>
      <span class="post-count">
          <span class="test-post-count" id="js-my-post-count"><?php echo $post_count; ?></span>
        </span>
      </a>
    <a href="/users/<?php echo $userid; ?>/replies" class="sidebar-menu-post with-count symbol <?php if($page == 3) {echo'selected';} ?>">
      <span>Replies</span>
      <span class="post-count">
          <span class="test-post-count" id="js-my-post-count"><?php echo $reply_count; ?></span>
        </span>
      </a>
    <a class="sidebar-menu-empathies with-count symbol <?php if($page == 2) {echo'selected';} ?>" href="/users/<?php echo $userid; ?>/empathies">
      <span>Yeahs</span>
      <span class="post-count">
        <span class="test-empathy-count"><?php echo $yeah_count; ?></span>
      </span>
    </a>
  </div>
</div>

                 <div class="sidebar-container sidebar-profile">
       <?php 
       if(!empty($user['prof_desc'])) {
        echo '<div class="profile-comment">
        <p class="js-truncated-text">' .htmlspecialchars($user['prof_desc']).'</p>
              </div>';
       }
       ?>
       <div class="user-data">
        <div class="user-main-profile data-content">
<h4><span>NNID</span></h4>
<div class="note"><?php if(!empty($user['nnid'])){echo $user['nnid'];}else{echo 'Not set.';} ?></div>
<?php 

  while($tags = mysqli_fetch_array($profile_tags)) {

    echo '<div class="user-main-profile data-content">
      <h4><span>'.$tags['tag_name'].'</span></h4>
      <div class="note">'.$tags['tag_content'].'</div>
    </div>';

  } 

        ?>
</div>
<div class="game-skill data-content">
  <!-- Nothings here lol. -->
</div>
</div>
  </div>
 	</div>
 	 <div class="main-column"><div class="post-list-outline">
  <h2 class="label"><?php 
  if(!isset($_GET['user'])) {
    echo "Your";
  } else {
    echo $johnnyjohnny['nickname'].'\'s';
  }
  ?> Favorite Communities</h2>
  <?php
  if($fav_count == 0) { 

    if(!isset($_GET['user'])) {
      echo "You have no favorite communities."; 
    } else {
      echo $johnnyjohnny['nickname']." has no favorite communities";
    }

  } else {

  	while($favorite = mysqli_fetch_array($favorites)) {
  echo '<ul class="list community-list">
  	<li class="trigger" data-href="/communities/'.$favorite['community_id'].'">
  	<div class="community-list-body">
  		<a class="icon-container" href="/communities/'.$favorite['community_id'].'">
  			<img class="icon" src="'.community_info($favorite['community_id'],'icon').'"></a>
  			<div class="body"><a class="title" href="/communities/'.$favorite['community_id'].'">'.community_info($favorite['community_id'],'name').'</a></div></div></li>';
  		}
  		}
  			?>
  			</ul>
</div>
</div>
 	</div>
 </body>
 </html>