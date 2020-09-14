<?php 

session_start();
$redirect = 0;
require("../lib/connect.php");
include("../lib/htm.php");
include("../lib/users.php");

$ciiverseid = $_GET['cvid'];

if($user['user_level'] < 1) {
  exit("You are not authorized to perform this action.");
}

$sql = "SELECT * FROM users WHERE ciiverseid = '".mysqli_real_escape_string($db,$ciiverseid)."' ";
$query = mysqli_query($db,$sql);
$row = mysqli_fetch_array($query);

$count = mysqli_num_rows($query);

if($count == 0) {
	exit('An error occured.');
}

if($row['user_level'] >= $user['user_level']) {
  exit('An error occured.');
}

$posts = $db->query("SELECT * FROM posts WHERE owner = '".$_SESSION['ciiverseid']."' AND deleted = 0 ORDER BY post_id DESC");

$comments = $db->query("SELECT * FROM comments WHERE owner = '".$_SESSION['ciiverseid']."' ORDER BY id DESC");

$yeahs = $db->query("SELECT * FROM yeahs WHERE owner = '".$_SESSION['ciiverseid']."' ORDER BY yeah_id DESC");

$post_count = mysqli_num_rows($posts);
$reply_count = mysqli_num_rows($comments);
$yeah_count = mysqli_num_rows($yeahs);

$page = 5;
$userid = $_SESSION['ciiverseid'];

/*
    <?php 
    if($user['user_type'] > 2) {
    echo '<li>
    <p>Log in to account. <br> This will log you out of your own account and log in to '.$row['nickname'].'\'s account. <br>
    <a href="/login/login.php?token='.$row['user_token'].'&reqwre=1">Login.</a></p>
    </li>';
    }
    ?>
    <br>

    <p>Nickname: <?php echo htmlspecialchars($row['nickname']); ?></p>
    <p>Profile pic: <?php echo htmlspecialchars($row['pfp']); ?></p>
    <p>Profile pic type: <?php if($row['pfp_type'] == 1){echo 'Mii';}else{echo 'Custom';} ?></p>
    <p>NNID: <?php if(empty($row['nnid'])){echo 'This user didn\'t set an NNID.';}else{echo htmlspecialchars($row['nnid']);} ?></p>
    <?php 
    if($user['user_level'] > 2) {
      echo 'IP Address: '.(empty($row['ip']) ? 'Empty.' : $row['ip']);
    }
    ?>
*/

?>

<html>
<head>
  <?php
    formHeaders('Manage Account - Ciiverse');
    ?>
  </head>
	<body>
		<div id="wrapper">
		<div id="sub-body">
         <?php 
           if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
  echo form_top_bar($_SESSION['ciiverseid'], $_SESSION['nickname'], $_SESSION['pfp'], 'edit_profile');
  } else { 
  echo ftbnli('edit_profile'); }
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
</div>
<div class="game-skill data-content">
  <!-- Nothings here lol. -->
</div>
</div>
  </div>
 

</div> 
    <div class="main-column"><div class="post-list-outline">
  <h2 class="label">Manage <?php echo $ciiverseid; ?>'s Account</h2>
  <ul class="settings-list">
        <form action='/users/settings.php' method='post'>
    <br>
    <li>
      <input type="text" class="textarea" style="cursor: auto; height: auto;" name="nickname" maxlength="32" placeholder="Nickname" value="<?php echo htmlspecialchars($row['nickname']); ?>" <?php if($user['user_level'] < 6) { echo 'disabled'; }?>>
    </li>
    <li>
      <input type="text" class="textarea" style="cursor: auto; height: auto;" name="profile_pic" placeholder="Profile Picture" value="<?php echo htmlspecialchars($row['pfp']); ?>" <?php if($user['user_level'] < 6) { echo 'disabled'; }?>>
    </li>
    <li>
      <input type="text" class="textarea" style="cursor: auto; height: auto;" name="nnid" maxlength="16" placeholder="NNID" value="<?php echo htmlspecialchars($row['nnid']); ?>" <?php if($user['user_level'] < 6) { echo 'disabled'; }?>>
    </li>
    <?php 
    if($user['user_level'] > 2) {
      echo '<li>
      <input type="text" class="textarea" style="cursor: auto; height: auto;" name="ip" maxlength="16" placeholder="IP Address" value="'.htmlspecialchars($row['ip']).'" '.($user['user_level'] < 6 ? 'disabled' : '').'>
    </li>';
    }
    ?>
    <br>
    <p>Other Settings:</p>
    <li>
      <input type="checkbox" <?php if($row['can_post_images'] > 0) { echo 'checked=""'; } ?> name="can_post_images"><label for="show_replies">Can post images.</label>
    </li>
    <li>
      <input type="checkbox" <?php if($row['user_type'] == 0) { echo 'checked=""'; } ?> name="is_disabled"><label for="show_replies">Is disabled.</label>
    </li>

    <br>
    <input type="hidden" name="ciiverseid" value="<?php echo $ciiverseid; ?>">
    <div class="form-buttons">
      <input type="submit" class="black-button apply-button" name="Edit_Profile" value="Save Changes">
    </div>
  </form>
    </ul>
</div></div></div>
<script type="text/javascript">
      $(".apply-button").click(function(e){
      $(this).addClass('disabled');
    });
  </script>
	</body>
</html>