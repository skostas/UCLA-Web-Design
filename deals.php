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
	<title>Flora's Flowers: Special Offers</title>

	<!--link style sheet to page-->
	<link type="text/css" rel="stylesheet" href="heading.css"/>
	<link type="text/css" rel="stylesheet" href="deals.css"/>

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
			<form class = "login" method = "post" action="login.php">
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
				<li><a href="deals.php" title="Specials" id="deals">Special Offers</a>				</li>
				<li><a href="testimonials.php" title="Testimonials">Testimonials</a></li>
			</ul>
		</nav>
	</header>
	<main>
		<?php if($_SESSION['loggedin']){//if user is logged in ?>
			<!--current promos-->
			<section> 
				<h4>Current Promotions*</h4> 
				<form method = "post" action=<?php echo $_SERVER['PHP_SELF'] ?>>
					<fieldset class = "promo">
						<h5>5% OFF Total:</h5>
						<p>
							5% off total, before, tax! No minimum purchase required! 
						</p>

						<input type ="submit" value="Apply to Cart!" name="percentOff"/>

					</fieldset>
					<fieldset class = "promo">
						<h5>No Sales Tax:</h5>
						<p>
							No sales tax applied. No Catch or minimum purchase!  
						</p>
						<input type ="submit" value="Apply to Cart!" name="salesTax"/>

					</fieldset>
					<fieldset class = "promo">
						<h5> $20 OFF Total:</h5>
						<p>
							Get $20 off grand total on orders of $150 or more, before tax. 
						</p>
						
						<input type ="submit" value="Apply to Cart!" name="totalOff"/>
					</fieldset>
					<!--button to remove promo-->
					<input id = "noPromo" type = "submit" value = "Clear Promo Currently Applied to Account" name="delete"/>
					
					
				</form>
				<small>*Promotions cannot be combined or applied retro-actively.</small>
				
			</section>
		<?php }else{//if not logged in ?>
			<p id ="no_access">
				You must be logged in to view our special offers. 
			</p>
		<?php } ?>
		
	</main>
	<footer>
		<hr/>
		<!--copyright info-->
		<small>Flora's Flowers &copy; 2019</small>
	</footer>
	
<?php 
	try{//try to connect to db
		$user_db = new SQLite3('users.db');
	}catch(Exception $ex){//if cannot connect, catch error
		echo $ex->getMessage();
	}

	if(isset($_POST['percentOff'])){//if applying bogo promo
		//add promo to account
		$added = promoQuery('5PERCENT', $user_db); 
		//tell user status of promo on account
		confirm($added);
	}elseif(isset($_POST['totalOff'])){//if applying 20 off total promo
		//add promo to account
		$added = promoQuery('20OFF', $user_db);
		//tell user status of promo on account
		confirm($added);
	}elseif(isset($_POST['salesTax'])){//if applying no sales tax promo
		//add promo to account
		$added = promoQuery('NoTax', $user_db);
		//tell user status of promo on account
		confirm($added);
	}

	if(isset($_POST['delete'])){//if clicked the button to remove current promo
		$deleted = delete($user_db);
		if($deleted){//if successully deleted
			$alertBox = '<script> alert("Promo has been removed from your cart."); </script>';
			echo $alertBox; 
		}
	}
	//end connection to database
	$user_db->close();
	
	/**
	queries database to add specified promo to current user's account, only adds if no other promo has been applied to account  

	@param string $promo the name of the promotion being applied
	@param Object $sqlObj the SQLite object for database

	@return true if successful and false if unsuccessful at adding to account 
	*/
	function promoQuery($promo, $sqlObj){
		//store current user
		$user = $_SESSION['username']; 
		//query current Promos of users
		$statement = "SELECT Promo FROM users WHERE Username = '$user';";
		$run = $sqlObj->query($statement);

		if($run){ //if successfully queried 
			while($row = $run->fetchArray()){//while there are still rows to be parsed
				if($row['Promo'] !== NULL){//a promo is already credited to account 
					return false; 
				}
			}
		}
		//query statement to update Promo associated with user's account
		$statement = "UPDATE users SET Promo = '$promo' WHERE Username = '$user';"; 
		//execute query 
		$run = $sqlObj->query($statement);  
		//return success of query, cast to a bool
		return (bool) $run; 
	}

	/**
	creates and displays an alert box to tell user whether promo was credited to account 
	@param boolean $added the success of adding promotion to account 
	*/
	function confirm($added){
		if($added){// successfully added to account
			$alertBox = '<script> alert("Promotion has been credited to your account!");</script>'; 
			echo $alertBox;  	
		}else{//if could not add 
			$alertBox = '<script> alert("Could not add promotion to your account. Only one promotion may be applied at one time.");</script>';
			echo $alertBox;  
		}
	}
	
	/**
	removes current promo from users account 
	@param Object $sqlObj the SQL object specified to access database

	@returns false if unsucc at deleting promo and true if successful 
	*/
	function delete($sqlObj){
		try{//try to connect to db
			$user_db = new SQLite3('users.db');
		}catch(Exception $ex){//if cannot connect, catch error
			echo $ex->getMessage();
		}
		$user = $_SESSION['username'];

		//remove any prommo for current user by setting Promo to null in db
		$statement = "UPDATE users SET Promo = NULL WHERE Username = '$user';";
		$run = $user_db->query($statement); 
		if ($run){//if query was successful
			return true; 
		}else{//if query unsucc
			return false; 
		}

	}
?>
</body>
</html>


