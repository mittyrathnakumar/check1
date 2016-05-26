<?php

namespace AppBundle\Controller;

use AppBundle\Repository\DashboardRepository;
use AppBundle\Repository\ProjectRepository;
use AppBundle\Service\Constants;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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
		
		$session = new Session();
		$userID = $session->get('UserID');
		
		if(empty($userID)){
			return $this->redirectToRoute('Login');
		
		}
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
		
		if(!empty($postData['action']) && $postData['action'] == 'updateCauseAction'){			
			$dashboardRepository->updateKPIAction($userID, $newVal, $KPIID, $month);
		}
		
				
		$KPIResults = $dashboardRepository->getKPIResults($postData['Month'], "");
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
	 * @Route("/Dashboard/{Month}", name="DashboardMonth")
	 * @method({"GET","POST"})
	 */
	
	/*public function renderDashboardMonthAction(Request $request, $Month) {
	
		$session = new Session();
		$userID = $session->get('UserID');
	
		if(empty($userID)){
			return $this->redirectToRoute('Login');
		}
		
		$postData = $request->request->all();
		
		if(!empty($postData['Month']))	
			$Month = $postData['Month'];		
		
		if(!empty($postData['ShowAll']) && $postData['ShowAll'] == 1){
			return $this->redirectToRoute('Dashboard');
		}
	
		$KPIResults = $this->dashboardRepository->getKPIResults("", "", $Month);
		$monthYearArray = $this->dashboardRepository->getmonthYearArray();
	
		//echo "<pre>";print_r($KPIResults);exit;
	
		return $this->render('Dashboard/Dashboard.html.twig', [
				"KPIResults" => $KPIResults,
				"monthYearArray" => $monthYearArray,
				"Month" => $Month
		]);
	}*/
	
	/**
	 * @Route("/KPIProjects/{KPIID}", name="KPIProjects")
	 * @method({"GET","HEAD"})
	 */
	
	/*public function renderKPIProjectsAction(Request $request, $KPIID) {
		
		$session = new Session();
		$userID = $session->get('UserID');
		
		if(empty($userID)){
			return $this->redirectToRoute('Login');		
		}		
	
		$dashboardRepository = new DashboardRepository();
		$KPIResults = $dashboardRepository->getKPIResults("", $KPIID);
		$monthYearArray = $dashboardRepository->getmonthYearArray();
		$KPIData = $dashboardRepository->getKPIData($KPIID);
	
		//echo "<pre>";print_r($KPIResults);exit;
	
		return $this->render('Dashboard/KPIProjectDetails.html.twig', [
				"KPIResults" => $KPIResults,
				"monthYearArray" => $monthYearArray,
				"KPI" => $KPIData['KPI_NAME'],
				"KPIData" => $KPIData
		]);
		
	}	
	*/
	
	/**
	 * @Route("/KPIProjects/{KPIID}/{Month}", name="KPIProjectsSearch")
	 * @method({"GET","HEAD"})
	 */
	
	public function renderKPIMonthAction(Request $request, $KPIID, $Month) {
		
		$session = new Session();
		$userID = $session->get('UserID');
		
		if(empty($userID)){
			return $this->redirectToRoute('Login');		
		}	
	
		$dashboardRepository = new DashboardRepository();		
		
		
		$KPIResults = $dashboardRepository->getKPIResults($Month, $KPIID);
		$monthYearArray = $dashboardRepository->getmonthYearArray();
		$KPIData = $dashboardRepository->getKPIData($KPIID);
	
		/* Defect based KPI Calculation */
		$DefectBasedKPIs = array('6', '7', '17', '18', '19', '20');
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
