<?php 

session_start();
$redirect = '/communities/'.$_GET['cid'];
require('lib/connect.php');
include('lib/htm.php');
include('lib/users.php');

$cid = mysqli_real_escape_string($db,$_GET['cid']);

$sql = "SELECT community_picture, community_name, comm_desc, community_banner, rd_oly, deleted FROM communities WHERE id='$cid' ";
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
  exit("Community does not exist");
}

if($row['deleted'] == 1) {
  exit("This community has been deleted and is no longer available.");
}

$ansql = "SELECT posts.content, posts.owner, posts.date_time, posts.yeahs, posts.feeling, posts.screenshot, posts.post_id, users.nickname, users.user_type, posts.comments FROM posts, users WHERE posts.community_id = '$cid' AND users.ciiverseid = posts.owner ORDER BY post_id DESC limit 100";
$aresult = mysqli_query($db,$ansql);

$counting = mysqli_num_rows($aresult);
?>

<html>

<head>
  <?php
  formHeaders($name.' Community - Ciiverse');
  ?>
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
        <a href="/communities/<?php echo $cid; ?>">
          <img src="<?php echo $banner; ?>">
        </a>
      </span>
    <header id="sidebar-community-body">
      <span id="sidebar-community-img">
        <span class="icon-container">
          <a href="/communities/<?php echo $cid; ?>">
            <img src="<?php echo $picture; ?>" class="icon">
          </a>
        </span>
        <span class="platform-tag">
            <img src="">
        </span>
      </span>
      <h1 class="community-name">
        <a href="/communities/<?php echo $cid; ?>"><?php echo $name; ?> Community</a>      </h1>
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
                if($_SESSION['ciiverseid'] !== '124598Dom' && $user['user_type'] < 3 && $cid == 56) {
                echo 'This is a Dominic only community.';
                } else {
                form_post_thingy();
              }
  } else {
    if($user['user_type'] > 2) { form_post_thingy(); } else {
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
  <a href="/users/' . $row['owner'] .  '" class="icon-container '; if($row['user_type'] > 2) { echo 'official-user'; }  echo '"><img src="'.htmlspecialchars(user_pfp($row['owner'],$row['feeling'])).'" class="icon"></a>
  <p class="user-name"><a href="/users/' . htmlspecialchars($row['owner']) . '">' . htmlspecialchars($row['nickname']) . '</a></p>
  <div class="timestamp-container"><span class="timestamp">'.humanTiming(strtotime($row['date_time'])).'</span></div>
    </p>

  <div class="body">
    <div class="post-content">
        <div class="tag-container">
        </div>
            '.(empty($row['screenshot']) ? '' : '<a class="screenshot-container still-image" href="/post/'.$row['post_id'].'"><img src="'.$row['screenshot'].'"></a>').'
            <p class="post-content-text">' . htmlspecialchars($row['content']) . '</p>
    		</div>
      <div class="post-meta">
      <div class="empathy symbol"><span class="symbol-label">Yeahs</span><span class="empathy-count">'.$row['yeahs'].'</span></div>
      <div class="reply symbol"><span class="symbol-label">Comments</span><span class="reply-count">'.$row['comments'].'</span></div>
  </div>
  		</div>

 	</div>
  ';
}
 }
  ?>
  <script type="text/javascript">
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