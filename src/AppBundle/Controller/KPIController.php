<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;

use AppBundle\Repository\KPIRepository;
use AppBundle\Repository\ProjectRepository;
use AppBundle\Repository\DashboardRepository;




/**
 * @author Dhara Sheth
 */
class KPIController extends Controller 
{	
	

	
	/**
	 * @Route("/KPI/ITSMDefects", name="ITSMDefects")
	 * @Method({"GET","HEAD"})
	 */
	public function renderITSMDefectsAction(Request $request) {
	
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
			$response = $KPIRepository->updateDefectDetails($postData);
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
	 * @Route("/KPI/AddIntakeProcess", name="AddIntakeProcess")
	 * @Method({"GET","POST"})
	 */
	public function renderAddIntakeProcessAction(Request $request) {
	
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
			$referrer = $request->attributes->get('_route');
			$parameters = array();
			$parameters['referrer'] = $referrer;
			return $this->redirectToRoute('Login', $parameters);
		}
		
		/* === */	
	
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
	 * @Method({"GET","POST"})
	 */
	public function renderQualityEstimationAction(Request $request) {
	
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
		
		$KPIRepository = new KPIRepository();
		
		/* Delete Intake Process */
		
		$postData = $request->request->all();
		
		
		if(!empty($postData['ShowAll']) && $postData['ShowAll'] == 1){
			$response = array("Month" => strtoupper(date('M-y')));
			return $this->redirectToRoute('QualityEstimation', $response);
		}
		
		if(!empty($postData['action']) && $postData['action'] == 'deleteProcess'){
		
			$response = $KPIRepository->deleteQualityOfEstimation($postData['ID']);
			return new JsonResponse($response);
		
		}
		
		/* Render Listing Page */
		
		else {
			
			if(!empty($postData)){
				if(!empty($postData['Month']))
					$Month = $postData['Month'];
				else
					$Month = '';
			} else {
				if(!empty($request->query->all()['Month']))
					$Month = $request->query->all()['Month'];
				else
					$Month = strtoupper(date('M-y'));
			}
			
			$EstimationDetails = $KPIRepository->getEstimationDetails($Month, $postData, "");			

			$monthYearArray = $KPIRepository->getmonthArray();								
				
			return $this->render('KPI/QualityEstimation.html.twig', [
						"EstimationDetails" => $EstimationDetails,
						"monthYearArray" => $monthYearArray,
						"Month"=> $Month
			]);
			
		}
		
		
	}
	
	/**
	 * @Route("/KPI/AddQualityEstimationDetails", name="AddEstimationData")
	 * @Method({"GET","POST"})
	 */
	public function renderAddQualityEstimationDetailsAction(Request $request) {
	
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
	
// 	public function renderEditQualityEstimationDetailsAction(Request $request, $ID) {
	
// 		/* User Authentication */
// 		$session = new Session();
// 		$userID = $session->get('UserID');
	
// 		if(empty($userID)){
// 			return $this->redirectToRoute('Login');
// 		}
	
// 		$postData = $request->request->all();
// 		//echo "<pre>";print_r($postData);exit;
	
// 		$KPIRepository = new KPIRepository();
	
// 		/* Submit Data */
// 		if(!empty($postData)){
	
// 			/* Submit Edited Data */
// 			if(!empty($ID)){
// 				$status = $KPIRepository->addEditEstimationnDetails($postData, $ID, $userID);
// 				//echo $status;exit;
// 				return $this->redirectToRoute('QualityEstimation');
// 			}
	
// 		}
	
// 		/*  Show form to edit data */
// 		else {
// 			if(!empty($ID)){
// 				$KPIRepository = new KPIRepository();
// 				$Project = new ProjectRepository();
// 				$Projects = $Project->getProjectNames($postData);
// 				$EstimationDetails = $KPIRepository->getEstimationDetails($postData,$ID);
// 				//$monthYearArray = $KPIRepository->getmonthArray();

// 				//echo "<pre>";print_r($EstimationDetails);exit;
					
// 				return $this->render('KPI/AddEditQEDetails.html.twig', [
// 						"EstimationDetails" => $EstimationDetails,
// 						"Projects" => $Projects,
// 						"EstimationID" => $ID
// 				]);
// 			}
// 		}
	
// 	}
	
	/**
	 * @Route("/KPI/EditQualityEstimationDetails/{ID}/{Month}", name="EditMonthEstimationData")
	 * @Method({"GET", "POST"})
	 */
	public function renderEditMonthQualityEstimationDetailsAction(Request $request, $ID, $Month) {
	
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
		//echo "<pre>";print_r($postData);exit;
	
		$KPIRepository = new KPIRepository();
		$Project = new ProjectRepository();
	
		/* Submit Data */
		if(!empty($postData)){
	
			/* Submit Edited Data */
			if(!empty($ID)){
				$status = $KPIRepository->addEditEstimationnDetails($postData, $ID, $userID);
				$response = array("Month" => $postData['Month']);
				return $this->redirectToRoute('QualityEstimation', $response);
			}
	
		}
	
		/*  Show form to edit data */
		else {
			if(!empty($ID)){				
				//echo $Month;exit;
				$Projects = $Project->getProjectNames($postData);
				$EstimationDetails = $KPIRepository->getEstimationDetails($Month, $postData, $ID);
				//$monthYearArray = $KPIRepository->getmonthArray();
	
				//echo "<pre>";print_r($EstimationDetails);exit;
					
				return $this->render('KPI/AddEditQEDetails.html.twig', [
						"EstimationDetails" => $EstimationDetails,
						"Projects" => $Projects,
						"EstimationID" => $ID,
						"Month" => $Month
				]);
			}
		}
	
	}
	
	/**
	 * @Route("/KPI/DeliverySlippages", name="DeliverySlippages")
	 * @method({"GET","POST"})
	 */
	
	public function renderDeliverySlippagesAction(Request $request) {
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
	/**
	 * @Route("/KPI/ProdTestAccounts", name="ProdTestAccounts")
	 * @Method({"GET","HEAD"})
	 */
	
	public function renderProdTestAccounts(Request $request) {
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
		
		$check = "in";
		if(empty($userID)){
			return $this->redirectToRoute('Login');
		}
		
		$postData = $request->request->all();
		return $this->render ( 'KPI/ProductionTestAccounts.html.twig', [
		]);
		
	}
	
	/**
	 * @Route("/KPI/ViewProdTestAccounts", name="ViewProdTestAccounts")
	 * @Method({"GET","HEAD"})
	 */
	
	public function renderViewProdTestAccounts(Request $request) {		
		
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
		$KPIRepository = new KPIRepository();
		
		$prodTestAccountsEntitys = array ();		
		$prodTestAccountsEntitys = $KPIRepository->viewProdTestAccounts();
		
		//echo "<pre>";print_r($prodTestAccountsEntitys);exit;
		
		return $this->render ( 'KPI/ViewProductionTestAccounts.html.twig', [
				"ProdTestAccountsEntitys" => $prodTestAccountsEntitys
		]);
	
	}
	
	/**
	 * @Route("/KPI/ProdTestAccounts", name="AddProdTestAccounts")
	 * @Method({"POST","HEAD"})
	 */
	
	public function renderAddProdTestAccounts(Request $request) {
		$check = "in";
		$session = new Session();
		$userID = $session->get('UserID');
		if(empty($userID)){
			return $this->redirectToRoute('Login');
		}
	
		$postData = $request->request->all();
		$KPIRepository = new KPIRepository();
	
		$status = $KPIRepository->addProdTestAccounts($postData);
		$prodTestAccountsEntitys = $KPIRepository->viewProdTestAccounts($postData);
	
		return $this->render ( 'KPI/ViewProductionTestAccounts.html.twig', [
				"ProdTestAccountsEntitys"=>$prodTestAccountsEntitys,
				"msg" =>$status
		]);
	
	}
	
	
	/**
	 * @Route("/KPI/EditProdTestAccounts/{rowId}", name="EditProdTestAccounts")
	 * @Method({"GET","HEAD"})
	 */
	
	public function renderEditProdTestAccounts(Request $request,$rowId) {
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
		$KPIRepository = new KPIRepository();
		
		$prodTestAccountsEntity = $KPIRepository->viewProdTestAccountsForEdit($rowId);
		
		return $this->render ( 'KPI/ProductionTestAccounts.html.twig', [
				"ProdTestAccountsEntity"=>$prodTestAccountsEntity,
				"rowId"=>$prodTestAccountsEntity->getRowId()
		]);
	
	}
	/**
	 * @Route("/KPI/UpdateProdTestAccounts", name="UpdateProdTestAccounts")
	 * @Method({"POST","HEAD"})
	 */
	
	public function renderUpdateProdTestAccounts(Request $request) {
		$session = new Session();
		$userID = $session->get('UserID');
		
		if(empty($userID)){
			return $this->redirectToRoute('Login');
		}
	
		$postData = $request->request->all();
		$KPIRepository = new KPIRepository();
		
		$status = $KPIRepository->updateProdTestAccounts($postData);
		$prodTestAccountsEntitys = $KPIRepository->viewProdTestAccounts();
		
		return $this->render ( 'KPI/ViewProductionTestAccounts.html.twig', [
				"ProdTestAccountsEntitys"=>$prodTestAccountsEntitys,
				"msg" =>$status
		]);
	
	}
}
