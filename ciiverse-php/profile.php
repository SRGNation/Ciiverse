<?php 

session_start();
Require('lib/connect.php');
include('lib/htm.php');
include('lib/users.php');

$userid = mysqli_real_escape_string($db,$_GET['ciiverseid']);
$page = mysqli_real_escape_string($db,$_GET['page']);

$stmt = $db->prepare("SELECT nickname, prof_desc, user_type, nnid, user_level, favorite_post, hide_replies, hide_yeahs FROM users WHERE ciiverseid = ?");
$stmt->bind_param('s', $_GET['ciiverseid']);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$pfp = user_pfp($userid,0);

$count = mysqli_num_rows($result);

if($count !== 1) {
  exit('That user doesn\'t exist.');
}

$posts = $db->query("SELECT * FROM posts WHERE owner = '$userid' AND deleted < 1 ORDER BY post_id DESC");
$comments = $db->query("SELECT * FROM comments WHERE owner = '$userid' ORDER BY id DESC");
$yeahs = $db->query("SELECT * FROM yeahs WHERE owner = '$userid' ORDER BY yeah_id DESC");
$deleted = $db->query("SELECT * FROM posts WHERE owner = '$userid' AND deleted != 0 AND deleted != 5 ORDER BY post_id DESC");
$followers = $db->query("SELECT * FROM follows WHERE follow_to = '$userid' ORDER BY id DESC");
$following = $db->query("SELECT * FROM follows WHERE follow_by = '$userid' ORDER BY id DESC");
$favorites = $db->query("SELECT * FROM favorite_communities WHERE owner = '$userid' ORDER BY id DESC LIMIT 10");
$profile_tags = $db->query("SELECT * FROM profile_tags WHERE owner = '$userid' ORDER BY id ASC");

$chk = $db->query("SELECT * FROM follows WHERE follow_to = '$userid' AND follow_by = '".$_SESSION['ciiverseid']."'");
$following_user = mysqli_num_rows($chk);

$post_count = mysqli_num_rows($posts);
$reply_count = mysqli_num_rows($comments);
$yeah_count = mysqli_num_rows($yeahs);
$deleted_count = mysqli_num_rows($deleted);
$follower_count = mysqli_num_rows($followers);
$following_count = mysqli_num_rows($following);

if($page == 4 & $_GET['ciiverseid'] != $_SESSION['ciiverseid']) {
  exit('You\'re not aloud to view this page.');
}

if($page == 3 & $row['hide_replies'] == 1 & $_GET['ciiverseid'] != $_SESSION['ciiverseid']) {
  exit('You\'re not aloud to view this page.');
}

if($page == 2 & $row['hide_yeahs'] == 1 & $_GET['ciiverseid'] != $_SESSION['ciiverseid']) {
  exit('You\'re not aloud to view this page.');
}


?>

<html>
	<head>
    <?php 
    if(isset($_SESSION['loggedin'])) {
      switch($page) 
      {
        case 1:
          if($_SESSION['ciiverseid'] !== $_GET['ciiverseid']) {
            formHeaders($row['nickname'].'\'s posts - Ciiverse');
          } else {
            formHeaders('Your posts - Ciiverse');
          }
        break;
        case 2:
          if($_SESSION['ciiverseid'] !== $_GET['ciiverseid']) {
            formHeaders($row['nickname'].'\'s yeahs - Ciiverse');
          }else{
            formHeaders('Your yeahs - Ciiverse');
          }
        break;
        case 3:
          if($_SESSION['ciiverseid'] !== $_GET['ciiverseid']) {
            formHeaders($row['nickname'].'\'s replies - Ciiverse');
          }else{
            formHeaders('Your replies - Ciiverse');
          }
        break;
        case 4:
          formHeaders('Your deleted posts - Ciiverse');
        break;
        case 5:
          if($_SESSION['ciiverseid'] !== $_GET['ciiverseid']) {
            formHeaders($row['nickname'].'\'s followers - Ciiverse');
          }else{
            formHeaders('Your followers - Ciiverse');
          }
        break;
        case 6:
          if($_SESSION['ciiverseid'] !== $_GET['ciiverseid']) {
            formHeaders($row['nickname'].'\'s following - Ciiverse');
          }else{
            formHeaders('Your following - Ciiverse');
          }
      }
    } else {
      switch($page) {
        case 1:
          formHeaders($row['nickname'].'\'s posts - Ciiverse');
        break;
        case 2:
          formHeaders($row['nickname'].'\'s yeahs - Ciiverse');
        break;
        case 3:
          formHeaders($row['nickname'].'\'s replies - Ciiverse');
        break;
        case 5:
          formHeaders($row['nickname'].'\'s followers - Ciiverse');
        break;
        case 6: 
          formHeaders($row['nickname'].'\'s following - Ciiverse');
      }
    }

    ?>
  </head>
	<body>
		<div id="wrapper" <?php if(!$_SESSION['loggedin']) { echo 'class="guest"'; } ?>>
			<div id="sub-body">
        <?php 
          //LMAO how fucking high was I when I was writing this code!?
          if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
            echo form_top_bar($_SESSION['ciiverseid'], $_SESSION['nickname'], $_SESSION['pfp'], 'user');
          } else { 
            echo ftbnli('user');
          }
        ?>
      </div>
			<div id="main-body">
				<div id="sidebar" class="user-sidebar">
					<div class="sidebar-container"> <?php
            $stmt = $db->prepare("SELECT post_id, screenshot, owner FROM posts WHERE post_id = ? AND deleted = 0");
            $stmt->bind_param('i', $row['favorite_post']);
            $stmt->execute();
            $result = $stmt->get_result();
            $fav_post = $result->fetch_assoc();

            if(!empty($fav_post['screenshot'])) { 
              echo '<a href="/post/'.$fav_post['post_id'].'" id="sidebar-cover" style="background-image:url('.htmlspecialchars($fav_post['screenshot']).')"><img src="'.htmlspecialchars($fav_post['screenshot']).'" class="sidebar-cover-image"></a><div id="sidebar-profile-body" class="with-profile-post-image">';
            } else {
              echo '<div id="sidebar-profile-body" class="without-profile-post-image">';
            }
          ?>
          <div class="icon-container <?php echo print_badge($userid); ?>">
          <a href="/users/<?php echo $userid; ?>">
          <img src="<?php echo $pfp; ?>" class="icon">
        </a>
      </div>
      <?php 
        if($row['user_type'] > 1) {
          printOrganization($row['user_type'],0);
        }
      ?>
      <a href="/users/<?=$_GET['ciiverseid']?>" class="nick-name"><?=$row['nickname']?></a>
      <p class="id-name"><?php echo $userid; ?></p>
      <?php if($row['user_level'] > 0) {echo '<p class="admin-level">Level '.$row['user_level'].' Admin</p>';} ?>
      </div>
       <?php if(isset($_SESSION['ciiverseid']) && $_SESSION['ciiverseid'] == $userid) { echo '<div id="edit-profile-settings"><a class="button symbol" href="/edit/profile">Edit Profile</a></div>'; }
        if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 'true') {
        if($userid !== $_SESSION['ciiverseid']) {

          if($following_user == 0) {
            echo '<button type="button" data-user-id="'.$_GET['ciiverseid'].'" class="follow-button button symbol">Follow</button>';
          } else {
            echo '<button type="button" data-user-id="'.$_GET['ciiverseid'].'" class="unfollow-button button symbol">Follow</button>';
          }

        }

        if($userid !== $_SESSION['ciiverseid']) {
        if($row['user_level'] < $user['user_level'])
          echo '<div id="edit-profile-settings"><a class="button symbol" href="/users/manage_user.php?cvid='.$_GET['ciiverseid'].'">Manage Account</a></div>';
        }
       } ?>
      <ul id="sidebar-profile-status">
      <li><a href="/users/<?=$_GET['ciiverseid']?>/following"><span><span class="number"><?php echo $following_count; ?></span>Following</span></a></li>
      <li><a href="/users/<?=$_GET['ciiverseid']?>/followers"><span><span class="number"><?php echo $follower_count; ?></span>Followers</span></a></li>
    </div> 
    <div class="sidebar-setting sidebar-container">
  <div class="sidebar-post-menu">
    <a href="/users/<?php echo $userid; ?>" class="sidebar-menu-post with-count symbol <?php if($page == 1) {echo'selected';} ?>">
      <span>All posts</span>
      <span class="post-count">
          <span class="test-post-count" id="js-my-post-count"><?php echo $post_count; ?></span>
        </span>
      </a>
      <?php 
      if($row['hide_replies'] == 0 || $userid == $_SESSION['ciiverseid']) { ?>
    <a href="/users/<?php echo $userid; ?>/replies" class="sidebar-menu-post with-count symbol <?php if($page == 3) {echo'selected';} ?>">
      <span>Replies</span>
      <span class="post-count">
          <span class="test-post-count" id="js-my-post-count"><?php echo $reply_count; ?></span>
        </span>
      </a> <?php
    }
      ?>
      <?php if($row['hide_yeahs'] == 0 || $userid == $_SESSION['ciiverseid']) { ?>
    <a class="sidebar-menu-empathies with-count symbol <?php if($page == 2) {echo'selected';} ?>" href="/users/<?php echo $userid; ?>/empathies">
      <span>Yeahs</span>
      <span class="post-count">
        <span class="test-empathy-count"><?php echo $yeah_count; ?></span>
      </span>
    </a>
  <?php } ?>
    <?php
    if($userid == $_SESSION['ciiverseid']) {
    ?> <a class="sidebar-menu-post with-count symbol <?php if($page == 4) {echo'selected';} ?>" href="/users/<?php echo $userid; ?>/deleted">
      <span>Deleted Posts</span>
      <span class="post-count">
        <span class="test-empathy-count"><?php echo $deleted_count; ?></span>
      </span>
    </a> <?php
  }
    ?>
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
        <?php 

        while($tags = mysqli_fetch_array($profile_tags)) {

          echo '<div class="user-main-profile data-content">
          <h4><span>'.$tags['tag_name'].'</span></h4>
            <div class="note">'.$tags['tag_content'].'</div>
        </div>';

        } 

        ?>
      </div>
    </div>
  <?php
  if(mysqli_num_rows($favorites) !== 0) {
  echo '<div class="sidebar-container sidebar-favorite-community"><h4><a href="'.($_SESSION['ciiverseid'] == $userid ? '/communities/favorites' : '/users/'.$userid.'/favorites').'" class="symbol favorite-community-button"><span>Favorite Communities</span></a></h4>
<ul class="test-favorite-communities">';

  while($brenda = mysqli_fetch_array($favorites)) {
    echo '<li class="favorite-community"><a href="/communities/'.$brenda['community_id'].'"><span class="icon-container"><img src="'.community_info($brenda['community_id'],'icon').'" class="icon"></span></a>'.(community_info($brenda['community_id'],'type') == 2 ? '<span class="platform-tag"><img src="/img/platform-tag-3ds.png"></span>' : '').''.(community_info($brenda['community_id'],'type') == 3 ? '<span class="platform-tag"><img src="/img/platform-tag-wiiu.png"></span>' : '').''.(community_info($brenda['community_id'],'type') == 4 ? '<span class="platform-tag"><img src="/img/platform-tag-wiiu-3ds.png"></span>' : '').'</li>';
  }
echo '</ul></div>';
}
?>
</div>        
<div class="main-column">
   <div class="post-list-outline">
       <h2 class="label"><?php echo $row['nickname']; ?>'s <?php if($page == 1){echo 'Posts';}elseif($page == 3){echo 'Replies';}elseif($page == 2){echo 'Yeahs';}elseif($page == 4){echo 'Deleted Posts';}elseif($page == 5){echo 'Followers';}elseif($page == 6){echo 'Following';} ?></h2>
            <div class="list <?php if($page < 5) {echo 'post';} else {echo 'follow';} ?>-list">
                          <?php
            if($page == 1) {
            if($post_count == 0) { echo '<div class="no-content"><p>This user has no posts yet.</p></div>'; } else {
             while($post_row = mysqli_fetch_array($posts)): 

              printPost($post_row['post_id'],1);

      endwhile;
    }
      } elseif($page == 3) {
        if($reply_count == 0) { echo '<div class="no-content"><p>This user has no replies yet.</p></div>'; } else {
          while($post_row = mysqli_fetch_array($comments)): 

            if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
              if($post_row['owner'] == $_SESSION['ciiverseid']) {
                $yeah_disabled = true;
              } else {
                $yeah_disabled = false;
              }
            } else {
              $yeah_disabled = true;
            }

            if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
              $check_yeah_added = $db->query("SELECT * FROM yeahs WHERE owner = '".$_SESSION['ciiverseid']."' AND post_id = ".$post_row['id']." AND type = 'comment' ");
              $yeahed = mysqli_num_rows($check_yeah_added);
            } else {
              $yeahed = 0;
            }

            $get_yeahs = $db->query("SELECT yeah_id FROM yeahs WHERE post_id = ".$post_row['id']." AND type = 'comment'");
            $yeah_count = mysqli_num_rows($get_yeahs);

            echo '<div id="post" class="post post-subtype-default trigger" data-href="/post/'.$post_row['post_id'].'" tabindex="0"><a href="/users/' . $userid . '" class="icon-container '.print_badge($userid).'"><img src="'.htmlspecialchars(user_pfp($userid,$post_row['feeling'])).'" class="icon"></a><p class="user-name"><a href="/users/' . $userid . '">' . htmlspecialchars($row['nickname']) . '</a></p><div class="timestamp-container"><span class="timestamp">'.humanTiming(strtotime($post_row['date_time'])).'</span></div><div class="body"><div class="post-content"><div class="tag-container"></div><p class="post-content-text">' . htmlspecialchars($post_row['content']) . '</p></div><div class="post-meta"><button '.($yeah_disabled == true ? 'disabled' : '').' class="symbol submit empathy-button" id="'.$post_row['id'].'" data-yeah-type="comment" data-remove="'.$yeahed.'" type="button"><span class="empathy-button-text">'.($yeahed == 0 ? print_yeah($post_row['feeling']) : 'Unyeah').'</span></button><div class="empathy symbol"><span class="symbol-label">Yeahs</span><span class="empathy-count">'.$yeah_count.'</span></div></div></div></div>';
            endwhile;
        }
      } elseif($page == 2) {
        if($yeah_count == 0) { echo '<div class="no-content"><p>This user has no yeahs yet.</p></div>'; } else {
          while($post_row = mysqli_fetch_array($yeahs)):
            if($post_row['type'] == 'post') {
              $query = $db->query("SELECT post_id, owner FROM posts WHERE post_id = ".$post_row['post_id']." AND posts.deleted < 1");
              $post = mysqli_fetch_array($query);


          if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
          if($post['owner'] == $_SESSION['ciiverseid']) {
            $yeah_disabled = true;
          } else {
            $yeah_disabled = false;
          }
        } else {
          $yeah_disabled = true;
        }

        if(mysqli_num_rows($query) !== 0) {
          printPost($post['post_id'],1);
        }

            } else {

              $query = $db->query("SELECT comments.id, comments.post_id, comments.content, comments.owner, users.pfp, users.nickname, users.user_type, comments.date_time, comments.feeling FROM comments, users WHERE comments.id = ".$post_row['post_id']." AND users.ciiverseid = comments.owner");
              $post = mysqli_fetch_array($query);

              $get_yeahs = $db->query("SELECT yeah_id FROM yeahs WHERE post_id = ".$post_row['post_id']." AND type = 'comment'");
              $yeah_count = mysqli_num_rows($get_yeahs);

                        if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                          if($post['owner'] == $_SESSION['ciiverseid']) {
                            $yeah_disabled = true;
                          } else {
                            $yeah_disabled = false;
                          }
                        } else {
                          $yeah_disabled = true;
                        }

              if(mysqli_num_rows($query) !== 0) {

                if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                  $check_yeah_added = $db->query("SELECT * FROM yeahs WHERE owner = '".$_SESSION['ciiverseid']."' AND post_id =".$post['id']." AND type = 'comment' ");
                  $yeahed = mysqli_num_rows($check_yeah_added);
                } else {
                  $yeahed = 0;
                }

                echo '
              <div id="post"  data-href="/post/'.$post['post_id'].'" class="post post-subtype-default trigger" tabindex="0">
              <p class="community-container">
<a class="test-community-link" href="/post/'.$post['post_id'].'"><img src="'.user_pfp($post['owner'],0).'" class="community-icon">Comment on a post</a></p>
              <a href="/users/'.$post['owner'].'" class="icon-container '.print_badge($post['owner']).'"><img src="' . user_pfp($post['owner'],$post['feeling']) . '" class="icon"></a>
                <p class="user-name"><a href="/users/' . $post['owner'] . '">' . htmlspecialchars($post['nickname']) . '</a></p>
  <div class="timestamp-container"><span class="timestamp">'.humanTiming(strtotime($post['date_time'])).'</span></div>
  <div class="body">
    <div class="post-content">
        <div class="tag-container">
        </div>
            <p class="post-content-text">'.htmlspecialchars($post['content']).'</p>
    </div>
          <div class="post-meta">
      <button '.($yeah_disabled == true ? 'disabled' : '').' class="symbol submit empathy-button" id="'.$post_row['post_id'].'" data-yeah-type="comment" data-remove="'.$yeahed.'" type="button"><span class="empathy-button-text">'.($yeahed == 0 ? print_yeah($post['feeling']) : 'Unyeah').'</span></button>
      <div class="empathy symbol"><span class="symbol-label">Yeahs</span><span class="empathy-count">'.$yeah_count.'</span></div>
  </div>
  </div>
</div>';
}

              }
          endwhile;
        }
      } elseif($page == 4) {
            if($deleted_count == 0) { echo '<div class="no-content"><p>You don\'t have any deleted posts.</p></div>'; } else {
             while($post_row = mysqli_fetch_array($deleted)): 
              printPost($post_row['post_id'],1);
      endwhile;
    }
      } elseif($page == 5) {
        if($follower_count == 0) { echo '<div class="no-content"><p>This user has no followers yet.</p></div>'; } else {
          echo '<ul class="list-content-with-icon-and-text arrow-list" id="friend-list-content" data-next-page-url="">';
          while($post_row = mysqli_fetch_array($followers)):
            $get_u = $db->query("SELECT * FROM users WHERE ciiverseid = '".$post_row['follow_by']."'");
            $users = mysqli_fetch_array($get_u);

            echo '
            <li class="trigger" data-href="/users/'.$users['ciiverseid'].'">
          <a href="/users/'.$users['ciiverseid'].'" class="icon-container '.print_badge($post_row['follow_by']).'">
            <img src="'.user_pfp($users['ciiverseid'],0).'" class="icon">
          </a>

            <div class="toggle-button"></div>
          <div class="body">
            <p class="title">
              <span class="nick-name">
                <a href="/users/'.$users['ciiverseid'].'">'.$users['nickname'].'</a>
              </span>
              <span class="id-name">'.$users['ciiverseid'].'</span>
            </p>
          </div>
        </li>
            ';
          endwhile;
        }
       } elseif($page == 6) {
        if($following_count == 0) { echo '<div class="no-content"><p>This user didn\'t follow anyone yet.</p></div>'; } else {
          echo '<ul class="list-content-with-icon-and-text arrow-list" id="friend-list-content" data-next-page-url="">';
          while($post_row = mysqli_fetch_array($following)):
            $get_u = $db->query("SELECT * FROM users WHERE ciiverseid = '".$post_row['follow_to']."'");
            $users = mysqli_fetch_array($get_u);

            echo '
            <li class="trigger" data-href="/users/'.$users['ciiverseid'].'">
          <a href="/users/'.$users['ciiverseid'].'" class="icon-container '.print_badge($post_row['follow_to']).'">
            <img src="'.user_pfp($users['ciiverseid'],0).'" class="icon">
          </a>

            <div class="toggle-button"></div>
          <div class="body">
            <p class="title">
              <span class="nick-name">
                <a href="/users/'.$users['ciiverseid'].'">'.$users['nickname'].'</a>
              </span>
              <span class="id-name">'.$users['ciiverseid'].'</span>
            </p>
          </div>
        </li>
            ';
          endwhile;
       }
      }
        ?>
          </div>
          </div>
        </div>
        <?php 
        printFooter();
        ?>
			</div>
		</div>
	</body>

</html>