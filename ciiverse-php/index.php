<?php

session_start();
$redirect = '/';
require('lib/connect.php');
include('lib/htm.php');
include('lib/users.php');

$sql = "SELECT id, community_picture, community_name, community_banner FROM communities WHERE rd_oly = 'false' AND deleted = 0 LIMIT 4";
$cinfo = mysqli_query($db,$sql);

#Use Cedar xddd

 ?>
<html>
<?php 

formHeaders('Community list - Ciiverse');

?>
<body>
<div id="wrapper">
<div id="sub-body">
  <?php
  if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
  echo form_top_bar($_SESSION['ciiverseid'], $_SESSION['nickname'], $_SESSION['pfp'], 'communities');
  } else { 
  echo ftbnli('communities'); }

        ?>
                </menu>
              </li>
            </ul>
          </li>
        </menu>
      </div>
    <div id="main-body">
      <div class="body-content" id="community-top">
        <div class="community-top-sidebar">
  </div>
      <div class="community-main">
        <h3 class="community-title symbol">All communities</h3>
        <div>
          <ul class="list community-list community-card-list test-hot-communities">
        <?php 

        while($infoc = mysqli_fetch_array($cinfo)) {
        echo '<li id="community" class="trigger test-community-list-item " data-href="/communities/'.$infoc['id'].'" tabindex="0">
  <img src="'.$infoc['community_banner'].'" class="community-list-cover">
  <div class="community-list-body">
  <span class="icon-container"><img src="'.$infoc['community_picture'].'" class="icon"></span>
  <div class="body">
      <a class="title" href="/communities/'.$infoc['id'].'" tabindex="-1">'.$infoc['community_name'].'</a>
      </li>
'; } ?>
  </div>
  </ul>
  </div>
</li>
</div>
        </div>
      </div>

    </div>
        </div>
</body>
</html>