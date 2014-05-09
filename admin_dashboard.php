<?php
require "_modules/settings.php";
require_once "_modules/productmanager.php";
require_once "_modules/account.php";

session_start();

if (isset($_SESSION['user'])) {
	if ($_SESSION['isAdmin'] !== true) {
		header("Location: login.php");
		exit;	
	}	
}else{
	header("Location: login.php");
	exit;	
}


$manager = new ProductManager();
$account = new Account();
$msg = "";

if (isset($_GET['UserActivate']))
	$account->forceActivate($_GET['UserActivate'], true);
if (isset($_GET['UserDeactivate']))
	$account->forceActivate($_GET['UserDeactivate'], false);
if (isset($_GET['UserBan']))
	$account->forceBan($_GET['UserBan'], true);
if (isset($_GET['UserUnban']))
	$account->forceBan($_GET['UserUnban'], false);
if (isset($_GET['UserDelete']))
	$account->forceDelete($_GET['UserDelete']);

if (isset($_GET['make_admin'])) {
	//Chack if user is super admin
	if ($_SESSION['user'] == "admin@firstserve.co.za") {
		$account->makeAdmin($_GET['make_admin']);
	}else{
		header("Location: error.php?fatalError=Only+super+admin+account+can+perform+this+action");
		exit;	
	}
}
if (isset($_GET['revoke_admin'])) {
	//Chack if user is super admin
	//deny super admin from revoking own admin rights
	if ($_SESSION['user'] == "admin@firstserve.co.za") {
		if ($_GET['revoke_admin'] != "admin@firstserve.co.za" || $_GET['revoke_admin']!=$_SESSION['user']) {
			$account->revokeAdmin($_GET['revoke_admin']);
		}else{
			header("Location: error.php?fatalError=Cannot+revoke+own+administration+previledges");
			exit;
		}
	}else{
		header("Location: error.php?fatalError=Only+super+admin+account+can+perform+this+action");
		exit;	
	}
	
}
if (isset($_GET['UserUnban']) || isset($_GET['UserDeactivate']) || isset($_GET['UserActivate']) || isset($_GET['UserBan']) || isset($_GET['UserDelete']) || isset($_GET['make_admin']) || isset($_GET['make_admin'])){
	header("Location: admin_dashboard.php");
	exit;
}
	
if (isset($_POST['action'])) {
	if ($_POST['action'] == "addProduct") {
		
		if (empty($_POST['name']))
			$msg = "Book name cannot be empty";
		if (empty($_POST['price']))
			$msg = "Book price cannot be empty";
		if (empty($_POST['isbn']))
			$msg = "Book isbn cannot be empty";
		if (empty($_POST['author']))
			$msg = "Book author cannot be empty";
		if (empty($_POST['theme']))
			$msg = "Book theme cannot be empty";
		if (empty($_POST['release']))
			$msg = "Book release cannot be empty";
		if ($_POST['category'] == 0)
			$msg = "You cannot add a book into no category<br/>Add atleast one category first and then add a book";
			
		if ($msg == "") {
			
			$icon = rand(100,10000) . basename($_FILES['icon']['name']);
			$bookfile = rand(100,10000) . basename($_FILES['book']['name']);
			
			if ($manager->addProduct($_POST['name'], $_POST['category'], $_POST['price'], $_POST['isbn'], $_POST['author'], $_POST['theme'], $bookfile, $icon, $_POST['release'])) {
				//save uploaded icon image
				$uploaddir = 'images/prod_icons/';
				$uploadfile = $uploaddir . $icon;
				
				if (!move_uploaded_file($_FILES['icon']['tmp_name'], $uploadfile)) {
					$msg =  "Failed to upload Icon Image";
				}
				//save uploaded book file
				$uploaddir = '_data/books/';
				$uploadfile = $uploaddir . $bookfile;
				
				if (!move_uploaded_file($_FILES['book']['tmp_name'], $uploadfile)) {
					$msg =  "Failed to upload Book Image";
				}
				$msg = "Book added successfully";
			}else{
				$msg = "Failed to add Book";
			}
		}
	}
	if ($_POST['action'] == "deleteCategory") {
		if ($manager->deleteCategory($_POST['category']))
			$msg = "Category deleted";
		else
			$msg = "Failed to delete category";
	}
	if ($_POST['action'] == "addCategory") {
		if ($manager->addCategory($_POST['name'], $_POST['desc']))
			$msg = "Category added";
		else
			$msg = "Failed to add category";
	}
}

if (isset($_POST['mail'])) {
	if (empty($_POST['subject']))
		$msg = "Subject cannot be empty";
	else if (empty($_POST['body']))
		$msg = "E-mail Body cannot be empty";
	else{
		if ($account->mailCustomers($_POST['subject'], $_POST['body']))
			$msg = "Emails sent successfully";
		else
			$msg = "Failed to send emails";
	}
}


$pageTitle = "Admin Dashboard";
include "_comp/header.php";
?>
 
<!-- Page Content
================================================== -->
<SECTION id=typography>
	<DIV class=page-header>

<p><?php if ($msg != "") { ?><div class="alert alert-block alert-warning fade in">
<button type="button" class="close" data-dismiss="alert">×</button>
<p><?php echo $msg; ?></p></div><?php } ?></p>

<H1>Administator <?php echo $_SESSION['user']; ?></H1></DIV>
<table class="table table-bordered">
<tr><td>

<ul id="myTab" class="nav nav-tabs">
	<li class="active"><a href="#customer" data-toggle="tab">Customers</a></li>
    <li class=""><a href="#product" data-toggle="tab">Products</a></li>
    <li class=""><a href="#admins" data-toggle="tab">admins</a></li>
	<li class=""><a href="#massmail" data-toggle="tab">Mail list</a></li>
    <li class=""><a href="#invoices" data-toggle="tab">Invoices and Orders</a></li>
</ul>

<div id="myTabContent" class="tab-content">
<!-- =============customer Tab=============-->
<div class="tab-pane fade active in" id="customer">
<table class="table">
<tr>
<td width="80%">
<table class="table table-bordered">
<?php
$page = 1;
if (isset($_GET['custpage']))
	$page = $_GET['custpage'];
$customers = $account->listCustomers($page);
foreach($customers as $customer) { ?>
<tr>
<td><img src="images/user.png" alt="User Icon"  /></td>
<td>
<table class="table table-condensed">
<tr><td colspan="3"><h3><?php echo $customer->getFullname(); ?></h3></td></tr>
<tr><td>Country: <?php echo $customer->getCountry(); ?></td><td>Username: <?php echo $customer->getUsername(); ?></td><td>Cell Number: <?php echo $customer->getCellnum(); ?></td></tr>
<tr><td>Reg. Date: <?php echo $customer->getDateCreated(); ?></td><td>Last Login: <?php echo $customer->getDateLastLogin(); ?></td><td>Contact E-mail: <?php echo $customer->getEmail(); ?></td></tr>
<tr><td colspan="3">postal Address: <?php echo $customer->getPostalAddr(); ?></td></tr>
<tr class="info">
<td><a href="admin_dashboard.php?<?php if ($customer->getIsActive()) echo "UserDeactivate="; else echo "UserActivate="; echo $customer->getUsername(); ?>" class="btn"><?php if ($customer->getIsActive()) echo "Deactivate Customer"; else echo "Activate Customer"; ?></a>
<a href="admin_dashboard.php?<?php echo "make_admin=". $customer->getUsername(); ?>" class="btn"><?php echo "Make Admin"; ?></a>
</td>
<td><a href="admin_dashboard.php?<?php if ($customer->getIsBan()) echo "UserUnban="; else echo "UserBan="; echo $customer->getUsername(); ?>" class="btn"><?php if ($customer->getIsBan()) echo "Unban Customer"; else echo "Ban Customer"; ?></a></td>
<td><a href="admin_dashboard.php?UserDelete=<?php echo $customer->getUsername(); ?>" class="btn btn-danger">Remove Customer</a></td></tr>
</table>
</td>
</tr>
<?php } ?>
</table>
</td>
<td width="20%">Total Customers: <?php echo $account->countCustomers(); ?></td>
</tr>
</table>
</div>
<!-- =============product Tab=============-->
<div class="tab-pane fade" id="product">

<a href="#myModal" role="button" class="btn btn-success" data-toggle="modal">Add a new Book</a>
<hr/>
<form action="admin_dashboard.php" method="post">
	<label for="delcategory">Select a category to Delete</label>
    <input type="hidden" name="action" value="deleteCategory" />
    <select id="delcategory" name="category" >
    	 <?php
			$categories = $manager->getCategoryList();
			foreach($categories as $category) {
				echo '<option value="'.$category->getId().'">'.$category->getName().'</option>';	
			}
			if (count($categories) == 0)
				echo '<option value="0">No Category</option>';
		?>
    </select>
    <script type="text/javascript">
    	function displayEdit() {
			cat = document.getElementById("delcategory").value;
			myWindow = window.open(("apps/editCategory.php?id="+cat) ,"Edit_Category","height=300,width=350");
			if (window.focus()) {myWindow.focus()}		
			return false;
		}
		
		
    </script>
    <button type="submit" class="btn btn-danger">Delete Category</button> <button type="button" class="btn btn-primary" onclick="javascript:displayEdit()">Edit Selected Category</button>  <br/>
    <p class="text-error" style="text-decoration:blink">Warning: Deleting a category will also delete all the products added under it!</p>
</form>
<hr/>
<form action="admin_dashboard.php" method="post">
	<input type="hidden" name="action" value="addCategory" />
    <label for="addcategory">Please enter category name</label>
    <input id="addcategory" type="text" name="name" />
    <label for="descCat">Please enter category description</label>
    <textarea id="descCat" name="desc" rows="3"></textarea>
  	<br/>
    <button type="submit" class="btn btn-primary">Add Category</button>
</form>
<hr/>

<p>To edit and delete books please use the <a href="catalogue.php">catalogue</a> and <a href="product.php">product</a> page</p>

<form action="admin_dashboard.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="MAX_FILE_SIZE" value="700000" />
<input type="hidden" name="action" value="addProduct" />
<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
<h3 id="myModalLabel">New Book</h3>
</div>
<div class="modal-body">

<table class="table">
	<tr>
    	<td>Name: </td>
        <td><input name="name" type="text" /></td>
    </tr>
    <tr>
    	<td>Price: </td>
        <td><input name="price" type="text" /></td>
    </tr>
    <tr>
    	<td>ISBN: </td>
        <td><input name="isbn" type="text" /></td>
    </tr>
    <tr>
    	<td>Author: </td>
        <td><input name="author" type="text" /></td>
    </tr>
    <tr>
    	<td>Theme: </td>
        <td><input name="theme" type="text" /></td>
    </tr>
    <tr>
    	<td>Release: </td>
        <td><input name="release" type="text" /></td>
    </tr>
    <tr>
    	<td>Category: </td>
        <td><select name="category">
        <?php
			$categories = $manager->getCategoryList();
			foreach($categories as $category) {
				echo '<option value="'.$category->getId().'">'.$category->getName().'</option>';	
			}
			if (count($categories) == 0)
				echo '<option value="0">No Category</option>';
		?>
        </select></td>
    </tr>
    <tr>
    	<td>Book File: </td>
        <td><input type="file" name="book" /></td>
    </tr>
    <tr>
    	<td>Book Icon: </td>
        <td><input type="file" name="icon" /></td>
    </tr>
</table>

</div>
<div class="modal-footer">
<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
<button class="btn btn-primary" type="submit">Save changes</button>
</div>
</div>
</form>

</div>
<!-- =============admins Tab=============-->
<div class="tab-pane fade" id="admins">
<p>
<table class="table">
<tr>
<td width="80%">
<table class="table table-bordered">
<?php
$page = 1;
if (isset($_GET['custpage']))
	$page = $_GET['custpage'];
$customers = $account->listAdmins($page);
foreach($customers as $customer) { ?>
<tr>
<td><img src="images/user.png" alt="User Icon"  /></td>
<td>
<table class="table table-condensed">
<tr><td colspan="3"><h3><?php echo $customer->getFullname(); if ($customer->getUsername()=="admin@firstserve.co.za")echo" (Super admin)";  ?></h3></td></tr>
<tr><td>Country: <?php echo $customer->getCountry(); ?></td><td>Username: <?php echo $customer->getUsername(); ?></td><td>Cell Number: <?php echo $customer->getCellnum(); ?></td></tr>
<tr><td>Reg. Date: <?php echo $customer->getDateCreated(); ?></td><td>Last Login: <?php echo $customer->getDateLastLogin(); ?></td><td>Contact E-mail: <?php echo $customer->getEmail(); ?></td></tr>
<tr><td colspan="3">postal Address: <?php echo $customer->getPostalAddr(); ?></td></tr>
<tr class="info">
<td><a href="admin_dashboard.php?<?php echo "revoke_admin="; echo $customer->getUsername(); ?>" class="btn"><?php echo "Revoke Admin"; ?></a></td>
<td></td>
<td><a href="admin_dashboard.php?UserDelete=<?php echo $customer->getUsername(); ?>" class="btn btn-danger">Remove Administrator</a></td></tr>
</table>
</td>
</tr>
<?php } ?>
</table>
</td>
<td width="20%">Total Customers: <?php echo $account->countAdmins(); ?></td>
</tr>
</table>
</p>
</div>
<!-- =============mass mail Tab=============-->
<div class="tab-pane fade" id="massmail">
<p>
<form method="post" action="admin_dashboard.php" class="form-horizontal">
<input type="hidden" name="mail" vale="all" />
<div class="control-group">
    <label class="control-label" for="subject">Mail Subject</label>
    <div class="controls">
      <input type="text" id="subject" name="subject" value="<?php if (isset($_POST['mail'])) echo $_POST['subject']; ?>" />
    </div>
  </div>
<div class="control-group">
    <label class="control-label" for="body">Mail Body</label>
    <div class="controls">
      <textarea rows="5" class="input-xlarge" name="body" id="body">
      	<?php if (isset($_POST['mail'])) echo $_POST['body']; ?>	  
	  </textarea><br/>
      <p><em>Accepts HTML tags</em></p>
    </div>
  </div>
<div class="control-group">
    <div class="controls">
      <br />
		<button class="btn btn-primary" name="submit" type="submit">Send mail to all customers</button>
    </div>
  </div>

</form>
</p>
</div>
<!-- =============invoices Tab=============-->
<div class="tab-pane fade" id="invoices">
<p>
sd
</p>
</div>

      </div>  
</td></tr></table>
<p></p>
</SECTION>


<?php
include "_comp/footer.php";
?>