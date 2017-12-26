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

}

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