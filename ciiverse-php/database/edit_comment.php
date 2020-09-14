<?php

session_start();
require('../lib/connect.php');

if(!$_SESSION['loggedin']) {
	exit('Please log in to continue.');
}

if($user['has_db_access'] == 0) {
	exit('Not authorized fagit.');
}

if($_SERVER['REQUEST_METHOD'] !== "POST") {
$comment_id = mysqli_real_escape_string($db,$_GET['id']);
$this_post = $db->query("SELECT * FROM comments WHERE id = $comment_id");

if(mysqli_num_rows($this_post) == 0) {
	exit("Comment not found.");
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
$ip = mysqli_real_escape_string($db,$_POST['ip']);
$comment_id = mysqli_real_escape_string($db,$_POST['comment_id']);

$db->query("UPDATE comments SET owner = '$owner', content = '$content', feeling = $feeling_id, ip = '$ip', post_id = $post_id WHERE id = $comment_id");
header('location: /database/edit_comment.php?id='.$comment_id);

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
        <div class="panel-heading">Edit Comment. <a href="/post/<?php echo $post['post_id']; ?>">View in site.</a></div>
        <div class="panel-body">
		<form action="edit_comment.php" method="post">
		    <input type="hidden" name="comment_id" value="<?php echo $post['id']; ?>">
            <input type="hidden" name="csrftoken" value="<?php echo $_COOKIE['csrf_token']; ?>">
            <div class="form-group">
            <label for="owner">Comment Owner</label>
			<input class="form-control" type="text" placeholder="Comment Owner goes here." maxlength="32" name="owner" value="<?php echo $post['owner']; ?>">
            </div>
            <div class="form-group">
            <label for="owner">Post ID</label>
            <input class="form-control" type="text" placeholder="Post ID goes here." maxlength="11" name="post_id" value="<?php echo $post['post_id']; ?>">
            </div>
            <div class="form-group">
            <label for="content">Comment Content</label>
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
            <label for="ip">IP Address</label>
            <input class="form-control" type="text" placeholder="IP goes here." name="ip" value="<?php echo $post['ip']; ?>">
            </div>
            <input class="btn btn-primary" type="submit" value="Edit">
        	<a class="btn btn-danger" href="delete_comment.php?id=<?php echo $_GET['id']; ?>">Delete Comment</a>
		    </form>
            </div>
        </div>
        </div>
	</body>
</html>