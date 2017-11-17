<?php 

require("lib/connect.php");
include("lib/menu.php");

session_start();

if(isset($_SESSION['ciiverseid'])) { 

		$cvid = $_SESSION['ciiverseid'];

		$sql = "SELECT is_owner FROM users WHERE ciiverseid = '$cvid' ";
		$result = mysqli_query($db,$sql);
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);

		$is_owner = $row['is_owner'];
	}
?>

<html>
	<head>
		<title>Updates - Ciiverse</title>
		<link rel="stylesheet" href="/offdevice.css"></link>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<link rel="shortcut icon" href="/icon.png" />
		<script async src="https://www.google-analytics.com/analytics.js"></script>
 		<script src="js/complete-en.js"></script>
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