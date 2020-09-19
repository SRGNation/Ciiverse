<?php 

$redirect = '/rules';
session_start();
require('lib/connect.php');
include('lib/users.php');
include('lib/htm.php');

if(!$_SESSION['loggedin']) {
  exit('You have to login in order to view this page.');
}

$followers = $db->query("SELECT * FROM follows WHERE follow_to = '".$_SESSION['ciiverseid']."' ORDER BY id DESC");
$following = $db->query("SELECT * FROM follows WHERE follow_by = '".$_SESSION['ciiverseid']."' ORDER BY id DESC");

$follower_count = mysqli_num_rows($followers);
$following_count = mysqli_num_rows($following);

$userdata = $db->query("SELECT * FROM profile_tags WHERE owner = '".$_SESSION['ciiverseid']."' ORDER BY id DESC LIMIT 20");
$ud_count = mysqli_num_rows($userdata);

?>

<html>
<head>
  <?php 
  formHeaders('Profile tags - Ciiverse');
  ?>
</head>
<body>
<div id="wrapper" <?php if(!$_SESSION['loggedin']) { echo 'class="guest"'; } ?>>
  <div id="sub-body">
      <?php
          echo form_top_bar($_SESSION['ciiverseid'], $_SESSION['nickname'], $_SESSION['pfp'], 'user');
        ?>
  </div>
  <div id="main-body">
  <?=userSidebar($_SESSION['ciiverseid'], true, true)?>
<div class="main-column">
  <div class="post-list-outline">
    <h2 class="label">Profile tags <button type="button" class="button" style="max-width: 50px;padding: 4px 4px 4px;font-size: 12px;margin-right: 0;margin-top: -25px;" data-modal-open="#add-tag-page"><span class="symbol-label">+ Add</span></button></h2>
      <div class="list follow-list">
        <ul class="list-content-with-icon-and-text arrow-list" id="friend-list-content">
          <?php
          if($ud_count > 0)
          {
            while($row = mysqli_fetch_array($userdata))
            {
              echo '<li class="trigger"><p class="title"><div style="display: inline;"><a class="symbol button edit-button rm-post-button" href="/userdata/'.$row['id'].'/delete"><span class="symbol-label">Delete</span></a></div><div style="margin-top: -15px;"><span class="nick-name"><p style="font-size: 24px;font-weight: bold;">'.htmlspecialchars($row['tag_name']).'</p></span><span class="text">'.htmlspecialchars($row['tag_content']).'</span></p></div></li>';
            }
          }
          else
          {
            echo '<div class="no-content"><div><p>You don\'t have any profile tags yet. Profile tags are bits of info that show up on the bottom of your profile description. You can use them to describe a little bit more about yourself, or to plug outside social medias or your website. To add a profile tag, click on the "+ Add" button on the top right corner. You can add up to 20 profile tags.</p></div></div>';
          }
          ?>
        </ul>
      </div>
  </div>
</div>
</div>
<div class="dialog none" id="add-tag-page" data-modal-types="edit-post" data-is-template="1">
<div class="dialog-inner">
  <div class="window">
    <h1 class="window-title">Add a profile tag.</h1>
    <form class="edit-post-form" action="/tag/create" method="post">
    <div class="window-body">
        <input type="text" class="textarea" style="cursor: auto; height: auto;" placeholder="Tag name" name="tag_name" maxlength="16">
        <input type="text" class="textarea" style="cursor: auto; height: auto;" placeholder="Tag text" name="tag_content" maxlength="64">
        <input type="hidden" name="csrf_token" value="<?=$_COOKIE['csrf_token']?>">
    <div class="form-buttons">
          <input class="olv-modal-close-button gray-button" type="button" value="Cancel">
          <input class="post-button black-button" type="submit" value="Confirm">
    </div></form>
  </div>
</div>
</div>
</div>
</body>
</html>