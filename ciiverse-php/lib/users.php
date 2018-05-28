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
    return '/img/defult_pfp_'.$feel.'.png';
    } else {
        return 'https://mii-secure.cdn.nintendo.net/'.$user['mii_hash'].'_'.$feel.'_face.png';
    }
  } else {
  if(!empty($user['pfp'])) {
  return $user['pfp']; } else {
  return '/img/defult_pfp_'.$feel.'.png'; 
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

?>