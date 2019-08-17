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
	<title>Flora's Flowers: Home</title>

	<!--link style sheets to page-->
	<link type="text/css" rel="stylesheet" href="heading.css"/>
	<link type="text/css" rel="stylesheet" href="index.css"/>
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
				<li><a href="index.php" title="Home Page" id="home">Home</a></li>
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
		<!--top products-->
		<section id="product_section">
			<h3 class="section_title">Customer Favorites:</h3>

			<!--product 1-->
			<figure>
				<!--image links to product page-->
				<a href="productsWeddings.php"><img src="products/W0001.jpg" title="Wedding Bouquet" width="300" alt="wedding bouqet"/></a>
				<figcaption>
					Summer Wedding Bouquet!<br/>Available only for bulk order.
				</figcaption>
			</figure>

			<!--product 2-->
			<figure>
				<!--image links to product page-->
				<a href="productsCenterpieces.php"><img src="products/C0001.jpg" title="Centerpiece" width ="300" alt="rustic floral centerpiece"/></a>
				<figcaption>
					Rustic Centerpiece!<br/>$30/unit.
				</figcaption>
			</figure>

			<!--product 3-->
			<figure>
				<!--image links to product page--> 
				<a href="productsSeasonal.php"><img src="products/S0001.jpg" title="Wreath" width="300" alt="fall floral wreath"/></a>
				<figcaption>
					Festive Fall Wreath!<br/>$45/unit.
				</figcaption>
			</figure>
		</section>

		<!--media-->
		<section id="media_section">
			<h3 class="section_title">Flora's in the News:</h3>
			<!--article 1-->
			<article>
				<!--title-->
				<h4 class="artl">Flora's Donates Flower Arrangments to Every Church in Town</h4>
				<hr/>
				<!--article content-->
				<p class = "mediaTxt">
					As many of you are aware, the churches in our town have been stuggling financially. Our churches rely solely on donations to maintain themselves. With the economy being poor, members of our churches' communities have been unable to make donations of the same magnitude as they have in previous years. While the churches have been able to get by, they have made notable changes to do so--cutting back on luxuries such as floral arrangments for the pews. 
				</p>	
				<p class="mediaTxt">
					With the holidays approaching, Flora's Flowers decided to do their part to help our churches. Flora's has decided to donate 42 floral arrangments to every church in town by Christmas Eve. 
				</p>
				<p class="mediaTxt">
					When asked about her decision to donate, Flora, the owner of Flora's Flowers, said,"I knew how importnant it is to the community that our churches look festive for holiday services, so I wanted to help out in any way I could".
				</p>
				<p class="mediaTxt">
					Way to go Flora!
				</p>
			</article>

			<!--article 2-->
			<article>
				<h4 class="artl">Flora of Flora's Flowers wins Local Florist Competition</h4>
				<hr/>

				<!--conent for article-->
				<p class="mediaTxt">
					Flora's Flowers has just won itself a float in the Rose Parade!  
				</p>
				<p class = "mediaTxt">
					On Wednesday night, Flora's Flowers competed against twenty-eight other Florists from our region in the Annual Battle of the Florist Compeition. This is considered to be one of the most competitive competitions in the nation. 
				</p>
				<p class="mediaTxt">
					Each florist was tasked with creating a 6 to 8 foot sculpture made completely of flowers. And at the end of the night Flora's scupture reigned superior. The prize was a float in next year's Rose Parade.
				</p>
			</article>

			<!--article 3-->
			<article>
				<h4 class ="artl">Flora's Flowers Named "Most Successful Small Business"</h4>
				<hr/>

				<!--content for article-->
				<p class="mediaTxt">
					On Friday afternoon, the annual list of Most Successful Small Business was released. The rankings are decided based on an algorithm which factors in qualties such as gross profit and average customer review. These rankings are generated for: Cusine, Home and Lifestyle, Entertainment, and Leisure. 
				</p>
				<p class="mediaTxt">
					Flora's Flowers came in ranked number 1 for small businesses in the Home and Lifestyle category. While we will never know exactly what won Flora's this accolade, her loyal customers think it was the floral shop's phenomenal customer reviews that put her over the edge. 
				</p>
			</article>
		</section>
		</main>
	<footer>
		<hr/>
		<!--copyright info-->
		<small>Flora's Flowers &copy; 2019</small>
	</footer>
	
</body>
</html>

