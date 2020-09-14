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