<?php

/*
/   TWILIO TEXT MESSAGE API
/   Author: Charles Christensen
/   Edited: June 3, 2019
/
/   Required Dependencies: 
/       - Twilio account
/       - Twilio PHP libraries
/  
/   Intended Use:
/   Use webhooks to send Twilio messages.
/
*/

// Global variables.
$TWILIO_SID = "";
$TWILIO_TOKEN = "";

// Check and get required parameters.
if ( !isset($_POST['dst'])  ||
     !isset($_POST['msg'])    ) {
    header("Location: /notes/?sent=choices");
    exit();
}

// Create new Twilio connection.
require_once 'Twilio/autoload.php';
use Twilio\Rest\Client;
$twilio = new Client($TWILIO_SID, $TWILIO_TOKEN);

// Send messages.
$txt_sids = [];
$dsts = $_POST['dst'];
$msg = "[HEADER]\n" . $_POST['msg'];
foreach ($dsts as $dst)
{
  try {
    $to = "$dst";
    $message = $twilio->messages
                        ->create($to,                      // << TO
                          array("from" => "+16503000220",  // << FROM
                                "body" => $msg             // << MESSAGE
                                )
                        );
    $txt_sids[] = $message->sid;
  }
  catch (Exception $e) {
    header("Location: /notes/?sent=$dst");
    exit();
  }
}

header("Location: /notes/?sent=true");
exit();

?>
