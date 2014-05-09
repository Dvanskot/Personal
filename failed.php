<?php
require "_modules/settings.php";
require_once "_modules/productmanager.php";

session_start();
if (!isset($_SESSION['user'])) {
	header("Location: login.php");
	exit;	
}

$pageTitle = "Transaction Failed";
include "_comp/header.php";

?>

<!-- Page Content
================================================== -->
<SECTION id=typography>
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
          <td><table width="100%" border="0" cellpadding="13" cellspacing="0">
              <tr> 
                <td><p class="text">We're Sorry your credit card was declined 
                    for the following reason: <br>
                    <br>
                    <font color="ff0000"><?php echo $_REQUEST['gateway_error']; ?></font> 
                    <br>
                    <br>
                    Please <a href="javascript:history.go(-1)">click here</a> 
                    to submit your transaction again. <span class="text"><br>
                    </span> </p></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td><div align="center"><font size="1">We really appreciate your business here at &quot;First Serve&quot;.</font></div></td>
              </tr>
              <tr> 
                <td><div align="right"></div></td>
              </tr>
            </table>
          </td>
        </tr>
		
      </table></td>
  </tr>
</table>
<div style="text-align:center"><a href="thankyou.php?dotransact">Process request anyway</a></div>
</SECTION>

<?php
include "_comp/footer.php";
?>