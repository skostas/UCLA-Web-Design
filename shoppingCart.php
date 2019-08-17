#!/usr/local/bin/php
<?php
	//send all headers at once 
	ob_start();
	//name session
	session_name('finalProject'); 
	//start a session
	session_start();
	$_SESSION['page'] = $_SERVER['PHP_SELF']; 
	//store username 
	$user = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8"/>

	<!--webpage browser title-->
	<title>Flora's Flowers: Cart</title>

	<!--link style sheets to page-->
	<link type="text/css" rel="stylesheet" href="heading.css"/>
	<link type ="text/css" rel="stylesheet" href="shoppingCart.css"
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
				<li><a href="testimonials.php" title="Testimonials">Testimonials</a></li>
			</ul>
		</nav>
	</header>
	<main>
		<?php if($_SESSION['loggedin']){// if logged in?>
		<section id=cartItems>
			<!--section title-->
			<h3 id = "sectionTitle">Shopping Cart</h3>
			<!--order form-->
			<form method = "post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<!--shows itemized order-->
				<fieldset id = "items">
					<h4 class = "flushLeft">Your Items: </h4>
					<?php 
						//get items in cart 
						$cart = getCart($user);
						if(!$cart){//if cart it empty ?>
							<p> No Items in Cart </p> 
						<?php }
						else{//if cart had items
							try{//try to connect to user db
								$product_db = new SQLite3('products.db');
							}catch(Exception $ex){//if unsucc
								$ex->getMessage();
							}
							//separate $cart items into elements of array using '#' as delimiter
							$cartItems = explode('#',$cart); 
							//index for delete buttons
							$i = 1;
							foreach($cartItems as $item){//for every item in cart
								//remove white space
								$item = trim($item); 
								//separate item id from quant
								$fields = explode('=', $item);
								
								foreach($fields as $field){//for each field of an item
									//get rid of white space
									$field = trim($field); 
								}
								//query db for info about product in cart 
								$statement = "SELECT Product_Number, Images, Inventory, Price, Description, Units_Per_Purchase FROM products WHERE Product_Number = '$fields[0]';";
								$run = $product_db->query($statement); 

								if ($run){//if no error in query
									while ($row = $run->fetchArray()){//while there are still more records to be looked through ?>
										<figure>
											<img src=<?php echo $row['Images'];?> width ="60" alt=<?php echo $row['Product_Number'];?>>
											<figcaption class = "flushRight">
											<?php if($row['Units_Per_Purchase'] === 1){//if can purchase by the unit
												echo $row['Description'],'<br/>$',$row['Price'],'/unit';
											}else{//if must purchase in bluk
												echo $row['Description'],'<br/>$',$row['Price'],'/',$row['Units_Per_Purchase']," units";
											} 
											if($row['Inventory']-($fields[1]*$row['Units_Per_Purchase']) <= 0){//if cart order puts the inventory to or below 0 in stock
												//notify that is one back order 
												echo '<br/><strong>Item is on back order!</strong>'; 
											} ?>
												<!--display quantity ordered-->
												<br/> Quant.: <?php echo $fields[1]; ?>

												<!--checkbox to delete item if no longer want-->
												<br/><br/><label for ="<?php echo 'delete',$i; ?>">delete </label>
												<input type="checkbox" id ="<?php echo 'delete',$i++; ?>" name ="deleted[]" value = "<?php echo $row['Product_Number']?>"/>							</figcaption>
										</figure><hr>
									<?php }
								}
								
							}?> 
						<div class = "flushRight"> 
							<!--update cart so deletes checked items-->
							<input type ="submit" name = "delete" value ="Update Cart"/>
						</div>
						<?php } ?> 
					
				</fieldset>

				<!--shows cost of order-->
				<fieldset id= "cost" class = "flushRight">
					<h4 class = "flushLeft">Your Quote: </h4>
					<?php
						$total = 0; 
						if($cart){// if there are items in cart
							foreach($cartItems as $item){//for every item within the cart
								$item = trim($item); 
								//separate item id from quant
								$fields = explode('=', $item);
								  
								foreach($fields as $field){//for each field of an item
									//get rid of white space
									$field = trim($field); 
								}
							
								//query for price, units per purchase and description for a given product number 
								$statement = "SELECT Price, Description, Units_Per_Purchase FROM products WHERE Product_Number='$fields[0]';"; 
								$run = $product_db->query($statement);
								if($run){//if query was successful
									while($row = $run->fetchArray()){ //while there are still rows to parse 
										//add to cost to total
										$total = $total + $row['Price']*$fields[1]; ?>
										<p> 
											<!--Display itemized break down for order, quant, and cost-->
											<?php echo '(',$row['Description'],')X', $fields[1]; ?> 
											<br/> <?php echo '$',money_format('%i',$row['Price']*$fields[1]); ?>
										</p> 
									<?php }
								}
								
							}
							//end connection to db
							$product_db->close(); 
						}?>
					<!--display total before tax-->
					Total: $<?php echo money_format('%i', $total) ?> <br/> 
					<?php	
						$stateTax = 0.0725;
						$tax = $stateTax*$total;

						try{// try to connect to db 
							$user_db=new SQLite3('users.db');
						}catch(Exception $ex){//catch error if cannot connect 
							echo $ex->getMessage(); 
						}
						//get promo stored in users record
						$promo = queryPromo($user_db);
						//end connection to db 
						$user_db->close(); 

						if(!$promo){//no promo credited to account 
							$promo = "N/A";
							$discount = NULL; 
							
						}elseif($promo === "20OFF"){//if have 20 off promo
							if($total >= 150){//if spent enough for promo to apply
								$discount = -20; 		
							}
						}elseif($promo === "NoTax"){//if have no tax promo 
							$discount = 0-$tax;
						}elseif($promo==="5PERCENT"){//if have 5% off promo
							$discount = -$total*0.05; 
						}				
						
						$grandTotal = $tax+$total+$discount;  
					 ?>
					<!--display discount, tax, and grand total--> 
					Promo: <?php echo "(",$promo, ") $", money_format('%i',$discount); ?><br/>
					Tax: $<?php echo money_format('%i',$tax);?><br/><hr>
					Grand Total: $<?php echo money_format('%i',$grandTotal); ?><br/>
					
					<!--button to place order--> 
					<input type ="submit" name="purchases" value="Place Order!"/>
				</fieldset>
			</form>
			
			
		</section>  

		<!--quick add to cart--> 
		<?php
			try{ //try to establish connection 
			//open products DB
				$products_db = new SQLite3('products.db');
			}
			catch(Exception $ex){ //catch error if unsucc
				echo $ex->getMessage(); 
			}
			
			//query db for fields of products
			$statement = "SELECT Images, Description, Price, Inventory,Units_Per_Purchase,Product_Number FROM products;";
			$run = $products_db->query($statement);

			if($run){//if query successful ?>
				<table>
					<thead>
						<tr>
							<th class = "flushLeft" >Recently Viewed:</th>
						</tr>
					</thead>
					<tbody>
						<?php $counter = 0; 
							while($row = $run->fetchArray()){ //while there are still more records to look through
								if(isset($_COOKIE[$row['Product_Number']])){ //if product was stored in cookie ?>
									<!--add product as a new row to recently viewed table--> 
									<tr>
										<td><figure>
											<image src=<?php echo $row['Images'];?> width =80" alt=<?php echo $row['Product_Number'];?>>
											<figcaption class="flushLeft">
											<?php if($row['Units_Per_Purchase'] === 1){//if can purchase by the unit
												echo $row['Description'],'<br/>$',$row['Price'],'/unit';
											}else{//if must purchase in bulk
												echo $row['Description'],'<br/>$',$row['Price'],'/',$row['Units_Per_Purchase']," units";
											} ?>	
											<!--add to cart form-->		
											<form method = "post" action="cartAdd.php">
												<label for = <?php echo $row['Product_Number'];?>> Quantity: </label>
												<input type ="text" id = <?php echo $row['Product_Number'];?> class = "quant" name=<?php echo $row['Product_Number'] ?> required pattern="\d+"/>
												<input type ="submit" value="Add to Cart" name="cartAdd"/>
											</form>
											</figcaption>
										</figure></td>
									</tr>
										 
									<?php if ($counter >= 4){//if already displayed 4 items 
										//dont display any more
										break; 
									}
									//increment counter
									$counter++; 
								} ?>
								</tr>
							<?php }?>
							</tbody>
						</table>
					<?php }
		}else{//if not logged in ?>
		<!--no assess display-->
		<p id="no_access"> 
			You must be logged in to your account to view your shopping cart.
		</p>
		<?php }?>
		
		
	</main> 
	<footer>
		<hr/>
		<!--copyright info-->
		<small>Flora's Flowers &copy; 2019</small>
	</footer>
	<?php
		if(isset($_POST['purchases'])){//if placed order

			if($cart){// if there are items in cart
				try{//try to connect to user db
					$product_db = new SQLite3('products.db');
				}catch(Exception $ex){//if unsucc
					$ex->getMessage();
				}
				$message = "Thank you for choosing Flora's! Below is a copy of your order: \nYour Purchase Summary:"; 
				foreach($cartItems as $item){//for each item in cart 
					$item = trim($item); 
					//separate Product No. from quant
					$fields = explode('=', $item);
								  
					foreach($fields as $field){//for each field of an item
						//get rid of white space
						$field = trim($field); 
					}
							
					//query for price, units per purchase and description for a given product number 
					$statement = "SELECT Price, Description, Units_Per_Purchase, Inventory FROM products WHERE Product_Number='$fields[0]';"; 
					$run = $product_db->query($statement);
					if($run){//if query successful 
								
						while($row = $run->fetchArray()){ //while there are still rows to parse 
							$message = $message."\n(".$row['Description'].")X". $fields[1]."\n\t\$".money_format('%i',$row['Price']*$fields[1]);
							
							//store new inventory values 
							$newInventory[] = $row['Inventory']-$fields[1]; 	
						}
					}
								
				}
			//complete message for email body
			$message = $message."\n\nTotal: \$".money_format('%i', $total)."\nPromo: (".$promo.") \$".money_format('%i',$discount)."\nTax: \$".money_format('%i', $tax)."\nGrand Total: \$".money_format('%i', $grandTotal)."\nSincerly,\nFlora's Flowers"; 
			
			$i = 0; 
			foreach($cartItems as $item){//for every item in cart
				$item = trim($item); 
				//separate Product No. from quant
				$fields = explode('=', $item);
				
				//query database to update inventory of purchased products 				   					
				$statement = "UPDATE products SET Inventory = $newInventory[$i] WHERE Product_Number = '$fields[0]';";
				$run = $product_db->query($statement); 
				$i++; 
			}

			//end connection to db
			$product_db->close();
			
				//send email confirming order
				$sent = emailOrder($user, $message);
			
				if($sent){// if message successfully sent
					$page = $_SESSION['page'];
					//reload page , to show now cleared cart
					header("Location: $page");
				}else{//if email could not be sent
					//alert user
					$alert = '<script language="javascript"> alert("Your order has been received but a receipt could not be sent to your email on file.");</script>';
					echo $alert; 
				}
				
			}else{//if there were no items in cart
				//alert user
				$alert = '<script language="javascript"> alert("There are no items in your cart to purchase.");</script>';
				echo $alert; 
			}
			
		}
	if(isset($_POST['delete']) && count($_POST['deleted'])> 0){// if user pressed button to update cart and at least one of the check boxes have been selected 
		//store value of checked boxes
		$deleteBtns = $_POST['deleted']; 
		foreach($deleteBtns as $btn){//for every checked box
			foreach($cartItems as $item){//for every item in cart
				//separate Product No. from quant
				$fields = explode('=', $item);
			 		  
				foreach($fields as $field){//for each field of an item
					//get rid of white space
					$field = trim($field); 
				}
				if($btn === $fields[0]){//if check box had value of product number in cart
					//store append delimiter to item 
					$item = '#'.$item; 

					//start index of item in cart 
					$cIndex = strpos($cart, $item);

					//length of item in cart
					$length = strlen($item);

					//any items after item to be deleted  
					$cartAfter = substr($cart, $cIndex + $length);
					//any items before item to be deleted
					$cartBefore = substr($cart, 0, $cIndex); 

					//update cart to exclude deleted item
					$cart = $cartBefore.$cartAfter; 
				} 	 		
			}
			
		}
		try{//try to connect to user db
			$user_db = new SQLite3('users.db');
		}catch(Exception $ex){//if unsucc
			$ex->getMessage();
		}
		
		//query to update users cart in db  
		$statement = "UPDATE users SET Cart = '$cart' WHERE Username = '$user';";
		$run = $user_db->query($statement); 

		//end connection to db
		$user_db->close(); 

		//redirect back to shopping cart 
		$page = $_SESSION['page'];
		header("Location: $page"); 
	
	}


	/**
	sends order confirmation to user, with receipt
	
	@param string $user the username of current user
	@param string $message the  body of of the email

	@return false if failed to send and hash value of address if successfully sent 
	*/
	function emailOrder($user, $message){
		try{//try to connect to user db
			$user_db = new SQLite3('users.db');
		}catch(Exception $ex){//if unsucc
			$ex->getMessage();
		}

		//query for email of current user
		$statement = "SELECT Email FROM users WHERE Username='$user';";
		$run = $user_db->query($statement); 

		if(run){//if no errors in query 
			while($row = $run->fetchArray()){//while there are still rows to be parsed
				$email = $row['Email'];
			}
		}
		
		//query database to clear users cart; 
		$statement = "UPDATE users SET Cart = NULL WHERE Username = '$user';";
		$run = $user_db->query($statement); 

		//end connection to db
		$user_db->close(); 

		//email subject
		$subject = 'Flora\'s Flowers: Your Recent Order';

		//send order confirmation email to user 
		$sent = mail($email,$subject, $message); 
		return $sent; 	
	}


	/**
	gets contents stored in user's cart 

	@param string $user the username of current user

	@return string of cart items or null if no items in cart 
	*/
	function getCart($user){
		try{//try to connect to user db
			$user_db = new SQLite3('users.db');
		}catch(Exception $ex){//if unsucc
			$ex->getMessage();
		}

		//query db for cart items of current user
		$statement = "SELECT Cart FROM users WHERE Username = '$user';";
		$run = $user_db->query($statement); 
						
		if($run){//if there was no error in query 
			while($row = $run->fetchArray()){//while there is still rows to be parsed
				//store contents of cart					
				$cart = $row['Cart'];
			}
		}
		//end connection to db
		$user_db->close();

		//return contents of cart
		return $cart; 
	}

	/**function to get the promotion credited to users account

	@param object $sqlObj the SQLite3 object for desired database 	

	@return string name of promo if one has been credited and NULL if no promo is attached to account
	*/
	function queryPromo($sqlObj){
		$user = $_SESSION['username']; 
		//query for any promo 
		$statement = "SELECT Promo FROM users WHERE Username = '$user';";
		$run = $sqlObj->query($statement); 
		
		if($run){//if no error with query 
			while($row=$run->fetchArray()){//while there are still records to be parsed
				return $row['Promo']; 
			}
		}

	}
?>
</body>
</html>
