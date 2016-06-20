<?php

namespace AppBundle\Repository;


use AppBundle\Service\OracleDatabaseService;
use AppBundle\Service\Validate;
use AppBundle\Entity\Users;



class UserRepository
{
	/**
	 * @var OracleDatabaseService
	 */
	private $oracle;
	
	/**
	 * 
	 */
	public function __construct() {
		$this->oracle = new OracleDatabaseService();
	}	
	
	
	/**
	 * @param $email, $password
	 */
	
	public function fetchUserDetails($email, $password) {
		$this->oracle->openConnection('KPIDASHBOARD');
		$conn = $this->oracle->getConnection();
		
		$objValidate = new Validate();
		$email = $objValidate->cv($email);
		$password = $objValidate->cv($password);		

		$query = "SELECT * FROM KPI_USERS
				WHERE EMAILID = :email				
				AND ACTIVE = 1";		
		
		$queryParse = oci_parse($conn, $query);		
		oci_bind_by_name($queryParse, ':email', $email);				
		
		oci_execute($queryParse);
		
		$temp = '';
		$user = new Users();
		while($row = oci_fetch_array($queryParse)){
			$temp = 'Record found';			
			
			if (password_verify($password, $row['PASSWORD'])) {					
				
				$user->setEmail($row['EMAILID']);
				$user->setFirstName($row['FIRSTNAME']);
				$user->setLastName($row['LASTNAME']);
				$user->setUserRole($row['ROLEID']);
				$user->setUserID($row['USERID']);
				$user->setUserActive($row['ACTIVE']);
				
				$query = "SELECT HOME_PAGE FROM KPI_USERROLE
						WHERE ROLEID = ".$row['ROLEID'];
				$queryParse = oci_parse($conn, $query);
				oci_execute($queryParse);
				$result = oci_fetch_array($queryParse);
				
				//print_r($result);exit;
				
				$user->setUserHomePage($result['HOME_PAGE']);				
				
				date_default_timezone_set('Australia/Sydney');
				
				$query1 = "UPDATE KPI_USERS SET
						LAST_LOGIN = '".date("d/M/Y h:i:s A") ."'
						WHERE USERID = ".$row['USERID'];
				
				$query1Parse = oci_parse($conn, $query1);				
				oci_execute($query1Parse);
				
				
			} else {				
				$user->setInvalid('Invalid Password !!!');
			}			
			
			/* Get the Module access for the RoleID */
			
			$query = "SELECT MODULE_ID 
					FROM KPI_USER_RIGHTS
					WHERE ROLE_ID = ".$row['ROLEID'];
			
			$queryParse = oci_parse($conn, $query);			
			
			oci_execute($queryParse);
			$row = oci_fetch_array($queryParse);
			
			$user->setModuleIDs($row['MODULE_ID']);
			
			
			/* === */
		} 
		
		if(empty($temp))
			$user->setInvalid('User not found with given Email ID !!!');
			
		return $user;
	}

	public function updateUserLogout($userID){
		
		$this->oracle->openConnection('KPIDASHBOARD');
		$conn = $this->oracle->getConnection();
		
		date_default_timezone_set('Australia/Sydney');
		
		$query = "UPDATE KPI_USERS SET
				LAST_LOGOUT = '".date("d/M/Y h:i:s A")."'
				WHERE USERID = ".$userID;
		
		$queryParse = oci_parse($conn, $query);
		$row = oci_execute($queryParse);
		
		return $row;
	}
	
}


