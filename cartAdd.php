#!/usr/local/bin/php
<?php
	//send all headers at once 
	ob_start();
	//name session
	session_name('finalProject'); 
	//start a session
	session_start();
	
	if(isset($_POST['cartAdd'])){//if clicked add to cart
		//get product number
		$ProductNo = getProduct(); 
		//add to cart 
		cartAdd($ProductNo);

		//remove any cookie associated with that product
		if(isset($_COOKIE[$ProductNo])){//if there is a cookie for that product 
			//delete cookie
			setCookie($ProductNo, '', time(0) -3600); 
		}  	
	}
	//page user was on prior
	$priorPage = $_SESSION['page'];
	//redirect to page they were on 
	header("Location: $priorPage");
?>
<!DOCTYPE html>
<html  lang="en">
<head>
	<!--link to js file-->
	<script src="products.js" defer></script>
	<!--browser title-->
	<title>Adding...</title>
</head>
<body>
<?php 

/**
gets the product number for the item user wants to add to cart
@return string of the product number for desired product
*/
function getProduct(){
	try{// try to est a connection to database
		//open products db 
		$products_db= new SQLite3('products.db');
	}catch(Exception $ex){//if fail, catch error and print error message instead
		echo $ex->getMessage();
	}

	//query products db for all product numbers
	$statement = 'SELECT Product_Number FROM products;';
	$run = $products_db->query($statement);
	
	if ($run){//if no error in query
		while($row = $run->fetchArray()){//while there are still more records to look through
			//store product no. of current record	
			$key = $row['Product_Number'];

			if ($_POST[$key] !== NULL){//if the button for that product has been pressed
				//break out of loop
				break;
			}
		}
		//end connection to db 
		$products_db->close();
	}
	//return product number of desired product
	return $key; 
}


/**
adds item to current user's cart 
@param String $ProductNo the product number of the item bring added to the cart
*/
function cartAdd($ProductNo){
	try {//try to connect to db
		//open user db
		$user_db = new SQLite3('users.db');
	}catch(Exception $ex){//if fail, catch error and dispaly error message instead
		echo $ex->getMessage();
	}

	//store current users username
	$username = $_SESSION['username'];
	
	//query db for items in current user's cart
	$statement = "SELECT Cart FROM users WHERE Username = '$username';";
	$run = $user_db->query($statement);


	if($run){//if there were no errors in the query
		while($row = $run->fetchArray()){//while there are still rows to parse
			if($row['Cart'] !== NULL){//if cart is not empty 
				//append to end of cart
				$cart = $row['Cart'].'#'.$ProductNo.'='.$_POST["$ProductNo"];
			}else{//if cart is empty
				//populate cart
				$cart = $ProductNo.'='.$_POST["$ProductNo"];
			}
			//query database to update cart
			$statement = "UPDATE users SET Cart = '$cart' WHERE Username = '$username';";
			$run = $user_db->query($statement);			
		}
	}
	//end connection to db 
	$user_db->close(); 
}
?>
</body>
</html> 