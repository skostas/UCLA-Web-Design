# UCLA-Web-Design
FINAL PROJECT
___________________________________________________________________________________________________________________________________
INFO RELEVANT FOR VIEWING FUNCTIONALITY
____________________________________________________________________________________________________________________________________


To check that "contact" messages are sent to business, log into gmail account below:
email: florasflowerbusiness@gmail.com
password: pic40afinal 

To login to website: 
username : florasflowers*
password: pic40afinal
(or create new account)

*if choose to user this account, the gmail account listed above will also be where order receipts are sent. 

The inventory for each item started at 100 items. Due to my testing the code some are below that, 
but if you attempt to order 100+ units for any item, you will be able to see the funcationality for out of stock items/tracking inventory. 


________________________________________________________________________________________________________________________________________
LIST OF MAIN FEATURES: 
________________________________________________________________________________________________________________________________________

All Pages: 
-nav bar links(css and html): links to all pages (tab for current page changes color)
			      drop down menu for flower arrangments subpages 
-logo link(html): redirected to home page if click on logo 
-login (php SQLite): can log in and out and remembers across pages. 
	             When login , returns you to page you made login from and login replaced with logout display, welcome message, and shopping cart 
	             If unsucc. login, returns to page you made attempt from with failed login message
-account creation (php, SQLite, and js): can create a new account. Will check that email is not already in system and that username is not already taken, if either are the case, will return an alert box to notify user
					 after successful account creation, automatically directed back to page you were on in site and logs user in.

 
Home:
-Product lists (html): link to resepective product pages when click on image


About: 
-contact form (php): sends email to business with customers message and contact
-alert box (js): notifies when message sent


Flower Arrangments:
-landing page(php and css): loops to generate icons with hover feat. to display name 
-links to about message board (html)
-Category Page Creation (php SQLite): Takes info stored in database to generate product content for pages
				      Can enlarge images by clicking image (directed to new tab) 
-Adding to cart (php, SQLite, and js): must be logged in to do so (will get alert box if not logged in) 
-Event Listener1(js and php): when click on an image to enlarge, will store a cookie for that product via JSON ajax call if logged in (deleted when log out or close browser)
			      If add to cart after clicking on, no longer stored in cookie.
-Onsubmit (js): when click on addCart button and meet regular expression parameters, provide an alert box that item was added to cart	 
 

Special Offers:
-must be logged in to view (php)
-Buttons to credit different promotions to cart (html, php, SQLite) 
-bars user from using multiple promotions at once (php, SQLite, js)
-button to remove credit (php, SQLite, js)


Testimonials: 
-Review Form (php): can write a review and submit--> stored in file on server
-Read Reviews (js): AJAX call every sec to check file on server that stores reviews and update accordingly. 


Shopping Cart: 
-must be logged in to view (php)
-talleys total cost of your cart (php SQLite)  
-Inventory Tracking(php SQLite): shows when items in cart are on back order (ie cannot fill order bc ordered more than are in stock) 
-Purchase Button (php, js, SQLite): sends confirmation email to user, shows alert box if email could not be sent, clears users cart, and updates inventory. 
-Removing Items(php SQLite): Can remove items that placed in cart, cart cost and cart display update therein.
-Recently View: Items that user recently viewed (in current logged-in session) are displayed with quick add option. Updates the cart and total as you add
		If add to cart, will disappear from recently viewed bar 
-Promos: applies promos to receipt when user applied one to cart 
