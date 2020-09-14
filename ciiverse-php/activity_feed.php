<?php

session_start();
$redirect = '/feed';
require('lib/connect.php');
include('lib/users.php');
include('lib/htm.php');

if($_SESSION['loggedin'] == false) {
	exit('You need to be logged in to view this page.');
}

$followers = $db->query("SELECT * FROM follows WHERE follow_to = '".$_SESSION['ciiverseid']."' ORDER BY id DESC");
$following = $db->query("SELECT * FROM follows WHERE follow_by = '".$_SESSION['ciiverseid']."' ORDER BY id DESC");

$activity_feed = $db->query("SELECT * FROM posts WHERE deleted = 0 AND (owner IN (SELECT follow_to FROM follows WHERE follow_by = '".$_SESSION['ciiverseid']."') OR owner = '".$_SESSION['ciiverseid']."') ORDER BY post_id DESC LIMIT 50");

$follower_count = mysqli_num_rows($followers);
$following_count = mysqli_num_rows($following);

?>

<html>
<head>
	<?php 
	formHeaders('Activity Feed - Ciiverse');
	?>
</head>
<body>
	<div id="wrapper">
		<div id="sub-body">
         <?php 

  			echo form_top_bar($_SESSION['ciiverseid'], $_SESSION['nickname'], $_SESSION['pfp'], 'feed');

         ?>
      </div>
      <div id="main-body">
        <div id="sidebar" class="general-sidebar"><div class="sidebar-container">
        <?php
            $stmt = $db->prepare("SELECT post_id, screenshot, owner FROM posts WHERE post_id = ? AND deleted = 0");
            $stmt->bind_param('i', $user['favorite_post']);
            $stmt->execute();
            $result = $stmt->get_result();
            $fav_post = $result->fetch_assoc();

            if(!empty($fav_post['screenshot'])) { 
              echo '<a href="/post/'.$fav_post['post_id'].'" id="sidebar-cover" style="background-image:url('.htmlspecialchars($fav_post['screenshot']).')"><img src="'.htmlspecialchars($fav_post['screenshot']).'" class="sidebar-cover-image"></a><div id="sidebar-profile-body" class="with-profile-post-image">';
            } else {
              echo '<div id="sidebar-profile-body" class="without-profile-post-image">';
            }
          ?>
<div class="icon-container <?php echo print_badge($_SESSION['ciiverseid']); ?>">
<a href="/users/<?php echo $_SESSION['ciiverseid']; ?>">
<img src="<?php echo user_pfp($_SESSION['ciiverseid'],0); ?>" alt="chance" class="icon">
</a>
</div>
<?php printOrganization($user['user_type'],0) ?>
<a href="/users/<?php echo $_SESSION['ciiverseid']; ?>" class="nick-name"><?php echo $user['nickname']; ?></a>
<p class="id-name"><?php echo $_SESSION['ciiverseid']; ?></p>
</div><ul id="sidebar-profile-status">
<li><a href="/users/<?php echo $_SESSION['ciiverseid']; ?>/following"><span><span class="number"><?php echo $following_count; ?></span>Following</span></a></li>
<li><a href="/users/<?php echo $_SESSION['ciiverseid']; ?>/followers"><span><span class="number"><?php echo $follower_count; ?></span>Followers</span></a></li>
</ul>
</div><div class="sidebar-setting sidebar-container">
<ul>
<li><a href="/communities/55" class="sidebar-menu-info symbol"><span>Ciiverse Changelog</span></a></li>
<li><a href="/rules" class="sidebar-menu-guide symbol"><span>Ciiverse Rules</span></a></li>
</ul>
</div>
</div>
<div class="main-column">
  <div class="headline"><h2 class="headline-text"><span class="symbol activity-headline">Activity Feed</span></h2><form class="search" action="/user_search.php" method="GET"><input type="text" name="query" title="Search Users" placeholder="Search Users" minlength="1" maxlength="16"><input type="submit" value="q" title="Search">
</form></div>
<div id="js-main">
  <div class="list post-list js-post-list">
    <?php 

    while($activity = mysqli_fetch_array($activity_feed)) {

      printPost($activity['post_id'],2);

    }

    ?>
  </div>
</div>
  </div>
  			</div>
		</div>
</body>
</html>