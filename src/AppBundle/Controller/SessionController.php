<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;


class SessionController extends Controller
{
	
	 /**
     * @Route("/SessionCheck", name="SessionCheck")
     */
	
    public function sessioncheckAction(Request $request) {
    	
    	//echo 'in===';exit;
    	$session = new Session();  
    	//echo "<pre>";print_r($session);exit;
    	$sessionUID = $session->get('UserID');
    	
    	//$session = $request->getSession();   	    	 
    	//$sessionUID = $session->get('UserID');//exit;
    	
    	if(empty($sessionUID)){
    		$response = 1;
    		//$this->redirectToRoute('Login', array(), 301);
    	} else {    		
    		$response = 0;
    	}    	
    	
    	return $response;
    }  	 
	 
}
