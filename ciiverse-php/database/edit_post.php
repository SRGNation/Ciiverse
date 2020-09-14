<?php

session_start();
if(isset($_GET['id'])) {
$redirect = '/database/edit_post.php?id='.$_GET['id'];
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

if($_SERVER['REQUEST_METHOD'] !== "POST") {
$post_id = mysqli_real_escape_string($db,$_GET['id']);
$this_post = $db->query("SELECT * FROM posts WHERE post_id = $post_id");

if(mysqli_num_rows($this_post) == 0) {
	exit("Post not found.");
}

$post = mysqli_fetch_array($this_post);
} else {

if($_COOKIE['csrf_token'] !== $_POST['csrftoken']) {
    exit();
}

$post_id = mysqli_real_escape_string($db,$_POST['post_id']);
$owner = mysqli_real_escape_string($db,$_POST['owner']);
$content = mysqli_real_escape_string($db,$_POST['content']);
$feeling_id = mysqli_real_escape_string($db,$_POST['feeling_id']);
$deleted = mysqli_real_escape_string($db,$_POST['deleted']);
$screenshot = mysqli_real_escape_string($db,$_POST['screenshot']);
$ip = mysqli_real_escape_string($db,$_POST['ip']);
$cid = mysqli_real_escape_string($db,$_POST['cid']);
$web_url = mysqli_real_escape_string($db,$_POST['web_url']);

$db->query("UPDATE posts SET owner = '$owner', content = '$content', feeling = $feeling_id, deleted = $deleted, ip = '$ip', community_id = $cid, web_url = '$web_url', screenshot = '$screenshot' WHERE post_id = $post_id");
header('location: /database/edit_post.php?id='.$post_id);

}

?>
<html>
	<head>
        <title>Edit Post - Staff Panel</title>
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
        <div class="panel-heading">Edit Post. <a href="/post/<?php echo $_GET['id']; ?>">View in site.</a></div>
        <div class="panel-body">
		<form action="edit_post.php" method="post">
		    <input type="hidden" name="post_id" value="<?php echo $post['post_id']; ?>">
            <input type="hidden" name="csrftoken" value="<?php echo $_COOKIE['csrf_token']; ?>">
            <div class="form-group">
            <label for="owner">Post Owner</label>
			<input class="form-control" type="text" placeholder="Post Owner goes here." maxlength="32" name="owner" value="<?php echo $post['owner']; ?>">
            </div>
            <div class="form-group">
            <label for="cid">Community ID</label>
			<input class="form-control" type="text" placeholder="Community ID goes here." maxlength="11" name="cid" value="<?php echo $post['community_id']; ?>">
            </div>
            <div class="form-group">
            <label for="content">Post Content</label>
			<textarea class="form-control" type="text" rows="4" maxlength="1000" name="content" placeholder="Post Content goes here."><?php echo htmlspecialchars($post['content']); ?></textarea>
            </div>
            <div class="form-group">
            <label for="feeling_id">Feeling</label>
			<select class="form-control" name="feeling_id">
          	    <option value="0" <?php if($post['feeling'] == 0) {echo 'selected';} ?>>
                0 - Normal
                </option>
                <option value="1" <?php if($post['feeling'] == 1) {echo 'selected';} ?>>
                1 - Happy
                </option>
                <option value="2" <?php if($post['feeling'] == 2) {echo 'selected';} ?>>
                2 - Like
                </option>
                <option value="3" <?php if($post['feeling'] == 3) {echo 'selected';} ?>>
                3 - Surprised
                </option>
                <option value="4" <?php if($post['feeling'] == 4) {echo 'selected';} ?>>
                4 - Frustrated
                </option>
                <option value="5" <?php if($post['feeling'] == 5) {echo 'selected';} ?>>
                5 - Puzzled
                </option>
                <option value="69" <?php if($post['feeling'] == 69) {echo 'selected';} ?>>
                69 - Comedy 2018
                </option>
        	    </select>
            </div>
            <div class="form-group">
            <label for="screenshot">Screenshot</label>
        	<input class="form-control" type="text" name="screenshot" placeholder="Screenshot goes here." value="<?php echo $post['screenshot']; ?>">
            </div>
            <div class="form-group">
            <label for="web_url">Web URL</label>
            <input class="form-control" type="text" name="web_url" placeholder="Web URL goes here." value="<?php echo $post['web_url']; ?>">
            </div>
            <div class="form-group">
            <label for="deleted">Deleted Type</label>
        	<select class="form-control" name="deleted">
        	<option value="0" <?php if($post['deleted'] == 0) {echo 'selected';} ?>>
        	0 - Not deleted
        	</option>
        	<option value="1" <?php if($post['deleted'] == 1) {echo 'selected';} ?>>
        	1 - Deleted by poster
        	</option>
        	<option value="2" <?php if($post['deleted'] == 2) {echo 'selected';} ?>>
        	2 - Deleted by mod
        	</option>
        	<option value="3" <?php if($post['deleted'] == 3) {echo 'selected';} ?>>
        	3 - Deleted by admin
        	</option>
        	<option value="4" <?php if($post['deleted'] == 4) {echo 'selected';} ?>>
        	4 - Deleted by owner
        	</option>
        	<option value="5" <?php if($post['deleted'] == 5) {echo 'selected';} ?>>
        	5 - Pretends post doesn't exist
        	</option>
        	</select>
            </div>
            <div class="form-group">
            <label for="ip">IP Address</label>
            <input class="form-control" type="text" placeholder="IP goes here." name="ip" value="<?php echo $post['ip']; ?>">
            </div>
            <input class="btn btn-primary" type="submit" value="Edit">
        	<a class="btn btn-danger" href="delete_post.php?id=<?php echo $_GET['id']; ?>">Delete Post</a>
		    </form>
            </div>
        </div>
        </div>
	</body>
</html>