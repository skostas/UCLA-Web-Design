#!/usr/local/bin/php
<?php
	ob_start(); 
	//response type from AJAX will be text 
	header('Content-Type: text/plain; charset=utf-8'); 
	
	//decode from JSON 
	$category = json_decode($_POST['category'], true); 
	$category = chop($category);
	 
	try{//try to connect to db
		$product_db = new SQLite3('products.db'); 
	}catch(Exception $ex){//if cannot catch error and display error message instead 
		echo $ex->getMessage();
	}
	
	$statement = "SELECT Product_Number FROM products WHERE Category = '$category';";
	$run = $product_db->query($statement); 
	
	if($run){//if query was successful
		while($row = $run->fetchArray()){//while there are still more records to look through
			//echo product number to JS file 
			echo "#",$row['Product_Number']; 
		}
	}
	 
?>