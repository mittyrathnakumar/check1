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
	
		
		return $this->render('KPI/ITSMDefects.html.twig', [
				"DefectDetails" => $DefectDetails
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
		$DefectDetails = $KPIRepository->getDefectDetails();				
		
		return $this->render('KPI/ITSMDefects.html.twig', [
				"DefectDetails" => $DefectDetails,
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
		$postData = $request->request->all();
		
		
		/* Refresh the page */
		
		if(!empty($postData['ShowAll']) && $postData['ShowAll'] == 1){					
			return $this->redirectToRoute('IntakeProcess');
		}
		
		/* === */
		
		/* Delete Intake Process */
		
		if(!empty($postData['action']) && $postData['action'] == 'deleteProcess'){
				
			$response = $KPIRepository->deleteIntakeProcess($postData['ID']);
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
			
			$ProcessDetails = $KPIRepository->getProcessDetails($Month, $postData, "");
			$monthYearArray = $KPIRepository->getmonthArray();			

			return $this->render('KPI/IntakeProcess.html.twig', [
					"ProcessDetails" => $ProcessDetails,
					"monthYearArray" => $monthYearArray,
					"Month" => $Month
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
					
			$status = $KPIRepository->addEditIntakeProcessDetails($postData);
			return $this->redirectToRoute('IntakeProcess');
		}
		
		/*  Show form to add data */
		else {	
			return $this->render('KPI/AddEditIntakeProcess.html.twig');				
		}
	
	}
	
	/**
	 * @Route("/KPI/EditIntakeProcess/{IntakeID}/{Month}", name="EditMonthIntakeProcess")
	 * @Method({"GET", "POST"})
	 */
	public function renderEditMonthIntakeProcessAction(Request $request, $IntakeID, $Month) {
	
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

			if(!empty($postData['IntakeID'])){
				$status = $KPIRepository->addEditIntakeProcessDetails($postData, $postData['IntakeID']);
				$response = array("Month" => $postData['Month']);
				return $this->redirectToRoute('IntakeProcess', $response);
			}
				
		}
	
		/*  Show form to edit data */
		
		else {
			if(!empty($IntakeID)){
				$KPIRepository = new KPIRepository();
				$ProcessDetails = $KPIRepository->getProcessDetails($Month, $postData, $IntakeID);
					
				return $this->render('KPI/AddEditIntakeProcess.html.twig', [
						"ProcessDetails" => $ProcessDetails,
						"IntakeID" => $IntakeID,
						"Month" => $Month
				]);
			}
		}
	
	}
	
	/**
	 * @Route("/KPI/Documentation", name="Documentation")
	 * @Method({"GET","POST"})
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
		$postData = $request->request->all();
		
	
		/* Refresh the page */
		
		if(!empty($postData['ShowAll']) && $postData['ShowAll'] == 1){			
			return $this->redirectToRoute('Documentation');
		}
		
		/* === */		

		/* Delete Intake Process */
		
		if(!empty($postData['action']) && $postData['action'] == 'deleteDocument'){
		
			$response = $KPIRepository->deleteDocument($postData['ID']);
			return new JsonResponse($response);
		
		}
		
		/* === */
		
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
	
			$DocumentDetails = $KPIRepository->getDocumentDetails($Month, $postData, "");
			$monthYearArray = $KPIRepository->getmonthArray();			
			
			return $this->render('KPI/Documentation.html.twig', [
					"DocumentDetails" => $DocumentDetails,
					"monthYearArray" => $monthYearArray,
					"Month" => $Month
			]);
		}
	
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
		$KPIRepository = new KPIRepository();	
		
		/* Project Autocomplete Request */
		
		$keyword = $request->query->get('term');
		
		if(!empty($keyword)){
			$result = $KPIRepository->getAutoCompleteData($keyword, 1);
			echo $result; /* Displays the fetched Projects as a Drop down for Auto Complete */
			exit;
		}
		
		/* Submit Added Data */
		if(!empty($postData)){				
		
			$status = $KPIRepository->addEditDocumentationDetails($postData);
			return $this->redirectToRoute('Documentation');
		}
	
		/*  Show form to add data */
		
		else {
			return $this->render('KPI/AddEditDocumentDetails.html.twig');
		}
	
	}
	
	/**
	 * @Route("/KPI/EditDocumentDetails/{DocumentID}/{Month}", name="EditMonthDocumentDetails")
	 * @Method({"GET", "POST"})
	 */
	public function renderEditMonthDocumentDetailsAction(Request $request, $DocumentID, $Month) {
	
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
		
			if(!empty($postData['DocumentID'])){

				$status = $KPIRepository->addEditDocumentationDetails($postData, $postData['DocumentID']);
				$response = array("Month" => $postData['Month']);
				return $this->redirectToRoute('Documentation', $response);
			}
				
		}
	
		/*  Show form to edit data */
		
		else {
			if(!empty($DocumentID)){				
				$DocumentDetails = $KPIRepository->getDocumentDetails($Month, $postData, $DocumentID);			
				$DocumentDetails = $DocumentDetails[0];				
					
				return $this->render('KPI/AddEditDocumentDetails.html.twig', [
						"DocumentDetails" => $DocumentDetails,						
						"DocumentID" => $DocumentID,
						"Month" => $Month
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
		$postData = $request->request->all();
		
		/* Refresh the page */
		
		if(!empty($postData['ShowAll']) && $postData['ShowAll'] == 1){
			$response = array("Month" => strtoupper(date('M-y')));
			return $this->redirectToRoute('QualityEstimation', $response);
		}
		
		/* === */
		
		/* Delete QE Record */
		
		if(!empty($postData['action']) && $postData['action'] == 'deleteQE'){
		
			$response = $KPIRepository->deleteQualityOfEstimation($postData['ID']);
			return new JsonResponse($response);
		
		}
		
		/* === */
		
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
			
			$status = $KPIRepository->addEditEstimationnDetails($postData, "", $userID);
			return $this->redirectToRoute('QualityEstimation');
		}
	
		/*  Show form to add data */
		
		else {		
			return $this->render('KPI/AddEditQEDetails.html.twig');
		}
	
	}
	
	
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
		$KPIRepository = new KPIRepository();	
	
		/* Submit Data */
		
		if(!empty($postData)){
	
			if(!empty($ID)){
				$status = $KPIRepository->addEditEstimationnDetails($postData, $ID, $userID);
				$response = array("Month" => $postData['Month']);
				return $this->redirectToRoute('QualityEstimation', $response);
			}
	
		}
	
		/*  Show form to edit data */
		
		else {
			if(!empty($ID)){								
				$EstimationDetails = $KPIRepository->getEstimationDetails($Month, $postData, $ID);
				
				return $this->render('KPI/AddEditQEDetails.html.twig', [
						"EstimationDetails" => $EstimationDetails,
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
		
		$KPIRepository = new KPIRepository();
		$DashRepository = new DashboardRepository();
		
		$postData = $request->request->all();
		
		/* Update Dates */
		
		if(!empty($postData['action'])){		
			$response = $KPIRepository->updateDate($postData['newDate'], $postData['projectID'],$postData['action']);		
			return new JsonResponse($response);
	
		}
		
		/* === */

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
		
		$KPIRepository = new KPIRepository();
		$DashRepository = new DashboardRepository();		
		$postData = $request->request->all();
		
		
		if(!empty($postData['action'])){
			
			$response = $KPIRepository->updateSTAutomation($postData['newValue'], $postData['projectID'],$postData['action']);			
			return new JsonResponse($response);
	
		}

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
	

			return $this->render ( 'KPI/STAutomation.html.twig', [
					"STAutomation" => $STAutomation,
					"monthYearArray" => $monthYearArray,
					"Month" => $Month
			]);
		}
	}
	
	/**
	 * @Route("/KPI/ViewProdTestAccounts", name="ViewProdTestAccounts")
	 * @Method({"GET","POST"})
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
		$postData = $request->request->all();		
		
		/* Delete Record */
		
		if(!empty($postData['action']) && $postData['action'] == 'deleteProdTestAccData'){
		
			$response = $KPIRepository->deleteProdTestAccData($postData['ID']);
			return new JsonResponse($response);
		
		}
	
		$prodTestAccountsEntitys = array ();
		$prodTestAccountsEntitys = $KPIRepository->viewProdTestAccounts();	
	
		return $this->render ( 'KPI/ViewProductionTestAccounts.html.twig', [
				"ProdTestAccountsEntitys" => $prodTestAccountsEntitys
		]);
	
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
		
	
		$postData = $request->request->all();
		
		return $this->render ( 'KPI/ProductionTestAccounts.html.twig', [
				]);
		
	}
	
	
	
	/**
	 * @Route("/KPI/ProdTestAccounts", name="AddProdTestAccounts")
	 * @Method({"POST","HEAD"})
	 */
	
	public function renderAddProdTestAccounts(Request $request) {
		
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
