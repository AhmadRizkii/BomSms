<?php

//yudha tira pamungkas
//paste token mu di $token

function post($url, $params) {

	$ch = curl_init(); 
	curl_setopt ($ch, CURLOPT_URL, $url); 
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 

	if(!empty($params)) {
		curl_setopt ($ch, CURLOPT_POSTFIELDS, $params);
		curl_setopt ($ch, CURLOPT_POST, 1); 
	}

	curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt ($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
	curl_setopt ($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
	curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);

	$result = curl_exec($ch);
	curl_close($ch);
	return $result;

}

function fetch_value($str,$find_start,$find_end) {
	$start = @strpos($str,$find_start);
	if ($start === false) {
		return "";
	}
	$length = strlen($find_start);
	$end    = strpos(substr($str,$start +$length),$find_end);
	return trim(substr($str,$start +$length,$end));
}

function inStr($s, $as){
	$s = strtoupper($s);
	if(!is_array($as)) $as=array($as);
	for($i=0;$i<count($as);$i++) if(strpos(($s),strtoupper($as[$i]))!==false) return true;
		return false;
}



    
	$token = '47477929627.c0a94fa.ededbe2370944183be64a9971286252d';
	$pisah = explode('.', $token);
	$user_id = $pisah[0];
	

	

	function start($user_id, $token) {
		$page = array();
		$page['order'] = post('http://instagetlikes.ru/api/getExtraOrders','user_id='.$user_id.'');
		$page['task'] = post('http://instagetlikes.ru/api/getPhotoTask','user_id='.$user_id.'');


		$_id = array();
		$_id['order'] = fetch_value($page['order'], '"_id":"','"');
		$_id['task'] = fetch_value($page['task'], '"_id":"','"');
		$_id['photo'] = fetch_value($page['task'], '"photo_id":"','"');

		if ($page) {
			$post['order'] = post('http://instagetlikes.ru/api/performExtraOrder', 'token='.$token.'&_id='.$_id['order'].'');
			$post['task'] = post('http://instagetlikes.ru/api/addFollowers', 'photo_id='.$_id['photo'].'&token='.$token.'');
			$coin['order'] = fetch_value($post['order'], '"count_coins":',',');
			$coin['task'] = fetch_value($post['task'], '"count_coins":','}');

			if (inStr($post['order'], '"status":"ERROR",')) {

				if (inStr($post['task'], 'already follows')) {
					sleep(0);
				} elseif (inStr($post['task'], 'OK')) {
					echo "Success: ".$coin['task']."<br>";
					flush();
					ob_flush();
					sleep(0);
				}

			} elseif (inStr($post['order'], '"status":"OK"')) {
				echo "Success: ".$coin['order']." <br>";
				flush();
				ob_flush();
				sleep(0);
			}

		}


	}
	
	while (true) { 
        start($user_id, $token);
	}
	

?>