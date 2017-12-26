<?php 

session_start();
$redirect = '/post/'.$_GET['pid'];
require("lib/connect.php");
include("lib/htm.php");
include("lib/users.php");

$pid = mysqli_real_escape_string($db,$_GET['pid']);

$sql1 = "SELECT * FROM posts WHERE post_id = '$pid' ";
$res1 = mysqli_query($db,$sql1);
$row1 = mysqli_fetch_array($res1);

$count = mysqli_num_rows($res1);

if($count !== 1) {
	die("This post doesn't exist.");
}

#This is to get the user information.
$sql2 = "SELECT nickname, pfp, user_type FROM users WHERE ciiverseid = '".$row1['owner']."'";
$res2 = mysqli_query($db,$sql2);
$row2 = mysqli_fetch_array($res2);

#This is to get the community information.
$sql3 = "SELECT community_name, community_picture FROM communities WHERE id =".$row1['community_id'];
$res3 = mysqli_query($db,$sql3);
$row3 = mysqli_fetch_array($res3);

#These are to get the comments.
$sql4 = "SELECT comments.id, comments.post_id, comments.content, comments.owner, users.pfp, users.nickname, users.user_type, comments.date_time FROM comments, users WHERE comments.post_id = '$pid' AND users.ciiverseid = comments.owner ORDER BY id ASC";
$res4 = mysqli_query($db,$sql4);

#These are to get the yeahs.
$aaa = $db->query("SELECT * FROM yeahs WHERE post_id = '$pid' ");

$count = mysqli_num_rows($res4);
$yeah_count = mysqli_num_rows($aaa);

#This is to see if you already yeahed this post.
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 'true') {
  $check_yeah = $db->query("SELECT * FROM yeahs WHERE post_id = '$pid' AND owner = '".$_SESSION['ciiverseid']."' ");
  $yeah_cnt = mysqli_num_rows($check_yeah);
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
		<div id="wrapper">
			<div id="sub-body">
				<?php 
				  if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
  echo form_top_bar($_SESSION['ciiverseid'], $_SESSION['nickname'], $_SESSION['pfp'], 'posts');
  } else { 
  echo ftbnli('posts'); }
				?>
        </div>
			<div id="main-body">
				<div class="main-column">
					<div class="post-list-outline">
						<section class="post post-subtype-default" id="post-content">
							<header class="community-container">
    							<h1 class="community-container-heading">
      								<a href="/communities/<?php echo $row1['community_id']; ?>"><img class="community-icon" src="<?php echo $row3['community_picture']; ?>"><?php echo $row3['community_name']; ?> Community</a>
    							</h1>
  							</header>
  							<div class="user-content">
    							<a class="icon-container <?php if($row2['user_type'] > 2) { echo "official-user"; } ?>" href="/users/<?php echo $row1['owner']; ?>"><img class="icon" src="<?php echo htmlspecialchars(user_pfp($row1['owner'],$row1['feeling'])); ?>"></a>
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
                  if(!empty($row1['screenshot'])) {
                     echo '<div class="screenshot-container still-image"><img src="'.$row1['screenshot'].'"></div>';
                    }
                  ?>
          						<p class="post-content-text"><?php echo htmlspecialchars($row1['content']); ?></p>
    							<div class="post-meta">
                    <form action="/<?php if($yeah_cnt == 0){echo 'add';}else{echo 'delete';} ?>_yeah/<?php echo $pid; ?>/post">
                    <?php 
                    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                    if($row1['owner'] == $_SESSION['ciiverseid']) {
                      echo '<button disabled class="symbol submit empathy-button" type="submit"><span class="empathy-button-text">'.print_yeah($row1['feeling']).'</span></button>';
                    } else {
                      if($yeah_cnt > 0) {
                      echo '<button class="symbol submit empathy-button" type="submit"><span class="empathy-button-text">Unyeah</span></button>';
                      } else {
                      echo '<button class="symbol submit empathy-button" type="submit"><span class="empathy-button-text">'.print_yeah($row1['feeling']).'</span></button>';
                      }
                    }

                    } else {
    								echo '<button disabled class="symbol submit empathy-button" type="submit"><span class="empathy-button-text">'.print_yeah($row1['feeling']).'</span></button>';
                    }
                    ?>
                      <div class="empathy symbol"><span class="symbol-label">Yeahs</span><span class="empathy-count"><?php echo $yeah_count; ?></span></div>
                      <div class="reply symbol"><span class="symbol-label">Comments</span><span class="reply-count"><?php echo $count; ?></span></div> 
                    </form>
  </div>
  <?php 
  if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
  	if($_SESSION['ciiverseid'] == $row1['owner']) {
  	echo '<button type="button" class="symbol button edit-button edit-post-button" data-modal-open="#edit-post-page">
<span class="symbol-label">Edit</span></button>';
	} else {
  if($user['user_type'] > 1) { 
		if($user['user_type'] > $row2['user_type']) {
			  	echo '<button type="button" class="symbol button edit-button edit-post-button" data-modal-open="#edit-post-page">
<span class="symbol-label">Edit</span></button>';
		}
	}
}
}
  ?>
</section>

<div id="reply-content">
  <h2 class="reply-label">Comments</h2>
  	 <ul class="list reply-list test-reply-list">
  <?php if($count !== 0) { while($row = mysqli_fetch_array($res4)): echo '
<li class="post other trigger">
  <a class="icon-container '; if($row['user_type'] > 2) { 
  	echo 'official-user';
  } echo '" href="/users/'.$row['owner'].'"><img class="icon" src="'.htmlspecialchars(user_pfp($row['owner'],0)).'"></a>
  <div class="body">
    <div class="header">
      <p class="user-name"><a href="/users/'.$row['owner'].'">'.htmlspecialchars($row['nickname']).'</a></p>
      <p class="timestamp-container">
        <a class="timestamp">'.humanTiming(strtotime($row['date_time'])).'</a>
        <span class="spoiler-status"> ·Spoilers</span>
      </p>
    </div>
    <p class="reply-content-text">'.htmlspecialchars($row['content']).'</p>
    <div class="reply-meta">
        <button class="symbol submit empathy-button" type="button" disabled><span class="empathy-button-text">Yeah!</span></button>
        <div class="empathy symbol"><span class="symbol-label">Yeahs</span><span class="empathy-count">Sorry you still can\'t yeah comments...</span></div>
    </div>
    </div>';
    endwhile;
     } else { echo 'This post has no comments.'; }
     ?>
    
</li>
</ul>
<h2 class="reply-label">Add a comment.</h2>
	<?php 
	if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) { 
	echo '<form id="reply-form" action="/posts/comment.php" method="post">
  <div class="textarea-container">
      <textarea name="body" class="textarea-text textarea" maxlength="400" placeholder="Add a comment here or whatever." data-required=""></textarea>
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
<div class="dialog none" id="edit-post-page" data-modal-types="edit-post">
<div class="dialog-inner">
  <div class="window">
    <h1 class="window-title">Edit Post</h1>
    <div class="window-body">
      <form class="edit-post-form" action="/delete_post/<?php echo $pid; ?>" method="post">
        <p class="select-button-label">Select an action:</p>
        <select name="edit-type">
          <option value="delete" data-track-category="post" data-track-label="default" data-action="/delete_post/<?php echo $pid; ?>">
            Delete
          </option>
        </select>
        <div class="form-buttons">
          <input class="olv-modal-close-button gray-button" type="button" value="Cancel">
          <input class="post-button black-button" type="submit" value="Submit">
      
    </div></form>
  </div>
</div>
</div>
<div class="dialog none" id="view-embed-link-code" data-is-template="1" data-modal-types="view_embed_link">
<div class="dialog-inner">
  <div class="window">
    <h1 class="window-title">Embed this post on a website.</h1>
    <div class="window-body">
      <div class="embed-warn">To embed this post on a website, copy the code below. Note that the design and content of the embedded post may change at any time.</div>
      <textarea name="body" class="textarea-text textarea" onclick="this.focus(); this.select();" readonly=""></textarea>
      <input class="olv-modal-close-button gray-button" type="button" value="Close">
    </div>
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
			</div>
		</div>
	</body>
</html>