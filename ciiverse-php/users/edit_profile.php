<?php 

session_start();
$redirect = '/edit/profile';
require('../lib/connect.php');
include('../lib/users.php');
include('../lib/htm.php');

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

if(isset($_POST['dark_mode'])) {
  setcookie('dark_mode', 1, time() + (86400 * 30), '/');
} else {
  if(isset($_COOKIE['dark_mode'])) {
      setcookie('dark_mode', '', time() - 3600, '/');
  }
}

$profile_pic = $user['pfp'];
$pfp_type = $_POST['pfptype'];
$prof_desc = $_POST['prof_desc'];
$nnid = $_POST['nnid'];
if(!empty($_POST['nickname'])) {
  $nick = $_POST['nickname'];
} else {
  $nick = 'Kelsi Nielsen';
}
$ciiverseid = $_SESSION['ciiverseid'];

if(strlen($prof_desc) > 400) {
  exit('Profile description can\'t be more than 400 characters.');
}

if(isset($_POST['hates_yeah_notifs'])) {
  $hates_yeah_notifs = 1;
} else {
  $hates_yeah_notifs = 0;
}

if($_POST['hide_replies'] != 0 && $_POST['hide_replies'] != 1)
{
  exit('Your hide replies option is invalid.');
}

if($_POST['hide_yeahs'] != 0 && $_POST['hide_yeahs'] != 1)
{
  exit('Your hide yeahs option is invalid.');
}

/* 

  <li> 
      <input type="text" class="textarea" style="cursor: auto; height: auto;" name="nnid" maxlength="16" placeholder="NNID" value="<?php echo $user['nnid']; ?>"><br>
      <input type="checkbox" <?php if($user['pfp_type'] == 1) {echo 'checked=""';} ?> name="pfp_type"><label for="pfp_type">Use Mii from the NNID for your profile picture.</label></li>
<br><li><input maxlength="2000" class="textarea" style="cursor: auto; height: auto;" type="text" placeholder="Profile Picture URL" id="prof_pic" name="prof_pic" value="<?php echo $user['pfp']; ?>"></li>

*/

if($pfp_type == 1) {

  $mii_hash = $user['mii_hash'];
  $avatar = 0;

  if(isset($_FILES['avatar'])) { 
  $img = $_FILES['avatar'];
  } else {
  $img = null;
  }

  if(!empty($img['name'])) {
      $filename = $img['tmp_name'];
  
      $profile_pic = uploadImage($filename);
      if ($profile_pic == 1) {
        exit('Image failed to upload.');
      }
    } else {
      $profile_pic = $user['pfp'];
    }

}elseif($pfp_type == 2) {

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
    
    $avatar = 1;
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

}elseif($pfp_type == 3) {

  $profile_pic = '';
  $avatar = 0;
  $mii_hash = $user['mii_hash'];

}

$stmt = $db->prepare("UPDATE users SET nickname = ?, prof_desc = ?, pfp = ?, pfp_type = ?, nnid = ?, mii_hash = ?, hates_yeah_notifs = ?, hide_replies = ?, hide_yeahs = ? WHERE ciiverseid = ?");
$stmt->bind_param('sssissiiis', $nick, $prof_desc, $profile_pic, $avatar, $nnid, $mii_hash, $hates_yeah_notifs, $_POST['hide_replies'], $_POST['hide_yeahs'], $ciiverseid);
$stmt->execute();

/* $_SESSION['prof_desc'] = $prof_desc;
$_SESSION['nickname'] = $nick;
$_SESSION['pfp'] = $profile_pic; */
}

$stmt = $db->prepare("SELECT post_id, screenshot, owner FROM posts WHERE post_id = ? AND deleted = 0");
$stmt->bind_param('i', $user['favorite_post']);
$stmt->execute();
$result = $stmt->get_result();
$fav_post = $result->fetch_assoc();

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
        <?=userSidebar($_SESSION['ciiverseid'], false, true)?>
</div>        
    <div class="main-column"><div class="post-list-outline">
  <h2 class="label">Edit Profile</h2>
  <form class="setting-form" method="post" action="/edit/profile" enctype="multipart/form-data">
  <input type="hidden" name="csrf_token" value="<?php echo $_COOKIE['csrf_token']; ?>">
    <ul class="settings-list">
     <li> 
      <p class="settings-label">Nickname</p>
      <input type="text" class="textarea" style="cursor: auto; height: auto;" name="nickname" maxlength="32" placeholder="Nickname goes here." value="<?php echo $user['nickname']; ?>"></li>
         <li> 
      <p class="settings-label">Nintendo Network ID (NNID)</p>
      <input type="text" class="textarea" style="cursor: auto; height: auto;" name="nnid" maxlength="16" placeholder="NNID goes here." value="<?php echo $user['nnid']; ?>"><br></li>
        <li class="pfp-settings">
        <div class="icon-container" style="padding-right: 8px; float: left;">
        <img class="icon mii <?php if($user['pfp_type'] == 0) {echo 'none';} ?>" src="<?php if(!empty($user['mii_hash'])) {echo 'https://mii-secure.cdn.nintendo.net/'.$user['mii_hash'].'_normal_face.png';} else {echo '/img/defult_pfp_normal.png';} ?>">
        <img class="icon default <?php if(!empty($user['pfp']) || $user['pfp_type'] == 1) {echo 'none';} ?>" src="/img/defult_pfp_normal.png">
        <img class="icon custom <?php if(empty($user['pfp']) || $user['pfp_type'] == 1) {echo 'none';} ?>" src="<?php if(!empty($user['pfp'])) {echo htmlspecialchars($user['pfp']);} else {echo '/img/defult_pfp_normal.png';} ?>">
        </div>
        <p class="settings-label">Do you want the avatar shown beside your content to use a custom image, a Mii, or none?</p>
        <label>
        <input type="radio" id="pfptype_1" name="pfptype" value="1" <?php if($user['pfp_type'] == 0) {echo 'checked';} ?> />
        Custom
        </label>
        <label>
        <input type="radio" id="pfptype_2" name="pfptype" value="2" <?php if($user['pfp_type'] == 1) {echo 'checked';} ?> />
        Mii
        </label>
        <label>
        <input type="radio" id="pfptype_3" name="pfptype" value="3" <?php if(empty($user['pfp']) & $user['pfp_type'] == 0) {echo 'checked';} ?> />
        No avatar
        </label>
        <label class="file-button-container <?php if(empty($user['pfp']) || $user['pfp_type'] == 1) {echo 'none';} ?>">
        <span class="input-label">File upload <span>PNG, JPG, BMP, and GIF are allowed.</span></span>
        <input type="file" class="file-button" name="avatar" accept="image/*">
        </label>
        </li>
      </li>
    </li><li class="setting-profile-comment">
        <p class="settings-label">Profile Comment</p>
        <textarea id="prof_desc" class="textarea" name="prof_desc" maxlength="400" placeholder="Write about yourself here."><?php echo htmlspecialchars($user['prof_desc']); ?></textarea>
      </li>
        <li class="setting-profile-post">
            <p class="settings-label">Favorite Post</p>
            <p class="note">You can set one of your own or someone elses screenshot posts as your favorite from the favorite button on that post.</p>
            <?php
            if($user['favorite_post'] !== null) {
                echo '<div class="select-content"><button id="profile-post" type="button" data-modal-open="#profile-post-page" class="submit"><img src="' . htmlspecialchars($fav_post['screenshot']) . '"><span class="symbol">Remove</span></button></div>';
            }
            ?>
        </li>
        <li>
          <p class="settings-label">Do you want to hide your replies page?</p>
          <div class="select-content">
            <div class="select-button">
              <select name="hide_replies" id="yeah_notifs">
                <option value="0" <?php if($user['hide_replies'] == 0) {echo 'selected';} ?>>Don't Hide</option>
                <option value="1" <?php if($user['hide_replies'] == 1) {echo 'selected';} ?>>Hide</option>
              </select>
            </div>
          </div>
        </li>
        <li>
          <p class="settings-label">Do you want to hide your yeahs page?</p>
          <div class="select-content">
            <div class="select-button">
              <select name="hide_yeahs" id="yeah_notifs">
                <option value="0" <?php if($user['hide_yeahs'] == 0) {echo 'selected';} ?>>Don't Hide</option>
                <option value="1" <?php if($user['hide_yeahs'] == 1) {echo 'selected';} ?>>Hide</option>
              </select>
            </div>
          </div>
        </li>
        <li>
          <p class="settings-label">Yeah Notifs</p>
          <input type="checkbox" <?php if($user['hates_yeah_notifs'] == 1) { echo 'checked'; } ?> name="hates_yeah_notifs"><label for="hates_yeah_notifs">Don't give me yeah notifs</label>
        </li>
        <li>
          <p class="settings-label">Dark Mode</p>
          <input type="checkbox" <?php if(isset($_COOKIE['dark_mode'])) {echo 'checked';} ?> name="dark_mode"><label for="dark_mode">Enable Dark Mode</label>
          <p class="note">This will only enable Dark Mode for this browser. Note: You may need to refresh the page after saving so dark mode will show up.</p>
        </li>
    </ul>   
    <div class="form-buttons">
      <input type="submit" class="black-button apply-button" name="Edit_Profile" value="Save">
    </div>
  </form>
</div>
<div class="dialog none" id="profile-post-page" data-modal-types="edit-post">
<div class="dialog-inner">
  <div class="window">
    <h1 class="window-title">Remove favorite post</h1>
    <form class="edit-post-form" action="/unfavorite_post" method="post">
    <div class="window-body">
        <p class="window-body-content">Are you sure you want to remove this post from your favorite?</p>
    <div class="form-buttons">
          <input class="olv-modal-close-button gray-button" type="button" value="No">
          <input class="post-button black-button" type="submit" value="Yes">
    </div></form>
  </div>
</div>
</div>
</div></div>
<script type="text/javascript">
      $(".apply-button").click(function(e){
      $(this).addClass('disabled');
    });
  </script>
  </body>
</html>