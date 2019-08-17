//when window loads, create updater object and call read_text	
window.onload=function(){read_text();};


/*
 *
 *CALL AJAX TO UPDATE TESTIMONIALS
 *
 */

/**
performs AJAX call to check list of testimonials
@param {object} updater is a reviews object
*/
function read_text(){
	//creates object to do ajax call
	let xhttp = new XMLHttpRequest();
	//set callback func for when ready state changes
	xhttp.onreadystatechange = function(){
		if(this.readyState === 4 && this.status ===200){//when operation is complete (4) and successfull (200)
			//display content of testimonial.txt on webpage
			displayTestimonials(this.responseText);
			setTimeout(read_text, 1000);
		}
	};

	//make get request to read file, asyncronously
	xhttp.open("POST","testimonials.txt", true);
	//execute
	xhttp.send();
}

/**
displays the list of testimonials displayed on webpage
@param {string} value is the content of testimonials.txt
*/
function displayTestimonials(value){
	let currentText = document.getElementById("reviews").innerHTML = value;  
}

