<?php 

session_start();
$redirect = 0;
require('lib/connect.php');
include('lib/htm.php');
include('lib/users.php');

$search = mysqli_real_escape_string($db,$_GET['query']);

if(strlen($search) < 4) {
	exit("search query is too short.");
}

$query = $db->query("SELECT * FROM users WHERE ciiverseid LIKE '%$search%' OR nickname LIKE '%$search%' OR nnid LIKE '%$search%' ");

$q_count = mysqli_num_rows($query);

?>

<html>
	<head>
		<?php 
		formHeaders('Search '.$search.' - Ciiverse');
		?>
	</head>
	<body>
		<div id="wrapper">
			<div id="sub-body">
				<?php 

            	if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
  				echo form_top_bar($_SESSION['ciiverseid'], $_SESSION['nickname'], $_SESSION['pfp'], 'feed');
  				} else { 
  				echo ftbnli('feed'); }

				?>
			</div>
			<div id="main-body">
				<div class="main-column">
   				<div class="post-list-outline">
       			<h2 class="label">Search Users</h2>
       			<form class="search" action="/user_search.php" method="GET">
				<input type="text" name="query" value="<?php echo $search; ?>" placeholder="Chance, SRGNation, etc." maxlength="32">
				<input type="submit" value="q" title="Search">
				</form>
				<div class="search-user-content search-content">
				<p class="user-found note"><?php echo $q_count.' '.($q_count == 0 || $q_count > 1 ? 'results' : 'result');  ?></p>
				<div class="list">
				<ul id="searched-user-list" class="list-content-with-icon-and-text arrow-list">
				<?php 
				if($q_count > 0) {
				while($u_search = mysqli_fetch_array($query)) {

				echo '<li class="trigger" data-href="/users/'.$u_search['ciiverseid'].'">
				<a href="/users/'.$u_search['ciiverseid'].'" class="icon-container '.print_badge($u_search['ciiverseid']).'"><img class="icon" src="'.user_pfp($u_search['ciiverseid'],0).'"></a>
				<div class="body">
				<p class="title">
				<span class="nick-name"><a href="/users/'.$u_search['ciiverseid'].'">'.htmlspecialchars($u_search['nickname']).'</a></span>
				<span class="id-name">'.$u_search['ciiverseid'].'</span>
				</p>
				<p class="text">'.htmlspecialchars($u_search['prof_desc']).'</p>
				</div>
				</li>';
			}
				} ?>
			</ul></div></div>
       		</div>
       	</div>
			</div>
		</div>
	</body>
</html>