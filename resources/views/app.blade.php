<?php
if(!empty($_POST)){
require_once('AfricasTalkingGateway.php');
 
//receiving the POST from AT
$sessionId=$_POST['sessionId'];
$serviceCode=$_POST['serviceCode'];
$phoneNumber=$_POST['phoneNumber'];
$text=$_POST['text'];
 
 if ( $text == "" ) {

  //Serve our services menu
  $response = "CON Karibu, Please choose a service.\n";
  $response .= " 1. Send me an inspiring message.\n";
  $response .= " 2. Please call and inspire me!\n";
  $response .= " 3. Is it your lucky day!\n"
}
 
 elseif($text == "1" ){
      $response = "END Please check your SMS inbox.\n";
 
       //send SMS with an inspirational quote
            $recipients = $phoneNumber;
            $message    = "Hi mr. In order to succeed, we must first believe that we can. 
Read more at: http://www.brainyquote.com/quotes/topics/topic_motivational.html";
            $gateway    = new AfricasTalkingGateway($username, $apikey);
            try { $results = $gateway->sendMessage($recipients, $message); }
            catch ( AfricasTalkingGatewayException $e ) {  echo "Encountered an error while sending: ".$e->getMessage(); }
             
    }elseif($text == "2" ){
          $response = "END Please wait while we place your call.\n";
 
        //Make a call
          // Specify your Africa's Talking phone number as 12-digit code 
          $from = "+254711082962";
          $to   = $phoneNumber;
          // Create a new instance of our awesome gateway class
          $gateway = new AfricasTalkingGateway($username, $apikey);
          // Any gateway errors will be captured by our custom Exception class below, 
          // so wrap the call in a try-catch block
          try
          {
          // Make the call
          $gateway->call($from, $to);
          echo "If you can dream it, you can do it. To get this and more visit
          : http://www.brainyquote.com/quotes/topics/topic_motivational.html\n";
          // Our API will now contact your callback URL once the recipient answers the call!
          }
          catch ( AfricasTalkingGatewayException $e )
          {
          echo "Encountered an error while making the call: ".$e->getMessage();
          }
    }elseif($userResponse == "3"){
     
    $response = "END Congratulations its your lucky day.\n";
    // Search DB and the Send Airtime
    $recipients = array( array("phoneNumber"=>"".$phoneNumber."", "amount"=>"KES 10") );
    //JSON encode
    $recipientStringFormat = json_encode($recipients);
    //Create an instance of our awesome gateway class and pass your credentials
    $gateway = new AfricasTalkingGateway($username, $apiKey);    
    // Thats it, hit send and we'll take care of the rest. Any errors will be captured in the Exception class as shown below
   try {
    $results = $gateway->sendAirtime($recipientStringFormat);
 
    //Store the service details 
    foreach($results as $result) {
          $status = $result->status;
          $amount = $result->amount;
          $airtimeNo = $result->phoneNumber;
          $discount = $result->discount;
          $requestId = $result->requestId;
          $error = $result->errorMessage;
    }
 
   }
   catch(AfricasTalkingGatewayException $e){
    echo $e->getMessage();
   }
   
  //Done
    }

}
?>
