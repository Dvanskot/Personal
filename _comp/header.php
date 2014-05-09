<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML lang=en><HEAD><TITLE><?php echo SITE_NAME . " - " . $pageTitle; ?></TITLE>
<META charset=utf-8>
<META name=viewport content="width=device-width, initial-scale=1.0">
<META name=description content="">
<META name=author content=""><!-- Le styles --><LINK rel=stylesheet 
href="Bootstrap_files/bootstrap.css"><LINK rel=stylesheet 
href="Bootstrap_files/bootstrap-responsive.css"><LINK rel=stylesheet 
href="Bootstrap_files/docs.css"><LINK rel=stylesheet 
href="Bootstrap_files/prettify.css">

 <link href="css/design.css" rel="stylesheet">
<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements --><!--[if lt IE 9]>
<SCRIPT src="Base%20·%20Bootstrap_files/html5shiv.js"></SCRIPT>
<![endif]--><!-- Le fav and touch icons --><LINK 
rel=apple-touch-icon-precomposed 

href="assets/ico/apple-touch-icon-144-precomposed.png" sizes="144x144"><LINK 
rel=apple-touch-icon-precomposed 
href="assets/ico/apple-touch-icon-114-precomposed.png" sizes="114x114"><LINK 
rel=apple-touch-icon-precomposed 
href="assets/ico/apple-touch-icon-72-precomposed.png" sizes="72x72"><LINK 
rel=apple-touch-icon-precomposed 
href="assets/ico/apple-touch-icon-57-precomposed.png"><LINK rel="shortcut icon" 
href="assets/ico/favicon.png">


<META name=GENERATOR content="MSHTML 8.00.6001.19400"></HEAD>
<BODY data-target=".bs-docs-sidebar" data-spy="scroll">
<!-- Navbar
    ================================================== -->
<DIV class="navbar navbar-inverse navbar-fixed-top">
	<DIV class=navbar-inner>
        <DIV class=container>
            <BUTTON class="btn btn-navbar" type=button data-target=".nav-collapse" data-toggle="collapse">
                <SPAN class=icon-bar></SPAN><SPAN class=icon-bar></SPAN>
                <SPAN class=icon-bar></SPAN>
            </BUTTON>
            <A class=brand href="index.php">First-Serve</A>
    		<DIV class="nav-collapse collapse">
				<UL class=nav>
                    <LI class=active><A href="index.php">Home</A></LI>
                    <LI><A href="catalogue.php">Catalogue</A></LI>
                    <LI><A href="search.php">Search</A></LI>
                    <?php if (!isset($_SESSION['user'])) {
                    	echo'<LI><A href="login.php">Login</A></LI>
                    		<LI><A href="register.php">Register</A></LI>';
					}else{
						echo '<LI><A href="logout.php">Logout</A></LI>
							<LI><A href="settings.php">Settings</A></LI>';
					} ?>
                    <LI><A href="contact.php">Contact Us</A></LI>
                    <LI><A href="about.php">About Us</A></LI>
                    <LI><A href="cart.php">Shopping Cart (<?php
					if (!isset($_SESSION['user'])) {
					 if (isset($_SESSION['cart'])){require_once "_modules/productmanager.php";$manager = new ProductManager();echo $manager->tempCartTotalBooks();}else{ echo"0";}
					}else{
						$manager = new ProductManager();echo $manager->permCartTotalBooks();
					}
					 ?>)</A></LI>
                </UL>
            </DIV>
		</DIV>
	</DIV>
</DIV>
<!-- Subhead
================================================== -->
<HEADER id=overview class="jumbotron subhead">
	<DIV class=container>
		<H1>KGL Book Store </H1>
		<P class=lead>We serve Academics with dignity. </P>
    </DIV>
</HEADER>
<DIV class=container>
<!-- Docs nav
     ================================================== -->
	<DIV class=row>
<DIV class="span3 bs-docs-sidebar">
    		<UL class="nav nav-list bs-docs-sidenav">
              <LI><A href="catalogue.php"><I class=icon-chevron-right></I>All Catalogue (<?php require_once "_modules/productmanager.php";$manager = new ProductManager();echo $manager->countCategories();?>)</A></LI>
              <?php
			  	$categories = $manager->getRecentCategories();
				foreach($categories as $category) {
              echo '<LI><A href="catalogue.php?category='.$category->getId().'"><I class=icon-chevron-right></I>'.$category->getName().' ('.$manager->getTotalProductsPerCategory($category->getId()).')</A></LI>';
               } ?>
              <LI><A href="cart.php"><I class=icon-chevron-right></I>Shopping Cart (<?php
					if (!isset($_SESSION['user'])) {
					 if (isset($_SESSION['cart'])){require_once "_modules/productmanager.php";$manager = new ProductManager();echo $manager->tempCartTotalBooks();}else{ echo"0";}
					}else{
						$manager = new ProductManager();echo $manager->permCartTotalBooks();
					}
					 ?>
              </A></LI>
            </UL>
        </DIV>
