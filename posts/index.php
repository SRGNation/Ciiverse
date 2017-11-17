<?php 

require("../login/IncludesOrSomething/db_login.php");
include("../login/IncludesOrSomething/functions.php");
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
	<head>
		<title><?php echo $row2['nickname']; ?>'s post - Ciiverse</title>
		<link rel="stylesheet" href="/offdevice.css"></link>
		<link rel="shortcut icon" href="/icon.png" />
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
               echo '<li id="global-menu-mymenu"><a href="/users/profile.php?ciiverseid='. $_SESSION['ciiverseid'] .'"><span class="icon-container '; if($is_owner == 'true') {echo "official-user";} echo '"><img src="'.$_SESSION['pfp']. '"></span><span>User Page</span></a></li>';
             }

             ?>
             <li id="global-menu-community" class="selected"><a href="/" class="symbol"><span>Communities</span></a></li>
<li id="global-menu-my-menu"><button class="symbol js-open-global-my-menu open-global-my-menu"></button>
                <menu id="global-my-menu" class="invisible none">
               
    <?php
    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) { } else {
    echo '<li><a href="/register" class="symbol my-menu-guide"><span>Sign Up</span></a></li>
    <li><a href="/login" class="symbol my-menu-guide"><span>Sign In</span></a></li>';
    }
    ?>
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
				<div class="main-column">
					<div class="post-list-outline">
						<section class="post post-subtype-default" id="post-content">
							<header class="community-container">
    							<h1 class="community-container-heading">
      								<a href="/communities?cid=<?php echo $row1['community_id']; ?>"><img class="community-icon" src="<?php echo $row3['community_picture']; ?>"><?php echo $row3['community_name']; ?> Community</a>
    							</h1>
  							</header>
  							<div class="user-content">
    							<a class="icon-container <?php if($row1['is_verified'] == true) { echo "official-user"; } ?>" href="/users?ciiverseid=<?php echo $row1['owner']; ?>"><img class="icon" src="<?php echo $row2['pfp']; ?>"></a>
    							<div class="user-name-content">
      								<p class="user-name"><a href="/users?ciiverseid=<?php echo $row1['owner']; ?>"><?php echo $row2['nickname']; ?></a><span class="user-id"><?php echo $row1['owner']; ?></span></p>
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
    									} ?> class="symbol submit empathy-button" type="button"><span class="empathy-button-text">Yeah!</span></button>
        				<div class="empathy symbol"><span class="symbol-label">Yeahs</span><span class="empathy-count">0</span></div>
      								<div class="reply symbol"><span class="symbol-label">Comments</span><span class="reply-count"><?php echo $row1['comments']; ?></span></div>
    		
  </div>
</section>
<div id="reply-content">
  <h2 class="reply-label">Comments</h2>
  	 <ul class="list reply-list test-reply-list">
  <?php if($count !== 0) { while($row = mysqli_fetch_array($res4)): echo '
<li class="post other trigger">
  <a class="icon-container '; if($row['is_verified'] == 'true') { 
  	echo 'official-user';
  } echo '" href="/users?ciiverseid='.$row['owner'].'"><img class="icon" src="'.htmlspecialchars($row['owner_pfp']).'"></a>
  <div class="body">
    <div class="header">
      <p class="user-name"><a href="/users?ciiverseid='.$row['owner'].'">'.htmlspecialchars($row['owner_nickname']).'</a></p>
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
    <input class="black-button reply-button" type="submit" value="Send" data-track-category="reply" data-post-content-type="text">
    <input type="hidden" maxlength="11" name="pid" value="'.$pid.'"
  </div>
</form>'; } else {
	echo "<p>You need an account to comment.<br>Don't have one? You can create one <a href='/register'>here.</a></p>";
}
	?>
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