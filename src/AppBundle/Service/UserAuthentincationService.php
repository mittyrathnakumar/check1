<?php

namespace AppBundle\Service;


use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @author Dhara Sheth
 */
class UserAuthentincationService
{		
	
	public function authenticateUser(){	
	
    	$session = new Session();
    	$sessionUID = $session->get('UserID');    	
    
    	if(empty($sessionUID)){
    		$response = 1;    
    	} else {    		
    		$response = 0;
    	}    	
    	
    	return $response;
	}
	
	
}