<?php
/******************************************************************************
#                     PHP BrainTree Payment Terminal v1.0
#******************************************************************************
#      Author:     Convergine.com
#      Email:      info@convergine.com
#      Website:    http://www.convergine.com
#
#
#      Version:    1.0
#      Copyright:  (c) 2013 - Convergine.com
#
#*******************************************************************************/

/*The following PHP extensions are required:
* curl
* dom
* hash
* openssl
* SimpleXML
* xmlwriter
 * */

session_start();
error_reporting(E_ALL ^ E_NOTICE);
require("functions.php"); 

//THIS IS TITLE ON PAGES
$title = "BrainTree Payment Terminal v1.0"; //site title

//THIS IS ADMIN EMAIL FOR NEW PAYMENT NOTIFICATIONS.
$admin_email = "dwpegues@gmail.com"; //this email is for notifications about new payments

//IF YOU NEED TO ADD MORE SERVICES JUST ADD THEM THE SAME WAY THEY APPEAR BELOW.
$services = array(
	array("Yearly Plan",(getObjectValue($new_plan_detail,'amount')*12),12),
	array("Monthly Plan",(getObjectValue($new_plan_detail,'amount')),1),
);

//NOW, IF YOU WANT TO ACTIVATE THE DROPDOWN WITH SERVICES ON THE TERMINAL
//ITSELF, CHANGE BELOW VARIABLE TO TRUE;			
$show_services = true;

// set  to   RECUR  - for recurring payments, ONETIME - for onetime payments
$payment_mode = "ONETIME";

/* Note: Plans are created in the Control Panel. You cannot create or update them through the API.
 * This script will display all of your plans when payment mode will be set to RECUR
 * 
 *    You can 'filter' the plans that you want to be displayed, 
 * 	  if let's say, you have many plans for many services and you want
 * 	  to charge with this only specific plans,
 * 
 *    by adding their id's (you can easily find them in your BrainTree panel)
 *    below, in this format : $recur_services = array('id_plan_1_that_I_want_to_display','id_plan_2')
 * 		
 *    If you choose to leave this empty as it is, all of your plans will be displayed
 * */
$recur_services = array();

// IF YOU'RE GOING LIVE FOLLOWING VARIABLE SHOULD BE SWITCH TO true
$liveMode = (get_option('payment_mode') == 'live') ? true : false;
/* https redirection */
$redirect_non_https = $liveMode;
$paypal_merchant_email = get_option('paypal_email_address');

if(!$liveMode){
	
	//TEST MODE   
	define('MERCHANT_ID',get_option('test_braintree_merchant_id'));
	define('PUBLIC_KEY',get_option('test_braintree_public_key'));
	define('PRIVATE_KEY',get_option('test_braintree_private_key'));
	define('TEST_MODE', 'sandbox'); //do not change this
	$paypal_merchant_email = get_option('test_paypal_email_address');
} else {
	
	//LIVE MODE
	define('MERCHANT_ID',get_option('braintree_merchant_id'));
	define('PUBLIC_KEY',get_option('braintree_public_key'));
	define('PRIVATE_KEY',get_option('braintree_private_key'));
	define('TEST_MODE', 'production');//do not change this
	$paypal_merchant_email = get_option('paypal_email_address');
}

/*******************************************************************************************************
    PAYPAL EXPRESS CHECKOUT CONFIGURATION VARIABLES
********************************************************************************************************/
$enable_paypal 				= true; //shows/hides paypal payment option from payment form.
$paypal_success_url 		= base_url()."user/success/";
$paypal_cancel_url 			= base_url()."user/cancle/";
$paypal_ipn_listener_url 	=  base_url()."user/ipn/" ; 
$paypal_custom_variable 	= "some_var";
$paypal_currency 			= "USD";

if($liveMode){
	$sandbox = false;
} else {
	$sandbox = true;
}

//DO NOT CHANGE ANYTHING BELOW THIS LINE, UNLESS SURE OF COURSE
define("PAYMENT_MODE",$payment_mode);

//DO NOT CHANGE ANYTHING BELOW THIS LINE, UNLESS SURE OF COURSE
if($redirect_non_https){
    if ($_SERVER['SERVER_PORT']!=443) {
        // $sslport=443; //whatever your ssl port is
        // $url = "https://". $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
		// header("Location: $url");
        // exit();
    }
}

if(!$sandbox){
    define("PAYPAL_URL_STD","https://www.paypal.com/cgi-bin/webscr");
} else {
    define("PAYPAL_URL_STD","https://www.sandbox.paypal.com/cgi-bin/webscr");
}

require 'braintree/lib/Braintree.php';

/* Please do not edit here. START */
Braintree_Configuration::environment(TEST_MODE);
Braintree_Configuration::merchantId(MERCHANT_ID);
Braintree_Configuration::publicKey(PUBLIC_KEY);
Braintree_Configuration::privateKey(PRIVATE_KEY);

$plans = Braintree_Plan::all();
#die(print_r($plans));

if(count($recur_services) < 1){
	
	/* Get all plans, build $recur_services array */
	foreach($plans as $plan => $details){
		//plan id  |  service name   |   price  to charge   | Billing Frequency (daily not supported) | Trial Duration Unit | trial period | Trial amount | Trial Duration Unit
		$allDiscounts = 0;foreach($details->discounts as $possibleDiscount=>$discount){$dsc = $discount->amount;$dsc = str_replace(',', '', $allDiscounts);$allDiscounts += $dsc;}
		$trialTime = $details->trialDuration;
		$price = $details->price;
		$price = str_replace(',', '', $price);
		
		if(strlen($trialTime) < 1){$trialTime = 0;}
		$recur_services[$details->id] = array($details->name,$price,$details->billingFrequency,$details->trialDurationUnit,$trialTime,$allDiscounts);
	}
}else{
	
	/* Filter plans by $recur_services array, build plans array, rebuild $recur_services */
	$recur_services_list = array();
	
	foreach($plans as $plan => $details){
		//plan id  |  service name   |   price  to charge   | Billing Frequency (daily not supported) | Trial Duration Unit | trial period | Trial amount | Trial Duration Unit
		$allDiscounts = 0;foreach($details->discounts as $possibleDiscount=>$discount){$dsc = $discount->amount;$dsc = str_replace(',', '', $allDiscounts);$allDiscounts += $dsc;}
		$trialTime = $details->trialDuration;
		$price = $details->price;
		$price = str_replace(',', '', $price);
		
		if(strlen($trialTime) < 1){$trialTime = 0;}
		if(in_array($details->id,$recur_services)) :
			$recur_services_list[$details->id] = array($details->name,$price,$details->billingFrequency,$details->trialDurationUnit,$trialTime,$allDiscounts);
		endif;
	}
	unset($recur_services);$recur_services = $recur_services_list;
}
#die(print_r($recur_services));
/* END */
?>