#!/usr/local/bin/php
<?php
	//send all headers at once 
	ob_start();
	//name session
	session_name('finalProject'); 
	//start a session
	session_start();
	//store current page in $_SESSION
	$_SESSION['page']=$_SERVER['PHP_SELF']; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Flora's Flowers: Arrangements</title>
	<meta charset="UTF-8"/>
	<!--link to style sheets-->
	<link type="text/css" rel="stylesheet" href="heading.css"/>
	<link type="text/css" rel="stylesheet" href="productLanding.css"/>
</head>
<body>
	<header>
		<!--Title-->
		<h1 id="title">Flora's Flowers</h1>

		<!--logo-->
		<a href="index.php" title ="homepage"> <img alt="logo" id ="logo" src="logo.png" width="100"/></a>
		<!--login -->
		<?php if($_SESSION['loggedin']){//if logged in ?>
			<!--Logged in display-->
			<section class = "login">
				<a title="shopping cart" href="shoppingCart.php"><img src="shoppingCart.png" width = "50" alt="shopping cart"></a>
				<p>
					Welcome <?php $username= $_SESSION['username']; 
					echo "$username";?>
				</p>
				<p>
					<!--redirects to logout.php and logs user out of account--> 
					<a href = "logout.php" title = "logout">Logout</a>
				</p>
			</section>
		<?php }else{// if not logged in ?>
			<!--login --> 
			<form class = "login" method = "post" action= "login.php" >
				<!--user name field-->
				<label for="username">Username: </label>
				<input type="text" id = "username" name="name" required/><br/>

				<!--password field-->
				<label for="pword">Password: </label>
				<input type="password" id="pword" name="pword" required/><br/>
				
				<!--login button-->
				<input type ="submit" value ="Login" name="login"/>
			
				<?php if($_SESSION['loginError'] === true){//if tried, unsuccessfully to log in ?>
					<p>
						Username or password was incorrect.<br/>Please Try Again. 
					</p>
				<?php } ?>
				
				<!--link to create new account-->
				<br/><a href="newAccount.php">Create Account</a>
			</form>
			
		<?php }?>

		<!--subtitle-->
		<h2 id="subtitle">Flowers for Every Occasion</h2>

		<!--navigation menu-->
		<nav>
			<!--unordered list-->
			<ul>
				<li><a href="index.php" title="Home Page">Home</a></li>
				<li><a href="about.php" title="About Flora's">About</a></li>
				<li id="dropdown">
					<a href="products.php" title="Flower Arrangements" id="products">Flower Arrangements</a>
					<div id = "dropdown_content">
						<a href="productsWeddings.php">Weddings</a>
						<a href="productsCenterpieces.php">Centerpieces</a>
						<a href="productsSympathy.php">Sympathy</a>
						<a href="productsSeasonal.php">Seasonal</a>
						<a href="productsSpecial-Someone.php">Special Someone</a>
						<a href="productsEveryday.php">Everyday</a>
					</div>
				</li>
				<li><a href="deals.php" title="Specials">Special Offers</a>				</li>
				<li><a href="testimonials.php" title="Testimonials">Testimonials</a></li>
			</ul>
		</nav>
	</header>

	<main>
		<!--landing page to view diff categories of flower arrangments-->
		<section>
			<h3>
				What's the Occasion?
			</h3>
			<p>
				Click an Icon Below to Get Started:
			</p>
			<?php 
				$categories = array(array('W','Weddings',), array('C','Centerpieces'), array('F','Sympathy'), array('S','Seasonal'),array( 'V','Special Someone'), array('E','Everyday'));
				foreach($categories as $char){ //for every category of array
					//store path to related image
					$imageFile = 'products/'.$char[0].'0003.jpg'; ?>

					<!--make image a link to category product page-->
					<a title=<?php echo "$char[1]";?> href=<?php echo "products$char[1].php";?>>
						<figure class = "container">
							<img src =<?php echo "$imageFile"; ?> width ="200" height ="200" alt=<?php echo "$char[1]";?>>
							<figcaption class = "overlay">
								<?php echo "$char[1]";?>
							</figcaption>
						</figure>
					</a>
			<?php }?>
		</section>
		
		<!--custom designs section--> 
		<section>
			<h3>
				Can't find what you're looking for?
			</h3>
			<p> 
				 Let's collaborate to design a custom arrangement! <a href="about.php">Send us a message</a> with your concept and we will send you a quote and get to work!
			</p>
		</section>
		
	</main>
	<footer>
		<hr/>
		<!--copyright info-->
		<small>Flora's Flowers &copy; 2019</small>
	</footer>
</body>
</html>
