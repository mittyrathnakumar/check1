<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
//use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\Repository\ProjectRepository;




/**
 * @author Dhara Sheth
 */
class ProjectController extends Controller{	
		
		/**
		 * @var projectRepository
		 */
		
		private $projectRepository;
		
		public function __construct(){
			$this->projectRepository = new ProjectRepository();		
		}	
		
		/**
		 * @Route("/Projects/AddProject", name="AddProject")
		 * @Method({"GET","HEAD"})
		 */
		
		public function renderAddProjectAction(Request $request) {		
			
			$session = new Session();
			$userID = $session->get('UserID');
			
			if(empty($userID)){
				return $this->redirectToRoute('Login');
			
			}
			
			return $this->render('Projects/AddEditProject.html.twig');
		}
		
		/**
		 * @Route("/Projects/AddProject", name="AddProjectPost")
		 * @Method({"POST","HEAD"})
		 */
		
		public function renderAddProjectPostAction(Request $request) {
		
			
			//$ProjectDetails = $this->projectRepository->getProjectDetails();
			$postData = $request->request->all();
			
			$status = $this->projectRepository->addEditProject($postData);
			
			/*return $this->render('Admin/Users.html.twig', [
					"UserDetails" => $UserDetails,
					"status" => $status
			]);*/
			$response = array("status" => $status);
			
			return $this->redirectToRoute('ViewProjects', $response);
		}
		
		
		/**
		 * @Route("/Projects/ViewProjects", name="ViewProjects")
		 * @Method({"GET","HEAD"})
		 */
		
		public function renderProjectsAction(Request $request) {
			
			$session = new Session();
			$userID = $session->get('UserID');
			
			if(empty($userID)){
				return $this->redirectToRoute('Login');
			
			}
			
			if(!empty($request->query->all()['status']))
				$msg = $request->query->all()['status'];
			else 
				$msg = "";
			
			$ProjectDetails = $this->projectRepository->getProjectDetails();
			//echo "<pre>";print_r($ProjectDetails);exit;
			
			return $this->render('Projects/ViewProjects.html.twig', [
					"ProjectDetails" => $ProjectDetails,
					"msg" => $msg
			]);
		}
		
		/**
		 * @Route("/Projects/EditProject/{ProjectID}", name="EditProject")
		 * @Method({"GET","HEAD"})
		 */
		
		public function renderEditProjectAction(Request $request, $ProjectID) {
			
			$session = new Session();
			$userID = $session->get('UserID');
			
			if(empty($userID)){
				return $this->redirectToRoute('Login');
			
			}
			
			$ProjectDetails = $this->projectRepository->getProjectDetails($ProjectID);
		
			//echo "<pre>";print_r($ProjectDetails);exit;
			
			return $this->render('Projects/AddEditProject.html.twig', [
					"ProjectDetails" => $ProjectDetails,
					"ProjectID" => $ProjectID
			]);
		}
		
		/**
		 * @Route("/Projects/EditProject", name="EditProjectPost")
		 * @Method({"POST","HEAD"})
		 */
		
		public function renderEditProjectPostAction(Request $request) {	
		
			//$ProjectDetails = $this->projectRepository->getProjectDetails();
			$postData = $request->request->all();
			//$ProjectDetails = $this->projectRepository->getProjectDetails();
			$status = $this->projectRepository->addEditProject($postData);	
			
			$response = array("status" => $status);
				
			return $this->redirectToRoute('ViewProjects', $response);			
			
		}
				
}
