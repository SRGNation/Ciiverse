<?php 

require('lib/connect.php');
include('lib/menu.php');
session_start();

if(isset($_SESSION['ciiverseid'])) { 

$cvid = mysqli_real_escape_string($db,$_SESSION['ciiverseid']);

$sql = "SELECT is_owner FROM users WHERE ciiverseid = '$cvid' ";
$result = mysqli_query($db,$sql);
$row = mysqli_fetch_array($result,MYSQLI_ASSOC);

$is_owner = $row['is_owner'];

}

$cid = mysqli_real_escape_string($db,$_GET['cid']);

$sql = "SELECT community_picture, community_name, comm_desc, community_banner, rd_oly FROM communities WHERE id='$cid' ";
$result = mysqli_query($db,$sql);
$row = mysqli_fetch_array($result);

$count = mysqli_num_rows($result);

if($count !== 0) {

$picture = $row['community_picture'];
$name = $row['community_name'];
$description = $row['comm_desc'];
$banner = $row['community_banner'];
$rd_oly = $row['rd_oly'];

} else {
  die("Community does not exist");
}

$ansql = "SELECT content, owner, post_id, owner_pfp, owner_nickname, is_verified, comments FROM posts WHERE community_id = '$cid' ORDER BY post_id DESC";
$aresult = mysqli_query($db,$ansql);

$counting = mysqli_num_rows($aresult);

function form_post_thingy() {
  global $cid;
        echo '<form method="post" action="/post.php">
          <input type="hidden" name="communityid" value="' . $cid . '">
        <div class="textarea-container" align="center">
    <textarea name="makepost" id="makepost" class="textarea-text textarea" maxlength="400" placeholder="Post something or whatever." style="margin-top:20px"></textarea>
  </div>
  <div class="form-buttons">
    <input type="submit" class="black-button post-button" value="Send" name="create-post">
  </div>
</form>';
}

?>

<html>

<head>
	<title><?php echo $name; ?> Community - Ciiverse</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<link rel="stylesheet" href="/offdevice.css"></link>
	<link rel="shortcut icon" href="/icon.png" />
	<script async src="https://www.google-analytics.com/analytics.js"></script>
  <script src="/js/complete-en.js"></script>
  <script type="text/javascript" src="/js/jquery-3.2.1.min.js"></script>
</head>

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
    <div id="sidebar">
      <section class="sidebar-container" id="sidebar-community">
      <span id="sidebar-cover">
        <a href="/communities?cid=<?php echo $cid; ?>">
          <img src="<?php echo $banner; ?>">
        </a>
      </span>
    <header id="sidebar-community-body">
      <span id="sidebar-community-img">
        <span class="icon-container">
          <a href="/communities?cid=<?php echo $cid; ?>">
            <img src="<?php echo $picture; ?>" class="icon">
          </a>
        </span>
        <span class="platform-tag">
            <img src="">
        </span>
      </span>
      <h1 class="community-name">
        <a href="/communities?cid=<?php echo $cid; ?>"><?php echo $name; ?> Community</a>      </h1>
    </header>
      <div class="community-description js-community-description">
        <p class="text js-truncated-text"><?php echo $description; ?></p>
      </div>
      
    <div class="sidebar-setting">
      <div class="sidebar-post-menu">
      </div>
    </div>
  </section>
    </div>
		<div class="main-column">
			<div class="post-list-outline">
        <?php 
        if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
          if($rd_oly == 'false') {
            form_post_thingy();
  } else {
    if($is_owner == true) { form_post_thingy(); } else {
    echo 'This is a read only community.';
    }
  }
}
?>
<div class="body-content" id="community-post-list">
          <div class="list post-list js-post-list">
				<?php 
        if($counting == 0) { echo "There are no posts on this community yet."; } else {
        while($row = mysqli_fetch_array($aresult)) { 
echo '<div id="post" data-href="/post/'.$row['post_id'].'" class="post post-subtype-default trigger" tabindex="0">
  <a href="/users/' . $row['owner'] .  '" class="icon-container '; if($row['is_verified'] == true) { echo 'official-user'; }  echo '"><img src="' .  htmlspecialchars($row['owner_pfp']) . '" class="icon"></a>
  <p class="user-name"><a href="/users/' . htmlspecialchars($row['owner']) . '">' . htmlspecialchars($row['owner_nickname']) . '</a></p>
    </p>
  <div class="body">
    <div class="post-content">
        <div class="tag-container">
        </div>
            <p class="post-content-text">' . htmlspecialchars($row['content']) . '</p>
    		</div>
      <div class="post-meta">
      <div class="reply symbol"><span class="symbol-label">Comments</span><span class="reply-count">'.$row['comments'].'</span></div>
  </div>
  		</div>

 	</div>
  ';
}
 }
  ?>
  <script type="text/javascript">
    $(".empathy-button").click(function(e){
      if ($(this).html() == "Yeah!") {
        $(this).html("Unyeah");
		        //.$ajax({
          //url: '/posts/yeah.php',
          //type: 'post',
        //})
      } else {
        $(this).html('Yeah!');
      }
    });
    $(".post-button").click(function(e){
      $(this).addClass('disabled');
    });
  </script>
</div>
</div>
</div>
</div>
</div>
</div>

</html>