<?php
require "_modules/settings.php";
require_once "_modules/productmanager.php";

$manager = new ProductManager();

session_start();
if (!isset($_SESSION['user'])) {
	header("Location: login.php");
	exit;	
}

if (isset($_GET['dotransact'])) {
	//create an transaction and download record
	if (!$manager->shipItems()){
		//header("Location: error.php?fatalError=transaction+failed+due+to+internal+error");
		exit;
	}
		

	header("Location: thankyou.php?done");
	exit;	
}

if (!isset($_GET['done'])){
	header("Location: error.php?fatalError=You+cannot+access+this+page+this+way");
	exit;
}

$pageTitle = "Transaction Successful";
include "_comp/header.php";

?>

<!-- Page Content
================================================== -->
<br>
<table width="580" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#003366">
  <tr> 
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><table width="580" border="0" align="center" cellpadding="5" cellspacing="0">
        <tr bgcolor="#F5F5F5" class="text"> 
          <td><p align="center">&nbsp;</p></td>
        </tr>
        <tr bgcolor="#FFFFFF" class="text"> 
          <td><table border="0" cellspacing="0" cellpadding="13">
              <tr> 
                <td><p class="text">Congratulations on your purchase! We really appreciate that you trusted us enough to make this purchase,
				 and we'll make sure everything goes smoothly from here.</p>
                  <p class="text"> The First Serve Company is committed to bringing you the very best products for your money.
				  	We're dedicated to being the greatest First Serve Company on the entire Internet.
                    <a href="http://www.google.com/" class="textlinks">We challenge you to find a better store!</a>.</p>
                  <p class="text"> You should receive a receipt in your email detailing this transaction.
				  
				  If you have any further questions, contact us at <a href="mailto: admin@firstserve.co.za" class="textlinks">admin@firstserve.co.za</a>.</p>
                  </td>
              </tr>
            </table>
            <p>&nbsp; </p></td>
        </tr>
      </table></td>
  </tr>
</table>
</SECTION>

<?php
include "_comp/footer.php";
?>