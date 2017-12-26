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

if(!empty($_POST['prof_pic'])) {
  $profile_pic = mysqli_real_escape_string($db,$_POST['prof_pic']);
}

$prof_desc = mysqli_real_escape_string($db,$_POST['prof_desc']);
$nnid = mysqli_real_escape_string($db,$_POST['nnid']);
if(!empty($_POST['nickname'])) {
  $nick = mysqli_real_escape_string($db,$_POST['nickname']); 
} else {
  $nick = mysqli_real_escape_string($db,$_SESSION['nickname']);
}
$ciiverseid = $_SESSION['ciiverseid'];

if(isset($_POST['pfp_type'])) {
  $pfp_type = 1;
} else {
  $pfp_type = 0;
}

if(isset($profile_pic)) {
$sql = "UPDATE users SET nickname = '$nick', prof_desc = '$prof_desc', pfp = '$profile_pic', pfp_type = '$pfp_type', nnid = '$nnid', mii_hash = null WHERE ciiverseid = '$ciiverseid' ";
} else {
$sql = "UPDATE users SET nickname = '$nick', prof_desc = '$prof_desc', pfp = '/img/defult_pfp.png' pfp_type = '$pfp_type', nnid = '$nnid', mii_hash = null WHERE ciiverseid = '$ciiverseid' ";
$profile_pic = '/img/defult_pfp.png';
}
$result = mysqli_query($db,$sql);

$_SESSION['prof_desc'] = $prof_desc;
$_SESSION['nickname'] = $nick;
$_SESSION['pfp'] = $profile_pic;
}

$sql = "SELECT * FROM users WHERE ciiverseid = '".$_SESSION['ciiverseid']."' ";
$query = mysqli_query($db,$sql);
$user = mysqli_fetch_array($query);

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
    <div class="main-column"><div class="post-list-outline">
  <h2 class="label">Edit Profile</h2>
  <form class="setting-form" method="post" action="/edit/profile">
    

    <ul class="settings-list">
     <li> 
      <input type="text" name="nickname" maxlength="32" placeholder="Nickname" value="<?php echo $_SESSION['nickname']; ?>"></li><br>
         <li> 
      <input type="text" name="nnid" maxlength="16" placeholder="NNID" value="<?php echo $user['nnid']; ?>"><br><br>
      <input type="checkbox" <?php if($user['pfp_type'] == 1) {echo 'checked=""';} ?> name="pfp_type"><label for="pfp_type">Use Mii from the NNID for your profile picture.</label></li>
<br><li><input maxlength="2000" type="text" placeholder="Profile Picture URL" id="prof_pic" name="prof_pic" value="<?php echo $_SESSION['pfp']; ?>"></li>
        <p class="settings-label">Profile Comment</p>
    </li><li class="setting-profile-comment">
        <textarea id="prof_desc" class="textarea" name="prof_desc" maxlength="400" placeholder="Write about yourself here."><?php echo $_SESSION['prof_desc']; ?></textarea></li>

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