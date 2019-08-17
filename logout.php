#!/usr/local/bin/php
<?php
	//send all headers at one time
	ob_start();
	//name session
	session_name('finalProject'); 

	//start session
	session_start(); 

	//delete cookies stored while user logged in 
	try{//try to connect to database
		$product_db = new SQLite3('products.db');
	}catch(Exception $ex){
		echo $ex->getMessage();
	}

	//query db to get all product numbers 
	$statement = 'SELECT Product_Number FROM products;'; 
	$run = $product_db->query($statement);
	 
	while($row = $run->fetchArray()){//while there are still more records to look through 
		//store product no.
		$name = $row['Product_Number'];

		if(isset($_COOKIE[$name])){//if cookie was set for that product
			//delete cookie 
			setCookie($name, '', time(0) -3600); 
		}
	}
	
?>
<!DOCTYPE html>
<html lang = "en">
<head>
	<!--Web browser title-->
	<title>Logging out...</title>
</head>
<body>
<?php
	//log out of account
	$_SESSION['loggedin'] = false; 

	//redirect back to page they were on prior 
	$priorPage = $_SESSION['page'];
	header("Location: $priorPage");



?>
</body>
</html>