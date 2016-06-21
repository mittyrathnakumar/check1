<?php

namespace AppBundle\Controller;

use AppBundle\Repository\DashboardRepository;
//use AppBundle\Repository\ProjectRepository;
//use AppBundle\Service\Constants;

//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;


class DashboardController extends Controller 
{	
	
	/**
	 * @Route("/Dashboard", name="Dashboard")
	 * @method({"GET","HEAD"})
	 */
	
	public function renderDashboardAction(Request $request) {	
		
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
		
		$dashboardRepository = new DashboardRepository();
		
		$KPIResults = $dashboardRepository->getKPIResults();
		$monthYearArray = $dashboardRepository->getmonthYearArray();
	
		return $this->render('Dashboard/Dashboard.html.twig', [
				"KPIResults" => $KPIResults,
				"monthYearArray" => $monthYearArray
		]);
	}
	
	/**
	 * @Route("/Dashboard", name="DashboardPost")
	 * @method({"POST","HEAD"})
	 */
	
	public function renderDashboardPostAction(Request $request) {
	
		$session = new Session();
		$userID = $session->get('UserID');
	
		if(empty($userID)){
			return $this->redirectToRoute('Login');
		}
	
		$postData = $request->request->all();
		$dashboardRepository = new DashboardRepository();
	
		if(!empty($postData['ShowAll']) && $postData['ShowAll'] == 1){
			return $this->redirectToRoute('Dashboard');
		}	
		
		/* Ajax Post request to update Monthly KPI Cause & Action */
		
		if(!empty($postData['action']) && $postData['action'] == 'updateMonthlyCauseAction'){
			//$response = array();
			$response = $dashboardRepository->updateMonthlyKPIAction($userID, $postData['causeInsert'], $postData['actionInsert'],
					$postData['kpiid'], $postData['month']);
			return new JsonResponse($response);
		}
		
		/* === */
	
		
		
		/* Ajax Post request to update Project based Cause & Action */
	
		if(!empty($postData['action']) && $postData['action'] == 'updateCauseAction'){
			//$dashboardRepository->updateKPIAction($userID, $newVal, $KPIID, $month);
			$dashboardRepository->updateKPIAction($userID, $postData);		
		}
		
		/* === */
		
		
		if(isset($postData['Weekly']) && $postData['Weekly'] == 1)
			$Weekly = $postData['Weekly'];
		else 
			$Weekly = 0;
		
		$KPIResults = $dashboardRepository->getKPIResults($postData['Month'], "", $Weekly);
		$monthYearArray = $dashboardRepository->getmonthYearArray();
	
		//echo "<pre>";print_r($KPIResults);exit;
		//echo $postData['Month'];exit;
	
		return $this->render('Dashboard/Dashboard.html.twig', [
				"KPIResults" => $KPIResults,
				"monthYearArray" => $monthYearArray,
				"postData" => $postData
		]);
	}
	
	/**
	 * @Route("/ViewCauseAction/{KPIID}/{Month}", name="ViewCauseAction")
	 * @method({"GET","HEAD"})
	 */
	
	public function renderViewCauseActionAction(Request $request, $KPIID, $Month) {
		
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
	
		if(!empty($postData['action']) && $postData['action'] == 'updateMonthlyCauseAction'){		
						
			$response = $dashboardRepository->updateMonthlyKPIAction($userID, $postData['cause'], $postData['action'],
					$KPIID, $Month);
			return new JsonResponse($response);
			
		} 
		else {
			
			$resultArr = array();
			$resultArr = $dashboardRepository->getMonthlyCauseAction($KPIID, $Month);
			return $this->render('Dashboard/ViewCauseAction.html.twig', [
					"KPIID" => $KPIID,
					"Month" => $Month,
					"resultArr" => $resultArr
			]);
			
		}
	}
	
	
	/**
	 * @Route("/CauseActionSubmit", name="CauseActionSubmit")
	 * @method({"POST"})
	 */
	
	public function renderCauseActionSubmitAction(Request $request) {
		
		$postData = $request->request->all();
		
		echo "<pre>";
		print_r($postData);exit;
	
		$session = new Session();
		$userID = $session->get('UserID');
	
		if(empty($userID)){
			return $this->redirectToRoute('Login');	
		}
		
		if(!empty($postData['action']) && $postData['action'] == 'updateMonthlyCauseAction'){
			$dashboardRepository = new DashboardRepository();
			$response = $dashboardRepository->updateMonthlyKPIAction($userID, $postData['cause'], $postData['action'],
					$postData['KPIID'], $postData['Month']);
			return new JsonResponse($response);
		}	
	}
	
	
	/**
	 * @Route("/KPIList", name="KPIList")
	 * @method({"GET","HEAD"})
	 */
	
	public function renderKPIListAction(Request $request) {
	
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
		
		return $this->render('Dashboard/KPIList.html.twig');
	}	

	
	/**
	 * @Route("/KPIProjects/{KPIID}/{Month}", name="KPIProjectsSearch")
	 * @method({"GET","POST"})
	 */
	
	public function renderKPIMonthAction(Request $request, $KPIID, $Month) {
		
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
	
		$dashboardRepository = new DashboardRepository();		
		$postData = $request->request->all();
		
		/* Ajax Post request to update Cause & Action */
		
		if(!empty($postData['action']) && $postData['action'] == 'updateCauseAction'){
			$dashboardRepository->updateKPIAction($userID, $postData);
		}
		
		$KPIResults = $dashboardRepository->getKPIResults($Month, $KPIID);
		$monthYearArray = $dashboardRepository->getmonthYearArray();
		$KPIData = $dashboardRepository->getKPIData($KPIID);
	
		//echo "<pre>";print_r($KPIResults);exit;
	
		return $this->render('Dashboard/KPIProjectDetails.html.twig', [
				"KPIResults" => $KPIResults,
				"monthYearArray" => $monthYearArray,
				"KPI" => $KPIData['KPI_NAME'],
				"KPIData" => $KPIData,
				"KPIID" => $KPIID,
				"Month" => $Month
		]);
	
	}
	
}
