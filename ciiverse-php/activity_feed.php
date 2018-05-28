<?php

session_start();
$redirect = '/feed';
require('lib/connect.php');
include('lib/users.php');
include('lib/htm.php');

if($_SESSION['loggedin'] == false) {
	exit('You need to be logged in to view this page.');
}

?>

<html>
<head>
	<?php 
	formHeaders('Activity Feed - Ciiverse');
	?>
</head>
<body>
	<div id="wrapper">
		<div id="sub-body">
         <?php 

  			echo form_top_bar($_SESSION['ciiverseid'], $_SESSION['nickname'], $_SESSION['pfp'], 'feed');

         ?>
      </div>
      <div id="main-body">
      	<div class="no-content track-error">
  			<div>
    			<p>The activity feed hasn't been added yet, but you can still search up users if you wanted that.</p>
    			<br>
    			<form method="GET" action="/user_search.php" class="search">
				<input type="text" name="query" placeholder="Search Users" maxlength="32">
				<input type="submit" value="q" title="Search">
				</form>
  			</div>
		</div>
      </div>
	</div>
</body>
</html>