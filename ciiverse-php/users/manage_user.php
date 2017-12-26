<?php 

session_start();
$redirect = 0;
require("../lib/connect.php");
include("../lib/htm.php");
include("../lib/users.php");

$ciiverseid = $_GET['cvid'];

if($user['user_type'] < 2) {
  exit("You are not authorized to perform this action. Sorry :(");
}

$sql = "SELECT * FROM users WHERE ciiverseid = '".mysqli_real_escape_string($db,$ciiverseid)."' ";
$query = mysqli_query($db,$sql);
$row = mysqli_fetch_array($query);

$count = mysqli_num_rows($query);

if($count == 0) {
	exit('An error occured.');
}

if($row['user_type'] >= $user['user_type']) {
  exit('An error occured.');
}

?>

<html>
<head>
  <?php
    formHeaders('Manage Account - Ciiverse');
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
  <h2 class="label">Manage <?php echo $ciiverseid; ?>'s Account</h2>
  <ul class="settings-list">
    <br>
    <li>
    <p>Nickname: <?php echo htmlspecialchars($row['nickname']); ?></p>
    <p>Profile pic: <?php echo htmlspecialchars($row['pfp']); ?></p>
    <p>Profile pic type: <?php if($row['pfp_type'] == 1){echo 'Mii';}else{echo 'Custom';} ?></p>
    <p>NNID: <?php if(empty($row['nnid'])){echo 'This user didn\'t set an NNID.';}else{echo htmlspecialchars($row['nnid']);} ?></p>
    </li>
    <br>
    <?php 
    if($user['user_type'] > 2) {
    echo '<li>
    <p>Log in to account. <br> This will log you out of your own account and log in to '.$row['nickname'].'\'s account. <br>
    <a href="/login/login.php?token='.$row['user_token'].'&reqwre=1">Login.</a></p>
    </li>';
    }
    ?>
    <br>
    <li>
    <p style="color: red;">Delete account.<br>WARNING: THIS CANNOT BE UNDONE. EVERYTHING ON THIS ACCOUNT WILL BE DELETED FOREVER IF YOU DELETE THIS ACCOUNT.<br></p>
    <a href="/users/delete_acc.php?cvid=<?php echo $ciiverseid; ?>">Delete Account</a>
    </li>
    <br>
    <form action='/users/settings.php' method='post'>
    <p>Other Settings:</p>
    <li>
      <input type="checkbox" <?php if($row['can_post_images'] > 0) { echo 'checked=""'; } ?> name="can_post_images"><label for="show_replies">Can post images.</label>
    </li>
    <li>
      <input type="checkbox" <?php if($row['user_type'] == 0) { echo 'checked=""'; } ?> name="is_disabled"><label for="show_replies">Is disabled.</label>
    </li>
    <br>
    <input type="hidden" name="ciiverseid" value="<?php echo $ciiverseid; ?>">
    <input type="submit" value="Save Changes">
  </form>
    </ul>
</div></div></div>
<script type="text/javascript">
      $(".apply-button").click(function(e){
      $(this).addClass('disabled');
    });
  </script>
	</body>
</html>