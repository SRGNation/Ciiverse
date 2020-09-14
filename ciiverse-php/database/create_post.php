<?php

session_start();
if(isset($_GET['id'])) {
$redirect = '/database/create_post.php';
} else {
$redirect = 0;	
}
require('../lib/connect.php');

if(!$_SESSION['loggedin']) {
	exit('Please log in to continue.');
}

if($user['has_db_access'] == 0) {
	exit('Not authorized fagit.');
}

if($_SERVER['REQUEST_METHOD'] == "POST") {

if($_COOKIE['csrf_token'] !== $_POST['csrftoken']) {
    exit();
}

$owner = mysqli_real_escape_string($db,$_POST['owner']);
$content = mysqli_real_escape_string($db,$_POST['content']);
$feeling_id = mysqli_real_escape_string($db,$_POST['feeling_id']);
$deleted = mysqli_real_escape_string($db,$_POST['deleted']);
$screenshot = mysqli_real_escape_string($db,$_POST['screenshot']);
$ip = mysqli_real_escape_string($db,$_POST['ip']);
$cid = mysqli_real_escape_string($db,$_POST['cid']);
$web_url = mysqli_real_escape_string($db,$_POST['web_url']);

$db->query("INSERT INTO posts (community_id, content, screenshot, web_url, owner, feeling, ip, deleted) VALUES ('$cid', '$content', '$screenshot', '$web_url', '$owner', '$feeling_id', '$ip', '$deleted')");
$find_latest_posts = $db->query("SELECT post_id FROM posts ORDER BY post_id DESC LIMIT 1");
$id = mysqli_fetch_array($find_latest_posts);
header('location: /database/edit_post.php?id='.$id['post_id']);

}

?>
<html>
	<head>
        <title>Create Post - Staff Panel</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="shortcut icon" href="/img/icon.png">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	</head>
	<body>
        <div class="container">
        <div class="page-header">    
		  <h1>Staff Panel</h1>
        </div>
        <div class="panel panel-default">
        <div class="panel-heading">Create Post</div>
        <div class="panel-body">
		<form action="create_post.php" method="post">
            <input type="hidden" name="csrftoken" value="<?php echo $_COOKIE['csrf_token']; ?>">
            <div class="form-group">
            <label for="owner">Post Owner</label>
			<input class="form-control" type="text" placeholder="Post Owner goes here." maxlength="32" name="owner">
            </div>
            <div class="form-group">
            <label for="cid">Community ID</label>
			<input class="form-control" type="text" placeholder="Community ID goes here." maxlength="11" name="cid">
            </div>
            <div class="form-group">
            <label for="content">Post Content</label>
			<textarea class="form-control" type="text" rows="4" maxlength="1000" name="content" placeholder="Post Content goes here."></textarea>
            </div>
            <div class="form-group">
            <label for="feeling_id">Feeling</label>
			<select class="form-control" name="feeling_id">
          	    <option value="0">
                0 - Normal
                </option>
                <option value="1">
                1 - Happy
                </option>
                <option value="2">
                2 - Like
                </option>
                <option value="3">
                3 - Surprised
                </option>
                <option value="4">
                4 - Frustrated
                </option>
                <option value="5">
                5 - Puzzled
                </option>
                <option value="69">
                69 - Comedy 2018
                </option>
        	    </select>
            </div>
            <div class="form-group">
            <label for="screenshot">Screenshot</label>
        	<input class="form-control" type="text" name="screenshot" placeholder="Screenshot goes here.">
            </div>
            <div class="form-group">
            <label for="web_url">Web URL</label>
            <input class="form-control" type="text" name="web_url" placeholder="Web URL goes here.">
            </div>
            <div class="form-group">
            <label for="deleted">Deleted Type</label>
        	<select class="form-control" name="deleted">
        	<option value="0">
        	0 - Not deleted
        	</option>
        	<option value="1">
        	1 - Deleted by poster
        	</option>
        	<option value="2">
        	2 - Deleted by mod
        	</option>
        	<option value="3">
        	3 - Deleted by admin
        	</option>
        	<option value="4">
        	4 - Deleted by owner
        	</option>
        	<option value="5">
        	5 - Pretends post doesn't exist
        	</option>
        	</select>
            </div>
            <div class="form-group">
            <label for="ip">IP Address</label>
            <input class="form-control" type="text" placeholder="IP goes here." name="ip">
            </div>
            <input class="btn btn-primary" type="submit" value="Create">
		    </form>
            </div>
        </div>
        </div>
	</body>
</html>