<?php

namespace AppBundle\Repository;

use AppBundle\Service\OracleDatabaseService;

/**
 * @author James Whitehead
 */
class ProjectRepository
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
	 * Returns an array of all Test Environment names
	 * 
	 * @return array
	 */
	public function getEnvironmentList() {
		$this->oracle->openConnection('Siebeldata');
		$conn = $this->oracle->getConnection();
		
		$query = "SELECT ENVIRONMENT_ID, ENVIRONMENT_NAME
				FROM ENVIRONMENT
				ORDER BY ENVIRONMENT_ID ASC";
		
		$stid = oci_parse($conn, $query);
		oci_execute($stid);
		
		$envList = array();
		
		while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
			$envList[] = $row['ENVIRONMENT_NAME'];
		}
		
		oci_free_statement($stid);
		
		$this->oracle->closeConnection();
		
		return $envList;
	}
	
	/**
	 * Returns an array containing all of the details related to the given Test Environment
	 * 
	 * @param string $env
	 * @return array
	 */
	public function getEnvironmentDetails($env) {
		$this->oracle->openConnection('Siebeldata');
		$conn = $this->oracle->getConnection();
		
		$query = "SELECT APPLICATION_ID, APPLICATION_NAME
				FROM APPLICATION
				ORDER BY APPLICATION_ID ASC";
		
		$stid = oci_parse($conn, $query);
		oci_execute($stid);
		
		$appList = array();
		
		while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
			$appList[] = $row['APPLICATION_ID'];
		}
		
		oci_free_statement($stid);
		
		$envDetails = '';
		
		foreach ($appList as $appId) {
			$query = "SELECT A.APPLICATION_NAME, AU.URL, TU.USERNAME, TU.PASSWORD
					FROM APPLICATION A
					LEFT JOIN APPLICATION_URL AU ON AU.APPLICATION_ID = A.APPLICATION_ID
					LEFT JOIN ENVIRONMENT E ON E.ENVIRONMENT_ID = AU.ENVIRONMENT_ID
					LEFT JOIN TEST_USER TU ON TU.APPLICATION_ID = AU.APPLICATION_ID
					AND TU.ENVIRONMENT_ID = E.ENVIRONMENT_ID
					WHERE E.ENVIRONMENT_NAME = :env
					AND A.APPLICATION_ID = :app";			
			
			
			$stid = oci_parse($conn, $query);
			oci_bind_by_name($stid, ':env', $env);
			oci_bind_by_name($stid, ':app', $appId);
			oci_execute($stid);
			
			while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
				$envDetails[] = $row;
			}
			
			oci_free_statement($stid);
		}
		
		$this->oracle->closeConnection();
		
		return $envDetails;
	}
	
}
