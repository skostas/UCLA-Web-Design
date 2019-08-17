#!/usr/local/bin/php
<?php
	//send headers at one time
	ob_start();
	//session name
	session_name('finalProject'); 
	//start final project session 
	session_start(); 
	//save page user came from
	$priorPage = $_SESSION['page']; 
	
?>

<!DOCTYPE html>
<html lang = "en">
<head>
	<!--browser title-->
	<title>Create an Account</title>
	<!--link to css files-->
	<link href="heading.css" type="text/css" rel="stylesheet"/>
	<link href="newAccount.css" type="text/css" rel="stylesheet"/>
</head>
<body>
	<header>
		<!--Title-->
		<h1 id="title">Flora's Flowers</h1>

		<!--logo-->
		<a href="index.php"> <img alt="logo" id ="logo" src="logo.png" width="100"/></a>
		
		<!--subtitle-->
		<h2 id="subtitle">Flowers for Every Occasion</h2>

	</header>
	<main>
		<!--new account section-->
		<section>
			<!--section title-->
			<h3>Create an Account</h3>
			<!--account creation form-->
			<form method ="post" action=<?php echo $_SERVER['PHP_SELF'];?>>
				<!--user information-->
				<fieldset>
					<p>
						To create an account please provide the information below:
					</p>
					<hr/>
					<!--email field-->
					<label for="email">Email Address: </label>
					<input type="email" id ="email" name="email" required pattern=".+@{1}.+"/><br/>
					<!--username field-->
					<label for="username">Username: </label>
					<input type="text" id ="username" name="username" required/><br/>
					<!--password--> 
					<label for ="password">Password: </label>
					<input type="password" id="password" name="password" required/>	
				</fieldset>
			
				<!--submit/clear form-->
				<div>
					<input type="reset" value="Clear"/>
					<input type="submit" name="createAccount" value="Create & Login"/>
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
	if(isset($_POST['createAccount'])){
		//store email
		$email = $_POST['email']; 
		//check if there is already and account with email 
		$existingEmail = checkEmail($email);
	
		//store username
		$username = $_POST['username'];
		//check if user name is already taken
		$existingUsername = checkUsername($username);

		if($existingEmail){//if email already exists
			//alert user that email address already exists 
			$alertBox = '<script language="javascript"> alert("The email you provided is already associated with an account.")</script>';
			echo "$alertBox"; 			
		}elseif($existingUsername){//if username already taken
			//alert user that cannot user that name
			$alertBox = '<script language="javascript"> alert("The username you chose is already associated with an account.\nPlease choose a differnet username.")</script>';
			echo $alertBox; 
		}else{//if username and email are both valid
			//store password
			$password = $_POST['password'];
			//create account
			$success = createAccount($username, $password, $email); 
			if($success){//if account creation was successful
				//log user in 
				$_SESSION['loggedin']=true; 
				$_SESSION['username'] = $username;
				$_SESSION['loginError'] = false; 
			}
			//redirect back to prior page
			header("Location: $priorPage");
		}
			
	}
/**
checks if email already exists in user database

@param string $email the email address provided to make an account 

@return boolean true if email already exists and false is email does not
*/
function checkEmail($email){
	//assume email is not in system
	$exists = false;
	try{//try to est a connection to db
		$user_db = new SQLite3('users.db');
	}catch (Exception $ex){//if cannot, catch error
		echo $ex->getMessage(); 
	}
	//query all emails
	$statement = 'SELECT Email FROM users;';
	$run= $user_db->query($statement); 

	if($run){//if no error in query 
		while($row=$run->fetchArray()){//while still rows to be parsed
			if(trim($row['Email']) === trim($email)){//if email found in db
				//email exists is true
				$exists = true;
				//break out of loop
				break; 
			}
		}
	}
	//end connection to db
	$user_db->close();
	//return whether email exists
	return $exists;

}

/**
checks if a username has already been taken within the database 

@param string $username the username provided to make new account

@return boolean true if username as been taken and false if it has not 
*/
function checkUsername($username){
	//assume users is not in system
	$exists = false;
	try{//try to est a connection to db
		$user_db = new SQLite3('users.db');
	}catch (Exception $ex){//if cannot, catch error
		echo $ex->getMessage(); 
	}
	//query all usernames
	$statement = 'SELECT Username FROM users';
	$run = $user_db->query($statement); 

	if($run){//if no error in query 
		while($row = $run->fetchArray()){//while there are still rows to be parsed
			if(trim($row['Username']) === trim($username)){//if provided username matches one in system
				//user name exists
				$exists = true;
				//break out of loop
				break; 
			}
		}
	}
	//end connection to db
	$user_db->close(); 
	//return if username already existed
	return $exists; 
}
/**
adds users credentials to database, giving them login access

@param string $username the username desired for new account
@param string $password the password desired for new account
@param string $email the email desired for new account

@return whether query was successful (returns false if erred) 
*/
function createAccount($username, $password, $email){
	try{//try to est a connection to db
		$user_db = new SQLite3('users.db');
	}catch (Exception $ex){//if cannot, catch error
		echo $ex->getMessage(); 
	}

	//hash password
	$password = hash('md2', $password);

	//query to insert a new user record
	$statement= "INSERT INTO users (Username, Password, Email) VALUES ('$username', '$password', '$email');";
	$run = $user_db->query($statement);

	//en connection to db
	$user_db->close();
	return $run;
}
?>
</body>
</html>

