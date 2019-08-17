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
	<title>Flora's Flowers: Testimonials</title>

	<!--link style sheet to page-->
	<link type = "text/css" rel ="stylesheet" href="heading.css"/>
	<link type="text/css" rel="stylesheet" href="testimonials.css"/>

	<!--link to js files-->
	<script src="testimonials.js" defer> </script>
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
				<li><a href="testimonials.php" id ="testimonials" title="Testimonials">Testimonials</a></li>
			</ul>
		</nav>
	</header>
	<main>
		<!--Previously written reviews-->
		<section id="read_review">
			<h3 class="section_title">Read What Our Customers Have to Say!</h3>
			<p id = "reviews">
			</p>
		</section>

		<!--form to write a new review-->
		<section id="write_review">
			<h3 class="section_title">Write a Review</h3>
			<form method="post" action=<?php echo $_SERVER['PHP_SELF']; ?>>
				<p id="instructions">
					Tell us what you thought of your experience with Flora's:
				</p>
				<div id="comment">
					<textarea name="customer_reviews" rows="8" cols="70"></textarea>
				</div>
				<br/><br/>

				<div id = "buttons">
					<label for="name">Name:</label>
					<input type="text" id="name" name="name">
					<br/><br/>
					<input type="reset" value="Clear"/>
					<input type="submit" value="Submit!" name ="submit"/>
				</div>
				
			</form>
		</section>
	</main>
	<footer>
		<hr/>
		<!--copyright info-->
		<small>Flora's Flowers &copy; 2019</small>
	</footer>

<?php
	if(isset($_POST['submit'])){//when submit a review 
		//store name and review provided
		$name = $_POST['name'];
		$review = $_POST['customer_reviews'];
		//add review to file 
		addReview($review, $name);
	}


	/**
	adds the submitted review and adds to file storing all reviews
	@param string $review the review message which user left
	@param string $name the name which user provides to be associated with review
	*/
	function addReview($review, $name){
		//create pointer to append to testimonials file 
		$reviewFile = fopen('testimonials.txt','a');
		$message = "$review\n<br/>-$name<br/>\n";
		//append message to file
		fwrite($reviewFile, $message);
		//remove reference to file
		fclose($reviewFile); 
	}
?>
</body>
</html>
