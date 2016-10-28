<?php

namespace AT_app\Http\Controllers;

use AT_app\Http\Controllers\AfricasTalkingGateway;

use Illuminate\Http\Request;

use AT_app\Http\Requests;


class UssdController extends Controller
{
    public function index(Request $request)
    {
        if(!empty($request)){

       $serviceCode = $request->get('serviceCode');
       $sessionId = $request->get('sessionId');
       $phoneNumber = $request->get('phoneNumber');
       $text = $request->get('text');
    
        // $serviceCode = $_POST["serviceCode"];
        // $sessionId = $_POST["sessionId"];
        // $phoneNumber = $_POST["phoneNumber"];
        // $text = $_POST["text"];

        if ( $text == "" ) {
        
        $response = "CON Welcome to".$serviceCode;
        $response .= " 1. Send me an inspiring message.\n";
        $response .= " 2. Please call and inspire me!\n";
        $response .= " 3. Is it your lucky day?\n";
        echo $response;

        }


        else {
            
            if ($text == '1') {

                $menu = "We will be shortly sending you an inspiring message:";

                $response = $menu;
                $this->getSms();

                $this->sendResponse($response, 2);
            }

        else {
            if ($text == '2') {
                $menu = "Please wait while we place your call.\n";

                $response = $menu;
                $this->getCall();

                $this->sendResponse($response, 2);
            }
        else {
            if ($text == '3') {
                $menu = "Today it seems you are lucky.\n";

                $response = $menu;
                $this->getAirtime();

                $this->sendResponse($response, 2);
            }
        } 
        }
        }
    }
    }


    public function getSms()
    {
            // if its part of the ussd replace the number with $phoneNumber    
            $recipients = "+254716XXXYYY";
            $message    = "Hi mr. In order to succeed, we must first believe that we can. 
            Read more at: http://www.brainyquote.com/quotes/topics/topic_motivational.html";
            // use your africastalking username and apikey
            $gateway    = new AfricasTalkingGateway($username, $apikey);
            $results = $gateway->sendMessage($recipients, $message);
        return $results;
    }

    public function getCall()
    {
        //use the africastalking assigned number
        $from = "+254711082XXX";
        //if you call it as a part of ussd replace the number with $phoneNumber
        $to   = "+254716YYXXZZ";
        // Create a new instance of our awesome gateway class
        $gateway = new AfricasTalkingGateway($username, $apikey);
        // Make the call
        $result=$gateway->call($from, $to);
        
        return $result;
        echo "If you can dream it, you can do it. To get this and more visit: http://www.brainyquote.com/quotes/topics/topic_motivational.html\n";
        // Our API will now contact your callback URL once the recipient answers the call!
    }
    public function getAirtime()
    {

        // here you can change $phonenumber with an actual phone number "+254708XXYYZZ"
        $recipients = array( array("phoneNumber"=>"".$phoneNumber."", "amount"=>"KES 10") );
        //JSON encode
        $recipientStringFormat = json_encode($recipients);
        //Create an instance of our awesome gateway class and pass your credentials
        $gateway = new AfricasTalkingGateway($username, $apikey);    
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
    }


    public function sendResponse($response, $type)
    {

        switch ($type) {
            case 1:
                $output = "CON ";
                break;
            case 2:
                $output = "END ";
                break;

        }


        $output .= $response;
        header('Content-type: text/plain');
        echo $output;
        exit;

    }
}
