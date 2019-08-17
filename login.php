#!/usr/local/bin/php
<?php
	//clear any previous headers
	ob_start(); 
	//session name
	session_name('finalProject');
	//start a session
	session_start(); 
	//file name of prior page
	$priorPage = $_SESSION['page'];	

	if(isset($_POST['login'])){//when login button pressed
		
		//currently not logged in 
		$_SESSION['loggedin'] = false; 

		
		//delete any cookies that existes prior to log in
		try{//try to connect to db 
			$product_db = new SQLite3('products.db');
		}catch(Exception $ex){//catch exception if cannot
			echo $ex->getMessage();
		}
		//query all product numbers
		$statement = 'SELECT Product_Number FROM products;'; 
		$run = $product_db->query($statement);
		
		while($row = $run->fetchArray()){//while there are still more product numbers
			$name = $row['Product_Number'];
			if(isset($_COOKIE[$name])){//if cookie was set
				//delete cookie 
				setCookie($name, '', time(0) - 3600); 
			}
		}
		$product_db->close(); 

		//store supplied password in $_SESSION
		$_SESSION['password'] = $_POST['pword'];
	
		//check if username is in system 
		$validUser = userExists();

		if($validUser){//if user exists in system 		
			//check that password matches username
			$validPword = pwordMatches(); 
		if($validPword){//if password matched that of username
				//log them in 
				$_SESSION['username'] = $_POST['name'];
				$_SESSION['loggedin'] = true;
				$_SESSION['loginError'] = false;  
							
			}else{//if password did not match
				$_SESSION['loggedin'] = false;
				//store login error
				$_SESSION['loginError'] = true; 
			}
		}else{// if username not in system
			//do not log in 
			$_SESSION['loggedin'] = false;	
			//store login error
			$_SESSION['loginError'] = true; 	
	}
	
	//redirect to page they were on prior
	header("Location: $priorPage");
	}
	/**
	checks if username provided exists in system
	
	@return boolean where true = user name is in system and false = name not in system
	*/
	function userExists(){
		//assume user is not in system
		$exists = false;
		try{//try to est connection to db 
			$user_db = new SQLite3('users.db');
		}catch(Exception $ex){ //if cannot catch exception 
			echo $ex->getMessage(); 
		}
		//query for all usernames
		$statement = 'SELECT Username FROM users;';
		$run = $user_db->query($statement); 

		if($run){//if not error in query 
			while($row = $run->fetchArray()){//while there are still rows to parse
				if(trim($row['Username']) === trim($_POST['name'])){//if provided user name matches one in db
					//user exists
					$exists = true;
					//break out of loop
					break;
				} 
			}
		}
		//close connection to db
		$user_db->close(); 
		return $exists;
	}

	/**
	checks if password corresponds with provided username
	
	@return boolean where true = password matches useranme and false = password does not match username
	*/
	function pwordMatches(){
		//assume pword does not match that in system
		$match = false;
		try{//try to est connection to db 
			$user_db = new SQLite3('users.db');
		}catch(Exception $ex){ //if cannot catch exception 
			echo $ex->getMessage(); 
		}
		//query for all usernames
		$statement = 'SELECT Username, Password FROM users;';
		$run = $user_db->query($statement); 

		$pword = $_SESSION['password'];
		$pword = hash('md2', $pword); 
		if($run){//query did not have error
			while($row = $run->fetchArray()){
				if(trim($row['Username']) === trim($_POST['name'])){//if provided username is the same as that queried	
					if($pword === trim($row['Password'])){//if provided pword matches that for the provided username
						//password matches
						$match = true; 
						//break out of loop
						break;
					}
				} 
			}
		}
		$user_db->close(); 
		return $match; 	
	}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<!--browser title-->
	<title>Logging in...</title>
</head>
<body>
</body>
</html>