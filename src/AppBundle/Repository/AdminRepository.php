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
	
	public function getUserAccessDetails($RoleID = ""){

		$this->oracle->openConnection('KPIDASHBOARD');
		$conn = $this->oracle->getConnection();
		$userDetails = array();
		
		/*$query = "SELECT U.USERID, U.FIRSTNAME, U.LASTNAME, UR.ROLENAME, URT.MODULE_ID FROM KPI_USERS U
				LEFT JOIN KPI_USERROLE UR ON UR.ROLEID = U.ROLEID
				LEFT JOIN KPI_USER_RIGHTS URT ON U.ROLEID = URT.ROLE_ID WHERE U.ACTIVE = 1"; */
		
		$query = "SELECT UR.ROLENAME, UR.ROLEID, URT.MODULE_ID 
				FROM KPI_USERROLE UR, KPI_USER_RIGHTS URT 
				WHERE UR.ROLEID = URT.ROLE_ID";
	
		if(!empty($RoleID))
			$query.= " AND UR.ROLEID = ".$RoleID;
		
		$query.= " ORDER BY UR.ROLENAME";
		
		//echo $query;exit;
		$queryParse = oci_parse($conn, $query);
		oci_execute($queryParse);
	
		if(!empty($RoleID)){
			$row = oci_fetch_array($queryParse, OCI_ASSOC+OCI_RETURN_NULLS);
			$userDetail = new Users();
			
			$userDetail->setUserRole($row['ROLENAME']);
			$userDetail->setUserRoleID($row['ROLEID']);	
			
			$moduleIDArr = explode(",", $row['MODULE_ID'] );			
			$userDetail->setModuleAccess($moduleIDArr);
			
			/* Get all the modules */
			
			$query1 = "SELECT ID, MODULE FROM KPI_MODULES WHERE ACTIVE = 1 ORDER BY MODULE";
			
			$query1Parse = oci_parse($conn, $query1);
			oci_execute($query1Parse);
			
			$modulesTemp = array();
			while($result = oci_fetch_array($query1Parse)){
				$modulesTemp = array();
				
				$modulesTemp['ID'] = $result['ID'];
				$modulesTemp['MODULE'] = $result['MODULE'];
				
				$modules[] = $modulesTemp;
			}			
		
			
			$userDetail->setModuleArr($modules);
			
			/* === */			
		
			
			oci_free_statement($queryParse);
			$this->oracle->closeConnection();
			
			//echo "<pre>";print_r($userDetail);exit;
			return $userDetail;
		}
	
		else {
	
			while ($row = oci_fetch_array($queryParse, OCI_ASSOC+OCI_RETURN_NULLS)) {
				$userDetail = new Users();
					
				$userDetail->setUserRole($row['ROLENAME']);	
				$userDetail->setUserRoleID($row['ROLEID']);
				
				$moduleIDArr = explode(",", $row['MODULE_ID'] );
				
				if(count($moduleIDArr) > 0){
					$moduleNameArr = array();
					foreach($moduleIDArr as $moduleID){
							
						$query1 = "SELECT MODULE FROM KPI_MODULES WHERE ACTIVE = 1
							AND ID = ".$moduleID;
						
						$query1Parse = oci_parse($conn, $query1);
						oci_execute($query1Parse);
						$row1 = oci_fetch_array($query1Parse);
						
						$moduleNameArr[] = $row1['MODULE'];
					}
					$moduleNames = implode(", ", $moduleNameArr);
				
					$userDetail->setModuleAccess($moduleNames);
				}	
				
					
				$userDetails[] = $userDetail;
	
			}
			
			//echo "<pre>";print_r($userDetails);exit;
				
			oci_free_statement($queryParse);
			$this->oracle->closeConnection();
			return $userDetails;
		}
				
	}
	
	public function updateUserAccessDetails($postData){
		$this->oracle->openConnection('KPIDASHBOARD');
		$conn = $this->oracle->getConnection();
		
		//echo "<pre>";print_r($postData);exit;
		
		if(!empty($postData['moduleArr'])){
			$query = "UPDATE KPI_USER_RIGHTS SET
					MODULE_ID = '".implode(",", $postData['moduleArr'])."'
					WHERE ROLE_ID = ".$postData['RoleID'];
			
			//echo $query;exit;
			$queryParse = oci_parse($conn, $query);
			$row = oci_execute($queryParse);
		}
		
		oci_free_statement($queryParse);
		$this->oracle->closeConnection();
		return $row;
		
	}
}