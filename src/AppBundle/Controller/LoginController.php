<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Repository\UserRepository;


/**
 * @author Dhara Sheth
 */
class LoginController extends Controller 
{			
	
	/**
	 * @Route("/Login", name="Login")
	 * @Method({"GET","HEAD"})
	 */
	public function loginAction(Request $request) {	
		
		//echo "<pre>";print_r($request);exit;
		
		$session = new Session();
		$userID = $session->get('UserID');
		
		if(empty($userID)){			
			return $this->render('index.html.twig');
		}
		
		/*if(!empty($request->query->all())){
			echo $request->query->all()[0];exit;
			return $this->redirectToRoute('Dashboard');
		}
		else {
			return $this->redirectToRoute('Dashboard');
		}*/
		return $this->redirectToRoute('Home');
		
	}
	
	/**
	 * @Route("/Login", name="LoginPost")
	 * @Method({"POST"})
	 */
	
	// Handles Login form submission
	
	public function loginPostAction(Request $request) {		
		
		// Check in the database for a valid user - Repository / Entity
		
		$email = $request->request->get('email');
		$password = $request->request->get('password');
		
		$userDetail = new UserRepository();
		$userDetails = $userDetail->fetchUserDetails($email, $password);
		
		$invalid = $userDetails->getInvalid();
	
		
		if(empty($invalid)){	
			
			$response = array();
			
			$UserID = $userDetails->getUserID();
			$UserName = $userDetails->getFirstName();
			$UserRole = $userDetails->getUserRole();
			
			
			$session = new Session();
			//$session->start();
			
			// If user is valid - store the data into session and navigate to Dashboard page.
				
			$session->set('UserID', $UserID);
			$session->set('UserName', $UserName);
			$session->set('UserRole', $UserRole);
					
			$response['status'] = 1;			
		}
		
		else 
			$response['status'] = $invalid;
		
		return new JsonResponse($response);	 

		 
	}	
	
}
