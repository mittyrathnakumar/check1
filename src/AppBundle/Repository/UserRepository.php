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
		
		//echo $query;exit;
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
				//$user->setUserRole($row['USERROLE']);
				$user->setUserID($row['USERID']);
				$user->setUserActive($row['ACTIVE']);				
				
			} else {				
				$user->setInvalid('Invalid Password !!!');
			}			
		} 
		
		if(empty($temp))
			$user->setInvalid('User not found with given Email ID !!!');
			
		return $user;
	}
	
	
}


