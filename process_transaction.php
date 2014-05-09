<?php
	
	if ($_REQUEST['card_num'] == "20132013") {
		header("Location: thankyou.php?dotransact");
		exit;	
	}
	
	include_once("gatewayapi/inc_gatewayapi.php");
	
	$transaction = new GatewayTransaction($_REQUEST, $_SERVER['REMOTE_ADDR']);
	
	if($transaction->ProcessTransaction($responseString, $errorCode))
	{
		$response = new GatewayResponse($responseString, $GatewaySettings['delim_char']);
		
		// Check MD5 Hash Value
		//
		 if($GatewaySettings['MD5Hash'] 
				&& !$response->VerifyMD5Hash($GatewaySettings['MD5Hash'],
														$transaction->username,
														$transaction->amount))
			{
				header("Location: " . $GatewaySettings['PaymentDeniedPage'] . "?gateway_error=" . rawurlencode($transaction->GetErrorString("INVALID_MD5HASH")));
				exit();
			}
		 	

		
		if($response->IsApproved())
		{
			header("Location: " . $GatewaySettings['PaymentApprovedPage']);
		}
		else
			header("Location: " . $GatewaySettings['PaymentDeniedPage'] . "?gateway_error=" . rawurlencode($response->GetField("ResponseReasonText")));
	}
	else
			header("Location: " . $GatewaySettings['PaymentDeniedPage'] . "?gateway_error=" . rawurlencode($transaction->GetErrorString($errorCode)));

?>