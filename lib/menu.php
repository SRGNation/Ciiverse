<?php 

#These functions are for to form the top bar.

function form_top_bar($cvid, $nickname, $pfp, $page) {
	global $is_owner;

	if($page == 'user') {
		global $userid;
	}

	$html1 = '<menu id="global-menu"><li id="global-menu-logo"><h1><a href="/"><img src="/img/ciiverse.png" alt="Miiverse" width="165" height="30"></a></h1></li><li id="global-menu-list">
	<ul>';

	if($page == 'user') {
		if($cvid == $userid) {
	$html2 = '<li id="global-menu-mymenu" class="selected"><a href="/users/'. $cvid .'"><span class="icon-container ';
	} else {
	$html2 = '<li id="global-menu-mymenu"><a href="/users/'. $cvid .'"><span class="icon-container ';
	}
	} else {
	$html2 = '<li id="global-menu-mymenu"><a href="/users/'. $cvid .'"><span class="icon-container ';
	}

				 if($is_owner == 'true') {$html3 = 'official-user"><img src="'.$pfp.'"></span><span>User Page</span></a></li>';} else {
					$html3 = '"><img src="'.$pfp.'"></span><span>User Page</span></a></li>';
				}

			if($page == 'communities') {
	$html4 = '<li id="global-menu-community" class="selected"><a href="/" class="symbol"><span>Communities</span></a></li>';
	} else {
	$html4 = '<li id="global-menu-community"><a href="/" class="symbol"><span>Communities</span></a></li>';
	}

	if($page == 'updates') {
	$html5 = '<li id="global-menu-news" class="selected"><a class="symbol" href="/notifications"><span class="badge" style="display: none;"></span></a></li>';
	} else {
	$html5 = '<li id="global-menu-news"><a class="symbol" href="/notifications"><span class="badge" style="display: none;"></span></a></li>';
	}

	$html6 = '
	<li id="global-menu-my-menu"><button class="symbol js-open-global-my-menu open-global-my-menu"></button>
	<menu id="global-my-menu" class="invisible none">
	<li><a href="/edit/profile" class="symbol my-menu-profile-setting"><span>Edit Profile</span></a></li>	
	<li><a class="symbol my-menu-info" href="/changelog"><span>Ciiverse Changelog</span></a></li>
	'.($is_owner == 'true' ? '<li><a class="symbol my-menu-info" href="/admin_panel.php"><span>Admin Panel</span></a></li>' : '').'
	<li><a href="/login/logout.php" class="symbol my-menu-guide"><span>Log Out</span></a></li>
	</menu>';
	$html7 = '</li>';

	$finals = "$html1 $html2 $html3 $html4 $html5 $html6 $html7";

	return $finals;
}

function ftbnli($page) {
		$html1 = '<menu id="global-menu">
	<li id="global-menu-logo"><h1><a href="/"><img src="/img/ciiverse.png" alt="Miiverse" width="165" height="30"></a></h1></li>
	<li id="global-menu-list">
	<ul>';

	if($page == 'communities') {
	$html2 = '<li id="global-menu-community" class="selected" align="right"><a href="/" class="symbol"><span>Communities</span></a></li>';
	} else {
	$html2 = '<li id="global-menu-community" align="right"><a href="/" class="symbol"><span>Communities</span></a></li>';
	}

	$html3 = '<li id="global-menu-my-menu" align="right">
	<button class="symbol js-open-global-my-menu open-global-my-menu"></button>
	<menu id="global-my-menu" class="invisible none">
	<a href="/register" class="symbol my-menu-guide"><span>Sign Up</span></a>
	<a href="/login" class="symbol my-menu-guide"><span>Sign In</span></a>
	</menu>
	</li>
	</li>
	';

	$finals = "$html1 $html2 $html3";

	return $finals;
}

?>