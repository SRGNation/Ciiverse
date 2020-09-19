<?php 

session_start();
$redirect = '/notifications';
require("lib/connect.php");
include("lib/htm.php");
include("lib/users.php");

if(!$_SESSION['loggedin']) {
	exit('You have to login in order to view this page.');
}

/* 
CREATE TABLE `ciiverse_2`.`notifs` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `notif_to` VARCHAR(32) NOT NULL , `notif_by` VARCHAR(32) NOT NULL , `type` INT(11) NOT NULL , `merged_with` INT(11) NOT NULL , `post_id` INT(11) NOT NULL , `rd_notif` TINYINT(1) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

ALTER TABLE `notifs` ADD `date_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `rd_notif`;

1=Yeah on Post
2=Yeah on Comment
3=Follow
4=Comment on Post 
*/

$notifs = $db->query("SELECT * FROM notifs WHERE notif_to = '".$_SESSION['ciiverseid']."' ORDER BY id DESC LIMIT 50");
$notif_count = mysqli_num_rows($notifs);

?>

<html>
	<head>
		<?php formHeaders('Updates - Ciiverse'); ?>
	</head>
	<body>
		<div id="wrapper">
			<div id="sub-body">
				<?php 
				echo form_top_bar($_SESSION['ciiverseid'], $_SESSION['nickname'], $_SESSION['pfp'], 'updates');
				?>
			</div>
			<div id="main-body">
				<?=userSidebar($_SESSION['ciiverseid'], true, true)?>
				<div class="main-column messages">
					<div class="post-list-outline">
						<h2 class="label">Updates</h2>
						<div class="list news-list">
							<?php 
								if($notif_count == 0) {
									echo '<div class="no-content"><div><p>No Updates lol.</p></div></div>';
								} else {
									while($updates = mysqli_fetch_array($notifs)) {

										$users = $db->query("SELECT * FROM users WHERE ciiverseid = '".$updates['notif_by']."' ");
										$u = mysqli_fetch_array($users);
										
										if($updates['type'] == 1 || $updates['type'] == 4) {
											$posts = $db->query("SELECT * FROM posts WHERE post_id = '".$updates['post_id']."' AND deleted != 5");
											$p = mysqli_fetch_array($posts);
										}elseif($updates['type'] == 2) {
											$comments = $db->query("SELECT * FROM comments WHERE id = '".$updates['post_id']."'");
											$c = mysqli_fetch_array($comments);
										}

										if($updates['type'] == 4) {
											$finduser = $db->query("SELECT * FROM users WHERE ciiverseid = '".$p['owner']."'");
											$user_post = mysqli_fetch_array($finduser);
										}

										if($updates['type'] == 1 || $updates['type'] == 4) {
											if(strlen($p['content']) > 20) {
												$content = mb_substr($p['content'],0,17).'...';
											} else {
												$content = $p['content'];
											}
										} elseif($updates['type'] == 2) {
											if(strlen($c['content']) > 20) {
												$content = mb_substr($c['content'],0,17).'...';
											} else {
												$content = $c['content'];
											}
										}

										echo '<div class="news-list-content '.($updates['rd_notif'] == 0 ? 'notify' : '').' trigger" tabindex="0"';

										if($updates['type'] == 1 || $updates['type'] == 2 || $updates['type'] == 4) {
											echo 'data-href="/post/'.($updates['type'] == 1 || $updates['type'] == 4 ? $updates['post_id'] : $c['post_id']) .'">';
										}elseif($updates['type'] == 3) {
											echo 'data-href="/users/'.$updates['notif_by'].'">';
										}

										echo '<a href="/users/'.$updates['notif_by'].'" class="icon-container '.print_badge($updates['notif_by']).'"><img src="'.user_pfp($updates['notif_by'],0).'" class="icon"></a><div class="body">';

										if($updates['type'] == 1) {
											echo '<div class="body"><a href="/users/'.$updates['notif_by'].'" class="nick-name">'.$u['nickname'].'</a> gave <a href="/post/'.$updates['post_id'].'" class="link">your post&nbsp;('.($p['deleted'] == 0 ? htmlspecialchars($content) : 'deleted').')</a> a Yeah. ';
										}elseif($updates['type'] == 2) {
											echo '<div class="body"><a href="/users/'.$updates['notif_by'].'" class="nick-name">'.$u['nickname'].'</a> gave <a href="/post/'.$c['post_id'].'" class="link">your comment&nbsp;('.htmlspecialchars($content).')</a> a Yeah. ';
										}elseif($updates['type'] == 3) {
											echo '<div class="body">Followed by <a href="/users/'.$updates['notif_by'].'" class="nick-name">'.$u['nickname'].'</a> ';
										}elseif($updates['type'] == 4) {
											echo '<div class="body"><a href="/users/'.$updates['notif_by'].'" class="nick-name">'.$u['nickname'].'</a> commented on <a href="/post/'.$p['post_id'].'" class="link">'.($p['owner'] == $_SESSION['ciiverseid'] ? 'your' : $user_post['nickname'].'\'s').' post&nbsp;('.htmlspecialchars($content).') </a>';
										}

										echo '<span class="timestamp">'.humanTiming(strtotime($updates['date_time'])).'</span>
										</div>
										</div>
										</div>';
									}
								}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
<?php 
$db->query("UPDATE notifs SET rd_notif = 1 WHERE notif_to = '".$_SESSION['ciiverseid']."'");
?>