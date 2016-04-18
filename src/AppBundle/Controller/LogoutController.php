<?php


namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use AppBundle\Controller\SessionController;
use Symfony\Component\HttpFoundation\Request;

class LogoutController extends Controller
{
	/**
	 * @Route("/Logout", name="Logout")
	 */
	public function logoutAction(Request $request) {
	
		// Unsets session variables
		 
		$session = $request->getSession();
		$session->remove('UserID');
		$session->remove('UserName');
		$session->remove('UserRole');
		 
		return $this->redirectToRoute('Login', array(), 301);
	}
	 
}