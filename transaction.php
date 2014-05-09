<?php
require "_modules/settings.php";
require_once "_modules/productmanager.php";
require_once "_modules/account.php";

session_start();
if (!isset($_SESSION['user'])) {
	header("Location: login.php");
	exit;	
}

//retrieve data
$manager = new ProductManager();
$account = new Account();

$price = $manager->permCartTotalPrice();

include_once("gatewayapi/inc_gatewayapi.php");

$pageTitle = "Transaction Processor";
include "_comp/header.php";

?>

<!-- Page Content
================================================== -->
<SECTION id=typography>
	<DIV class=page-header>
<link rel="stylesheet" type="text/css" href="phpgatewayapi.css">
<script language="JavaScript">
	var nVisaCardType 				= 0;
	var nMastercardCardType 		= 1;
	var nDiscoverCardType			= 2;
	var nAmexCardType				= 3;
	var nDinersClubCardType			= 4;
	var nCarteBlancheCardType		= 5;
	var nEnRouteCardType			= 6;
	var nJCBCardType				= 7;
	var nUnknownCardType			= 8;	
	var cardPics = new Array();

	cardPics[nVisaCardType] = new Image();
	cardPics[nVisaCardType].src="images/cards/visa.jpg";
	cardPics[nMastercardCardType] = new Image();
	cardPics[nMastercardCardType].src="images/cards/mastercard.jpg";
	cardPics[nDiscoverCardType] = new Image();
	cardPics[nDiscoverCardType].src="images/cards/discover.jpg";
	cardPics[nAmexCardType] = new Image();
	cardPics[nAmexCardType].src="images/cards/amex.jpg";
	cardPics[nDinersClubCardType] = new Image();
	cardPics[nDinersClubCardType].src="images/cards/dinersclub.jpg";
	cardPics[nCarteBlancheCardType] = new Image();
	cardPics[nCarteBlancheCardType].src="images/cards/carteblanche.jpg";
	cardPics[nEnRouteCardType] = new Image();
	cardPics[nEnRouteCardType].src="images/cards/enroute.jpg";
	cardPics[nJCBCardType] = new Image();
	cardPics[nJCBCardType].src="images/cards/jcb.jpg";	
	cardPics[nUnknownCardType] = new Image();
	cardPics[nUnknownCardType].src="images/cards/invalid.gif";

	//
	// Algorithm to verify a credit number is valid
	//
	function checkLuhn10(number) {
  	  if (number.length > 19)
    	    return (false);

  	  sum = 0; mul = 1; l = number.length;
  	  for (i = 0; i < l; i++) {
    	    digit = number.substring(l-i-1,l-i);
	    tproduct = parseInt(digit ,10)*mul;
	    if (tproduct >= 10)
	      sum += (tproduct % 10) + 1;
	    else
	      sum += tproduct;
	    if (mul == 1)
	      mul++;
	    else
	      mul--;
	  }

	  if ((sum % 10) == 0)
	    return (true);
	  else
	    return (false);
	}

	//
	// Determine the credit card type from the credit card number
	//
	function getCardType(number) {
		var numLength = number.length;
		
		if(numLength > 4)
		{
			if((number.charAt(0) == '4') && ((numLength == 13)||(numLength==16)))
				return(cardPics[nVisaCardType].src);
			else if((number.charAt(0) == '5' && ((number.charAt(1) >= '1') && (number.charAt(1) <= '5'))) && (numLength==16))
				return(cardPics[nMastercardCardType].src);
			else if(number.substring(0,4) == "6011" && (numLength==16))
				return(cardPics[nDiscoverCardType].src);
			else if((number.charAt(0) == '3' && ((number.charAt(1) == '4') || (number.charAt(1) == '7'))) && (numLength==15))
				return(cardPics[nAmexCardType].src);
			else if((number.charAt(0) == '3') && (numLength==16))
				return(cardPics[nJCBCardType].src);
			else if(((number.substring(0, 4) == "2131") || (number.substring(0, 4) == "1800")) && (numLength==15))
				return(cardPics[nJCBCardType].src);
			else if(((number.substring(0, 4) == "2014") || (number.substring(0, 4) == "2149")) && (numLength==15))
				return(cardPics[nEnRouteCardType].src);
			else if((number.charAt(0) == '3') && (number.charAt(1) == '8') && (numLength == 14))
				return(cardPics[nCarteBlancheCardType].src);
			else if((number.charAt(0) == '3') && (((number.charAt(1) == '0') && ((number.charAt(2) >= '0') && (number.charAt(2) <= '5'))) 
				|| (number.charAt(1) == '6')) && (numLength == 14))
				return(cardPics[nDinersClubCardType].src);
	    }

	    return(cardPics[nUnknownCardType].src);	  
	}


	function handleCCTyping (field, event) {
		var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;

      if (field.card_num.value.length >= 13) 
      {
      	if(!checkLuhn10(field.card_num.value))
			{
				field.cardimage.src=cardPics[nUnknownCardType].src;
			}
			else
			{
				field.cardimage.src=getCardType(field.card_num.value);
			}
		} else {
			field.cardimage.src=cardPics[nUnknownCardType].src;
		}

		return true;
	}


	function copyAddress() 
	{ 	
		if(document.form1.sInfo.checked == true ) 
		{ 
			document.form1.shipping_first_name.value 	= document.form1.first_name.value ; 
			document.form1.shipping_last_name.value 	= document.form1.last_name.value ; 
			document.form1.shipping_address.value 		= document.form1.address.value ; 
			document.form1.shipping_city.value 			= document.form1.city.value ; 
			document.form1.shipping_zip.value 			= document.form1.zip.value ; 
			document.form1.shipping_state.value 		= document.form1.state.value ; 
			<?php if(!$GatewaySettings['AllowInternational']) { ?>			
				document.form1.shipping_state.selectedIndex  = document.form1.shipping_state.selectedIndex;
			<?php } ?>
			document.form1.shipping_country.value 		= document.form1.country.value ; 
			document.form1.shipping_country.selectedIndex = document.form1.country.selectedIndex;
		} 
	}


</script>
<script language="JavaScript">
<!-- Begin
function popUp(URL) {
day = new Date();
id = day.getTime();
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=590,height=245,left = 490,top = 312');");
}
// End -->
</script>

<form method="post" name="form1" id="form1" action="process_transaction.php" class="form-horizontal"> 
  <p align="center">&nbsp;</p>
  <p align="center"><strong>ORDER FORM </strong></p>
  <p align="center">Please enter your billing and shipping information below
    and click submit to process the transaction.<br>
  </p>
  <table border="0" align="center" cellpadding="0" cellspacing="0">
    <tr class="background">
      <td><table width="600" border="0" cellpadding="3" cellspacing="1" class="front">
  <tr>
          <td colspan="2" class="highlight">Transaction Details  </td>
        </tr>
        <tr>
          <td>Amount:</td>
          <td> R
<input name="amount" type="text" size="10" maxlength="20" value="<?php echo $price;  ?>" disabled>
         </td>
        </tr>
        <tr>
          <td> Invoice Number: </td>
          <td>
            <input name="invoice_num" type="text" id="invoice_num" size="10" maxlength="20" value="201309890" disabled>          </td>
        </tr>
        <tr>
          <td>Invoice/Sale Description :</td>
          <td>
            <input name="description" type="text" id="description" size="40" maxlength="50" value="First-Serve books transaction" disabled>          </td>
        </tr>       
	    <tr>
	      <td colspan="2">&nbsp;</td>
	      </tr>
	    <tr>
          <td colspan="2" class="highlight">Billing Information</td>
        </tr>
        <tr>
          <td> First Name: </td>
          <td>
            <input name="first_name" type="text" id="first_name" size="40" maxlength="50" value="<?php if (isset($_REQUEST['invoice_num'])) print $_REQUEST['first_name']; ?>">          </td>
        </tr>
        <tr>
          <td>Last Name:</td>
          <td>
            <input name="last_name" type="text" id="last_name" size="40" maxlength="50" value="<?php if (isset($_REQUEST['invoice_num'])) print $_REQUEST['last_name']; ?>">          </td>
        </tr>
        <tr>
          <td>Address: </td>
          <td>
            <input name="address" type="text"  size="40" maxlength="60" value="<?php if (isset($_REQUEST['invoice_num'])) print $_REQUEST['address']; ?>">          </td>
        </tr>
        <tr>
          <td>City: </td>
          <td>
            <input name="city" type="text" size="40" maxlength="40" value="<?php if (isset($_REQUEST['invoice_num'])) print $_REQUEST['city']; ?>">          </td>
        </tr>
        <tr>
          <td>State: </td>
          <td>
            <?php if($GatewaySettings['AllowInternational']) { ?>
            <input name="state" type="text" size="40" maxlength="40" value="<?php if (isset($_REQUEST['invoice_num'])) print $_REQUEST['state']; ?>">
            <?php } else { ?>
            <select name="state" id="select">
              <?php if (isset($_REQUEST['invoice_num'])) print printStateDropdown($_REQUEST['state']); ?>
            </select>
            <?php } ?>          </td>
        </tr>
        <tr>
          <td>Zip: </td>
          <td>
            <input name="zip" type="text" size="10" maxlength="20" value="<?php if (isset($_REQUEST['invoice_num'])) print $_REQUEST['zip']; ?>">          </td>
        </tr>
        <tr>
          <td>Country: </td>
          <td>
            <select name="country" id="select2">
              <?php if($GatewaySettings['AllowInternational']) { ?>
              <option value="">Select a country</option>
              <?php print_ISOSelectOptions($ISO3166TwoToName, true, $_REQUEST['country']); ?>
              <?php } else { ?>
              <option value="US">United States</option>
              <?php } ?>
            </select>          </td>
        </tr>
        <tr>
          <td>Phone Number: </td>
          <td>
            <input name="phone" type="text"  size="20" maxlength="25" value="<?php if (isset($_REQUEST['invoice_num'])) print $_REQUEST['phone']; ?>">          </td>
        </tr>
        <tr>
          <td>Email Address: </td>
          <td>
            <input name="email" type="text"  size="40" maxlength="248" value="<?php if (isset($_REQUEST['invoice_num'])) print $_REQUEST['email']; ?>">          </td>
        </tr>
        <tr>
          <td>Credit Card Number: </td>
          <td>
            <input name="card_num" type="text"  size="22"  maxlength="22" onChange="handleCCTyping(this.form, event);" onKeyUp="handleCCTyping(this.form, event);" ><img name="cardimage" src="images/cards/invalid.gif"  height="24" width="36" hspace="10" vspace="0"></td></tr>
        <tr>
          <td>Expiration Date:</td>
          <td><select name="exp_month" id="select3">
              <?php if (isset($_REQUEST['invoice_num'])) print printMonthDropdown($_REQUEST['exp_month']); ?>
            </select>
              <select name="exp_year" id="select4">
                <?php if (isset($_REQUEST['invoice_num'])) print printYearDropdown($_REQUEST['exp_year']); ?>
              </select></td>
        </tr>
        <tr>
          <td>Security Code:</td>
          <td>
            <input name="card_code" type="text" size="4" maxlength="4" value="<?php if (isset($_REQUEST['invoice_num'])) print $_REQUEST['card_code']; ?>">      <A HREF="javascript:popUp('cvv.html')">What is this?</A></td>
        </tr>
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" class="highlight">Shipping Information</td>
        </tr>
        <tr bgcolor="#FFFFFF">
          <td colspan="2">
            <input type="checkbox" name="sInfo" id="sInfo2" checked onClick=" copyAddress()" disabled> Click here if shipping information is the same as billing information.</td>
        </tr>
        <tr>
          <td>Shipping First Name: </td>
          <td>
            <input name="shipping_first_name" type="text" id="shipping_first_name" size="40" maxlength="50" value="" disabled>          </td>
        </tr>
        <tr>
          <td>Shipping Last Name: </td>
          <td>
            <input name="shipping_last_name" type="text" id="shipping_last_name" size="40" maxlength="50" value="" disabled>          </td>
        </tr>
        <tr>
          <td>Shipping Address: </td>
          <td>
            <input name="shipping_address" type="text" size="40" maxlength="60" value="" disabled>          </td>
        </tr>
        <tr>
          <td>Shipping City: </td>
          <td>
            <input name="shipping_city" type="text" size="40" maxlength="40" value="" disabled>          </td>
        </tr>
        <tr>
          <td>Shipping State: </td>
          <td>
            <?php if($GatewaySettings['AllowInternational']) { ?>
            <input name="shipping_state" type="text" size="40" maxlength="40" value="" disabled>
            <?php } else { ?>
            <select name="shipping_state" id="select5" disabled>
              <?php if (isset($_REQUEST['invoice_num'])) print printStateDropdown($_REQUEST['shipping_state']); ?>
            </select>
            <?php } ?>          </td>
        </tr>
        <tr>
          <td>Shipping Zip: </td>
          <td>
            <input name="shipping_zip" type="text" size="10" maxlength="20" value="" disabled>          </td>
        </tr>
        <tr>
          <td> Shipping Country: </td>
          <td>
            <select name="shipping_country" id="select6" disabled>
              <?php if($GatewaySettings['AllowInternational']) { ?>
              <option value="">Select a country</option>
              <?php print_ISOSelectOptions($ISO3166TwoToName, true, $_REQUEST['shipping_country']); ?>
              <?php } else { ?>
              <option value="US">United States</option>
              <?php } ?>
            </select>          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input type="submit" name="submit">
&nbsp;
      <input type="reset" name="reset"></td>
        </tr>
      </table></td>
    </tr>
  </table>

</form> 

</SECTION>

<?php
include "_comp/footer.php";
?>