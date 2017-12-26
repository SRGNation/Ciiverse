<?php 

session_start();
if($_GET['page'] == 1) {
  $redirect = '/users/'.$_GET['ciiverseid'];
} elseif ($_GET['page'] == 3) {
    $redirect = '/users/'.$_GET['ciiverseid'].'/replies';
} elseif($_GET['page'] == 2) {
    $redirect = '/users/'.$_GET['ciiverseid'].'/empathies';
}
Require('lib/connect.php');
include('lib/htm.php');
include('lib/users.php');

$userid = mysqli_real_escape_string($db,$_GET['ciiverseid']);
$page = mysqli_real_escape_string($db,$_GET['page']);

$sql = "SELECT nickname, prof_desc, user_type, nnid FROM users WHERE ciiverseid= '$userid'";
$pfp = user_pfp($userid,0);

$result = $db->query($sql);
$row = $result->fetch_assoc();

$count = mysqli_num_rows($result);


if($count !== 1) {
die('That Ciiverse ID does not exist.');
}

$posts = $db->query("SELECT * FROM posts WHERE owner = '$userid' ORDER BY post_id DESC");

$comments = $db->query("SELECT * FROM comments WHERE owner = '$userid' ORDER BY id DESC");

$yeahs = $db->query("SELECT * FROM yeahs WHERE owner = '$userid' ORDER BY yeah_id DESC");

$post_count = mysqli_num_rows($posts);
$reply_count = mysqli_num_rows($comments);
$yeah_count = mysqli_num_rows($yeahs);

?>

<html>
	<head>
    <?php 

    if(isset($_SESSION['loggedin'])) {
    if($page == 1) {
    if($_SESSION['ciiverseid'] !== $_GET['ciiverseid']) {
    formHeaders($row['nickname'].'\'s posts - Ciiverse');
    }else{
    formHeaders('Your posts - Ciiverse');
    }
    }elseif($page == 3) {
    if($_SESSION['ciiverseid'] !== $_GET['ciiverseid']) {
    formHeaders($row['nickname'].'\'s replies - Ciiverse');
    }else{
    formHeaders('Your replies - Ciiverse');
    }
    }elseif($page == 2) {
    if($_SESSION['ciiverseid'] !== $_GET['ciiverseid']) {
    formHeaders($row['nickname'].'\'s yeahs - Ciiverse');
    }else{
    formHeaders('Your yeahs - Ciiverse');
    }
    }
      } else {
    if($page == 1) {
    formHeaders($row['nickname'].'\'s posts - Ciiverse');
    }elseif($page == 3) {
    formHeaders($row['nickname'].'\'s replies - Ciiverse');
    }elseif($page == 2) {
    formHeaders($row['nickname'].'\'s yeahs - Ciiverse');
    }
    }

    ?>
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

      <div class="icon-container <?php if($row['user_type'] > 2) {echo "official-user";} ?>">
        <a href="/users/<?php echo $userid; ?>">
          <img src="<?php echo $pfp; ?>" class="icon">
        </a>

      </div>
      <?php 
        if($row['user_type'] > 1) {
          printOrganization($row['user_type'],0);
        }
      ?>
      <a href="/users/<?php echo $userid; ?>" class="nick-name"><?php echo $row['nickname']?></a>
      <p class="id-name"><?php echo $userid; ?></p>
      </div>
       <?php if(isset($_SESSION['ciiverseid']) && $_SESSION['ciiverseid'] == $userid) { echo '<div id="edit-profile-settings"><a class="button symbol" href="/edit/profile">Edit Profile</a></div>'; }
       if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 'true') {
       if($user['user_type'] > 1) {
        if($userid !== $_SESSION['ciiverseid']) {
          echo '<div id="edit-profile-settings"><a class="button symbol" href="/users/manage_user.php?cvid='.$userid.'">Manage Account</a></div>';
        }
      }
       } ?>
    </div> 
    <div class="sidebar-setting sidebar-container">
  <div class="sidebar-post-menu">
    <a href="/users/<?php echo $userid; ?>" class="sidebar-menu-post with-count symbol <?php if($page == 1) {echo'selected';} ?>">
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
       if(!empty($row['prof_desc'])) {
        echo '<div class="profile-comment">
        <p class="js-truncated-text">' .htmlspecialchars($row['prof_desc']).'</p>
              </div>';
       }
       ?>
       <div class="user-data">
        <div class="user-main-profile data-content">
<h4><span>NNID</span></h4>
<div class="note"><?php if(!empty($row['nnid'])){echo $row['nnid'];}else{echo 'Not set.';} ?></div>
</div>
<div class="game-skill data-content">
  <!-- Nothings here lol. -->
</div>
</div>
  </div>
 

</div>        
<div class="main-column">
   <div class="post-list-outline">
       <h2 class="label"><?php echo $row['nickname']; ?>'s <?php if($page == 1){echo 'Posts';}elseif($page == 3){echo 'Relpies';}elseif($page == 2){echo 'Yeahs';} ?></h2>
            <div class="list post-list js-post-list">
                          <?php
            if($page == 1) {
            if($post_count == 0) { echo "This user has no posts yet."; } else {
             while($post_row = mysqli_fetch_array($posts)): 
              echo '
              <div id="post"  data-href="/post/'.$post_row['post_id'].'" class="post post-subtype-default trigger" tabindex="0">
  <a href="/users/' . $userid . '" class="icon-container '; if($row['user_type'] > 2) { echo 'official-user'; } echo '"><img src="' . htmlspecialchars(user_pfp($userid,$post_row['feeling'])) . '" class="icon"></a>
  <p class="user-name"><a href="/users/' . $userid . '">' . htmlspecialchars($row['nickname']) . '</a></p>
  <div class="timestamp-container"><span class="timestamp">'.humanTiming(strtotime($post_row['date_time'])).'</span></div>
  <div class="body">
    <div class="post-content">
        <div class="tag-container">
        </div>
            '.(empty($post_row['screenshot']) ? '' : '<a class="screenshot-container still-image" href="/post/'.$post_row['post_id'].'"><img src="'.$post_row['screenshot'].'"></a>').'
            <p class="post-content-text">' . htmlspecialchars($post_row['content']) . '</p>
    </div>
          <div class="post-meta">
      <div class="empathy symbol"><span class="symbol-label">Yeahs</span><span class="empathy-count">'.$post_row['yeahs'].'</span></div>
      <div class="reply symbol"><span class="symbol-label">Comments</span><span class="reply-count">'.$post_row['comments'].'</span></div>
  </div>
  </div>
</div>
				';
      endwhile;
    }
      } elseif($page == 3) {
                    if($reply_count == 0) { echo "This user has no replies yet."; } else {
             while($post_row = mysqli_fetch_array($comments)): 
              echo '
              <div id="post" class="post post-subtype-default trigger" data-href="/post/'.$post_row['post_id'].'" tabindex="0">
  <a href="/users/' . $userid . '" class="icon-container '; if($row['user_type'] > 2) { echo 'official-user'; } echo '"><img src="' . htmlspecialchars(user_pfp($userid,0)) . '" class="icon"></a>
  <p class="user-name"><a href="/users/' . $userid . '">' . htmlspecialchars($row['nickname']) . '</a></p>
  <div class="timestamp-container"><span class="timestamp">'.humanTiming(strtotime($post_row['date_time'])).'</span></div>
  <div class="body">
    <div class="post-content">
        <div class="tag-container">
        </div>
            <p class="post-content-text">' . htmlspecialchars($post_row['content']) . '</p>
    </div>
          <div class="post-meta">
          <div class="empathy symbol"><span class="symbol-label">Yeahs</span><span class="empathy-count">'.$post_row['yeahs'].'</span></div>
    </div>
  </div>
</div>
        ';
      endwhile;
    }
      } elseif($page == 2) {
        if($yeah_count == 0) { echo "This user has no yeahs yet."; } else {
          while($post_row = mysqli_fetch_array($yeahs)):
            if($post_row['type'] == 'post') {
              $query = $db->query("SELECT posts.post_id, posts.content, posts.screenshot, posts.feeling, posts.date_time, posts.yeahs, posts.comments, users.nickname, users.user_type, posts.owner FROM posts, users WHERE posts.post_id = ".$post_row['post_id']." AND users.ciiverseid = posts.owner");
              $post = mysqli_fetch_array($query);

              echo '
              <div id="post"  data-href="/post/'.$post_row['post_id'].'" class="post post-subtype-default trigger" tabindex="0">
  <a href="/users/' . $post['owner'] . '" class="icon-container '; if($post['user_type'] > 2) { echo 'official-user'; } echo '"><img src="' . user_pfp($post['owner'],$post['feeling']) . '" class="icon"></a>
  <p class="user-name"><a href="/users/' . $post['owner'] . '">' . htmlspecialchars($post['nickname']) . '</a></p>
  <div class="timestamp-container"><span class="timestamp">'.humanTiming(strtotime($post['date_time'])).'</span></div>
  <div class="body">
    <div class="post-content">
        <div class="tag-container">
        </div>
            '.(empty($post['screenshot']) ? '' : '<a class="screenshot-container still-image" href="/post/'.$post_row['post_id'].'"><img src="'.$post['screenshot'].'"></a>').'
            <p class="post-content-text">' . htmlspecialchars($post['content']) . '</p>
    </div>
          <div class="post-meta">
      <div class="empathy symbol"><span class="symbol-label">Yeahs</span><span class="empathy-count">'.$post['yeahs'].'</span></div>
      <div class="reply symbol"><span class="symbol-label">Comments</span><span class="reply-count">'.$post['comments'].'</span></div>
  </div>
  </div>
</div>';

            }
          endwhile;
        }
      }
        ?>
          </div>
          </div>
        </div>
			</div>
		</div>
	</body>

</html>