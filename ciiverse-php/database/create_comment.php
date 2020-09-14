<?php

session_start();
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
$ip = mysqli_real_escape_string($db,$_POST['ip']);
$post_id = mysqli_real_escape_string($db,$_POST['post_id']);

$db->query("INSERT INTO comments (owner, content, feeling, post_id, ip) VALUES ('$owner', '$content', $feeling_id, $post_id, '$ip')");
$find_latest_posts = $db->query("SELECT id FROM comments ORDER BY id DESC LIMIT 1");
$id = mysqli_fetch_array($find_latest_posts);
header('location: /database/edit_comment.php?id='.$id['id']);

}

?>
<html>
	<head>
        <title>Edit Comment - Staff Panel</title>
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
        <div class="panel-heading">Create Comment.</div>
        <div class="panel-body">
		<form action="create_comment.php" method="post">
            <input type="hidden" name="csrftoken" value="<?php echo $_COOKIE['csrf_token']; ?>">
            <div class="form-group">
            <label for="owner">Comment Owner</label>
			<input class="form-control" type="text" placeholder="Comment Owner goes here." maxlength="32" name="owner">
            </div>
            <div class="form-group">
            <label for="owner">Post ID</label>
            <input class="form-control" type="text" placeholder="Post ID goes here." maxlength="11" name="post_id">
            </div>
            <div class="form-group">
            <label for="content">Comment Content</label>
			<textarea class="form-control" type="text" rows="4" maxlength="1000" name="content" placeholder="Post Content goes here."></textarea></div>
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