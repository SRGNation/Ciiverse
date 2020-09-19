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
      <?=userSidebar($_SESSION['ciiverseid'], true, true)?>
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