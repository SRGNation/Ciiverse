<?php

function user_pfp($cii_id,$feeling) {

  global $db;

  $sql = "SELECT ciiverseid, pfp, nnid, pfp_type, mii_hash FROM users WHERE ciiverseid = '".mysqli_real_escape_string($db,$cii_id)."'";
  $query = mysqli_query($db,$sql);
  $user = mysqli_fetch_array($query);

  if($feeling == 0) {
    $feel = 'normal';
  }elseif($feeling == 1) {
    $feel = 'happy';
  }elseif($feeling == 2) {
    $feel = 'like';
  }elseif($feeling == 3) {
    $feel = 'surprised';
  }elseif($feeling == 4) {
    $feel = 'frustrated';
  }elseif($feeling == 5) {
    $feel = 'puzzled';
  }else{
    $feel = 'normal';
  }

  if($user['pfp_type'] == 1) {

    if(empty($user['mii_hash'])) {
    return htmlspecialchars('/img/defult_pfp_'.$feel.'.png');
    } else {
        return htmlspecialchars('https://mii-secure.cdn.nintendo.net/'.$user['mii_hash'].'_'.$feel.'_face.png');
    }
  } else {
  if(!empty($user['pfp'])) {
  return htmlspecialchars($user['pfp']); } else {
  return htmlspecialchars('/img/defult_pfp_'.$feel.'.png'); 
  }
}

}

/* 

  This is the old code.
  
  function user_pfp($cii_id,$feeling) {

  global $db;

  $sql = "SELECT ciiverseid, pfp, nnid, pfp_type, mii_hash FROM users WHERE ciiverseid = '".mysqli_real_escape_string($db,$cii_id)."'";
  $query = mysqli_query($db,$sql);
  $user = mysqli_fetch_array($query);

  if($feeling == 0) {
    $feel = 'normal';
  }elseif($feeling == 1) {
    $feel = 'happy';
  }elseif($feeling == 2) {
    $feel = 'like';
  }elseif($feeling == 3) {
    $feel = 'surprised';
  }elseif($feeling == 4) {
    $feel = 'frustrated';
  }elseif($feeling == 5) {
    $feel = 'puzzled';
  }

  if($user['pfp_type'] == 1) {
        if(empty($user['mii_hash'])) {
          $ch = curl_init();
      curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://ariankordi.net/seth/'. $user['nnid'],
        CURLOPT_HEADER => true,
        CURLOPT_RETURNTRANSFER => true));
      $response = curl_exec($ch);

          $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if($httpCode == 404 || $httpCode == 102) {
                    return '/img/defult_pfp.png';
                } else {
            $body = substr($response, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
            $dom = new DOMDocument;
            $dom->loadHTML($body);
            $db->query("UPDATE users SET mii_hash = '$body' WHERE ciiverseid = '$cii_id' ");
            return 'https://mii-secure.cdn.nintendo.net/'.$body.'_normal_face.png';
      }
    } else {
        return 'https://mii-secure.cdn.nintendo.net/'.$user['mii_hash'].'_'.$feel.'_face.png';
    }
  } else {
  if(!empty($user['pfp'])) {
  return $user['pfp']; } else {
  return '/img/defult_pfp.png'; 
  }
}

} */

/* More old code 
function user_pfp($cii_id,$feeling) {

  global $db;

  $sql = "SELECT ciiverseid, pfp, nnid, pfp_type, mii_hash FROM users WHERE ciiverseid = '".mysqli_real_escape_string($db,$cii_id)."'";
  $query = mysqli_query($db,$sql);
  $user = mysqli_fetch_array($query);

  if($feeling == 0) {
    $feel = 'normal';
  }elseif($feeling == 1) {
    $feel = 'happy';
  }elseif($feeling == 2) {
    $feel = 'like';
  }elseif($feeling == 3) {
    $feel = 'surprised';
  }elseif($feeling == 4) {
    $feel = 'frustrated';
  }elseif($feeling == 5) {
    $feel = 'puzzled';
  }else{
    $feel = 'normal';
  }

  if($user['pfp_type'] == 1) {

    if(empty($user['mii_hash'])) {
    $ch = curl_init();
    $api = "https://accountws.nintendo.net/v1/api/";

    curl_setopt_array($ch, array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array(
            "X-Nintendo-Client-ID: a2efa818a34fa16b8afbc8a74eba3eda",
            "X-Nintendo-Client-Secret: c91cdb5658bd4954ade78533a339cf9a"
        )
    ));

    curl_setopt($ch, CURLOPT_URL, $api . "admin/mapped_ids?input_type=user_id&output_type=pid&input=" . $user['nnid']);
    $mapped_ids = new SimpleXMLElement(curl_exec($ch));

    #This code works for some reason idk why but the code commented on top of this is Arians original code and it didn't work >:(
    if(empty($mapped_ids->mapped_id->out_id)) {
      $db->query("UPDATE users SET pfp_type = 0 WHERE ciiverseid = '$cii_id' ");

      return $user['pfp'];
    }

    $pid = $mapped_ids->mapped_id->out_id;
    curl_setopt($ch, CURLOPT_URL, $api . "miis?pids=" . $pid);
    $miis = new SimpleXMLElement(curl_exec($ch));
    curl_close($ch);

    foreach (json_decode(json_encode($miis), true)["mii"]["images"]["image"] as $a) {
        if ($a["type"] == "normal_face") {
           $aaa = $a["cached_url"];

            $mii1 = str_replace('http://mii-images.cdn.nintendo.net/', '', $aaa);
            $face = str_replace('_normal_face.png', '', $mii1); 

            $db->query("UPDATE users SET mii_hash = '$face' WHERE ciiverseid = '$cii_id' ");

            return 'https://mii-secure.cdn.nintendo.net/'.$face.'_'.$feel.'_face.png';
        }
    }
    } else {
        return 'https://mii-secure.cdn.nintendo.net/'.$user['mii_hash'].'_'.$feel.'_face.png';
    }
  } else {
  if(!empty($user['pfp'])) {
  return $user['pfp']; } else {
  return '/img/defult_pfp.png'; 
  }
}

}
*/

function account_deleted($cii_id) {

        global $db;

       if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
       $ciiverseid = mysqli_real_escape_string($db,$cii_id);
       $query = $db->query("SELECT ciiverseid FROM users WHERE ciiverseid ='$ciiverseid' ");
       
       $cont = mysqli_num_rows($query);
       
       if($cont !== 1) {
            return true;
       } else {
            return false;
       }
   }
}

function userSidebar($id, $showMinimum, $editProfile = false, $page = null) {
  global $db;
  global $user;

  $stmt = $db->prepare("SELECT ciiverseid, nickname, prof_desc, user_type, nnid, user_level, favorite_post, hide_replies, hide_yeahs, (SELECT COUNT(*) FROM follows WHERE follow_to = users.ciiverseid) AS follower_count, (SELECT COUNT(*) FROM follows WHERE follow_by = users.ciiverseid) AS following_count, (SELECT COUNT(*) FROM posts WHERE owner = users.ciiverseid AND deleted = 0) AS post_count, (SELECT COUNT(*) FROM comments WHERE owner = users.ciiverseid) AS comment_count, (SELECT COUNT(*) FROM yeahs WHERE owner = users.ciiverseid) AS yeah_count, (SELECT COUNT(*) FROM posts WHERE owner = users.ciiverseid AND deleted > 0) AS deleted_count FROM users WHERE ciiverseid = ?");
  $stmt->bind_param('s', $id);
  $stmt->execute();
  if($stmt->error) {
    return null;
  }
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();

  $stmt = $db->prepare("SELECT COUNT(*) FROM follows WHERE follow_to = ? AND follow_by = ?");
  $stmt->bind_param('ss', $id, $_SESSION['ciiverseid']);
  $stmt->execute();
  $result = $stmt->get_result();
  $following_user = $result->fetch_assoc()['COUNT(*)'];

  $stmt = $db->prepare("SELECT tag_name, tag_content, owner FROM profile_tags WHERE owner = ? ORDER BY id ASC");
  $stmt->bind_param('s', $id);
  $stmt->execute();
  $pftRes = $stmt->get_result();
  ?>
  <div id="sidebar" class="<?=(!$showMinimum ? 'user' : 'general')?>-sidebar"><div class="sidebar-container"> <?php
            $stmt = $db->prepare("SELECT post_id, screenshot, owner FROM posts WHERE post_id = ? AND deleted = 0");
            $stmt->bind_param('i', $row['favorite_post']);
            $stmt->execute();
            $result = $stmt->get_result();
            $fav_post = $result->fetch_assoc();

            if(!empty($fav_post['screenshot'])) { 
              echo '<a href="/post/'.$fav_post['post_id'].'" id="sidebar-cover" style="background-image:url('.htmlspecialchars($fav_post['screenshot']).')"><img src="'.htmlspecialchars($fav_post['screenshot']).'" class="sidebar-cover-image"></a><div id="sidebar-profile-body" class="with-profile-post-image">';
            } else {
              echo '<div id="sidebar-profile-body" class="without-profile-post-image">';
            }
          ?>
          <div class="icon-container <?=print_badge($id)?>">
          <a href="/users/<?=$id?>">
          <img src="<?=user_pfp($id, 0)?>" class="icon">
        </a>
      </div>
      <?php 
        if($row['user_type'] > 1) {
          printOrganization($row['user_type'],0);
        }
      ?>
      <a href="/users/<?=$id?>" class="nick-name"><?=$row['nickname']?></a>
      <p class="id-name"><?=$id?></p>
      <?php if($row['user_level'] > 0) {echo '<p class="admin-level">Level '.$row['user_level'].' Admin</p>';} ?>
      </div>
       <?php if(isset($_SESSION['ciiverseid']) && $_SESSION['ciiverseid'] == $id && !$editProfile) { echo '<div id="edit-profile-settings"><a class="button symbol" href="/edit/profile">Edit Profile</a></div>'; }
        if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 'true') {
        if($id !== $_SESSION['ciiverseid']) {

          if($following_user == 0) {
            echo '<button type="button" data-user-id="'.$id.'" class="follow-button button symbol">Follow</button>';
          } else {
            echo '<button type="button" data-user-id="'.$id.'" class="unfollow-button button symbol">Follow</button>';
          }

        }

        if($id !== $_SESSION['ciiverseid']) {
        if($row['user_level'] < $user['user_level'])
          echo '<div id="edit-profile-settings"><a class="button symbol" href="/users/manage_user.php?cvid='.$id.'">Manage Account</a></div>';
        }
       } ?>
      <ul id="sidebar-profile-status">
      <li><a href="/users/<?=$id?>/following"><span><span class="number"><?=$row['following_count']?></span>Following</span></a></li>
      <li><a href="/users/<?=$id?>/followers"><span><span class="number"><?=$row['follower_count']?></span>Followers</span></a></li>
    </div>
    <?php 
      if($showMinimum)
      {
        echo '<div class="sidebar-setting sidebar-container"><ul><li><a href="/communities/55" class="sidebar-menu-info symbol"><span>Ciiverse Changelog</span></a></li><li><a href="/userdata/list" class="sidebar-menu-setting symbol"><span>Manage Profile Tags</span></a></li><li><a href="/rules" class="sidebar-menu-guide symbol"><span>Ciiverse Rules</span></a></li></ul></div></div>';
        return;
      } 
    ?>
    <div class="sidebar-setting sidebar-container">
  <div class="sidebar-post-menu">
    <a href="/users/<?=$id?>" class="sidebar-menu-post with-count symbol <?php if($page == 1) {echo'selected';} ?>">
      <span>All posts</span>
      <span class="post-count">
          <span class="test-post-count" id="js-my-post-count"><?=$row['post_count']?></span>
        </span>
      </a>
      <?php 
      if($row['hide_replies'] == 0 || $id == $_SESSION['ciiverseid']) { ?>
    <a href="/users/<?=$id?>/replies" class="sidebar-menu-post with-count symbol <?php if($page == 3) {echo'selected';} ?>">
      <span>Replies</span>
      <span class="post-count">
          <span class="test-post-count" id="js-my-post-count"><?=$row['comment_count']?></span>
        </span>
      </a> <?php
    }
      ?>
      <?php if($row['hide_yeahs'] == 0 || $id == $_SESSION['ciiverseid']) { ?>
    <a class="sidebar-menu-empathies with-count symbol <?php if($page == 2) {echo'selected';} ?>" href="/users/<?php echo $id; ?>/empathies">
      <span>Yeahs</span>
      <span class="post-count">
        <span class="test-empathy-count"><?=$row['yeah_count']?></span>
      </span>
    </a>
  <?php } ?>
    <?php
    if($id == $_SESSION['ciiverseid']) {
    ?> <a class="sidebar-menu-post with-count symbol <?php if($page == 4) {echo'selected';} ?>" href="/users/<?php echo $id; ?>/deleted">
      <span>Deleted Posts</span>
      <span class="post-count">
        <span class="test-empathy-count"><?=$row['deleted_count']?></span>
      </span>
    </a> <?php
  }
    ?>
  </div>
</div>

                 <div class="sidebar-container sidebar-profile">
       <?php 
       if(!empty($row['prof_desc'])) {
        echo '<div class="profile-comment">
        <p class="js-truncated-text">' .htmlspecialchars($row['prof_desc']).'</p>
              </div>';
       }
       ?>
       <div class="user-data">
        <div class="user-main-profile data-content">
          <h4><span>NNID</span></h4>
            <div class="note"><?php if(!empty($row['nnid'])){echo $row['nnid'];}else{echo 'Not set.';} ?></div>
        </div>
        <?php 
          while($tags = $pftRes->fetch_assoc()) {
            echo '<div class="user-main-profile data-content"><h4><span>'.$tags['tag_name'].'</span></h4><div class="note">'.$tags['tag_content'].'</div></div>';
          } 
        ?>
      </div>
      <?=($editProfile ? '<a class="button" href="/userdata/list">Manage Profile Tags</a>' : '')?>
    </div> <?php
}

?>