<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;

use AppBundle\Repository\AdminRepository;




/**
 * @author Dhara Sheth
 */
class AdminController extends Controller 
{	
	
	/**
	 * @var adminRepository
	 */
	
	private $adminRespository;
	
	public function __construct(){
		$this->adminRespository = new AdminRepository();		
	}	
	
	
	/**
	 * @Route("/Admin/Users", name="Users")
	 * @Method({"GET","HEAD"})
	 */
	public function renderUsersAction(Request $request) {
	
		// USER AUTHENTICATION
		/*$session = new Session();
		$userID = $session->get('UserID');
	
		if(empty($userID)){
			return $this->redirectToRoute('Login');
	
		}*/
		
		/*if($request->query->all()['status'] != '')
			$status = $request->query->all()['status'];
		else 
			$status = '';
		*/
		
		$UserDetails = $this->adminRespository->getUserDetails();
		
		//echo "<pre>";print_r($UserDetails);exit;
		
		return $this->render('Admin/Users.html.twig', [
				"UserDetails" => $UserDetails
		]);
	
	}
	
	/**
	 * @Route("/Admin/AddUser", name="AddUser")
	 * @Method({"GET","HEAD"})
	 */
	public function renderAddUserAction(Request $request) {
	
		// USER AUTHENTICATION
		/*$session = new Session();
		$userID = $session->get('UserID');
	
		if(empty($userID)){
			return $this->redirectToRoute('Login');
	
		}*/
		
		return $this->render('Admin/AddEditUser.html.twig');
	
	}
	
	/**
	 * @Route("/Admin/AddUser", name="AddUserPost")
	 * @Method({"POST","HEAD"})
	 */
	public function renderAddUserPostAction(Request $request) {
	
		// USER AUTHENTICATION
		/*$session = new Session();
			$userID = $session->get('UserID');
	
			if(empty($userID)){
			return $this->redirectToRoute('Login');
	
			}*/
		
		$UserDetails = $this->adminRespository->getUserDetails();
		$postData = $request->request->all();
		
		$status = $this->adminRespository->addEditUser($postData);
		
		/*return $this->render('Admin/Users.html.twig', [
				"UserDetails" => $UserDetails,
				"status" => $status
		]);*/
		
		return $this->redirectToRoute('Users');
	
	}
	
	/**
	 * @Route("/Admin/EditUser/{UserID}", name="EditUser")
	 * @Method({"GET","HEAD"})
	 */
	public function renderEditUserAction(Request $request, $UserID) {
	
		// USER AUTHENTICATION
		/*$session = new Session();
			$userID = $session->get('UserID');
	
			if(empty($userID)){
			return $this->redirectToRoute('Login');
	
			}*/
	
		//echo $UserID;exit;
		$UserDetails = $this->adminRespository->getUserDetails($UserID);
		//echo "<pre>";print_r($UserDetails);exit;
		return $this->render('Admin/AddEditUser.html.twig', [
				"UserDetails" => $UserDetails,
				"UserID" => $UserID
		]);
	
	}
	
	/**
	 * @Route("/Admin/EditUser", name="EditUserPost")
	 * @Method({"POST","HEAD"})
	 */
	public function renderEditUserPostAction(Request $request) {
	
		// USER AUTHENTICATION
		/*$session = new Session();
		 $userID = $session->get('UserID');
	
		 if(empty($userID)){
		 return $this->redirectToRoute('Login');
	
		 }*/
	
		//$UserDetails = $this->adminRespository->getUserDetails();
		$postData = $request->request->all();
		
		//echo "<pre>";print_r($postData);exit;
		$status = $this->adminRespository->addEditUser($postData);
	
		/*return $this->render('Admin/Users.html.twig', [
				"UserDetails" => $UserDetails,
				"status" => $status
		]);*/
		
		return $this->redirectToRoute('Users', array("status" => $status));
	
	}	
	
	
	
	
	
	
	
	/**
	 * @Route("/Admin/CreateUser", name="CreateUserSubmit")
	 * @Method({"POST"})
	 */
	public function renderCreateUserSubmitAction(Request $request) {
	
		$email = $request->request->get('emailid');
		$password = $request->request->get('password');
		$firstname = $request->request->get('firstname');
		$lastname = $request->request->get('lastname');
		$role = $request->request->get('userrole');
	
		$adminRepository = new AdminRepository();
		$result = $adminRepository->createUser($email, $password, $firstname, $lastname, $role);
	
		return new JsonResponse($result);
	
	}
		
	/**
	 * @Route("/Admin/TDMTrackerAdmin", name="TDMTrackerAdmin")
	 */
	public function renderTDMRequestListAction(Request $request) {		
		
		// USER AUTHENTICATION
		$session = new Session();
		$userID = $session->get('UserID');
		
		if(empty($userID)){
			return $this->redirectToRoute('Login');
				
		}
		
		$assistingToolsRepository = new TestAssistingToolsRepository();
		$requestList = $assistingToolsRepository->getTDMRequestList();		
		
		return $this->render('Admin/TDMTrackerAdmin.html.twig', [
				"requestList" => $requestList
		]);
	}
	
	/**
	 * @Route("/Admin/TDMTrackerAdminUpdate/{refNum}", name="TDMTrackerAdminUpdate")
	 * @Method({"GET","HEAD"})
	 */	
	
	public function renderTDMTrackerRequestAction(Request $request, $refNum) {
		
		// USER AUTHENTICATION
		$session = new Session();
		$userID = $session->get('UserID');
		
		if(empty($userID)){
			return $this->redirectToRoute('Login');
		
		}
		
		$assistingToolsRepository = new TestAssistingToolsRepository();
		$requestList = $assistingToolsRepository->getTDMRequestRequestDetails($refNum);
	
		return $this->render('Admin/TDMTrackerAdminUpdate.html.twig', [
				"requestList" => $requestList
		]);
	}
	
	/**
	 *  @Route("/Admin/TDMTrackerAdminUpdate/{refNum}", name="TDMTrackerAdminUpdateData")
	 *  @Method({"POST"})
	 */
	public function renderTDMTrackerAdminUpdateDataAction(Request $request) {
	
		$comments = $request->request->get('comments');
		$referenceno = $request->request->get('referenceno');
		$status = $request->request->get('status');
	
		$objAdminRepo = new AdminRepository();
		$response = $objAdminRepo->updateTDMTrackerAdminRequestData($comments, $referenceno, $status);
	
		return new JsonResponse($response);
	}
	
	
	/**
	 * @Route("/Admin/SystemDetails", name="SystemDetails")
	 * @Method({"GET", "HEAD"})
	 */
	public function renderSystemDetailsAction(Request $request) {
		
		// USER AUTHENTICATION
		$session = new Session();
		$userID = $session->get('UserID');
		
		if(empty($userID)){
			return $this->redirectToRoute('Login');
				
		}

		$adminRepository = new AdminRepository();
		$machineDetails = $adminRepository->getMachineList();	
		
		return $this->render('Admin/SystemDetails.html.twig', [
				"machineDetails" => $machineDetails
		]);
		
	}
	
	/**
	 * @Route("/Admin/SystemDetails", name="UpdateSystemDetails")
	 * @Method({"POST"})
	 */
	public function renderUpdateSystemDetailsAction(Request $request) {
		$columnname = $request->request->get('column');
		$value = $request->request->get('value');
		$hostname = $request->request->get('hostname');
		
		$adminRepository = new AdminRepository();
		$systemDetailsUpdate = $adminRepository->updateSystemDetails($columnname, $value, $hostname);
	
		return new JsonResponse($systemDetailsUpdate);
	}
	
	/**
	 * @Route("/Admin/AdminDashboard", name="AdminDashboard")
	 * @Method({"GET", "HEAD"})
	 */
	public function renderAdminDashboardAction(Request $request) {
		
		// USER AUTHENTICATION 
		$session = new Session();
		$userID = $session->get('UserID');
		
		if(empty($userID)){
			return $this->redirectToRoute('Login');
			
		}
		
		
		return $this->render('Admin/AdminDashboard.html.twig');
		
	}
	
	/**
	 * @Route("/Admin/AdminDashboard", name="AdminDashboardCharts")
	 * @Method({"POST"})
	 */
	public function renderAdminDashboardChartsAction(Request $request) {
		$adminRepository = new AdminRepository();
		$result = $adminRepository->getTDMRequestDetails();	
		
		$response = array();
		
		$response["tdm_requeststatus"] = array(
				"complete" =>  $result['complete'],
				"inprogress" =>  $result['inprogress'],
				"delay" =>  $result['delay'],
				"newrequest" =>  $result['newrequest']				
		);
		
		$response["tdm_yearlystatus"] = array(
				"Q1" =>  $result['Q1'],
				"Q2" =>  $result['Q2'],
				"Q3" =>  $result['Q3'],
				"Q4" =>  $result['Q4']
		);	
		
		return new JsonResponse($response);
	}
	
	
	
	/**
	 * @Route("/Admin/AutomationDSR", name="AutomationDSR")
	 * @Method({"GET","HEAD"})
	 */
	public function renderAutomationDSRAction(Request $request) {
		
		// USER AUTHENTICATION
		$session = new Session();
		$userID = $session->get('UserID');
		
		if(empty($userID)){
			return $this->redirectToRoute('Login');
		
		}
		
		$adminRepository = new AdminRepository();		
		$result = $adminRepository->getProjectRelease();
			
	
		return $this->render('Admin/AutomationDSR.html.twig',[
				"prlist" => $result
		]);
	
	}	
	
	/**
	 * @Route("/Admin/AutomationDSR/{project}/{release}", name="AutomationDSRProjectSelect")
	 * @Method({"GET","HEAD"})
	 */
	public function renderAutomationDSRPRAction(Request $request, $project, $release) {
		
		// USER AUTHENTICATION
		$session = new Session();
		$userID = $session->get('UserID');
		
		if(empty($userID)){
			return $this->redirectToRoute('Login');
		
		}
		
		$adminRepository = new AdminRepository();
		$result = $adminRepository->getProjectRelease();
		$dsrresult = $adminRepository->getAutomationDSRData($project, $release);	
		
		return $this->render('Admin/AutomationDSR.html.twig',[
				"project" => $project,
				"release" => $release,
				"prlist" => $result,
				"testCaseDetails" => $dsrresult->getTestCases(),
				"totals" => $dsrresult->getResults()
		]);
	
	}
	
	/**
	 * @Route("/Admin/AutomationDSR/{project}/{release}", name="AutomationDSRChartAction")
	 * @Method({"POST"})
	 */
	public function renderAutomationDSRPRChartAction(Request $request, $project, $release) {
		$adminRepository = new AdminRepository();
		$result = $adminRepository->getProjectRelease();
		$dsrresult = $adminRepository->getAutomationDSRData($project, $release);		
		
		$totals = $dsrresult->getResults();
		$testcaseAssignedCount = $dsrresult->getTestCaseAssignedCount();
		$testcaseCompletedCount = $dsrresult->getTestCaseCompletedCount();
		$testcaseDates = $dsrresult->getDateArray();		
		
		$response = array();		
		
		$response["project_totals"] = array(
				"completed" => $totals['completed'],
				"notstarted" => $totals['notstarted'],
				"inreview" => $totals['inreview'],
				"inprogress" => $totals['inprogress'],
				"descoped" => $totals['descoped'],
				"blocked" => $totals['blocked']
		);
		
		$response["project_barchart"] = array(
				"assignedresult" => $testcaseAssignedCount,
				"completedresult" => $testcaseCompletedCount,
				"testdates" => $testcaseDates
		);
		
		return new JsonResponse($response);
	
	}
	
	/**
	 * @Route("/Admin/AutomationMatrices/{env}", name="AutomationMatrices", defaults = { "env" = "Oracle" })
	 * @Method({"GET","HEAD"})
	 */
	public function renderAutomationMatricesAction(Request $request, $env) {
		
		// USER AUTHENTICATION
		$session = new Session();
		$userID = $session->get('UserID');
		
		if(empty($userID)){
			return $this->redirectToRoute('Login');
		
		}
		
		$adminRepository = new AdminRepository();
		$matriceDetails = $adminRepository->getAutomationMatricesData($env);
		
		
	
		return $this->render('Admin/AutomationMatrices.html.twig',[
				"env" => $env,
				"matriceDetails" => $matriceDetails
		]);		
		
	
	}
	
	/**
	 * @Route("/Admin/AutomationMatrices/{env}", name="AutomationMatricesChart", defaults = { "env" = "Oracle" })
	 * @Method({"POST"})
	 */
	public function renderAutomationMatricesChartAction(Request $request, $env) {
		$adminRepository = new AdminRepository();		
		$from = strtoupper($request->request->get('from'));
		$to = strtoupper($request->request->get('to'));
		
		$matriceDetails = $adminRepository->getAutomationMatricesData($env);
		
		//$from = '01/OCT/14';
		//$to = '04/NOV/15';
		
		$barchartData = $adminRepository->getBarChartData($env, $from, $to);
		
		$response = array();
		$response['env'] = $env;
		
		if($env == 'Oracle'){
			$response['env_totals'] = array(
					"REGRESSION" => $matriceDetails[0]->getExecutionTotal(),
					"SHAKEDOWN" => $matriceDetails[1]->getExecutionTotal(),
			);
			
			$response['env_barchartdata'] = array(
					"DATECOUNTS" => $barchartData
			);
			
		}
		
		else if($env == 'Siebel'){			
			
			$response['env_totals'] = array(
					"TESTDATA" => $matriceDetails[4]->getExecutionTotal(),
					"SHAKEDOWN" => $matriceDetails[3]->getExecutionTotal(),
					"REGRESSION" => $matriceDetails[0]->getExecutionTotal(),
					"CVT" => $matriceDetails[2]->getExecutionTotal(),
						
			);
			
			$response['env_barchartdata'] = array(
					"DATECOUNTS" => $barchartData
			);
		}
		else if($env == 'Fusion'){
			
			$response['env_totals'] = array(
					"REGRESSION" => $matriceDetails[0]->getExecutionTotal(),
					"ALERT" => $matriceDetails[1]->getExecutionTotal(),
			);
			
			$response['env_barchartdata'] = array(
					"DATECOUNTS" => $barchartData
			);
		}	
			
		return new JsonResponse($response);
	
	}
}
