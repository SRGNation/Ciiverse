<?php 

require("../lib/connect.php");
include("../lib/menu.php");

session_start();

$ciiverseid = $_GET['cvid'];

if(isset($_SESSION['ciiverseid'])) { 

$cvid = $_SESSION['ciiverseid'];

$sql = "SELECT is_owner FROM users WHERE ciiverseid = '$cvid' ";
$result = mysqli_query($db,$sql);
$ses_row = mysqli_fetch_array($result,MYSQLI_ASSOC);

$is_owner = $ses_row['is_owner'];

}

if($is_owner !== 'true') {
	die("You are not authorized to perform this action. Sorry :(");
}

$sql = "SELECT nickname, pfp, prof_desc, is_owner, user_token FROM users WHERE ciiverseid = '".mysqli_real_escape_string($db,$ciiverseid)."' ";
$query = mysqli_query($db,$sql);
$row = mysqli_fetch_array($query);

$count = mysqli_num_rows($query);

if($count == 0) {
	die("Couldn't find that Ciiverse ID.");
}

if($row['is_owner'] == 'true') {
	die("You can't edit another admin's profile.");
}

?>

<html>
	<head>
		<title>Manage Profile - Ciiverse</title>
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
  <h2 class="label">Manage <?php echo $ciiverseid; ?>'s Profile</h2>
  <form class="setting-form" method="post" action="../users/submit_managed.php">
    

    <ul class="settings-list">
     <li> 
      <input type="text" name="nickname" maxlength="32" placeholder="Nickname" value="<?php echo $row['nickname']; ?>"></li>
<br><li><input maxlength="2000" type="text" placeholder="Profile Picture URL" id="prof_pic" name="prof_pic" value="<?php echo $row['pfp']; ?>"></li>
        <p class="settings-label">Profile Comment</p>
    </li><li class="setting-profile-comment">
        <textarea id="prof_desc" class="textarea" name="prof_desc" maxlength="400" placeholder="Write about yourself here. Or whatever."><?php echo $row['prof_desc']; ?></textarea>
    </li>
    <li>
    <p>User login token: <?php echo $row['user_token']; ?></p>
    </li>
    <li>
    <p style="color: red;">Delete account.<br>WARNING: THIS CANNOT BE UNDONE. EVERYTHING ON THIS ACCOUNT WILL BE DELETED FOREVER IF YOU DELETE THIS ACCOUNT.<br></p>
    <a href="/users/delete_acc.php?cvid=<?php echo $ciiverseid; ?>">Delete Account</a>
    </li>
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