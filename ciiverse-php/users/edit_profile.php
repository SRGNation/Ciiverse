<?php 

session_start();
$redirect = '/edit/profile';
require('../lib/connect.php');
include('../lib/htm.php');
include('../lib/users.php');

if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) { } else {
  die("You are not logged in. You need to log in to edit your profile.");
}

if(account_deleted($_SESSION['ciiverseid'])) {
  exit("An error occured. Please try logging back in and try again.");
}

if($_SERVER["REQUEST_METHOD"] == "POST") {

if($_POST['csrf_token'] !== $_COOKIE['csrf_token']) {
  exit();
}

$profile_pic = mysqli_real_escape_string($db,$_POST['prof_pic']);
$prof_desc = mysqli_real_escape_string($db,$_POST['prof_desc']);
$nnid = mysqli_real_escape_string($db,$_POST['nnid']);
if(!empty($_POST['nickname'])) {
  $nick = mysqli_real_escape_string($db,$_POST['nickname']); 
} else {
  $nick = 'Loser with no nickname.';
}
$ciiverseid = $_SESSION['ciiverseid'];


if(strlen($prof_desc) > 400) {
  $prof_desc = 'Profile descriptions can only allow up to 400 characters. It can\'t be any higher. Now please stop trying to hack Ciiverse it has been hacked enough times already :(';
}

if(isset($_POST['pfp_type'])) {
  $pfp_type = 1;
} else {
  $pfp_type = 0;
}

if($pfp_type == 1) {
#Haha frick you arian kordi
/* $ch = curl_init();
      curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://ariankordi.net/seth/'. $nnid,
        CURLOPT_HEADER => true,
        CURLOPT_RETURNTRANSFER => true));
      $response = curl_exec($ch);

          $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if($httpCode == 404 || $httpCode == 102) {
                    $mii_hash = '';
                } else {
$body = substr($response, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
$dom = new DOMDocument;
$dom->loadHTML($body);
$mii_hash = mysqli_real_escape_string($db,$body); */
    $ch = curl_init();
    $api = "https://accountws.nintendo.net/v1/api/";

    curl_setopt_array($ch, array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array(
            "X-Nintendo-Client-ID: a2efa818a34fa16b8afbc8a74eba3eda",
            "X-Nintendo-Client-Secret: c91cdb5658bd4954ade78533a339cf9a"
        )
    ));

    curl_setopt($ch, CURLOPT_URL, $api . "admin/mapped_ids?input_type=user_id&output_type=pid&input=" . $nnid);
    $mapped_ids = new SimpleXMLElement(curl_exec($ch));

    #This code works for some reason idk why but the code commented on top of this is Arians original code and it didn't work >:(
    if(empty($mapped_ids->mapped_id->out_id)) {
      $mii_hash = '';
      $dont_do = 1;
    }

    if(!isset($dont_do)) {
    $pid = $mapped_ids->mapped_id->out_id;
    curl_setopt($ch, CURLOPT_URL, $api . "miis?pids=" . $pid);
    $miis = new SimpleXMLElement(curl_exec($ch));
    curl_close($ch);

    foreach (json_decode(json_encode($miis), true)["mii"]["images"]["image"] as $a) {
        if ($a["type"] == "normal_face") {
           $aaa = $a["cached_url"];

            $mii1 = str_replace('http://mii-images.cdn.nintendo.net/', '', $aaa);
            $mii_hash = str_replace('_normal_face.png', '', $mii1); 
        }
    }
  }

} else {

  if(empty($user['mii_hash'])) {
    $mii_hash = '';
  } else {
    $mii_hash = $user['mii_hash'];
  }

}

$sql = "UPDATE users SET nickname = '$nick', prof_desc = '$prof_desc', pfp = '$profile_pic', pfp_type = '$pfp_type', nnid = '$nnid', mii_hash = '$mii_hash' WHERE ciiverseid = '$ciiverseid' ";
mysqli_query($db,$sql);

/* $_SESSION['prof_desc'] = $prof_desc;
$_SESSION['nickname'] = $nick;
$_SESSION['pfp'] = $profile_pic; */
}

$sql = "SELECT * FROM users WHERE ciiverseid = '".$_SESSION['ciiverseid']."' ";
$query = mysqli_query($db,$sql);
$user = mysqli_fetch_array($query);

$posts = $db->query("SELECT * FROM posts WHERE owner = '".$_SESSION['ciiverseid']."' AND deleted != 1 AND deleted != 5 ORDER BY post_id DESC");

$comments = $db->query("SELECT * FROM comments WHERE owner = '".$_SESSION['ciiverseid']."' ORDER BY id DESC");

$yeahs = $db->query("SELECT * FROM yeahs WHERE owner = '".$_SESSION['ciiverseid']."' ORDER BY yeah_id DESC");

$post_count = mysqli_num_rows($posts);
$reply_count = mysqli_num_rows($comments);
$yeah_count = mysqli_num_rows($yeahs);

$page = 5;
$userid = $_SESSION['ciiverseid'];

?>

<html>
  <head>
    <?php 

    formHeaders('Edit profile - Ciiverse');

    ?>
  </head>
  <body>
    <div id="wrapper">
    <div id="sub-body">
         <?php 
           if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
  echo form_top_bar($_SESSION['ciiverseid'], $_SESSION['nickname'], $_SESSION['pfp'], 'edit_profile');
  } else { 
  echo ftbnli('edit_profile'); }
         ?>
      </div>
      <div id="main-body">
    <div id="sidebar" class="user-sidebar">
          <div class="sidebar-container">
      
    <div id="sidebar-profile-body" class="without-profile-post-image">

      <div class="icon-container <?php if($user['user_type'] > 2) {echo "official-user";} ?>">
        <a href="/users/<?php echo $_SESSION['ciiverseid']; ?>">
          <img src="<?php echo user_pfp($_SESSION['ciiverseid'],0); ?>" class="icon">
        </a>

      </div>
      <?php 
        if($user['user_type'] > 1) {
          printOrganization($user['user_type'],0);
        }
      ?>
      <a href="/users/<?php echo $_SESSION['ciiverseid']; ?>" class="nick-name"><?php echo $user['nickname']; ?></a>
      <p class="id-name"><?php echo $_SESSION['ciiverseid']; ?></p>
      </div>
    </div> 
    <div class="sidebar-setting sidebar-container">
  <div class="sidebar-post-menu">
    <a href="/users/<?php echo $_SESSION['ciiverseid']; ?>" class="sidebar-menu-post with-count symbol <?php if($page == 1) {echo'selected';} ?>">
      <span>All posts</span>
      <span class="post-count">
          <span class="test-post-count" id="js-my-post-count"><?php echo $post_count; ?></span>
        </span>
      </a>
    <a href="/users/<?php echo $userid; ?>/replies" class="sidebar-menu-post with-count symbol <?php if($page == 3) {echo'selected';} ?>">
      <span>Replies</span>
      <span class="post-count">
          <span class="test-post-count" id="js-my-post-count"><?php echo $reply_count; ?></span>
        </span>
      </a>
    <a class="sidebar-menu-empathies with-count symbol <?php if($page == 2) {echo'selected';} ?>" href="/users/<?php echo $userid; ?>/empathies">
      <span>Yeahs</span>
      <span class="post-count">
        <span class="test-empathy-count"><?php echo $yeah_count; ?></span>
      </span>
    </a>
  </div>
</div>

                 <div class="sidebar-container sidebar-profile">
       <?php 
       if(!empty($user['prof_desc'])) {
        echo '<div class="profile-comment">
        <p class="js-truncated-text">' .htmlspecialchars($user['prof_desc']).'</p>
              </div>';
       }
       ?>
       <div class="user-data">
        <div class="user-main-profile data-content">
<h4><span>NNID</span></h4>
<div class="note"><?php if(!empty($user['nnid'])){echo $user['nnid'];}else{echo 'Not set.';} ?></div>
</div>
<div class="game-skill data-content">
  <!-- Nothings here lol. -->
</div>
</div>
  </div>
 

</div>        
    <div class="main-column"><div class="post-list-outline">
  <h2 class="label">Edit Profile</h2>
  <form class="setting-form" method="post" action="/edit/profile">
  <input type="hidden" name="csrf_token" value="<?php echo $_COOKIE['csrf_token']; ?>">

    <ul class="settings-list">
     <li> 
      <input type="text" class="textarea" style="cursor: auto; height: auto;" name="nickname" maxlength="32" placeholder="Nickname" value="<?php echo $user['nickname']; ?>"></li><br>
         <li> 
      <input type="text" class="textarea" style="cursor: auto; height: auto;" name="nnid" maxlength="16" placeholder="NNID" value="<?php echo $user['nnid']; ?>"><br>
      <input type="checkbox" <?php if($user['pfp_type'] == 1) {echo 'checked=""';} ?> name="pfp_type"><label for="pfp_type">Use Mii from the NNID for your profile picture.</label></li>
<br><li><input maxlength="2000" class="textarea" style="cursor: auto; height: auto;" type="text" placeholder="Profile Picture URL" id="prof_pic" name="prof_pic" value="<?php echo $user['pfp']; ?>"></li>
        <p class="settings-label">Profile Comment</p>
    </li><li class="setting-profile-comment">
        <textarea id="prof_desc" class="textarea" name="prof_desc" maxlength="400" placeholder="Write about yourself here."><?php echo htmlspecialchars($user['prof_desc']); ?></textarea></li>

    </ul>   
       <?php  /* <p><br>Other settings:<br></p>
           <input type="checkbox" checked="" name="show_replies"><label for="show_replies">Show replies on your profile.</label> */ ?>
    <div class="form-buttons">
      <input type="submit" class="black-button apply-button" name="Edit_Profile" value="Save">
    </div>
  </form>
</div></div></div>
<script type="text/javascript">
      $(".apply-button").click(function(e){
      $(this).addClass('disabled');
    });
  </script>
  </body>
</html>