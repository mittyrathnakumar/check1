<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
//use AppBundle\Controller\SessionController;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
//         return $this->render('default/index.html.twig', [
//             'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
//         ]);
        return $this->render('index.html.twig', [
        		'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ]);
        
        //return $this->redirectToRoute('Login', array(), 301);
    }
    
    /**
     * @Route("/About", name="About")
     */
    public function aboutAction(Request $request) {
    	
    	/*$session = new Session();
    	
    	$session = $request->getSession();   	    	 
    	$sessionUID = $session->get('UserID');
    	$userName = $session->get('UserName'); 
    	$userRole = $session->get('UserRole');*/    	
    	
    	return $this->render('about.html.twig');
    }
    
   
   
}
