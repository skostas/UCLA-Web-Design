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
	<meta charset="UTF-8"/>

	<!--webpage browser title-->
	<title>Flora's Flowers: About</title>

	<!--link style sheet to page-->
	<link type="text/css" rel = "stylesheet" href = "heading.css"/>
	<link type="text/css" rel="stylesheet" href="about.css"/>
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
			<form class = "login" method = 'post' action="login.php">
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
				<li><a href="about.php" title="About Flora's" id="about">About</a></li>
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
		<!--logo-->
		<img src="logo.png" alt="Flora's Flower Logo" width="500"
		id="big_logo"/>

		<!--Background section-->
		<section id="bio_section">
			<h3 class="section_title">How It All Started</h3>
			<p>
				Flora's Shop Opened in 1963, two years after Flora was born. Her parents, Bob and Diana, were the original store owners and they named their shop in honor of their daughter Flora. 
			</p>
			<p>
				Growing up, Flora spent most of her time helping out in the shop, and soon feel in love with creating unique flora arrangements. So it was more than fitting that upon retiring Bo and Diana handed the shop over to Flora. 
			</p>
			<p>
				Since becoming the owner of Flora's, Flora has expanded the business model of the shop. Flora's is now able to cater for larger events and to a more expansive region of customer. 
			</p>
		</section>
		
		<!--mission statement section-->
		<section id="mission_section">
			<h3 class="section_title">Mission Statement</h3>
			<p>
				Our goal is to provide our customers with quality flowers and floral arrangements at th best price. We will never, though, compromise the quality of our work or the materials we use for the sake of lower our margins. Ultimately, We hope to always exceed our customers expectations of us and our floral arrangments 
			</p>
		</section>

		<!--Contact Form Section-->
		<section id="contact_section">
			<form method = 'post' action=<?php echo $_SERVER['PHP_SELF'];?>>
				<fieldset>
					<h3 id="contact_title">Contact Us</h3>
					<p id="contact_info">
						Want to learn more about Flora's and our services?
						<br/>
						Call us at: 1-888-FLOWERS
						<br/>
						Or send us a message below:
					</p>
					<div class = "alignLeft">

						<!--input for customers email-->
						<label for ="userEmail">Email Address: </label>
						<input type ="email" name="userEmail" id="userEmail" required pattern=".+@{1}.+"/><br/>

						<!--input subject line--> 
						<label for ="subject">RE: </label>
						<input type = "text" name ="subject" id ="subject" required/><br/>  
					</div>
					<div class= "alignCenter">
						<!--comment box-->
						<textarea name="customer_inquiries" rows="8" cols="150"></textarea>
						<br/>

						<!--submit button-->
						<input type="submit" value="Send!" id="submit" name = "send"/>
					</div>
				</fieldset>
			</form>
		</section>
	</main>
	<footer>
		<hr/>
		<!--copyright info-->
		<small>Flora's Flowers &copy; 2019</small>
	</footer>

<?php
	if(isset($_POST['send'])){//when send button is pressed
		//store customers email
		$userEmail = $_POST['userEmail'];
		//store customers re line
		$subject= $_POST['subject'];
		//store customers message
		$message = $_POST['customer_inquiries'];
		//businesses email address
		$email = 'florasflowerbusiness@gmail.com'; 
		//send email to business with customer's message and contact into
		mail($email, "Customer Inquiry: $subject", $message."\nContact info: ".$userEmail);
		//alert box for confirmation 
		$alertBox = '<script langauge = "javascript"> alert("Message sent!");</script>';
		echo $alertBox; 
	}
		
?>

</body>
</html>
