<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;

//use Symfony\Component\HttpFoundation\ParameterBag;

use AppBundle\Repository\KPIRepository;
use AppBundle\Repository\ProjectRepository;
use AppBundle\Repository\DashboardRepository;





/**
 * @author Dhara Sheth
 */
class KPIController extends Controller 
{	
	
	/**
	 * @var adminRepository
	 */
	
	private $adminRespository;
	
	public function __construct(){
		$this->KPIRepository = new KPIRepository();		
	}	
	
	
	/**
	 * @Route("/KPI/ITSMDefects", name="ITSMDefects")
	 * @Method({"GET","HEAD"})
	 */
	public function renderITSMDefectsAction(Request $request) {
	
		// USER AUTHENTICATION
		$session = new Session();
		$userID = $session->get('UserID');
	
		if(empty($userID)){
			return $this->redirectToRoute('Login');	
		}				
	
		$KPIRepository = new KPIRepository();		
		$DefectDetails = $KPIRepository->getDefectDetails();
		$monthYearArray = $KPIRepository->getmonthArray();
		
		//echo "<pre>";print_r($DefectDetails);exit;
		
		return $this->render('KPI/ITSMDefects.html.twig', [
				"DefectDetails" => $DefectDetails,
				"monthYearArray" => $monthYearArray
		]);
	
	}
	
	/**
	 * @Route("/KPI/ITSMDefects", name="ITSMDefectsPost")
	 * @Method({"POST"})
	 */
	public function renderITSMDefectsPostAction(Request $request) {
	
		$postData = $request->request->all();
		
		if(!empty($postData['action']) && $postData['action'] == 'updateDefects'){					
			$KPIRepository = new KPIRepository();		
			$response = $this->KPIRepository->updateDefectDetails($postData);
			return new JsonResponse($response);
		}
		
		if(!empty($postData['ShowAll']) && $postData['ShowAll'] == 1){
			return $this->redirectToRoute('ITSMDefects');
		}
		
		$KPIRepository = new KPIRepository();
		$DefectDetails = $KPIRepository->getDefectDetails($postData);
		$monthYearArray = $KPIRepository->getmonthArray();		
		
		return $this->render('KPI/ITSMDefects.html.twig', [
				"DefectDetails" => $DefectDetails,
				"monthYearArray" => $monthYearArray,
				"postData" => $postData
		]);		

	}
	
	/**
	 * @Route("/KPI/IntakeProcess", name="IntakeProcess")
	 * @Method({"GET","POST"})
	 */
	public function renderIntakeProcessAction(Request $request) {
	
		// USER AUTHENTICATION
		$session = new Session();
		$userID = $session->get('UserID');
	
		if(empty($userID)){
			/*$RquestedUrl = $request->attributes->all()['_route'];
			$parameters = array();
			
			if($RquestedUrl != 'Login'){
				$parameters = array($RquestedUrl);
			}
			
			return $this->redirectToRoute('Login', $parameters);
			*/			
			return $this->redirectToRoute('Login');
		}
		
		$KPIRepository = new KPIRepository();
		
		/* Delete Intake Process */
		
		$postData = $request->request->all();
		if(!empty($postData['action']) && $postData['action'] == 'deleteProcess'){
			
			$response = $KPIRepository->deleteIntakeProcess($postData['ID']);
			return new JsonResponse($response);
			
		}
		
		/* Render Listing Page */
		
		else {	
		
			$ProcessDetails = $KPIRepository->getProcessDetails();
			$monthYearArray = $KPIRepository->getmonthArray();
		
			//echo "<pre>";print_r($ProcessDetails);exit;
		
			return $this->render('KPI/IntakeProcess.html.twig', [
					"ProcessDetails" => $ProcessDetails,
					"monthYearArray" => $monthYearArray
			]);
		}
	
	}
	
	/**
	 * @Route("/KPI/IntakeProcess/{Auto}", name="IntakeProcessAuto")
	 * @Method({"GET","HEAD"})
	 */
	/*public function renderIntakeProcessAutoAction(Request $request, $Auto) {
	
		// USER AUTHENTICATION
		$session = new Session();
		$userID = $session->get('UserID');
	
		if(empty($userID)){
			return $this->redirectToRoute('Login');
		}
		
		echo $Auto;exit;
	
		$KPIRepository = new KPIRepository();
		$ProcessDetails = $KPIRepository->getProcessDetails();
		$monthYearArray = $KPIRepository->getmonthArray();
	
		//echo "<pre>";print_r($DefectDetail);exit;
	
		return $this->render('KPI/IntakeProcess.html.twig', [
				"ProcessDetails" => $ProcessDetails,
				"monthYearArray" => $monthYearArray
		]);
	
	}	
	*/
	
	/**
	 * @Route("/KPI/AddIntakeProcess", name="AddIntakeProcess")
	 * @Method({"GET","POST"})
	 */
	public function renderAddIntakeProcessAction(Request $request) {
	
		/* User Authentication */		
		$session = new Session();
		$userID = $session->get('UserID');
	
		if(empty($userID)){
			return $this->redirectToRoute('Login');
		}
		
		$postData = $request->request->all();
		//echo "<pre>";print_r($postData);exit;
		
		$KPIRepository = new KPIRepository();
		
		/* Project Autocomplete Request */		
		$keyword = $request->query->get('term');		
		if(!empty($keyword)){
			$result = $KPIRepository->getAutoCompleteData($keyword);
			echo $result;
			exit;			
		} 
		
		/* Submit Added Data */		
		if(!empty($postData)){
			
			/* Submit Added Data */		
			$status = $KPIRepository->addEditIntakeProcessDetails($postData);
			return $this->redirectToRoute('IntakeProcess');
		}
		
		/*  Show form to add data */
		else {	
			return $this->render('KPI/AddEditIntakeProcess.html.twig');				
		}
	
	}
	
	/**
	 * @Route("/KPI/EditIntakeProcess/{IntakeID}", name="EditIntakeProcess")
	 * @Method({"GET", "POST"})
	 */
	public function renderEditIntakeProcessAction(Request $request, $IntakeID) {
	
		/* User Authentication */		
		$session = new Session();
		$userID = $session->get('UserID');
	
		if(empty($userID)){
			return $this->redirectToRoute('Login');
		}
		
		$postData = $request->request->all();
		//echo "<pre>";print_r($postData);exit;
		
		$KPIRepository = new KPIRepository();		 
		
		/* Submit Data */		
		if(!empty($postData)){
			
			/* Submit Edited Data */
			if(!empty($postData['IntakeID'])){				
				$status = $KPIRepository->addEditIntakeProcessDetails($postData, $postData['IntakeID']);				
				return $this->redirectToRoute('IntakeProcess');				
			}			
			
		}
		
		/*  Show form to edit data */
		else {
			if(!empty($IntakeID)){
				$KPIRepository = new KPIRepository();
				$ProcessDetails = $KPIRepository->getProcessDetails($IntakeID);
				//$monthYearArray = $KPIRepository->getmonthArray();
			
				//echo "<pre>";print_r($ProcessDetails);exit;
			
				return $this->render('KPI/AddEditIntakeProcess.html.twig', [
						"ProcessDetails" => $ProcessDetails,
						"IntakeID" => $IntakeID
				]);	
			}
		}	
		
	}
	
	/**
	 * @Route("/KPI/Documentation", name="Documentation")
	 * @Method({"GET","HEAD"})
	 */
	public function renderDocumentationAction(Request $request) {
	
		/* USER AUTHENTICATION */
		$session = new Session();
		$userID = $session->get('UserID');
	
		if(empty($userID)){			
			return $this->redirectToRoute('Login');
		}	
	
		$KPIRepository = new KPIRepository();
		$DocumentDetails = $KPIRepository->getDocumentDetails();
		//$monthYearArray = $KPIRepository->getmonthArray();
	
		//echo "<pre>";print_r($ProcessDetails);exit;
	
		return $this->render('KPI/Documentation.html.twig', [
				"DocumentDetails" => $DocumentDetails
		]);
	
	}
	
	/**
	 * @Route("/KPI/AddDocumentDetails", name="AddDocumentDetails")
	 * @Method({"GET","POST"})
	 */
	public function renderAddDocumentDetailsAction(Request $request) {
	
		/* User Authentication */
		$session = new Session();
		$userID = $session->get('UserID');
	
		if(empty($userID)){
			return $this->redirectToRoute('Login');
		}
	
		$postData = $request->request->all();
		//echo "<pre>";print_r($postData);exit;
	
		$KPIRepository = new KPIRepository();	
		
		/* Submit Added Data */
		if(!empty($postData)){
				
			/* Submit Added Data */
			$status = $KPIRepository->addEditDocumentationDetails($postData);
			return $this->redirectToRoute('Documentation');
		}
	
		/*  Show form to add data */
		else {
			$Project = new ProjectRepository(); 
			$Projects = $Project->getProjectNames($postData);
			//echo "<pre>";print_r($Projects);exit;
			
			return $this->render('KPI/AddEditDocumentDetails.html.twig', [
					"Projects" => $Projects 
			]);
		}
	
	}
	
	/**
	 * @Route("/KPI/EditDocumentDetails/{ID}", name="EditDocumentDetails")
	 * @Method({"GET", "POST"})
	 */
	public function renderEditDocumentDetailsAction(Request $request, $ID) {
	
		/* User Authentication */
		$session = new Session();
		$userID = $session->get('UserID');
	
		if(empty($userID)){
			return $this->redirectToRoute('Login');
		}
	
		$postData = $request->request->all();
		//echo "<pre>";print_r($postData);exit;
	
		$KPIRepository = new KPIRepository();
	
		/* Submit Data */
		if(!empty($postData)){
				
			/* Submit Edited Data */
			if(!empty($postData['ProjectID'])){
				$status = $KPIRepository->addEditDocumentationDetails($postData, $postData['ProjectID']);
				//echo $status;exit;
				return $this->redirectToRoute('Documentation');
			}
				
		}
	
		/*  Show form to edit data */
		else {
			if(!empty($ID)){
				$KPIRepository = new KPIRepository();
				$Project = new ProjectRepository();				
				$Projects = $Project->getProjectNames($postData);
				$DocumentDetails = $KPIRepository->getDocumentDetails($ID);
				//$monthYearArray = $KPIRepository->getmonthArray();
				$DocumentDetails = $DocumentDetails[0];
				//echo "<pre>";print_r($DocumentDetails);exit;
					
				return $this->render('KPI/AddEditDocumentDetails.html.twig', [
						"DocumentDetails" => $DocumentDetails,
						"Projects" => $Projects,
						"ProjectID" => $ID
				]);
			}
		}
	
	}
	
	/**
	 * @Route("/KPI/QualityEstimation", name="QualityEstimation")
	 * @Method({"GET","HEAD"})
	 */
	public function renderQualityEstimationAction(Request $request) {
	
		/* USER AUTHENTICATION */
		$session = new Session();
		$userID = $session->get('UserID');
	
		if(empty($userID)){
			return $this->redirectToRoute('Login');
		}
	
		$KPIRepository = new KPIRepository();
		$EstimationDetails = $KPIRepository->getEstimationDetails();
		//$monthYearArray = $KPIRepository->getmonthArray();
	
		//echo "<pre>";print_r($ProcessDetails);exit;
	
		return $this->render('KPI/QualityEstimation.html.twig', [
				"EstimationDetails" => $EstimationDetails
		]);
	
	}
	
	/**
	 * @Route("/KPI/AddQualityEstimationDetails", name="AddEstimationData")
	 * @Method({"GET","POST"})
	 */
	public function renderAddQualityEstimationDetailsAction(Request $request) {
	
		/* User Authentication */
		$session = new Session();
		$userID = $session->get('UserID');
	
		if(empty($userID)){
			return $this->redirectToRoute('Login');
		}
	
		
		$postData = $request->request->all();
		//echo "<pre>";print_r($postData);exit;
		
		$KPIRepository = new KPIRepository();
		
		/* Project Autocomplete Request */
		$keyword = $request->query->get('term');
		
		if(!empty($keyword)){
			$result = $KPIRepository->getAutoCompleteData($keyword);
			echo $result; /* Displays the fetched Projects as a Drop down for Auto Complete */
			exit;
		}
			
		/* Submit Added Data */
		if(!empty($postData)){
	
			/* Submit Added Data */
			$status = $KPIRepository->addEditEstimationnDetails($postData, "", $userID);
			return $this->redirectToRoute('QualityEstimation');
		}
	
		/*  Show form to add data */
		else {
			$Project = new ProjectRepository();
			$Projects = $Project->getProjectNames($postData);
			//echo "<pre>";print_r($Projects);exit;
				
			return $this->render('KPI/AddEditQEDetails.html.twig', [
					"Projects" => $Projects
			]);
		}
	
	}
	
	/**
	 * @Route("/KPI/EditQualityEstimationDetails/{ID}", name="EditEstimationData")
	 * @Method({"GET", "POST"})
	 */
	public function renderEditQualityEstimationDetailsAction(Request $request, $ID) {
	
		/* User Authentication */
		$session = new Session();
		$userID = $session->get('UserID');
	
		if(empty($userID)){
			return $this->redirectToRoute('Login');
		}
	
		$postData = $request->request->all();
		//echo "<pre>";print_r($postData);exit;
	
		$KPIRepository = new KPIRepository();
	
		/* Submit Data */
		if(!empty($postData)){
	
			/* Submit Edited Data */
			if(!empty($ID)){
				$status = $KPIRepository->addEditEstimationnDetails($postData, $ID, $userID);
				//echo $status;exit;
				return $this->redirectToRoute('QualityEstimation');
			}
	
		}
	
		/*  Show form to edit data */
		else {
			if(!empty($ID)){
				$KPIRepository = new KPIRepository();
				$Project = new ProjectRepository();
				$Projects = $Project->getProjectNames($postData);
				$EstimationDetails = $KPIRepository->getEstimationDetails($ID);
				//$monthYearArray = $KPIRepository->getmonthArray();

				//echo "<pre>";print_r($EstimationDetails);exit;
					
				return $this->render('KPI/AddEditQEDetails.html.twig', [
						"EstimationDetails" => $EstimationDetails,
						"Projects" => $Projects,
						"EstimationID" => $ID
				]);
			}
		}
	
	}
	
	/**
	 * @Route("/KPI/DeliverySlippages", name="DeliverySlippages")
	 * @method({"GET","POST"})
	 */
	
	public function renderDeliverySlippagesAction(Request $request) {
		$session = new Session();
		$userID = $session->get('UserID');
		$check = "in";
		//$obj1 = new KPIRepository ();
		if(empty($userID)){
			return $this->redirectToRoute('Login');
		}
	
		$KPIRepository = new KPIRepository();
		$DashRepository = new DashboardRepository();
		
		$postData = $request->request->all();
		if(!empty($postData['action'])){
			//echo $postData['projectID'];exit;
			$response = $KPIRepository->updateDate($postData['newDate'], $postData['projectID'],$postData['action']);
			//echo "<pre>";print_r("lalal");exit;
			return new JsonResponse($response);
	
		}
		//echo "<pre>";print_r($postData);exit;
		else{
			if(!empty($postData['Month']))
				$Month = $postData['Month'];
				else
					$Month = '';
	
					if(!empty($postData['ShowAll']) && $postData['ShowAll'] == 1){
						return $this->redirectToRoute('DeliverySlippages');
					}
	
					$deliverySlippages = $KPIRepository->getDeliverySlippages ($Month);
					$monthYearArray = $DashRepository->getmonthYearArray ();
	
					//echo "<pre>";print_r($KPIResults);exit;
	
					return $this->render ( 'KPI/DeliverySlippage.html.twig', [
							"DeliverySlippages" => $deliverySlippages,
							"monthYearArray" => $monthYearArray,
							"Month" => $Month
					]);
		}
	}
	
	/**
	 * @Route("/KPI/STAutomation", name="STAutomation")
	 * @method({"GET","POST"})
	 */
	
	public function renderSTAutomationAction(Request $request) {
		$session = new Session();
		$userID = $session->get('UserID');
		$check = "in";
		if(empty($userID)){
			return $this->redirectToRoute('Login');
		}
	
		$KPIRepository = new KPIRepository();
		$DashRepository = new DashboardRepository();
		
		$postData = $request->request->all();
		
		
		if(!empty($postData['action'])){
			//echo $postData['projectID'];exit;
			$response = $KPIRepository->updateSTAutomation($postData['newValue'], $postData['projectID'],$postData['action']);
			//echo "<pre>";print_r("lalal");exit;
			return new JsonResponse($response);
	
		}
		//echo "<pre>";print_r($postData);exit;
		else{
			if(!empty($postData['Month']))
				$Month = $postData['Month'];
				else
					$Month = '';
	
					if(!empty($postData['ShowAll']) && $postData['ShowAll'] == 1){
						return $this->redirectToRoute('STAutomation');
					}
	
					$STAutomation = $KPIRepository->getSTAutomation ($Month);
					$monthYearArray = $DashRepository->getmonthYearArray ();
	
					//echo "<pre>";print_r($KPIResults);exit;
	
					return $this->render ( 'KPI/STAutomation.html.twig', [
							"STAutomation" => $STAutomation,
							"monthYearArray" => $monthYearArray,
							"Month" => $Month
					]);
		}
	}
}
