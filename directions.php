<?php
	ini_set('memory_limit', '-1');	
	if(count($_GET) >= count($_POST))
	{
		$start = $_GET['start'];
		$way = $_GET['way'];
	}
	else
	{
		$start = $_POST['start'];
		$way = $_POST['way'];
	}
	if($start == NULL || $start == "") exit(0);
	$start = str_replace(' ', '+', $start);
	$way = str_replace(' ', '+', $way);

	if($way == NULL || $way == "") $url = "http://maps.googleapis.com/maps/api/directions/json?origin=".$start."&destination=".$start."&sensor=false";
	else $url = "http://maps.googleapis.com/maps/api/directions/json?origin=".$start."&destination=".$start."&waypoints=optimize:true|".$way."&sensor=false";
	
	$curl = curl_init();
	curl_setopt_array($curl, array(
	    CURLOPT_RETURNTRANSFER => 1,
	    CURLOPT_URL => $url,
	    CURLOPT_USERAGENT => 'BattleHack-Toronto'
	));
	$output = curl_exec($curl);
	$total = json_decode($output);
	$total = $total->routes[0]->legs[0]->steps;
	curl_close($ch);
	$return = array();
	foreach($total as $step)
	{
		array_push($return, array($step->end_location->lat, $step->end_location->lng));
	}
	//$return = (object) array('success' => (isset($total->formatted_address)?true:false), 'addr' => $total->formatted_address, 'lat' => $total->geometry->location->lat, 'long' => $total->geometry->location->lng);
	echo "<pre>";
	print_r(json_encode($return));
	echo "</pre>";
?>