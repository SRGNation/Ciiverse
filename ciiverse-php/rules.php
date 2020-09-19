<?php 

$redirect = '/rules';
session_start();
require('lib/connect.php');
include('lib/users.php');
include('lib/htm.php');

$followers = $db->query("SELECT * FROM follows WHERE follow_to = '".$_SESSION['ciiverseid']."' ORDER BY id DESC");
$following = $db->query("SELECT * FROM follows WHERE follow_by = '".$_SESSION['ciiverseid']."' ORDER BY id DESC");

$follower_count = mysqli_num_rows($followers);
$following_count = mysqli_num_rows($following);

?>

<html>
<head>
  <?php 
  formHeaders('Rules - Ciiverse');
  ?>
</head>
<body id="help">
<div id="wrapper" <?php if(!$_SESSION['loggedin']) { echo 'class="guest"'; } ?>>
  <div id="sub-body">
      <?php
        if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
          echo form_top_bar($_SESSION['ciiverseid'], $_SESSION['nickname'], $_SESSION['pfp'], 'communities');
        } else { 
          echo ftbnli('communities');
        }
        ?>
  </div>
  <div id="main-body">
<?=userSidebar($_SESSION['ciiverseid'], true, true)?>
<div class="main-column">
  <div class="post-list-outline">
  <h2 class="label">Ciiverse Rules</h2>
  <div id="guide" class="help-content">
    <div class="num1">
      
      <p>The following are the rules to Ciiverse.<br><br></p>

      <h2>Let's get the obvious ones out of the way first</h2>

      <h3>NSFW and other stuff</h3>
      <p>Accounts that are trusted enough by admins and/or the owner will get image uploading permissions. Please be aware that we can revoke these permissions at any time, so please don't post NSFW or NSFL images like gore.</p>

      <h3>Spam</h3>
      <p>Obviously, don't spam. This can be sending the one thing or a random string of letters and numbers multiple times really fast. Your account will be deleted and you will get IP banned if you spam. So don't spam...</p>

      <h3>Yeahbombing</h3>
      <p>This is techinacally spamming. If you are caught yeahbombing, then your yeahs will be purged and you will get disabled from yeahing. Making a new account just to yeahbomb will get your account deleted and you will get IP banned.</p>

      <br><h2>Now let's get the rest of the rules out of the way</h2>

      <h3>Drama</h3>
      <p>Causing drama here could get your account disabled temporarily. So don't cause drama here.</p>

      <h3>Alting</h3>
      <p>Unless you make an alt because you forgot your password or something else, alting is not tolerated here, if you are caught making multiple alts you will be warned. Continuing to make alts even after you got warned will result in an IP ban.</p>

      <h3>Hate speech and harassment</h3>
      <p>Racism, homophobia, transphobia, abliesm, or any sort of bigotry or prejudice against someone's identity is not tolerated here, even if you try to play it off as a joke.</p>
      <p>Harassment against other users is also not tolerated here.</p>
      <p>Accounts who break this rule will usually go off with a warning. However, if you continue to break this rule, you will have your account deleted and banned.</p>
    </div>
  </div>
</div></div>
</div>
</div>
</body>
</html>