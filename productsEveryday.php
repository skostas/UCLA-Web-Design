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
	<title>Flora's Flowers: Seasonal Arrangements</title>
	<meta charset="UTF-8"/>
	<!--link to style sheets-->
	<link type="text/css" rel="stylesheet" href="heading.css"/>
	<link type="text/css" rel="stylesheet" href="products.css"/>
	<!--link to js files-->
	<script src="products.js" defer> </script>
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
					<a href="products.php" title="Flower Arrangements" id="products">Flower Arrangments</a>
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
		<!--section title-->
		<h3 id = "sectionTitle"> Everyday Arrangements</h3>
<?php
	try{ //try to establish connection 
		//open products DB
		$products_db = new SQLite3('products.db');
	}
	catch(Exception $ex){ //if fails, catch error and disp message instead
		echo $ex->getMessage(); 
	}
	$statement = "SELECT Images, Description, Price, Inventory,Units_Per_Purchase,Product_Number FROM products WHERE Category ='everyday';";
	$run = $products_db->query($statement);
	if($run){ //if query successfull ?>

		<!-- products table-->
		<table>
			<tbody>
				
			<?php
			//represents index of product within row, initialize to 0 
			$counter = 0; 
			while($row = $run->fetchArray()){//while there are still records to be looked through
				
				if($counter === 0){//when first item in a row ?>
					<!--make a new row-->
					<tr>
				<?php } ?>
					<td><figure>
						<a href = <?php echo $row['Images'];?> name = "productPic" title = <?php echo $row['Product_Number'];?> target="_blank" rel="noopener"><img src=<?php echo $row['Images'];?> width ="200" alt=<?php echo $row['Product_Number'];?>></a>
						<figcaption>
						<?php if($row['Units_Per_Purchase'] === 1){//if can purchase by the unit
							echo $row['Description'],'<br/>$',$row['Price'],'/unit';
						}else{//if must purchase in bulk
							//display units per purchase
							echo $row['Description'],'<br/>$',$row['Price'],'/',$row['Units_Per_Purchase']," units";
						} ?>
						</figcaption>
					</figure>
					<?php if($_SESSION['loggedin']){//if logged in ?>

						<!--add to cart functionality-->
						<form method = "post" action="cartAdd.php" onsubmit="notify();">
							<label for = <?php echo $row['Product_Number'];?>> Quantity: </label>
							<input type ="text" id = <?php echo $row['Product_Number'];?> class = "quant" name=<?php echo $row['Product_Number'] ?> required pattern="\d+"/>
							<input type ="submit" value="Add to Cart" name="cartAdd"/>
						</form>
					<?php } ?>
					</td>
				<?php if ($counter === 5){//if last product in a row ?>
					<!--close row-->
					</tr>
				<?php
					$counter = -1; 
				}
				//increment row position counter 
				$counter++;
			} ?>
			</tr>
			</tbody>
		</table>
	</main>
	<footer>
		<hr/>
		<!--copyright info-->
		<small>Flora's Flowers &copy; 2019</small>
	</footer>

	<?php }
	if(!$_SESSION['loggedin']){//if not logged in 
		//remind that must log in to add items to cart
		$alert = '<script> alert("You must be logged in to add items to your cart");</script>';
		echo $alert; 
	} ?>
</body>
</html>
