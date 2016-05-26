<?php

namespace AppBundle\Repository;


use AppBundle\Service\OracleDatabaseService;
use AppBundle\Entity\Users;


/**
 * @author Dhara Sheth
 */
class AdminRepository
{
	/**
	 * @var OracleDatabaseService
	 */
	private $oracle;
	
	
	private $envStr;
	
	private $env;
	
	/**
	 * 
	 */
	public function __construct() {
		$this->oracle = new OracleDatabaseService();
	}
		
	
	/**
	 * Returns an array containing all of the details related to the given Test Environment
	 * 
	 * @param string $env
	 * @return array
	 */
	public function getUserDetails($UserID = "") {
		$this->oracle->openConnection('KPIDASHBOARD');
		$conn = $this->oracle->getConnection();
		$userDetails = array();
		
		$query = "SELECT U.*, UR.* FROM KPI_USERS U 
				LEFT JOIN KPI_USERROLE UR ON UR.ROLEID = U.ROLEID WHERE U.ACTIVE = 1";
		
		if(!empty($UserID))
			$query .= " AND USERID=".$UserID;
		
		$query.= " ORDER BY U.FIRSTNAME";
		
		//echo $query;exit;
		$queryParse = oci_parse($conn, $query);
		oci_execute($queryParse);		
		
		if(!empty($UserID)){
			$row = oci_fetch_array($queryParse, OCI_ASSOC+OCI_RETURN_NULLS);
			$userDetail = new Users();
			
			$userDetail->setUserID($row['USERID']);
			$userDetail->setFirstName($row['FIRSTNAME']);
			$userDetail->setLastName($row['LASTNAME']);
			$userDetail->setEmail($row['EMAILID']);
			$userDetail->setUserRole($row['ROLENAME']);
			$userDetail->setUserRoleID($row['ROLEID']);
			$userDetail->setUserActive($row['ACTIVE']);	
			
			oci_free_statement($queryParse);		
			$this->oracle->closeConnection();		
			return $userDetail;
		} 
		
		else {
		
			while ($row = oci_fetch_array($queryParse, OCI_ASSOC+OCI_RETURN_NULLS)) {
				$userDetail = new Users();
			
				$userDetail->setUserID($row['USERID']);
				$userDetail->setFirstName($row['FIRSTNAME']);
				$userDetail->setLastName($row['LASTNAME']);
				$userDetail->setEmail($row['EMAILID']);
				$userDetail->setUserRole($row['ROLENAME']);
				$userDetail->setUserRoleID($row['ROLEID']);
				$userDetail->setUserActive($row['ACTIVE']);
			
				$userDetails[] = $userDetail;
	
			}
			
			oci_free_statement($queryParse);
			$this->oracle->closeConnection();
			return $userDetails;
		}
	}
	
	public function addEditUser($postData){
		$this->oracle->openConnection('KPIDASHBOARD');
		$conn = $this->oracle->getConnection();	
		
		//print_r($postData);//exit;
		// ADD NEW USER
		if(empty($postData['UserID'])){
		
				$query = "SELECT COUNT(*) AS COUNT FROM KPI_USERS WHERE EMAILID = :email";
				$queryParse = oci_parse($conn, $query);
				oci_bind_by_name($queryParse, ':email', $postData['Email']);
				oci_execute($queryParse);
				$row = oci_fetch_array($queryParse);
				
				//echo $row['COUNT'];exit;
				
				if($row['COUNT'] == 0){
					
					$query = "INSERT INTO KPI_USERS (USERID, EMAILID, PASSWORD, FIRSTNAME, LASTNAME, ROLEID)
							VALUES (USERS_SEQ.nextval, :email, :password, :firstname, :lastname, :roleid)";
					/*$query = "INSERT INTO USERS (USERID, EMAILID, PASSWORD, FIRSTNAME, LASTNAME, ROLEID)
							VALUES (USERS_SEQ.nextval, '".$postData['Email']."', '".$password."', '".$postData['FirstName']."', '".$postData['LastName']."', '".$postData['RoleID']."')";
						*/
					//echo $query;exit;
					$queryParse = oci_parse($conn, $query);
					
					$password = password_hash($postData['Password'], PASSWORD_DEFAULT);
					
					oci_bind_by_name($queryParse, ':email', $postData['Email']);
					oci_bind_by_name($queryParse, ':password', $password);
					oci_bind_by_name($queryParse, ':firstname', $postData['FirstName']);
					oci_bind_by_name($queryParse, ':lastname', $postData['LastName']);
					oci_bind_by_name($queryParse, ':roleid', $postData['RoleID']);
					
					$row = oci_execute($queryParse);
					
					if($row)	
						return $status = 'User Added !!!';
					else 
						return $status = 'Some problem occurred, try again !!!';
					
				} else {
					return $status = 'User with given Email ID already exists !!!';
				}
		}
		
		else {
		
		// UPDATE USER
			
				$query = "UPDATE KPI_USERS SET
						EMAILID =  :email,
						FIRSTNAME =	:firstname,			 
						LASTNAME = :lastname,
						ROLEID = :roleid
						WHERE USERID = :userid";
			
				//print_r($postData);
				//echo $query;exit;
				$queryParse = oci_parse($conn, $query);
				
				oci_bind_by_name($queryParse, ':email', $postData['Email']);				
				oci_bind_by_name($queryParse, ':firstname', $postData['FirstName']);
				oci_bind_by_name($queryParse, ':lastname', $postData['LastName']);
				oci_bind_by_name($queryParse, ':roleid', $postData['RoleID']);
				oci_bind_by_name($queryParse, ':userid', $postData['UserID']);
				
				$row = oci_execute($queryParse);
					
				if($row)
					return $status = 'User Updated !!!';
				else
					return $status = 'Some problem occurred, try again !!!';
		}
		
	}
}