<?php 

session_start();
$redirect = '/communities/'.$_GET['cid'];
require('lib/connect.php');
include('lib/htm.php');
include('lib/users.php');

$cid = mysqli_real_escape_string($db,$_GET['cid']);

$sql = "SELECT community_picture, community_name, comm_desc, community_banner, rd_oly, deleted, type FROM communities WHERE id=$cid ";
$result = mysqli_query($db,$sql);
$row = mysqli_fetch_array($result);

$count = mysqli_num_rows($result);

if($count !== 0) {

$picture = $row['community_picture'];
$name = $row['community_name'];
$description = $row['comm_desc'];
$banner = $row['community_banner'];
$rd_oly = $row['rd_oly'];
$type = $row['type'];

} else {
  exit("Community does not exist");
}

if($row['deleted'] == 1) {
  exit("This community has been deleted and is no longer available.");
}

if(isset($_GET['offset']) && isset($_GET['date_time'])) {
  $offset = ($_GET['offset'] * 50);
  $date_time = mysqli_real_escape_string($db,$_GET['date_time']);
  $get_posts = $db->query("SELECT post_id FROM posts WHERE community_id = $cid AND deleted = 0 AND date_time < '$date_time' ORDER BY post_id DESC LIMIT 50 offset $offset");

  while($post = mysqli_fetch_array($get_posts)) {

    printPost($post['post_id'],0);

  } 

  exit();
}

$ansql = "SELECT post_id FROM posts WHERE community_id = $cid AND deleted = 0 ORDER BY post_id DESC LIMIT 50";
$aresult = mysqli_query($db,$ansql);

$counting = mysqli_num_rows($aresult);

#This code below will check if the user has favorited this community
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
$community = $db->query("SELECT * FROM favorite_communities WHERE community_id = '$cid' AND owner = '".$_SESSION['ciiverseid']."' ");
$favorite = mysqli_num_rows($community);
}

?>

<html>

<head>
  <?php
  formHeaders($name.' Community - Ciiverse');
  ?>
</head>

<div id="wrapper" <?php if(!$_SESSION['loggedin']) { echo 'class="guest"'; } ?>>
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
        <?php 

          if($type == 2) {
            echo '<span class="platform-tag"><img src="/img/platform-tag-3ds.png"></span>';
          }elseif($type == 3) {
            echo '<span class="platform-tag"><img src="/img/platform-tag-wiiu.png"></span>';
          }elseif($type == 4) {
            echo '<span class="platform-tag"><img src="/img/platform-tag-wiiu-3ds.png"></span>';
          }

        ?>
      </span>
      <h1 class="community-name">
        <a href="/communities/<?php echo $cid; ?>"><?php echo $name; ?> Community</a>      </h1>
    </header>
      <div class="community-description js-community-description">
        <p class="text js-truncated-text"><?php echo $description; ?></p>
      </div>
    <?php
    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    echo  '<a class="symbol button favorite-button '.($favorite > 0 ? 'checked' : '').'" href="/favorite_community.php?cid='.$cid.'&action='.($favorite == 0 ? 'favorite' : 'unfavorite').'"><span class="favorite-button-text">Favorite</span></a>';
   }
     ?>
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
                if($_SESSION['ciiverseid'] !== '124598Dom' && $cid == 56) {
                echo 'This is a Dominic only community.';
                } else {
                form_post_thingy();
              }
  } else {
    if($user['user_level'] > 5) { form_post_thingy(); } else {
    echo 'This is a read only community.';
    }
  }
}
?>
<div class="body-content" id="community-post-list">
          <div class="list post-list js-post-list">
				<?php 
        if($counting == 0) {
            echo '<div class="no-content"><p>There are no posts on this community yet.</p></div>'; 
        } else {
            while($row = mysqli_fetch_array($aresult)) {
                printPost($row['post_id'],0);
            }
            echo '<div id="post-load"><center><button id="LMPIC" class="black-button apply-button" commute_id="'.$cid.'" date_time="'.date("Y-m-d H:i:s").' ?>">Load More posts</button></center>';
        }
  ?>
  </div>
</div>
</div>
</div>
</div>
  <?php 
  printFooter();
  ?>
</div>
</div>

</html>