<?php
//error_reporting(E_ALL);										//uncomment this to show more errors

// Merchant Configuration
$GatewaySettings['username']				= "username"; 	// 	Gateway Username
$GatewaySettings['tran_key']				= "transkey"; 	//	Gateway Transaction Key - obtain it from the Gateway web interface

$GatewaySettings['AllowMC'] 				= TRUE;
$GatewaySettings['AllowVisa']				= TRUE;
$GatewaySettings['AllowAmex']				= TRUE;
$GatewaySettings['AllowDiscover']			= TRUE;
$GatewaySettings['AllowJCB']				= TRUE;
$GatewaySettings['AllowDiners']				= TRUE;
$GatewaySettings['AllowCarteBlanche']		= TRUE;
$GatewaySettings['AllowEnRoute']			= TRUE;
$GatewaySettings['AllowEChecks']			= TRUE;
$GatewaySettings['AllowInternational']		= TRUE;

// Gateway Configuration 
$GatewaySettings['version']					= "3.1"; 
$GatewaySettings['test_mode']				= "TRUE";		// "TRUE"/"FALSE"
$GatewaySettings['AllowTestOverride']		=  TRUE; 		// allow transaction post data to set the test_mode value
$GatewaySettings['delim_data']				=  "TRUE"; 		// delimit the resopnse; configure this through the gateway settings interface
$GatewaySettings['delim_char']				=  "|"; 		// set the delimiter you've configured through the gateway settings interface
$GatewaySettings['encap_char']				=  ""; 		// set the encapsulator you've configured through the gateway settings interface
$GatewaySettings['relay_response']			=  "FALSE"; 	// set the encapsulator you've configured through the gateway settings interface

// Email Settings
$GatewaySettings['email_customer']			= "TRUE";	// "TRUE"/"FALSE"
$GatewaySettings['merchant_email']			= "";			// Your Email address to send copy of receipts to, if you want them.
//$GatewaySettings['header_email_receipt']	= "Thanks for your purchase!";
//$GatewaySettings['footer_email_receipt']	= "Please come again!";

// Environment Configuration

//$GatewaySettings['url']				= "https://secure.authorize.net/gateway/transact.dll"; // authorize.net url (post)
$GatewaySettings['url']			 		= "https://gateway.merchantplus.com/cgi-bin/PAWebClient.cgi"; // NaviGate Url

// If the curl extension is not loaded in your php setup, you can use the curl command line tool.
// If you do not have the curl command line tool and you want to use it instead of adding the curl
// 	PHP extension, you can download it from: http://curl.haxx.se/download.html
//
$GatewaySettings['curl_location']		= "/usr/bin/curl";  

$GatewaySettings['PaymentApprovedPage']		= 	"thankyou.php"; 	// Approval Page
$GatewaySettings['PaymentDeniedPage']		=	"failed.php";     	// Failure Page

// Security 
$GatewaySettings['MD5Hash']	=	""; 

?>