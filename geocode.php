<?php
	if(count($_GET) >= count($_POST)) $addr = $_GET['addr'];
	else $addr = $_POST['addr'];
	if($addr == NULL || $addr == "") exit(0);
	$addr = str_replace(' ', '+', $addr);
	$url = "http://maps.googleapis.com/maps/api/geocode/json?address=".$addr."&sensor=false";
	$curl = curl_init();
	curl_setopt_array($curl, array(
	    CURLOPT_RETURNTRANSFER => 1,
	    CURLOPT_URL => $url,
	    CURLOPT_USERAGENT => 'BattleHack-Toronto'
	));
	$output = curl_exec($curl);
	//$output = '{ "results" : [ { "address_components" : [ { "long_name" : "1600", "short_name" : "1600", "types" : [ "street_number" ] }, { "long_name" : "Amphitheatre Parkway", "short_name" : "Amphitheatre Pkwy", "types" : [ "route" ] }, { "long_name" : "Mountain View", "short_name" : "Mountain View", "types" : [ "locality", "political" ] }, { "long_name" : "Santa Clara County", "short_name" : "Santa Clara County", "types" : [ "administrative_area_level_2", "political" ] }, { "long_name" : "California", "short_name" : "CA", "types" : [ "administrative_area_level_1", "political" ] }, { "long_name" : "United States", "short_name" : "US", "types" : [ "country", "political" ] }, { "long_name" : "94043", "short_name" : "94043", "types" : [ "postal_code" ] } ], "formatted_address" : "1600 Amphitheatre Parkway, Mountain View, CA 94043, USA", "geometry" : { "location" : { "lat" : 37.4219998, "lng" : -122.0839596 }, "location_type" : "ROOFTOP", "viewport" : { "northeast" : { "lat" : 37.4233487802915, "lng" : -122.0826106197085 }, "southwest" : { "lat" : 37.4206508197085, "lng" : -122.0853085802915 } } }, "types" : [ "street_address" ] } ], "status" : "OK" } ';
	$total = json_decode($output);
	$total = $total->results[0];
	curl_close($ch);
	$return = (object) array('success' => (isset($total->formatted_address)?true:false), 'addr' => $total->formatted_address, 'lat' => $total->geometry->location->lat, 'lng' => $total->geometry->location->lng);
	echo "<pre>";
	echo json_encode($return);
	echo "</pre>";
?>