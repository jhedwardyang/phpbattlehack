<?php
	ini_set('memory_limit', '-1');	
	$map = false;
	if(count($_GET) >= count($_POST))
	{
		$start = $_GET['start'];
		$way = $_GET['way'];
		if(isset($_GET['map'])) $map = true;
	}
	else
	{
		$start = $_POST['start'];
		$way = $_POST['way'];
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
	curl_close($ch);
	$total = $total->routes[0];
	$return = array( array($total->legs[0]->start_location->lat, $total->legs[0]->start_location->lng) );
	foreach($total->legs as $leg)
	{
		foreach($leg->steps as $step){
			array_push($return, array($step->end_location->lat, $step->end_location->lng));
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