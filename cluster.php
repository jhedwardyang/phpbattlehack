<?php
	$url = "http://leona.walnutio.com:3000/analysis/cluster";
	$curl = curl_init();
	curl_setopt_array($curl, array(
	    CURLOPT_RETURNTRANSFER => 1,
	    CURLOPT_URL => $url,
	    CURLOPT_USERAGENT => 'BattleHack-Toronto',
	    CURLOPT_POST => 1,
	    CURLOPT_POSTFIELDS => NULL
	));
	$output = curl_exec($curl);;
	curl_close($ch);
	echo $output;
?>