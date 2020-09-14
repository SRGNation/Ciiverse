<?php 

function formHeaders($title) {

	echo '<title>'.$title.'</title>
<link rel="stylesheet" href="/offdevice.css"></link>
'.(isset($_COOKIE['dark_mode']) ? '<link rel="stylesheet" href="/dark.css"></link>' : '').'
<link rel="shortcut icon" href="/img/icon.png" />
<script async src="https://www.google-analytics.com/analytics.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<script type="text/javascript" src="/js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="/js/complete-en.js"></script>
<script type="text/javascript" src="/js/ciiverse.js"></script>';
}

function form_top_bar($cvid, $nickname, $pfp, $page) {
	global $user;
  global $db;

  $query = $db->query("SELECT * FROM notifs WHERE rd_notif = 0 AND notif_to = '".$_SESSION['ciiverseid']."'");
  $notif_count = mysqli_num_rows($query);

	$pfp = user_pfp($cvid,0);

	if($page == 'user') {
		global $userid;
	}

	$html1 = '<menu id="global-menu"><li id="global-menu-logo"><h1><a href="/"><img src="/img/ciiverse.png" alt="Miiverse" width="165" height="30"></a></h1></li><li id="global-menu-list">
	<ul>';

	if($page == 'user') {
		if($cvid == $userid) {
	$html2 = '<li id="global-menu-mymenu" class="selected"><a href="/users/'. $cvid .'"><span class="icon-container '.print_badge($cvid).'"><img src="'.$pfp.'"></span><span>User Page</span></a></li>';
	} else {
	$html2 = '<li id="global-menu-mymenu"><a href="/users/'. $cvid .'"><span class="icon-container '.print_badge($cvid).'"><img src="'.$pfp.'"></span><span>User Page</span></a></li>';
	}
	} else {
	$html2 = '<li id="global-menu-mymenu"><a href="/users/'. $cvid .'"><span class="icon-container '.print_badge($cvid).'"><img src="'.$pfp.'"></span><span>User Page</span></a></li>';
	}

  if($page == 'feed') {
    $activity = '<li id="global-menu-feed" class="selected"><a href="/feed" class="symbol"><span>Activity Feed</span></a></li>';
  } else {
    $activity = '<li id="global-menu-feed"><a href="/feed" class="symbol"><span>Activity Feed</span></a></li>';
  }

			if($page == 'communities') {
	$html4 = '<li id="global-menu-community" class="selected"><a href="/" class="symbol"><span>Communities</span></a></li>';
	} else {
	$html4 = '<li id="global-menu-community"><a href="/" class="symbol"><span>Communities</span></a></li>';
	}

	if($page == 'updates') {
	$html5 = '<li id="global-menu-news" class="selected"><a class="symbol" href="/notifications"><span class="badge" style="display: none;">0</span></a></li>';
	} else {
	$html5 = '<li id="global-menu-news"><a class="symbol" href="/notifications"><span class="badge" '.($notif_count == 0 ? 'style="display: none;"' : 'style="display: block;"').'>'.$notif_count.'</span></a></li>';
	}

	$html6 = '
	<li id="global-menu-my-menu"><button class="symbol js-open-global-my-menu open-global-my-menu"></button>
	<menu id="global-my-menu" class="invisible none">
	<li><a href="/edit/profile" class="symbol my-menu-profile-setting"><span>Edit Profile</span></a></li>	
	<li><a class="symbol my-menu-info" href="/changelog"><span>Ciiverse Changelog</span></a></li>
  <li><a href="/rules" class="symbol my-menu-guide"><span>Ciiverse Rules</span></a></li>
	'.($user['user_level'] > 0 ? '<li><a class="symbol my-menu-miiverse-setting" href="/admin_panel.php"><span>Admin Panel</span></a></li>' : '').'
  '.($user['has_db_access'] == 1 ? '<li><a class="symbol my-menu-miiverse-setting" href="/database"><span>Staff Panel</span></a></li>' : '').'
	<li><a href="/login/logout.php?csrftoken='.$_COOKIE['csrf_token'].'" class="symbol my-menu-guide"><span>Log Out</span></a></li>
	</menu>';
	$html7 = '</li>';

	$finals = "$html1 $html2 $activity $html4 $html5 $html6 $html7";

	return $finals;
}

function ftbnli($page) {
	#This is for when you're not logged in.
  /* <li id="global-menu-login">
<a href="/login/" class="login">
<input type="image" alt="Sign in" src="/s/img/sign-in.png">
</a>
</li> */

  $html1 = '<menu id="global-menu">
  <li id="global-menu-logo"><h1><a href="/"><img src="/img/ciiverse.png" alt="Miiverse" width="165" height="30"></a></h1></li>';
 
  $html2 = '
  <li id="global-menu-login">
  <a href="/login/" class="login">
  <input type="image" alt="Sign in" src="/img/signin_base.png">
  </a>
  </li>
  '; 

  $finals = "$html1 $html2";

	/*	$html1 = '<menu id="global-menu">
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

	$finals = "$html1 $html2 $html3"; */

	return $finals;
}

function form_post_thingy() {
  global $cid;
  global $row;
  global $user;
        echo '
        <form method="post" id="post-form" action="/post.php" enctype="multipart/form-data">
        <div class="feeling-selector js-feeling-selector test-feeling-selector" style="display: none;"><label class="symbol feeling-button feeling-button-normal checked"><input type="radio" name="feeling_id" value="0" checked=""><span class="symbol-label">normal</span></label><label class="symbol feeling-button feeling-button-happy"><input type="radio" name="feeling_id" value="1"><span class="symbol-label">happy</span></label><label class="symbol feeling-button feeling-button-like"><input type="radio" name="feeling_id" value="2"><span class="symbol-label">like</span></label><label class="symbol feeling-button feeling-button-surprised"><input type="radio" name="feeling_id" value="3"><span class="symbol-label">surprised</span></label><label class="symbol feeling-button feeling-button-frustrated"><input type="radio" name="feeling_id" value="4"><span class="symbol-label">frustrated</span></label><label class="symbol feeling-button feeling-button-puzzled"><input type="radio" name="feeling_id" value="5"><span class="symbol-label">puzzled</span></label></div>
          <input type="hidden" name="communityid" value="'.$cid.'">
          <input type="hidden" name="csrf_token" value="'.$_COOKIE['csrf_token'].'">
        <div class="textarea-container" align="center">
    <textarea name="makepost" class="textarea-text textarea" maxlength="1000" placeholder="Share your thoughts in a post to '.$row['community_name'].' Community"></textarea>
  </div>
  <div id="url-stuff" style="display: none;" align="center">
  '.($user['can_post_images'] == 1 ? '<label class="file-button-container">
<span class="input-label">File upload <span>PNG, JPG, BMP, and GIF are allowed.</span></span>
<input type="file" class="file-button" name="screenshot" accept="image/*">
</label>' : '').'
  <input type="text" class="textarea" style="cursor: auto; height: auto;" placeholder="URL/Youtube video" name="url" maxlength="2000"></div>
  <div class="form-buttons" style="display: none;">
    <input type="submit" class="black-button post-button" value="Send" name="create-post">
  </div>
</form>';
}

function humanTiming($time) {
	#Credit goes to arian for this code.
	    if(time() - $time >= 345600) {
        return date("m/d/Y g:i A", $time);
    }
    $time = time() - $time;
    if (strval($time) < 1) {
        $time = 1;
    }
    $tokens = array(86400 => 'day', 3600 => 'hour', 60 => 'minute', 1 => 'second');
    foreach ($tokens as $unit => $text){
        if($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':''). ' ago';
    }
}

	function printOrganization($type,$custom) {
      if($type == 2) {
      echo '<span class="user-organization">Moderator</span>';
      }elseif($type == 3) {
      echo '<span class="user-organization">Admin</span>';
      }elseif($type == 4) {
      echo '<span class="user-organization">The person who made Ciiverse.</span>';
      }elseif($type == 5) {
      echo '<span class="user-organization">Donator</span>';
      }elseif($type == 6) {
      echo '<span class="user-organization">It\'s hip to be Pip.</span>';
      }elseif($type == 7) {
      echo '<span class="user-organization">Staff</span>';
      }
 }

    function print_yeah($feeling) {
    	if($feeling == 0 || $feeling == 1) {
    		return 'Yeah!';
    	}elseif($feeling == 2) {
    		return 'Yeah♥';
    	}elseif($feeling == 3) {
    		return 'Yeah!?';
    	}elseif($feeling == 4 || $feeling == 5) {
    		return 'Yeah...';
    	}elseif($feeling == 69) {
    		return 'Comedy 2018';
    	}else{
    		return 'No.';
    	}
    }

    function community_info($cid,$info) {

    	global $db;

    	if($info == 'icon') {
    		$query = $db->query("SELECT community_picture FROM communities WHERE id = $cid");
    		$icon = mysqli_fetch_array($query);

    		return $icon['community_picture'];
    	}elseif ($info == 'name') {
    		$query = $db->query("SELECT community_name FROM communities WHERE id = $cid");
    		$name = mysqli_fetch_array($query);

    		return $name['community_name'];
    	}elseif ($info == 'type') {
        $query = $db->query("SELECT type FROM communities WHERE id = $cid");
        $type = mysqli_fetch_array($query);

        return $type['type'];
      }

    }

  function printFooter() {
    echo '<div id="footer">
        <div id="footer-inner">
          <div class="link-container">
            <p id="copyright"><a href="https://github.com/SRGNation/Ciiverse">Ciiverse source code on GitHub.</a></p>            
            <p id="copyright"><a href="http://nintendo.com">Ciiverse is a fan recreation of Miiverse by Nintendo and Hatena. I am not affiliated with these companies and they deserve your business.</a></p>
          </div>
        </div>
      </div>';
  }

  function urlimageisvalid($image) {
    #this code isn't mine sorry.
    $params = array('http' => array(
                  'method' => 'HEAD'
               ));
     $ctx = stream_context_create($params);
     $fp = @fopen($image, 'rb', false, $ctx);
     if (!$fp) 
        return false;  // Problem with url

    $meta = stream_get_meta_data($fp);
    if ($meta === false)
    {
        fclose($fp);
        return false;  // Problem reading data from url
    }

    $wrapper_data = $meta["wrapper_data"];
    if(is_array($wrapper_data)){
      foreach(array_keys($wrapper_data) as $hh){
          if (substr($wrapper_data[$hh], 0, 19) == "Content-Type: image") // strlen("Content-Type: image") == 19 
          {
            fclose($fp);
            return true;
          }
      }
    }

    fclose($fp);
    return false;
  }

  function post_to_discord($post) {
    $content = array("content" => $post);
    $curl = curl_init(DISCORD_WEBHOOK);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($content));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    return curl_exec($curl);
  }

  function getVideoID($url) {

    $url = str_replace('https://www.youtube.com/watch?v=', '', $url);
    $url = str_replace('https://youtu.be/', '', $url);
    $url = str_replace('https://m.youtube.com/watch?v=', '', $url);

    return $url;

  }

  function printPost($postid,$show_community) {

    global $db;
    global $user;

    $query = $db->query("SELECT * FROM posts WHERE post_id = '$postid'");
    $post = mysqli_fetch_array($query);
    $query2 = $db->query("SELECT * FROM users WHERE ciiverseid = '".$post['owner']."'");
    $users = mysqli_fetch_array($query2);
    $query3 = $db->query("SELECT * FROM yeahs WHERE post_id = '$postid' AND type = 'post'");
    $yeahs = mysqli_num_rows($query3);
    $query4 = $db->query("SELECT * FROM comments WHERE post_id = '$postid'");
    $comments = mysqli_num_rows($query4);

    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
          if($post['owner'] == $_SESSION['ciiverseid']) {
            $yeah_disabled = true;
          } else {
            $yeah_disabled = false;
          }
        } else {
          $yeah_disabled = true;
        }

        if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
        $check_yeah_added = $db->query("SELECT * FROM yeahs WHERE owner = '".$_SESSION['ciiverseid']."' AND post_id = ".$postid);
        $yeahed = mysqli_num_rows($check_yeah_added);
        } else {
        $yeahed = 0;
        }

        if(strlen($post['content']) > 220) {
          $content = mb_substr($post['content'],0,220).'...';
        } else {
          $content = $post['content'];
        }

        if($post['deleted'] == 1) {
          $delete_type = 'Poster';
        }elseif($post['deleted'] == 2) {
          $delete_type = 'Moderator';
        }elseif($post['deleted'] == 3) {
          $delete_type = 'Administrator';
        }elseif($post['deleted'] == 4) {
          $delete_type = 'Owner';
        }

              echo '
              <div id="post"  data-href="/post/'.$post['post_id'].'" class="post post-subtype-default trigger '.($show_community > 1 ? 'post-list-outline' : '').'" tabindex="0">';

              if($show_community > 0) { 
              echo '<p class="community-container">
<a class="test-community-link" href="/communities/'.$post['community_id'].'"><img src="'.community_info($post['community_id'],'icon').'" class="community-icon">'.community_info($post['community_id'],'name').' Community</a></p>';
}

 echo '<a href="/users/'.$post['owner'].'" class="icon-container '.print_badge($post['owner']).'"><img src="' . htmlspecialchars(user_pfp($post['owner'],$post['feeling'])) . '" class="icon"></a>
  <p class="user-name"><a href="/users/'.$post['owner'].'">' . htmlspecialchars($users['nickname']) . '</a></p>
  <div class="timestamp-container"><span class="timestamp">'.humanTiming(strtotime($post['date_time'])).'</span></div>
  <div class="body">
  '.(strpos($post['web_url'], 'youtube') || strpos($post['web_url'], 'youtu.be') ? '<a href="/post/'.$post['post_id'].'" class="screenshot-container video"><img height="48" src="https://i.ytimg.com/vi/'.getVideoID($post['web_url']).'/default.jpg"></a>' : '').'
  '.($post['deleted'] > 0 ? '<p class="deleted-message">Deleted by '.$delete_type.''.($post['deleted'] > 1 ? '<br>Post ID: '.$post['post_id'] : '').'</p>' : '').'
    <div class="post-content">
        <div class="tag-container">
        </div>';
        if($post['deleted'] > 0 && $_SESSION['ciiverseid'] !== $post['owner']) { } else {  
           echo ' '.(empty($post['screenshot']) ? '' : '<a class="screenshot-container still-image" href="/post/'.$post['post_id'].'"><img src="'.$post['screenshot'].'"></a>').'
            <p class="post-content-text" id="not-full-post">' . htmlspecialchars($content) . '</p>
            <p class="post-content-text" id="full-post" style="display: none;">' . htmlspecialchars($post['content']) . '</p>
            '.(strlen($post['content']) > 220 ? '<p id="show_full"><a class="show_full_post">Show full post</a></p>' : '').'';
  }
    if($post['deleted'] == 0) {
          echo '<div class="post-meta">
      <button '.($yeah_disabled == true ? 'disabled' : '').' class="symbol submit empathy-button" id="'.$post['post_id'].'" data-yeah-type="post" data-remove="'.$yeahed.'" type="button"><span class="empathy-button-text">'.($yeahed == 0 ? print_yeah($post['feeling']) : 'Unyeah').'</span></button>
      <div class="empathy symbol"><span class="symbol-label">Yeahs</span><span class="empathy-count">'.$yeahs.'</span></div>
      <div class="reply symbol"><span class="symbol-label">Comments</span><span class="reply-count">'.$comments.'</span></div>
  </div>';
      }
echo '</div>
</div>
</div>';

  }

  function print_badge($user_dude) {

    global $db;

    $get_user_type = $db->query("SELECT user_type FROM users WHERE ciiverseid = '$user_dude'");
    $users = mysqli_fetch_array($get_user_type);

    if($users['user_type'] == 2) {
      return 'official-mod';
    }elseif($users['user_type'] == 3) {
      return 'official-admin';
    }elseif($users['user_type'] == 4) {
      return 'official-developer';
    }elseif($users['user_type'] == 5) {
      return 'official-user';
    }elseif($users['user_type'] == 6) {
      return 'official-pip';
    }elseif($users['user_type'] == 7) {
      return 'official-staff';
    } else {
      return '';
    }

  }

function uploadImage($filename) {
  $handle = fopen($filename, "r");
  $data = fread($handle, filesize($filename));
  if(!empty(CLOUDINARY_NAME))
  {
    $pvars = array('file' => (exif_imagetype($filename) == 1 ? 'data:image/gif;base64,' : (exif_imagetype($filename) == 2 ? 'data:image/jpg;base64,' : (exif_imagetype($filename) == 3 ? 'data:image/png;base64,' : (exif_imagetype($filename) == 6 ? 'data:image/bmp;base64,' : '')))) . (isset($resized) ? base64_encode($resized) : base64_encode($data)),
      'api_key' => CLOUDINARY_KEY,
      'upload_preset' => CLOUDINARY_PRESET);
    $timeout = 30;
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'https://api.cloudinary.com/v1_1/'. CLOUDINARY_NAME .'/auto/upload');
  }
  else
  {
    $pvars = array('file' => (exif_imagetype($filename) == 1 ? 'data:image/gif;base64,' : (exif_imagetype($filename) == 2 ? 'data:image/jpg;base64,' : (exif_imagetype($filename) == 3 ? 'data:image/png;base64,' : (exif_imagetype($filename) == 6 ? 'data:image/bmp;base64,' : '')))) . (isset($resized) ? base64_encode($resized) : base64_encode($data)),
        'upload_preset' => 'reverb-mobile');
    $timeout = 30;
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'https://api.cloudinary.com/v1_1/reverb/auto/upload');
  }
  curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
  curl_setopt($curl, CURLOPT_POST, 1);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $pvars);
  $out = curl_exec($curl);
  curl_close($curl);
  $pms = json_decode($out, true);

  if (@$image=$pms['secure_url']) {
    return $image;
  } else {
    return 1;
  }
}

?>