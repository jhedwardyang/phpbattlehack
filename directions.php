<?php
	ini_set('memory_limit', '-1');	
	$map = false;
	$index = 0;
	$print = 0;
	if(count($_GET) >= count($_POST))
	{
		if(isset($_GET['print'])) $print = 1;
		$start = $_GET['start'];
		$way = $_GET['way'];
		$index = $_GET['index'];
		if(isset($_GET['map'])) $map = true;
	}
	else
	{
		if(isset($_POST['print'])) $print = 1;
		$start = $_POST['start'];
		$way = $_POST['way'];
		$index = $_POST['index'];
		if(isset($_POST['map'])) $map = true;
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
	if($print){
		echo "<pre>";
		echo $total;
		echo "</pre>";
	}
	curl_close($curl);
	$total = $total->routes[0];
	$return = array( (object) array('duration' => array('text' => '', 'value' => 0), 'html_instructions' => '', 'distance' => array('text' => '', 'value' => 0),
		'loc' => array($total->legs[0]->start_location->lat, $total->legs[0]->start_location->lng), 'index' => $index));
	foreach($total->legs as $leg)
	{
		foreach($leg->steps as $step){
			array_push($return, (object) array('loc' => array($step->end_location->lat, $step->end_location->lng), 
				'duration' => $step->duration,
				'html_instructions' => $step->html_instructions,
				'distance' => $step->distance,
				'index' => $index
			));
		}
	}
	if(!$map)
	{
		//echo "<pre>";
		echo json_encode($return);
		//echo "</pre>";
	}
	else
	{
?>
<?php
		echo "[";
		foreach($return as $arr)
		{
			echo "new google.maps.LatLng(".$arr[0].",".$arr[1]."), 
";
		}
		echo "];";
?>
<?php
	}
?>