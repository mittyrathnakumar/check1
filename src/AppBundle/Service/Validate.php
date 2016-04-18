<?php

namespace AppBundle\Service;

class Validate
{
	
	// Functiont to render value to display
	public function rv($name){
		if (isset($_REQUEST[$name])) {
		    return $_REQUEST[$name];
		}
		  
		return false;
		
	}
	
	// Function to correct value before insertion	
	public function cv($name){
		
		return addslashes($name);
	
	}
	
	
}