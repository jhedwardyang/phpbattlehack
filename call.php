<?php
    // Include the Twilio PHP library
    require 'Services/Twilio.php';
 
    // Twilio REST API version
    $version = "2010-04-01";
 
    // Set our Account SID and AuthToken
    $sid = "ACeb64263658a378284735932fcc966cc1"; 
$token = "5b6fc237a813e94b05fc1d4856713596"; 
     
    // A phone number you have previously validated with Twilio
    $phonenumber = '19027019264';
     
    // Instantiate a new Twilio Rest Client
    $client = new Services_Twilio($sid, $token, $version);
 
    try {
        // Initiate a new outbound call
        $call = $client->account->calls->create(
            $phonenumber, // The number of the phone initiating the call
            '5162340602', // The number of the phone receiving call
            'http://ahri.walnutio.com/twiliostuff/ty.xml' // The URL Twilio will request when the call is answered
        );
        echo 'Started call: ' . $call->sid;
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }