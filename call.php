<?php
// Get the PHP helper library from twilio.com/docs/php/install
require_once('Services/Twilio.php'); // Loads the library
 
// Your Account Sid and Auth Token from twilio.com/user/account
$sid = "ACeb64263658a378284735932fcc966cc1"; 
$token = "5b6fc237a813e94b05fc1d4856713596"; 
$client = new Services_Twilio($sid, $token);
 
$call = $client->account->calls->create("+19027019264", "+15162340602", "http://ahri.walnutio.com/twiliostuff/ty.xml", array());
echo $call->sid;