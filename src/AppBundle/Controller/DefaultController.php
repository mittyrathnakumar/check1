<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="Homepage")
     */
    public function indexAction(Request $request)
    {
      
        return $this->redirectToRoute('Login', array(), 301);
    }
    
    /**
     * @Route("/Home", name="Home")
     */
    public function homeAction(Request $request) {   	
    	
    	return $this->render('Home.html.twig');
    }
   
   
}
