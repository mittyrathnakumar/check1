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
		
		$UserDetails = $this->adminRespository->getUserDetails();	
		
		return $this->render('Admin/Users.html.twig', [
				"UserDetails" => $UserDetails
		]);
	
	}
	
	/**
	 * @Route("/Admin/AddUser", name="AddUser")
	 * @Method({"GET","HEAD"})
	 */
	public function renderAddUserAction(Request $request) {
	
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
		
		return $this->render('Admin/AddEditUser.html.twig');
	
	}
	
	/**
	 * @Route("/Admin/AddUser", name="AddUserPost")
	 * @Method({"POST","HEAD"})
	 */
	public function renderAddUserPostAction(Request $request) {
	
		
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
		
		$UserDetails = $this->adminRespository->getUserDetails($UserID);
		
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
		
		$postData = $request->request->all();
		
		//echo "<pre>";print_r($postData);exit;
		$status = $this->adminRespository->addEditUser($postData);
	
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
	 * @Route("/Admin/UserAccess", name="UserAccess")
	 * @Method({"GET","HEAD"})
	 */
	public function renderUserAccessAction(Request $request) {		
		
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
		
		$UserDetails = $this->adminRespository->getUserAccessDetails();		
		
		return $this->render('Admin/UserAccess.html.twig', [
				"UserDetails" => $UserDetails
		]);
	
	}
	
	/**
	 * @Route("/Admin/EditUserAccess/{RoleID}", name="EditUserAccess")
	 * @Method({"GET","POST"})
	 */
	public function renderEditUserAccessAction(Request $request, $RoleID) {
	
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
		
		if(!empty($postData)){
			
			$UserDetails = $this->adminRespository->updateUserAccessDetails($postData);			
			
			return $this->redirectToRoute('UserAccess');
			
			
		} else {
			
			$UserDetails = $this->adminRespository->getUserAccessDetails($RoleID);
			
			return $this->render('Admin/EditUserAccess.html.twig', [
					"UserDetails" => $UserDetails,
					"RoleID" => $RoleID
			]);
		}
	
	}	
			
}
