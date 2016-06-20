<?php

namespace AppBundle\Service;

class Validate
{
	
	/* Functiont to render value to display */
	
	public function rv($name){
		if (isset($_REQUEST[$name])) {
		    return trim($_REQUEST[$name]);
		} else if (isset($_POST[$name])) {
			return trim($_POST[$name]);
		} else 
			return '';		
		
	}
	
	// Function to correct value before insertion	
	public function cv($name){
		
		return addslashes($name);
	
	}
	
	
}