<?php 

require("lib/connect.php");
include("lib/menu.php");
session_start();

if(isset($_SESSION['ciiverseid'])) { 

		$cvid = $_SESSION['ciiverseid'];

		$sql = "SELECT is_owner FROM users WHERE ciiverseid = '$cvid' ";
		$result = mysqli_query($db,$sql);
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);

		$is_owner = $row['is_owner'];
	}

$pid = mysqli_real_escape_string($db,$_GET['pid']);

$sql1 = "SELECT owner, content, is_verified, community_id, comments FROM posts WHERE post_id = '$pid' ";
$res1 = mysqli_query($db,$sql1);
$row1 = mysqli_fetch_array($res1);

$count = mysqli_num_rows($res1);

if($count !== 1) {
	die("This post doesn't exist.");
}

$sql2 = "SELECT nickname, pfp FROM users WHERE ciiverseid = '".$row1['owner']."'";
$res2 = mysqli_query($db,$sql2);
$row2 = mysqli_fetch_array($res2);

$sql3 = "SELECT community_name, community_picture FROM communities WHERE id =".$row1['community_id'];
$res3 = mysqli_query($db,$sql3);
$row3 = mysqli_fetch_array($res3);

$sql4 = "SELECT * FROM comments WHERE post_id = '$pid' ";
$res4 = mysqli_query($db,$sql4);

$count = mysqli_num_rows($res4);

?>

<html>
	<head><meta http-equiv="Content-Type" content="text/html; charset=gb18030">
		<title><?php echo $row2['nickname']; ?>'s post - Ciiverse</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<link rel="stylesheet" href="/offdevice.css"></link>
		<link rel="shortcut icon" href="/icon.png" />
    <script src="/js/complete-en.js"></script>
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
    							<a class="icon-container <?php if($row1['is_verified'] == true) { echo "official-user"; } ?>" href="/users/<?php echo $row1['owner']; ?>"><img class="icon" src="<?php if(!empty($row2['pfp'])) {echo $row2['pfp'];} else {echo "/img/defult_pfp.png";} ?>"></a>
    							<div class="user-name-content">
      								<p class="user-name"><a href="/users/<?php echo $row1['owner']; ?>"><?php echo $row2['nickname']; ?></a><span class="user-id"><?php echo $row1['owner']; ?></span></p>
      								<p class="timestamp-container">
        								<span class="timestamp">Timestamp lol</span>
        								<span class="spoiler-status">·Spoilers</span>
      								</p>
    							</div>
  							</div>
  							<div class="body">
          						<p class="post-content-text"><?php echo htmlspecialchars($row1['content']); ?></p>
    							<div class="post-meta">
    								<button <?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    									if($row1['owner'] == $cvid) {
    										echo "disabled";
    										}
    									} else {
    										echo "disabled";
    									} ?> class="symbol submit empathy-button" type="button" data-action="/posts/yeah.php"><span class="empathy-button-text">Yeah!</span></button>
        				<div class="empathy symbol"><span class="symbol-label">Yeahs</span><span class="empathy-count">0</span></div>
      								<div class="reply symbol"><span class="symbol-label">Comments</span><span class="reply-count"><?php echo $row1['comments']; ?></span></div>
    		
  </div>
  <?php 
  if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
  	if($_SESSION['ciiverseid'] == $row1['owner']) {
  	echo '<div align="right">
  		<form action="/delete_post/'.$pid.'">
      <button type="submit">Delete</button>
  </form>
  </div>';
	} else {
		if($is_owner == 'true') {
			  	echo '<div align="right">
  		<form action="/delete_post/'.$pid.'">
      <button type="submit">Delete</button>
  </form>
  </div>';
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
  <a class="icon-container '; if($row['is_verified'] == 'true') { 
  	echo 'official-user';
  } echo '" href="/users/'.$row['owner'].'"><img class="icon" src="'.htmlspecialchars($row['owner_pfp']).'"></a>
  <div class="body">
    <div class="header">
      <p class="user-name"><a href="/users/'.$row['owner'].'">'.htmlspecialchars($row['owner_nickname']).'</a></p>
      <p class="timestamp-container">
        <a class="timestamp">Timestamp lol.</a>
        <span class="spoiler-status"> ·Spoilers</span>
      </p>
    </div>
    <p class="reply-content-text">'.htmlspecialchars($row['content']).'</p>
    <div class="reply-meta">
        <button class="symbol submit empathy-button" type="button"><span class="empathy-button-text">Yeah!</span></button>
        <div class="empathy symbol"><span class="symbol-label">Yeahs</span><span class="empathy-count">0</span></div>
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
      <form class="edit-post-form" action="/posts/AYMHAAADAAADV44pW31Bvg.set_spoiler" method="post">
        <p class="select-button-label">Select an action:</p>
        <select name="edit-type">
          <option value="" selected="">Select an option.</option>
          <option value="spoiler" data-action="/posts/edit_post.php">Edit Post</option>
          <option value="delete" data-track-category="post" data-track-action="deletePost" data-track-label="default" data-action="">
            Delete
          </option>
        </select>
        <div class="form-buttons">
          <input class="olv-modal-close-button gray-button" type="button" value="Cancel">
          <input disabled="" class="post-button black-button disabled" type="submit" value="Submit" div="" <="">
      
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