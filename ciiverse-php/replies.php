<?php 

Require('lib/connect.php');
include('lib/menu.php');
session_start();

$userid = mysqli_real_escape_string($db,$_GET['ciiverseid']);

$sql = "SELECT nickname, pfp, prof_desc, is_owner FROM users WHERE ciiverseid= '$userid'";

$result = $db->query($sql);
$row = $result->fetch_assoc();

$count = mysqli_num_rows($result);


if($count == 1) {

if (empty($row['pfp'])) {
  $row['pfp'] = "/img/defult_pfp.png"; 
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

$get_sql = "SELECT * FROM posts WHERE owner = '$userid' ORDER BY post_id DESC";
$query = mysqli_query($db,$get_sql);

$q = mysqli_query($db,"SELECT * FROM comments WHERE owner = '$userid' ORDER BY id DESC");

$post_count = mysqli_num_rows($query);
$reply_count = mysqli_num_rows($q);

?>

<html>
	<head>
		<title>User page - <?php echo $row['nickname']; ?></title>
		<link rel="stylesheet" href="/offdevice.css" />
		<link rel="shortcut icon" href="/icon.png" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <script src="/js/complete-en.js"></script>
  </head>
	<body>
		<div id="wrapper">
			<div id="sub-body">
         <?php 

           if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
  echo form_top_bar($_SESSION['ciiverseid'], $_SESSION['nickname'], $_SESSION['pfp'], 'user');
  } else { 
  echo ftbnli('user'); }

         ?>
      </div>
			<div id="main-body">
				<div id="sidebar" class="user-sidebar">
					<div class="sidebar-container">
      
    <div id="sidebar-profile-body" class="without-profile-post-image">
      <div class="icon-container <?php if($row['is_owner'] == true) {echo "official-user";} ?>">
        <a href="/users/<?php echo $userid; ?>">
          <img src="<?php echo $row['pfp'] ?>" class="icon">
        </a>

      </div>
      <a href="/users/<?php echo $userid; ?>" class="nick-name"><?php echo $row['nickname']?></a>
      <p class="id-name"><?php echo $userid; ?></p>
      </div>
       <?php if(isset($_SESSION['ciiverseid']) && $_SESSION['ciiverseid'] == $userid) { echo '<div id="edit-profile-settings"><a class="button symbol" href="/edit/profile">Edit Profile</a></div>'; }
       if(isset($_SESSION['ciiverseid']) && $is_owner == 'true') {
        if($userid !== $_SESSION['ciiverseid']) {
          echo '<div id="edit-profile-settings"><a class="button symbol" href="/users/manage_user.php?cvid='.$userid.'">Manage Profile</a></div>';
        }
       } ?>
    </div> 
    <div class="sidebar-setting sidebar-container">
  <div class="sidebar-post-menu">
    <a href="/users/<?php echo $userid; ?>" class="sidebar-menu-post with-count symbol">
      <span>All posts</span>
      <span class="post-count">
          <span class="test-post-count" id="js-my-post-count"><?php echo $post_count; ?></span>
        </span>
      </a>
    <a href="/users/<?php echo $userid; ?>/replies" class="sidebar-menu-post with-count symbol selected">
      <span>Replies</span>
      <span class="post-count">
          <span class="test-post-count" id="js-my-post-count"><?php echo $reply_count; ?></span>
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
       <h2 class="label"><?php echo $row['nickname']; ?>'s replies</h2>
            <div class="list post-list js-post-list">
                          <?php
            if($reply_count == 0) { echo "This user has no replies yet."; } else {
             while($post_row = mysqli_fetch_array($q)): 
              echo '
              <div id="post" class="post post-subtype-default trigger" data-href="/post/'.$post_row['post_id'].'" tabindex="0">
  <a href="/users/' . $userid . '" class="icon-container '; if($row['is_owner'] == true) { echo 'official-user'; } echo '"><img src="' . htmlspecialchars($row['pfp']) . '" class="icon"></a>
  <p class="user-name"><a href="/users/' . $userid . '">' . htmlspecialchars($row['nickname']) . '</a></p>
  <div class="body">
    <div class="post-content">
        <div class="tag-container">
        </div>
            <p class="post-content-text">' . htmlspecialchars($post_row['content']) . '</p>
    </div>
          <div class="post-meta">
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