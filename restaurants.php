<?php
set_time_limit(60*10);//60 seconds /min * 10 minutes
ini_set('memory_limit', '-1');
$xml_string = file_get_contents("dinesafe.xml");
$xml = simplexml_load_string($xml_string);
$json = json_encode($xml);
$array = json_decode($json,TRUE);
$array = $array['ROW'];
$previous = NULL;
$counter = 1;
foreach($array as $row)
{
	if($previous == NULL)
	{
		$previous = $row;
		continue;
	}
	if($row['ESTABLISHMENT_ID'] != $previous['ESTABLISHMENT_ID'])
	{
		//CHECK substr($previous['INSPECTION_DATE'],0,4) == 2013
		echo "INSERT<br />";
		++$counter;
	}
	else
	{
	}
	$previous = $row;
}
echo 'insert last one<br />';
echo $counter;
?>