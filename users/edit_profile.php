<?php 

session_start();
require('../lib/connect.php');
include('../lib/menu.php');

if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) { } else {
  die("You are not logged in. You need to log in to edit your profile.");
}

if($_SERVER["REQUEST_METHOD"] == "POST") {

if(!empty($_POST['prof_pic'])) {
	$profile_pic = mysqli_real_escape_string($db,$_POST['prof_pic']);
} else {
	$profile_pic = "/defult_pfp.png";
}

$prof_desc = mysqli_real_escape_string($db,$_POST['prof_desc']);
if(!empty($_POST['nickname'])) {
	$nick = mysqli_real_escape_string($db,$_POST['nickname']); 
} else {
	$nick = mysqli_real_escape_string($db,$_SESSION['nickname']);
}
$ciiverseid = $_SESSION['ciiverseid'];

$sql = "UPDATE users SET nickname = '$nick', prof_desc = '$prof_desc', pfp = '$profile_pic' WHERE ciiverseid = '$ciiverseid' ";
$result = mysqli_query($db,$sql);

$_SESSION['prof_desc'] = $prof_desc;
$_SESSION['nickname'] = $nick;
$_SESSION['pfp'] = $profile_pic;

mysqli_query($db,"UPDATE posts SET owner_nickname = '$nick', owner_pfp = '$profile_pic' WHERE owner = '$ciiverseid' ");
mysqli_query($db,"UPDATE comments SET owner_nickname = '$nick', owner_pfp = '$profile_pic' WHERE owner = '$ciiverseid' ");

}

if(isset($_SESSION['ciiverseid'])) { 

$cvid = mysqli_real_escape_string($db,$_SESSION['ciiverseid']);

$sql = "SELECT is_owner FROM users WHERE ciiverseid = '$cvid' ";
$result = mysqli_query($db,$sql);
$ses_row = mysqli_fetch_array($result,MYSQLI_ASSOC);

$is_owner = $ses_row['is_owner'];

}

?>

<html>
	<head>
		<title>Edit Profile - Ciiverse</title>
		<link rel="shortcut icon" href="../icon.png"><link rel="stylesheet" href="../offdevice.css" type="text/css">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<link rel="stylesheet" href="../ciiverse.css" type="text/css">
    <script src="/js/complete-en.js"></script>
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
  <form class="setting-form" method="post" action="../users/edit_profile.php">
    

    <ul class="settings-list">
     <li> 
      <input type="text" name="nickname" maxlength="32" placeholder="Nickname" value="<?php echo $_SESSION['nickname']; ?>"></li>
<br><li><input maxlength="2000" type="text" placeholder="Profile Picture URL" id="prof_pic" name="prof_pic" value="<?php echo $_SESSION['pfp']; ?>"></li>
        <p class="settings-label">Profile Comment</p>
    </li><li class="setting-profile-comment">
        <textarea id="prof_desc" class="textarea" name="prof_desc" maxlength="400" placeholder="Write about yourself here. Or whatever."><?php echo $_SESSION['prof_desc']; ?></textarea>
    </ul>
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