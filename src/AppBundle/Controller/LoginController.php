<?php

namespace AppBundle\Controller;

use AppBundle\Service\UserAuthentincationService;
//use AppBundle\Service\OracleDatabaseService;

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
		return $this->render('index.html.twig');
	}
	
	/**
	 * @Route("/Login", name="LoginPost")
	 * @Method({"POST"})
	 */
	
	// Handles Login form submission
	
	public function loginPostAction(Request $request) {
		
		//$this->denyAccessUnlessGranted();
		
		
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
			//$authUser = new UserAuthentincationService();
			//$authUser->authenticateUser();	
			
			
			$session = new Session();
			//$session->start();
			
			// If user is valid - store the data into session and navigate to about us page.
				
			$session->set('UserID', $UserID);
			$session->set('UserName', $UserName);
			$session->set('UserRole', $UserRole);
			//$session->set('UserRole', 'ROLE_ADMIN');
					
			$response['status'] = 1;			
		}
		
		else 
			$response['status'] = $invalid;
		
		return new JsonResponse($response);
		 

		 
	}	
	
}
