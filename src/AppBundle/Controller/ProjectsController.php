<?php

namespace AppBundle\Controller;

//use AppBundle\Repository\ProjectRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
//use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;


class ProjectsController extends Controller 
{
	
	/**
	 * @var ReportingRepository
	 */
	private $projectRepository;
	
	public function __construct() {
			
		$this->projectRepository = new ProjectRepository();
		
	}	
	
	/**
	 * @Route("/Projects", name="Projects")
	 * @Method({"GET","HEAD"})
	 */
	
	public function renderProjectsAction(Request $request) {		
		return $this->render('Projects/Projects.html.twig');
	}
	
	
	/**
	 * @Route("/Reporting/ToscaExecutionResults/{app}/{executionType}/{iteration}", name="ToscaExecutionResults")
	 * @Method({"GET","HEAD"})
	 */
	
	public function renderToscaExecutionResults(Request $request, $app, $executionType, $iteration) {
		
		// USER AUTHENTICATION
		$session = new Session();
		$userID = $session->get('UserID');
		
		if(empty($userID)){
			return $this->redirectToRoute('Login');
		}
		
		$executionResults = $this->reportRepository->getToscaExecutionResults($app, $executionType, $iteration);	
		$channelTestResults = $this->reportRepository->getToscaChannelResults($app, $executionType, $iteration);		
		
		return $this->render('Reporting/ToscaExecutionResults.html.twig', [
				"application" => $app,
				"executionType" => $executionType,
				"iteration" => $iteration,
				"executionDetails" => $executionResults,
				"channelTestResults" => $channelTestResults,
				"testCases" => $executionResults->getTestCases()
		]);
	}
	
	/**
	 * @Route("/Reporting/ToscaExecutionResults/{app}/{executionType}/{iteration}", name="ToscaExecutionResultsCharts")
	 * @Method({"POST"})
	 */
	public function returnJsonForToscaExecutionResults(Request $request, $app, $executionType, $iteration) {
		$executionResults = $this->reportRepository->getToscaExecutionResults($app, $executionType, $iteration);
		
		$response = array();		
		$results = $executionResults->getResults();		
		
		if($app == 'Siebel'){			
			$response["statistics"] = array(
				"coreOrder" => $results["coreOrder"],
				"tbUIOrder" => $results["tbUIOrder"],
				"retreivals" => $results["retreivals"],
				"openUIOrder" => $results["openUIOrder"]
			);
			
			$response["totals"] = array(
				"passed" => $results["passed"],
				"failed" => $results["failed"],
				"in_progress" => $results["in_progress"],
				"no_result" => $results["no_result"]
			);
			
			$response["totalorders"] = array(
				"orderPassed" => $results["orderPassed"],
				"orderFailed" => $results["orderFailed"],
				"orderNoRun" => $results["orderNoRun"],
				"orderProgressed" => $results["orderProgressed"]
			);
			
			$response["totalret"] = array(
				"orderRetreivedPassed" => $results["orderRetreivedPassed"],
				"orderRetreivedFailed" => $results["orderRetreivedFailed"],
				"orderRetreivedProgress" => $results["orderRetreivedProgress"],
				"orderRetreivedNoRun" => $results["orderRetreivedNoRun"]
			);
		}	
		else if($app == 'Oracle'){
			
			$response["statistics"] = array(
					"totAccPay" => $results["totAccPay"],
					"totAccRec" => $results["totAccRec"],
					"totCSHMGM" => $results["totCSHMGM"],
					"totFIXASS" => $results["totFIXASS"],					
					"totGENLED" => $results["totGENLED"],
					"totHRPAY" => $results["totHRPAY"],
					"totINV" => $results["totINV"],
					"totOTL" => $results["totOTL"],					
					"totORPUR" => $results["totORPUR"],
					"totORMAN" => $results["totORMAN"],
					"totPA" => $results["totPA"]					
			);
				
			$response["totals"] = array(
					"passed" => $results["totPassed"],
					"failed" => $results["totFailed"],
					"in_progress" => $results["totProgress"],
					"no_result" => $results["totNoRun"]
			);
		}
		else if($app == 'Tallyman'){
				
			$response["statistics"] = array(
					"totSiebTall" => $results["totSiebTall"],
					"totTallSieb" => $results["totTallSieb"]
			);
		
			$response["totals"] = array(
					"passed" => $results["totPassed"],
					"failed" => $results["totFailed"],
					"in_progress" => $results["totProgress"],
					"no_result" => $results["totNoRun"]
			);
		}		
		
		return new JsonResponse($response);
	}
	
	/**
	 * @Route("/Reporting/FusionExecutionHistory/{executionType}/", name="FusionExecutionHistory", defaults={"executionType" = "ALERT TESTING"})
	 * @Method({"GET","HEAD"})
	 */
	public function renderFusionExecutionHistory(Request $request, $executionType) {			
		
		// USER AUTHENTICATION
		$session = new Session();
		$userID = $session->get('UserID');
		
		if(empty($userID)){
			return $this->redirectToRoute('Login');
		}
		
		$executionTypes = $this->reportRepository->getDistinctFusionExecutionTypes();
		$executionResults = $this->reportRepository->getFusionExecutionHistory($executionType);		
		
		return $this->render('Reporting/FusionExecutionHistory.html.twig', [				
				"application" => 'Safe-ST',
				"executionTypes" => $executionTypes,
				"executionType" => $executionType,
				"executionResults" => $executionResults
		]);
	}
	
	/**
	 * @Route("/Reporting/FusionExecutionResults/{executionType}/{iteration}/{release}", name="FusionExecutionResults", defaults={"executionType" = "REGRESSION"})
	 * @Method({"GET","HEAD"})
	 */
	public function renderFusionExecutionResults(Request $request, $executionType, $iteration, $release) {
		
		// USER AUTHENTICATION
		$session = new Session();
		$userID = $session->get('UserID');
		
		if(empty($userID)){
			return $this->redirectToRoute('Login');
		}
		
		$executionResults = $this->reportRepository->getFusionExecutionResults($executionType, $iteration, $release);	
		
		return $this->render('Reporting/FusionExecutionResults.html.twig', [
				"executionDetails" => $executionResults,
				"executionType" => $executionType,
				"testCases" => $executionResults->getFusionTestCases()				
		]);
	}
	
	/**
	 * @Route("/Reporting/FusionExecutionResults/{executionType}/{iteration}/{release}", name="FusionExecutionChartResults")
	 * @Method({"POST"})
	 */
	public function renderFusionExecutionChartResults(Request $request, $executionType, $iteration, $release) {
		$executionResults = $this->reportRepository->getFusionExecutionResults($executionType, $iteration, $release);
	
		$response = array();
		
		$response["totals"] = array(				
				"passed" =>  $executionResults->getTotalPassTestCaseCount(),
				"failed" =>  $executionResults->getTotalFailTestCaseCount()
		);
		
		return new JsonResponse($response);
	}	

	/**
	 * @Route("/Reporting/FusionServiceExecutionResults/{executionType}/{iteration}/{release}/{servicename}", name="FusionServiceExecutionResults")
	 * @Method({"GET","HEAD"})
	 */
	public function renderFusionServiceExecutionResults(Request $request, $executionType, $iteration, $release, $servicename) {
		
		// USER AUTHENTICATION
		$session = new Session();
		$userID = $session->get('UserID');
		
		if(empty($userID)){
			return $this->redirectToRoute('Login');
		}
		
		$executionResults = $this->reportRepository->getFusionServiceExecutionResults($executionType, $iteration, $release, $servicename);
			
		return $this->render('Reporting/FusionServiceExecutionResults.html.twig', [
				"executionDetails" => $executionResults,
				"executionType" => $executionType,
				"testCases" => $executionResults->getFusionTestCases(),
				"servicename" => $servicename
		]);
	}
	
	/**
	 * @Route("/Reporting/FusionServiceExecutionResults/{executionType}/{iteration}/{release}/{servicename}", name="FusionServiceExecutionChartResults")
	 * @Method({"POST"})
	 */
	public function renderFusionServiceExecutionChartResults(Request $request, $executionType, $iteration, $release, $servicename) {
		$executionResults = $this->reportRepository->getFusionServiceExecutionResults($executionType, $iteration, $release, $servicename);	
	
		$response = array();
		
		$response["totals"] = array(				
				"passed" =>  $executionResults->getTotalPassTestCaseCount(),
				"failed" =>  $executionResults->getTotalFailTestCaseCount()
		);
		
		return new JsonResponse($response);
	}

	/**
	 * @Route("/Reporting/FusionTCExecutionResults/{executionType}/{iteration}/{release}/{servicename}/{operationname}/{testcasename}", name="FusionTCExecutionResults")
	 * @Method({"GET","HEAD"})
	 */
	public function renderFusionTCExecutionResults(Request $request, $executionType, $iteration, $release, $servicename, $operationname, $testcasename) {
		
		// USER AUTHENTICATION
		$session = new Session();
		$userID = $session->get('UserID');
		
		if(empty($userID)){
			return $this->redirectToRoute('Login');
		}
		
		$executionResults = $this->reportRepository->getFusionTCExecutionResults($executionType, $iteration, $release, $servicename, $operationname, $testcasename);
		$xmls = $this->reportRepository->getFusionTCXML($executionType, $iteration, $release, $servicename, $operationname, $testcasename);		
	
		return $this->render('Reporting/FusionTCExecutionResults.html.twig', [
					"executionDetails" => $executionResults,
					"executionType" => $executionType,	
					"iteration" => $iteration,
					"release" => $release,
					"servicename" => $servicename,
					"operationname" => $operationname,
					"testcasename" => $testcasename,
					"xml" => $xmls
				]);
	}
	
	
	
	/**
	 * @Route("/Reporting/SiebelTestDataReports/{env}", name="SiebelTestDataReports", defaults={"env" = "E2E01"})
	 * @Method({"GET","HEAD"})
	 */
	public function renderSiebelTestDataReports(Request $request, $env) {	
		
		// USER AUTHENTICATION
		$session = new Session();
		$userID = $session->get('UserID');
		
		if(empty($userID)){
			return $this->redirectToRoute('Login');
		}
		
		$envList = $this->reportRepository->getEnvironment();
		$ReportResults = $this->reportRepository->getSiebelTestDataReports($env);
	
		return $this->render('Reporting/SiebelTestDataReports.html.twig', [
				"env" => $env,
				"envList" => $envList,
				"ReportResults" => $ReportResults
		]);
	}
		
}
