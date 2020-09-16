<?php 

session_start();
$redirect = '/post/'.$_GET['pid'];
require("lib/connect.php");
include("lib/htm.php");
include("lib/users.php");

$pid = mysqli_real_escape_string($db,$_GET['pid']);

$sql1 = "SELECT * FROM posts WHERE post_id = '$pid' AND deleted != 5";
$res1 = mysqli_query($db,$sql1);
$row1 = mysqli_fetch_array($res1);

$count = mysqli_num_rows($res1);

if($count !== 1) {
	die("This post doesn't exist.");
}

#This is to get the user information.
$sql2 = "SELECT nickname, pfp, user_type, user_level FROM users WHERE ciiverseid = '".$row1['owner']."'";
$res2 = mysqli_query($db,$sql2);
$row2 = mysqli_fetch_array($res2);

#This is to get the community information.
$sql3 = "SELECT community_name, community_picture FROM communities WHERE id =".$row1['community_id'];
$res3 = mysqli_query($db,$sql3);
$row3 = mysqli_fetch_array($res3);

#These are to get the comments.
$sql4 = "SELECT comments.id, comments.post_id, comments.content, comments.owner, users.pfp, users.nickname, users.user_type, comments.date_time, comments.feeling FROM comments, users WHERE comments.post_id = '$pid' AND users.ciiverseid = comments.owner ORDER BY id ASC";
$res4 = mysqli_query($db,$sql4);

#These are to get the yeahs.
$aaa = $db->query("SELECT * FROM yeahs WHERE post_id = '$pid' AND type = 'post'");
$get_yeah_data = $db->query("SELECT * FROM yeahs WHERE post_id = '$pid' AND owner != '".$_SESSION['ciiverseid']."' ORDER BY yeah_id DESC limit 30");

$count = mysqli_num_rows($res4);
$yeah_count = mysqli_num_rows($aaa);

#This is to see if you already yeahed this post.
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 'true') {
  $check_yeah = $db->query("SELECT * FROM yeahs WHERE post_id = '$pid' AND owner = '".$_SESSION['ciiverseid']."' AND type = 'post'");
  $yeah_cnt = mysqli_num_rows($check_yeah);
} else {
  $yeah_cnt = 0;
}

?>

<html>
<head>
  <?php 

if(isset($_SESSION['loggedin'])) {
  if($_SESSION['ciiverseid'] == $row1['owner']) {
  formHeaders('Your post - Ciiverse');
} else {
  formHeaders($row2['nickname'].'\'s post - Ciiverse');
}
} else {
  formHeaders($row2['nickname'].'\'s post - Ciiverse');
}

  ?>
	</head>
	<body>
		<div id="wrapper" <?php if(!$_SESSION['loggedin']) { echo 'class="guest"'; } ?>>
			<div id="sub-body">
				<?php 
				  if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
  echo form_top_bar($_SESSION['ciiverseid'], $_SESSION['nickname'], $_SESSION['pfp'], 'posts');
  } else { 
  echo ftbnli('posts'); }
				?>
        </div>
			<div id="main-body">
        <?php 
        if($row1['deleted'] == 1) {
          echo '<div class="no-content track-error">
  <div class="post-list-outline">
  <section class="post post-subtype-default" id="post-content">
    <p>Deleted by poster.</p>
  </div>
  </div>
</div>
<div id="report-violator-page" class="dialog none" data-modal-types="report report-violator" data-is-template="1">
</div>
</div>
      </div>
      </div>
</body></html>';
exit();
}
      if($row1['deleted'] == 2 && $row1['owner'] !== $_SESSION['ciiverseid']) {
  echo '<div class="no-content track-error">
  <div class="post-list-outline">
  <section class="post post-subtype-default" id="post-content">
    <p>Deleted by Moderator.<br>Post ID: '.$pid.'</p>
  </div>
  </div>
</div>
<div id="report-violator-page" class="dialog none" data-modal-types="report report-violator" data-is-template="1">
</div>
</div>
      </div>
      </div>
</body></html>';
exit();
      }
      if($row1['deleted'] == 3 && $row1['owner'] !== $_SESSION['ciiverseid']) {
  echo '<div class="no-content track-error">
  <div class="post-list-outline">
  <section class="post post-subtype-default" id="post-content">
    <p>Deleted by Administrator.<br>Post ID: '.$pid.'</p>
  </div>
  </div>
</div>
<div id="report-violator-page" class="dialog none" data-modal-types="report report-violator" data-is-template="1">
</div>
</div>
      </div>
      </div>
</body></html>';
exit();
      }
      if($row1['deleted'] == 4 && $row1['owner'] !== $_SESSION['ciiverseid']) {
  echo '<div class="no-content track-error">
  <div class="post-list-outline">
  <section class="post post-subtype-default" id="post-content">
    <p>Deleted by owner of Ciiverse.<br>Post ID: '.$pid.'</p>
  </div>
  </div>
</div>
<div id="report-violator-page" class="dialog none" data-modal-types="report report-violator" data-is-template="1">
</div>
</div>
      </div>
      </div>
</body></html>';
exit();
      }
        ?>
				<div class="main-column">
					<div class="post-list-outline">
						<section class="post post-subtype-default" id="post-content">
							<header class="community-container">
    						<h1 class="community-container-heading">
      						<a href="/communities/<?php echo $row1['community_id']; ?>"><img class="community-icon" src="<?php echo $row3['community_picture']; ?>"><?php echo $row3['community_name']; ?> Community</a>
    						</h1>
  						</header>
              <?php 
                if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true && $row1['deleted'] < 1) {
                  if($_SESSION['ciiverseid'] == $row1['owner'] || $user['user_level'] > $row2['user_level']) {
                    echo '<button type="button" class="symbol button edit-button rm-post-button" data-modal-open="#remove-post-page"><span class="symbol-label">Delete</span></button>';
                  }
                  if(!empty($row1['screenshot']))
                  {
                    if($user['favorite_post'] == $pid)
                    {
                      echo '<button type="button" class="symbol button edit-button profile-post-button done" data-modal-open="#profile-post-remove"><span class="symbol-label">Remove favorite post</span></button>';
                    }
                    else
                    {
                      echo '<button type="button" class="symbol button edit-button profile-post-button" data-modal-open="#profile-post-page"><span class="symbol-label">Favorite post</span></button>';         
                    }

                  }
                }
              ?>
  							<div class="user-content">
    							<a class="icon-container <?php echo print_badge($row1['owner']); ?>" href="/users/<?php echo $row1['owner']; ?>"><img class="icon" src="<?php echo htmlspecialchars(user_pfp($row1['owner'],$row1['feeling'])); ?>"></a>
    							<div class="user-name-content">
      								<p class="user-name"><a href="/users/<?php echo $row1['owner']; ?>"><?php echo $row2['nickname']; ?></a><span class="user-id"><?php echo $row1['owner']; ?></span></p>
      								<p class="timestamp-container">
        								<span class="timestamp"><?php echo humanTiming(strtotime($row1['date_time'])); ?></span>
        								<span class="spoiler-status">·Spoilers</span>
      								</p>
    							</div>
  							</div>
  							<div class="body">
                                  <?php 
                if($row1['deleted'] == 2) {
                  echo '<p class="deleted-message">
                  Deleted by Moderator.<br>
                  Post ID: '.$pid.'
                  </p>';
                }
                if($row1['deleted'] == 3) {
                  echo '<p class="deleted-message">
                  Deleted by Administrator.<br>
                  Post ID: '.$pid.'
                  </p>';
                }
                  if($row1['deleted'] == 4) {
                  echo '<p class="deleted-message">
                  Deleted by owner of Ciiverse.<br>
                  Post ID: '.$pid.'
                  </p>';
                }
                   ?>
                  <?php 
                  if(!empty($row1['screenshot'])) {
                     echo '<div class="screenshot-container still-image"><img src="'.$row1['screenshot'].'"></div>';
                    }
                  ?>
          						<p class="post-content-text"><?php echo htmlspecialchars($row1['content']); ?></p>    
                  <?php 

                  if(!empty($row1['web_url'])) {
                    echo '<p><a href="'.htmlspecialchars($row1['web_url']).'">'.htmlspecialchars($row1['web_url']).'</a></p>';
                  }

                  if(strpos($row1['web_url'], 'youtube') || strpos($row1['web_url'], 'youtu.be')) {
                    echo '<div class="screenshot-container video"><iframe class="youtube-player" type="text/html" width="490" height="276" src="https://www.youtube.com/embed/'.getVideoID($row1['web_url']).'?rel=0&amp;modestbranding=1&amp;iv_load_policy=3" allowfullscreen="" frameborder="0"></iframe></div>';
                  }

                  if($row1['deleted'] > 1) {
                    exit();
                  }

                  ?>
    							<div class="post-meta">
                    <button <?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 'true') {if($_SESSION['ciiverseid'] == $row1['owner']) {echo 'disabled';}}else{echo 'disabled';} ?> class="symbol submit empathy-button" id="<?php echo $pid; ?>" data-yeah-type="post" data-remove="<?php echo $yeah_cnt; ?>" type="button"><span class="empathy-button-text"><?php if($yeah_cnt > 0){echo 'Unyeah';}else{echo print_yeah($row1['feeling']);} ?></span></button>
                    <div class="empathy symbol"><span class="symbol-label">Yeahs</span><span class="empathy-count"><?php echo $yeah_count; ?></span></div>
                      <div class="reply symbol"><span class="symbol-label">Comments</span><span class="reply-count"><?php echo $count; ?></span></div></div>
</section>
<?php if($yeah_count > 0) { 
echo '<div id="empathy-content">';

if($_SESSION['loggedin']) {
  echo '<a href="/users/'.$_SESSION['ciiverseid'].'" class="post-permalink-feeling-icon visitor'.($user['user_type'] > 2 ? 'official-user' : '').'" '.($yeah_cnt == 0 ? 'style="display: none;"' : '').'><img src="'.user_pfp($_SESSION['ciiverseid'],$row1['feeling']).'" class="user-icon"></a>';
}

  while($yeah_data = mysqli_fetch_array($get_yeah_data)) {

    $get_owner_data = $db->query("SELECT * FROM users WHERE ciiverseid = '".$yeah_data['owner']."'");
    $owner_data = mysqli_fetch_array($get_owner_data);

    echo '<a href="/users/'.$yeah_data['owner'].'" class="post-permalink-feeling-icon visitor'.($owner_data['user_type'] > 2 ? 'official-user' : '').'">
<img src="'.user_pfp($yeah_data['owner'],$row1['feeling']).'" class="user-icon"></a>';

  }

echo '</div>
<br>';
} ?>
<div id="reply-content">
  <h2 class="reply-label">Comments</h2>
  	 <ul class="list reply-list test-reply-list">
  <?php if($count !== 0) { while($row = mysqli_fetch_array($res4)):
    $check_yeahed = $db->query("SELECT * FROM yeahs WHERE post_id = ".$row['id']." AND type = 'comment' AND owner = '".$_SESSION['ciiverseid']."'");
    $yeahed = mysqli_num_rows($check_yeahed);

    $get_yeahs = $db->query("SELECT yeah_id FROM yeahs WHERE post_id = ".$row['id']." AND type = 'comment'");
    $yeah_count = mysqli_num_rows($get_yeahs);

    echo '<li class="post '.($row['owner'] == $row1['owner'] ? 'my' : 'other').' trigger"><a class="icon-container '.print_badge($row['owner']).'" href="/users/'.$row['owner'].'"><img class="icon" src="'.htmlspecialchars(user_pfp($row['owner'],$row['feeling'])).'"></a><div class="body">
    <div class="header"><p class="user-name"><a href="/users/'.$row['owner'].'">'.htmlspecialchars($row['nickname']).'</a></p><p class="timestamp-container"><a class="timestamp">'.humanTiming(strtotime($row['date_time'])).'</a><span class="spoiler-status"> ·Spoilers</span></p></div><p class="reply-content-text">'.htmlspecialchars($row['content']).'</p><div class="reply-meta"><button '.($_SESSION['loggedin'] == false || $row['owner'] == $_SESSION['ciiverseid'] ? 'disabled' : '').' class="symbol submit empathy-button" id="'.$row['id'].'" data-yeah-type="comment" data-remove="'.($yeahed == 1 ? '1' : '0').'" type="button"><span class="empathy-button-text">'.($yeahed == 1 ? 'Unyeah' : print_yeah($row['feeling'])).'</span></button><div class="empathy symbol"><span class="symbol-label">Yeahs</span><span class="empathy-count">'.$yeah_count.'</span></div></div></div>';
    endwhile;
  } else { echo '<div class="no-reply-content"><div><p>This post has no comments.</p></div></div>'; }
     ?>
    
</li>
</ul>
<h2 class="reply-label">Add a comment.</h2>
	<?php 
	if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) { 
	echo '<form id="reply-form" action="/posts/comment.php" method="post">
  <div class="feeling-selector js-feeling-selector test-feeling-selector"><label class="symbol feeling-button feeling-button-normal checked"><input type="radio" name="feeling_id" value="0" checked=""><span class="symbol-label">normal</span></label><label class="symbol feeling-button feeling-button-happy"><input type="radio" name="feeling_id" value="1"><span class="symbol-label">happy</span></label><label class="symbol feeling-button feeling-button-like"><input type="radio" name="feeling_id" value="2"><span class="symbol-label">like</span></label><label class="symbol feeling-button feeling-button-surprised"><input type="radio" name="feeling_id" value="3"><span class="symbol-label">surprised</span></label><label class="symbol feeling-button feeling-button-frustrated"><input type="radio" name="feeling_id" value="4"><span class="symbol-label">frustrated</span></label><label class="symbol feeling-button feeling-button-puzzled"><input type="radio" name="feeling_id" value="5"><span class="symbol-label">puzzled</span></label></div>
  <div class="textarea-container" align="center">
      <textarea name="body" class="textarea-text textarea" maxlength="1000" placeholder="Add a comment here or whatever." data-required=""></textarea>
  </div>
  <div class="form-buttons">
    <input class="black-button reply-button" type="submit" value="Send" data-track-category="reply" data-post-content-type="text" data-action="/posts/comment.php">
    <input type="hidden" maxlength="11" name="pid" value="'.$pid.'"
  </div>
</form>'; } else {
	echo "<p>You need an account to comment.<br>Don't have one? You can create one <a href='/register'>here.</a></p>";
}
	?>
</div>
<div class="dialog none" id="profile-post-remove" data-modal-types="edit-post" data-is-template="1">
<div class="dialog-inner">
  <div class="window">
    <h1 class="window-title">Remove favorite post</h1>
    <form class="edit-post-form" action="/unfavorite_post" method="post">
    <div class="window-body">
        <p class="window-body-content">Are you sure you want to remove this post from your favorite?</p>
    <div class="form-buttons">
          <input class="olv-modal-close-button gray-button" type="button" value="No">
          <input class="post-button black-button" type="submit" value="Yes">
    </div></form>
  </div>
</div>
</div>
<div class="dialog none" id="profile-post-page" data-modal-types="edit-post" data-is-template="1">
<div class="dialog-inner">
  <div class="window">
    <h1 class="window-title">Favorite post</h1>
    <form class="edit-post-form" action="/favorite_post" method="post">
    <input type="hidden" maxlength="11" name="id" value="<?=$pid?>">
    <div class="window-body">
        <p class="window-body-content">Are you sure you want to favorite this post? <?=(!empty($user['favorite_post']) ? 'This will replace your current favorite post.' : '')?></p>
    <div class="form-buttons">
          <input class="olv-modal-close-button gray-button" type="button" value="No">
          <input class="post-button black-button" type="submit" value="Yes">
    </div></form>
  </div>
</div>
</div>
<div class="dialog none" id="remove-post-page" data-modal-types="edit-post" data-is-template="1">
<div class="dialog-inner">
  <div class="window">
    <h1 class="window-title">Delete Post</h1>
    <form class="edit-post-form" action="/delete_post/<?php echo $pid; ?>" method="post">
    <div class="window-body">
        <p class="window-body-content">Do you really want to delete this post?</p>
    <div class="form-buttons">
          <input class="olv-modal-close-button gray-button" type="button" value="No">
          <input class="post-button black-button" type="submit" value="Yes">
    </div></form>
  </div>
</div>
</div>
</div>
<script type="text/javascript">
	    $(".reply-button").click(function(e){
      $(this).addClass('disabled');
    });
</script>
	<!-- Ur mom gay. -->
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