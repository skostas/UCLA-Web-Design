//set event listener for products in category when window loads 
window.onload = get_category(); 


/**
ajax call to determine which category-page user is on 
calls get_products to therein set action listiners and cookies 
*/
function get_category(){
	let xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange= function(){
		if(this.readyState === 4 && this.status === 200){
			//call ajax to set action listener for each product number in given
			get_products(this.responseText); 
		}

	}
	xhttp.open("GET", 'serverInfo.php', true);
	xhttp.send(); 
}
/**
ajax call to determine what products are displayed on page 

*/
function get_products(category){
	//convert category to a JSON obj 
	let data = JSON.stringify(category); 

	//create new instance of 
	let xhttp = new XMLHttpRequest();
 
	xhttp.onreadystatechange = function(){//if successful and completed 
		if(this.readyState === 4 && this.status === 200){
			//get product numbers 
			let productNos = this.responseText;
		
			//split string at #'s 
			let products = productNos.split("#");

			//remove first element of array (always empty) 
			products.shift(); 

			//initialize index at 0
			let index = 0; 

			for (let product of products){//for each element of array
				//remove white space
				product.trim(); 

				//for each product image element, add action listener that makes cookie onclick
				document.getElementsByName("productPic")[index].addEventListener("click", function() {makeCookie(product)});
				//increment index
				index++;
			}
		}
	}
	xhttp.open("POST","productdb.php",true);
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
	xhttp.send("category="+ data); 
}
/**
makes a cookie with the specified name value pair that will expire in three hours 

@param {string} name the name for cookie name-value pair 
@param {string} value the value associated with a name for cookie name-value pair
*/
function makeCookie(value){
	//cookie value is product number of clicked image 
	let cName = value+"="+value+";";

	//add cookie with access to whole site and expiry on browser close 
	document.cookie = cName;
}

/**
notifies user that item is being added to cart via an alert box; 
*/
function notify(){ 
	alert("Adding to Cart..."); 
}