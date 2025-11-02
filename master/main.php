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
$MSGFILE = 'messages.txt';
$DSTFILE = '../members.csv';
$TWILIO_SID = "";
$TWILIO_TOKEN = "";
$PREFACE = "[HEADER]";

// Check and get required parameters.
if ( !isset($_POST['dst']) ) {
    header("Location: /notes/master/?sent=params");
    exit();
}

// Process an automated send.
$automated = FALSE;
if ($_POST['dst'] == "all") {
    $automated = TRUE;
    
    // Extract destinations.
    if (($h = fopen("{$DSTFILE}", "r")) !== FALSE) 
    {
      $i = 0;
      while (($data = fgetcsv($h, 1000, ",")) !== FALSE) 
      {
        $members[$i] = $data;
        $i++;
      }
      fclose($h);
    }
    $dsts = [];
    foreach ($members as $member) {
        array_push($dsts, $member[1]);
    }
    
    // Extract messages.
    $fhndl = fopen($MSGFILE, "r");
    $tmp = fread($fhndl,filesize($MSGFILE));
    fclose($fhndl);
    $msgs = explode("\n", $tmp);
}

// Process non-automated send.
else {

    $dsts = $_POST['dst'];
    
    // Extract messages.
    $tmp = $_POST['msgs'];
    $msgs = explode("\n", $tmp);
    if (isset($_POST['opt'])) {
      unlink($MSGFILE);
      $fhndl = fopen($MSGFILE,"c");
      fwrite($fhndl, "\xEF\xBB\xBF" . $tmp);
      fclose($fhndl);
    }   
}


// Create new Twilio connection.
require_once '../Twilio/autoload.php';
use Twilio\Rest\Client;
$twilio = new Client($TWILIO_SID, $TWILIO_TOKEN);

// Send messages.
foreach ($dsts as $dst)
{
  try {
    $to = "$dst";
    $msg = $msgs[random_int(0, count($msgs) - 1)];
    $body = $PREFACE . "\n" .$msg;
    $message = $twilio->messages
                        ->create($to,                      // << TO
                          array("from" => "+16503000220",  // << FROM
                                "body" => $body            // << MESSAGE
                                )
                        );
    $sid = $message->sid;
    if ($automated) {
        //print("+" . $dst . " [" . $sid . "] => " . $msg . "\n"); //todo
    }
  }
  catch (Exception $e) {
        if ($automated) {
            print("Error sending message to +". $dst . ".\n");
        }
        else {
            header("Location: /notes/master/?sent=$dst");
            exit();
      }
  }
}

if ($automated) {
    print("Message send complete.");
}
else {
    header("Location: /notes/?sent=true");
}
exit();

?>
