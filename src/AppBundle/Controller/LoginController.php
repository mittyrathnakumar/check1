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
		
		if(isset($request->query->all()['referrer']))
			$referrer = $request->query->all()['referrer'];//exit;
		
		$session = new Session();
		$userID = $session->get('UserID');
		
		if(empty($userID)){		
			if(isset($referrer)){
				$parameteres = array();
				$parameteres['referrer'] = $referrer;
			} else 
				$parameteres['referrer'] = '';
			
			return $this->render('index.html.twig', [
					"para" => $parameteres
			]);			
		} else {
			if(isset($referrer)){
				return $this->redirectToRoute($referrer);
			} else
				return $this->redirectToRoute('Home');				
				
		}
		
		
		
	}
	
	/**
	 * @Route("/Login", name="LoginPost")
	 * @Method({"POST"})
	 */
	
	/* Handles Login form submission */
	
	public function loginPostAction(Request $request) {
		
		$email = $request->request->get('email');
		$password = $request->request->get('password');
		$referrer = $request->request->get('referrer');
		
		$userDetail = new UserRepository();
		$userDetails = $userDetail->fetchUserDetails($email, $password);
		
		$invalid = $userDetails->getInvalid();
	
		
		if(empty($invalid)){	
			
			$response = array();
			
			$UserID = $userDetails->getUserID();
			$UserFirstName = $userDetails->getFirstName();
			$UserLastName = $userDetails->getLastName();			
			$UserRole = $userDetails->getUserRole();
			$UserModuleIDsTemp = $userDetails->getModuleIDs();	
			$UserModuleIDs = explode(",", $UserModuleIDsTemp);
			$UserHomePage = $userDetails->getUserHomePage();			
		
			$session = new Session();
			
			/* If user is valid - store the data into session. */
				
			$session->set('UserID', $UserID);
			$session->set('UserName', $UserFirstName." ".$UserLastName);
			$session->set('UserRole', $UserRole);
			$session->set('UserModuleIDAccess', $UserModuleIDs);
			
			/* === */
					
			$response['status'] = 1;
			$response['referrer'] =  $referrer;
			
			/* Set the default homepage from DB only if the referred URL is blank */
			
			if(empty($referrer))
				$response['home_page'] = $UserHomePage;					
			
		}
		
		else 
			$response['status'] = $invalid;
		
		return new JsonResponse($response);	 
		 
	}	
	
}
