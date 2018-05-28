<?php 

Require('../login/IncludesOrSomething/db_login.php');
include('../login/IncludesOrSomething/functions.php');
session_start();

$userid = mysqli_real_escape_string($db,$_GET['ciiverseid']);

$sql = "SELECT nickname, pfp, prof_desc, is_owner FROM users WHERE ciiverseid= '$userid'";

$result = $db->query($sql);
$row = $result->fetch_assoc();

$count = mysqli_num_rows($result);


if($count == 1) {

if (empty($row['pfp'])) {
  $row['pfp'] = "/defult_pfp.png"; 
}

} else { 
die('That Ciiverse ID does not exist.');
}

if(isset($_SESSION['ciiverseid'])) { 

$cvid = $_SESSION['ciiverseid'];

$sql = "SELECT is_owner FROM users WHERE ciiverseid = '$cvid' ";
$result = mysqli_query($db,$sql);
$ses_row = mysqli_fetch_array($result,MYSQLI_ASSOC);

$is_owner = $ses_row['is_owner'];

}

$get_sql = "SELECT content, community_id, post_id, comments FROM posts WHERE owner = '$userid' ORDER BY post_id DESC";
$query = mysqli_query($db,$get_sql);

$post_count = mysqli_num_rows($query);

?>

<html>
	<head>
		<title>User page - <?php echo $row['nickname']; ?></title>
		<link rel="stylesheet" href="../offdevice.css" />
		<link rel="shortcut icon" href="../icon.png" />
    <script src="https://d13ph7xrk1ee39.cloudfront.net/js/complete-en.js?fIOZ2c9u1kzE6kkD5AkljQ"></script>
  </head>
	<body>
		<div id="wrapper">
			<div id="sub-body">
         <menu id="global-menu">
          <li id="global-menu-logo"><h1><a href="/"><img src="/ciiverse.png" alt="Miiverse" width="165" height="30"></a></h1></li>
          <li id="global-menu-list">
            <ul>
             <?php 
             if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
               echo '<li'; if(isset($_SESSION['ciiverseid']) && $userid == $_SESSION['ciiverseid']) { echo ' class="selected"'; } echo ' id="global-menu-mymenu"><a href="/users/profile.php?ciiverseid='. $_SESSION['ciiverseid'] .'"><span class="icon-container '; if($is_owner == 'true') {echo "official-user";} echo '"';  echo '><img src="'.$_SESSION['pfp']. '"></span><span>User Page</span></a></li>';
             }

             ?>
             <li id="global-menu-community"><a href="/" class="symbol"><span>Communities</span></a></li>
<li id="global-menu-my-menu"><button class="symbol js-open-global-my-menu open-global-my-menu"></button>
                <menu id="global-my-menu" class="invisible none">
                  
                  <li>
    <?php
    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) { } else {
    echo '<a href="/register" class="symbol my-menu-guide"><span>Sign Up</span></a>';
    }
    ?>                  </li>

<li>
  <?php 
  if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) { } else {
    echo '<a href="/login" class="symbol my-menu-guide"><span>Sign In</span></a>';
  }
    ?>
                  </li>
                      <?php 
  if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    echo '<li><a href="/users/edit_profile.php" class="symbol my-menu-profile-setting"><span>Edit Profile</span></a></li> 
  <li><a class="symbol my-menu-info" href="/communities?cid=55"><span>Ciiverse Changelog</span></a></li>';
  }
    ?>
                      <?php 
  if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    echo '<li><a href="/login/logout.php" class="symbol my-menu-guide"><span>Log Out</span></a></li>';
  }
    ?>
                </menu>
      </div>
			<div id="main-body">
				<div id="sidebar" class="user-sidebar">
					<div class="sidebar-container">
      
    <div id="sidebar-profile-body" class="without-profile-post-image">
      <div class="icon-container <?php if($row['is_owner'] == true) {echo "official-user";} ?>">
        <a href="/users/profile.php?ciiverseid=<?php echo $userid; ?>">
          <img src="<?php echo $row['pfp'] ?>" class="icon">
        </a>

      </div>
      <a href="/users/profile.php?ciiverseid=<?php echo $userid; ?>" class="nick-name"><?php echo $row['nickname']?></a>
      <p class="id-name"><?php echo $userid; ?></p>
      </div>
       <?php if(isset($_SESSION['ciiverseid']) && $_SESSION['ciiverseid'] == $userid) { echo '<div id="edit-profile-settings"><a class="button symbol" href="../users/edit_profile.php">Edit Profile</a></div>'; } ?>
    </div> 
    <div class="sidebar-setting sidebar-container">
  <div class="sidebar-post-menu">
    <a href="/users/profile.php?ciiverseid=<?php echo $userid; ?>" class="sidebar-menu-post with-count symbol selected">
      <span>All posts</span>
      <span class="post-count">
          <span class="test-post-count" id="js-my-post-count"><?php echo $post_count; ?></span>
        </span>
      </a>
  </div>
</div>

                 <div class="sidebar-container sidebar-profile">
       <?php 
       if(!empty($row['prof_desc'])) {
        echo '<div class="profile-comment">
        <p class="js-truncated-text">' .htmlspecialchars($row['prof_desc']).'</p>
              </div>';
       }
       ?>
  </div>
 

</div>        
<div class="main-column">
   <div class="post-list-outline">
       <h2 class="label"><?php echo $row['nickname']; ?>'s posts</h2>
            <div class="list post-list js-post-list">
                          <?php
            if($post_count == 0) { echo "This user has no posts yet."; } else {
             while($post_row = mysqli_fetch_array($query)): 
              echo '
              <div id="post"  data-href="/posts?pid='.$post_row['post_id'].'" class="post post-subtype-default trigger" tabindex="0">
  <a href="/users/profile.php?ciiverseid=' . $userid . '" class="icon-container '; if($row['is_owner'] == true) { echo 'official-user'; } echo '"><img src="' . htmlspecialchars($row['pfp']) . '" class="icon"></a>
  <p class="user-name"><a href="/users/profile.php?ciiverseid=' . $userid . '">' . htmlspecialchars($row['nickname']) . '</a></p>
  <div class="body">
    <div class="post-content">
        <div class="tag-container">
        </div>
            <p class="post-content-text">' . htmlspecialchars($post_row['content']) . '</p>
    </div>
          <div class="post-meta">
      <div class="reply symbol"><span class="symbol-label">Comments</span><span class="reply-count">'.$post_row['comments'].'</span></div>
  </div>
  </div>
</div>
				';
      endwhile;
    }
        ?>
          </div>
          </div>
        </div>
			</div>
		</div>
	</body>

</html>