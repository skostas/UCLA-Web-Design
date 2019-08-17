#!/usr/local/bin/php
<?php
	//send all headers at once 
	ob_start();
	//name session
	session_name('finalProject'); 
	//start a session
	session_start();
	/**
	meant to be used to provide information is JS about which page a user was on 
	*/
	if(strpos("${_SESSION['page']}", 'Weddings') !== false){//if prior page contained the word wedding
		//wedding is category, so echo to js 
		echo 'wedding'; 
	}elseif(strpos("${_SESSION['page']}", 'Centerpieces') !== false){//if instead contained the word centerpieces
		//centerpiece is category, so echo to js
		echo 'centerpiece';
	}elseif(strpos("${_SESSION['page']}", 'Sympathy') !== false){//if instead contained the word sympathy
		//sympathy is category, so echo to js
		echo 'Sympathy'; 
	}elseif(strpos("${_SESSION['page']}", 'Seasonal') !== false){//if instead contained the word seasonal
		//seasonal is category, so echo to js
		echo 'seasonal';
	}elseif(strpos("${_SESSION['page']}", 'Special-Someone') !== false){//if instead contained the word someone special
		//someone special is category, so echo to js
		echo 'special_someone';
	}elseif(strpos("${_SESSION['page']}", 'Everyday') !== false){//if instead contained the word everyday, 
		//everyday is category so echo to js
		echo 'everyday';
	}
?>



