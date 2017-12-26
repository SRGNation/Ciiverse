<?php 

session_start();
$redirect = '/notifications';
require("lib/connect.php");
include("lib/htm.php");
include("lib/users.php");
?>

<html>
	<head>
		<?php formHeaders('Updates - Ciiverse'); ?>
	</head>
	<body>
		<div id="wrapper">
			<div id="main-body">
				<div class="main-column">
					<div class="post-list-outline">
						<h2 class="label">Updates</h2>
						<div class="news-list">
							<div align="center">
							<p>Coming soon.</p>
						</div>
						</div>
					</div>
				</div>
			</div>
			<div id="sub-body">
				<?php 
				echo form_top_bar($_SESSION['ciiverseid'], $_SESSION['nickname'], $_SESSION['pfp'], 'updates');
				?>
			</div>
		</div>
	</body>
</html>