<?php 

$redirect = '/rules';
session_start();
require('lib/connect.php');
include('lib/users.php');
include('lib/htm.php');



?>

<html>
<head>
  <?php 
  formHeaders('Rules - Ciiverse');
  ?>
</head>
<body id="help">
<div id="wrapper">
  <div id="sub-body">
      <?php
  if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
  echo form_top_bar($_SESSION['ciiverseid'], $_SESSION['nickname'], $_SESSION['pfp'], 'communities');
  } else { 
  echo ftbnli('communities'); }
        ?>
  </div>
  <div id="main-body">
<div class="main-column">
  <div class="post-list-outline">
  <h2 class="label">Ciiverse Rules</h2>
  <div id="guide" class="help-content">
    <div class="num1">
      
      <p>The following are the rules to Ciiverse.<br><br>
</p>

      <h2>Let's get the obvious ones out of the way first</h2>

<h3>NSFW and other stuff</h3>
      
      
      <p>Well usually you have to have image posting permissions to post images. Please do not post NSFW. If you do, you will usually get your image posting permissions taken away. But if you post gore and other illegal stuff, you will get IP banned and an account deletion.</p>
      

      <h3>Spam</h3>
      <p>Obviously, don't spam. This sending the same thing multiple times, or just sending random letters and numbers multiple times. Your account will be deleted and you will get IP banned if you do. So yeah.</p><br><h2>Now let's get the rest of the rules out of the way.</h2>
<h3>Using words like "Normie"</h3><p>If you use the word "Normie" unironically in your post, it will be deleted. I don't like that word for some reasons, so don't use it please.</p>

<h3>Drama</h3>
<p>Causing drama here could get your account disabled temporarily. So don't cause drama here.</p>

<h3>Yeahbombing</h3>
<p>This is techinacally spamming. If you are caught yeahbombing, then your yeahs will be purged and you will get disabled from yeahing. Making a new account just to yeahbomb will get your account deleted and you will get IP banned.</p>

<h3>Creating an account just to hate on Ciiverse</h3>
<p>If you do this you can go fuck yourself lol.</p>

<?php

if($_SESSION['loggedin'] == true && $user['user_type'] > 1) {
  echo '<br>
  <h2>Now here are the Mod/Admin rules. (No normal user can see this, by the way.)</h2>
  <h3>Don\'t delete/disable users who did nothing wrong.</h3>
  <p>If you disable a user who hasn\'t done anything wrong. You will go off with a warning. However, if you DELETE a user who did nothing wrong, you will get demoted.</p>
  <h3>Don\'t delete posts that didn\'t break the rules.</h3>
  <p>Doing this will most likely give you a warning. However, if you keep doing it, you will get demoted.</p>';
}

?>
    </div>
  </div>
</div></div>
</div>
</div>
</body>
</html>