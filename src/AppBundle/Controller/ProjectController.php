<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\Repository\ProjectRepository;
use AppBundle\Repository\DashboardRepository;




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
		 * @Route("/Projects/ViewProjects", name="ViewProjects")
		 * @Method({"GET","POST"})
		 */
		
		public function renderProjectsAction(Request $request) {
				
			/* USER AUTHENTICATION */
		
			$session = new Session();
			$userID = $session->get('UserID');
			
			if(empty($userID)){
				$referrer = $request->attributes->get('_route');
				$parameters = array();
				$parameters['referrer'] = $referrer;
				return $this->redirectToRoute('Login', $parameters);
			}
			
			/* === */
				
			$ProjectRepository = new ProjectRepository();
			$dashboardRepository = new DashboardRepository();
				
			$postData = $request->request->all();
				
			if(!empty($request->query->all()['status']))
				$msg = $request->query->all()['status'];
			else
				$msg = "";					
		
					
			if(!empty($postData)){
				if(!empty($postData['Month']))
					$Month = $postData['Month'];
				else
					$Month = '';
			} else {
				if(!empty($request->query->all()['Month']))
					$Month = $request->query->all()['Month'];
				else
					$Month = "";
			}
				

			if(!empty($postData['ShowAll']) && $postData['ShowAll'] == 1){
				return $this->redirectToRoute('ViewProjects');
			}
			
			//echo $Month;exit;
				
			$ProjectDetails = $ProjectRepository->getProjectDetails("", $Month);
			$monthYearArray = $dashboardRepository->getmonthYearArray();		
				
			return $this->render('Projects/ViewProjects.html.twig', [
					"ProjectDetails" => $ProjectDetails,
					"monthYearArray" => $monthYearArray,
					"postData" => $postData,
					"Month" => $Month,
					"msg" => $msg
			]);
		}
		
		/**
		 * @Route("/Projects/AddProject", name="AddProject")
		 * @Method({"GET","HEAD"})
		 */
		
		public function renderAddProjectAction(Request $request) {		
			
			/* USER AUTHENTICATION */
		
			$session = new Session();
			$userID = $session->get('UserID');
			
			if(empty($userID)){
				$referrer = $request->attributes->get('_route');
				$parameters = array();
				$parameters['referrer'] = $referrer;
				return $this->redirectToRoute('Login', $parameters);
			}
			
			/* === */
			
			return $this->render('Projects/AddEditProject.html.twig');
		}
		
		
		
		/**
		 * @Route("/Projects/AddProject", name="AddProjectPost")
		 * @Method({"POST","HEAD"})
		 */
		
		public function renderAddProjectPostAction(Request $request) {	

			$postData = $request->request->all();
			$ProjectRepository = new ProjectRepository();
			
			$response = 1;
				
			if(!empty($postData['Domain']) && !empty($postData['QCProjectName'])){				
				$response = $ProjectRepository->checkValidQCTableName($postData['QCProjectName'], $postData['Domain']);
			}			

			
			if($response == 0){
				$status = "Invalid QC Project Name OR Domain !!!";		
				$ProjectDetails = $postData;				
	
				return $this->render('Projects/AddEditProject.html.twig', [
						"msg" => $status,
						"ProjectDetails" => $ProjectDetails
				]);
				
			} else {
               $status = $ProjectRepository->addEditProject($postData);
               $response = array("status" => $status);
               
               return $this->redirectToRoute('ViewProjects', $response);
            } 
			
		}		
		
		
		/**
		 * @Route("/Projects/EditProject/{ProjectID}", name="EditProject")
		 * @Method({"GET","HEAD"})
		 */
		
		public function renderEditProjectAction(Request $request, $ProjectID) {
			
			/* USER AUTHENTICATION */
		
			$session = new Session();
			$userID = $session->get('UserID');
			
			if(empty($userID)){
				$referrer = $request->attributes->get('_route');
				$parameters = array();
				$parameters['referrer'] = $referrer;
				return $this->redirectToRoute('Login', $parameters);
			}
			
			/* === */
			
			$ProjectRepository = new ProjectRepository();
			$ProjectDetails = $ProjectRepository->getProjectDetails($ProjectID);

			
			return $this->render('Projects/AddEditProject.html.twig', [
					"ProjectDetails" => $ProjectDetails,
					"ProjectID" => $ProjectID
			]);
		}
		
		/**
		 * @Route("/Projects/EditProject/{ProjectID}", name="EditProjectPost")
		 * @Method({"POST","HEAD"})
		 */		
		
		public function renderEditProjectPostAction(Request $request, $ProjectID) {
			
			//echo $ProjectID;exit;
		
			$postData = $request->request->all();
				
			if(isset($postData['Month']))
				$Month = $postData['Month'];
			else
				$Month = '';
		
			$ProjectRepository = new ProjectRepository();
			$response = 1;

			if(!empty($postData['QCProjectName']) && !empty($postData['Domain'])){
				$response = $ProjectRepository->checkValidQCTableName($postData['QCProjectName'], $postData['Domain']);
			}

			if($response == 0){
				$status = "Invalid QC Project Name OR Domain !!!";
				$ProjectDetails = $postData;
				
				//return $this->redirectToRoute(EditProject);

				return $this->render('Projects/AddEditProject.html.twig', [
						"msg" => $status,
						"ProjectDetails" => $ProjectDetails,
						"ProjectID" => $ProjectID
				]);
			}
			else {
				$status = $ProjectRepository->addEditProject($postData);
				$response = array("Month" => $Month);
				return $this->redirectToRoute('ViewProjects', $response);

			}

		}
		
		/**
		 * @Route("/Projects/EditProject/{ProjectID}/{Month}", name="EditProjectMonth")
		 * @Method({"GET","POST"})
		 */
		
		public function renderEditProjectMonthAction(Request $request, $ProjectID, $Month) {
				
			/* USER AUTHENTICATION */
		
			$session = new Session();
			$userID = $session->get('UserID');
			
			if(empty($userID)){
				$referrer = $request->attributes->get('_route');
				$parameters = array();
				$parameters['referrer'] = $referrer;
				return $this->redirectToRoute('Login', $parameters);
			}
			
			/* === */
			
			$postData = $request->request->all();
			$ProjectRepository = new ProjectRepository();
			
			/* Edit Form Submit */
			
			if(!empty($postData)){	
				
				$response = 1;
				
				if(!empty($postData['QCProjectName']) && !empty($postData['Domain'])){
					$response = $ProjectRepository->checkValidQCTableName($postData['QCProjectName'], $postData['Domain']);
				}
				
				if($response == 0){
					$status = "Invalid QC Project Name OR Domain !!!";				
					$ProjectDetails = $postData;
				
					return $this->render('Projects/AddEditProject.html.twig', [
							"msg" => $status,
							"ProjectDetails" => $ProjectDetails,
							"Month" => $Month,
							"ProjectID" => $postData['ProjectID']
					]);
				} 
				else {				
			
					$status = $ProjectRepository->addEditProject($postData);					
					$response = array("status" => $status, "Month" => $Month);				
					return $this->redirectToRoute('ViewProjects', $response);
					
				}
				
			} 
			
			/* Edit Form Show */
			
			else {				
				
				//echo $Month;exit;
				$ProjectDetails = $ProjectRepository->getProjectDetails($ProjectID, $Month);
				
				return $this->render('Projects/AddEditProject.html.twig', [
						"ProjectDetails" => $ProjectDetails,
						"Month" => $Month,
						"ProjectID" => $ProjectID
				]);
			}
		}		
		
		//echo "<pre>";print_r($ProjectDetails);exit;				
}
