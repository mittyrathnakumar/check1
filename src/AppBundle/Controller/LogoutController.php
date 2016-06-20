<?php


namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Repository\UserRepository;


class LogoutController extends Controller
{
	/**
	 * @Route("/Logout", name="Logout")
	 */
	public function logoutAction(Request $request) {
	
		$session = $request->getSession();
		
		$userDetail = new UserRepository();
		$logout = $userDetail->updateUserLogout($session->get('UserID'));
		
		/* Unsets session variables */		 
		
		$session->remove('UserID');
		$session->remove('UserName');
		$session->remove('UserRole');
		
		/* === */		 
		 
		return $this->redirectToRoute('Login', array(), 301);
	}
	 
}