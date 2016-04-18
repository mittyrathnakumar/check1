<?php

namespace AppBundle\Repository;


use AppBundle\Service\OracleDatabaseService;
use AppBundle\Service\Validate;
use AppBundle\Entity\SystemDetails;
use AppBundle\Entity\ExecutionDetails;
use AppBundle\Entity\TestCase;
use AppBundle\Entity\AutomationMatrices;


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
	public function getUserDetails($env) {
		$this->oracle->openConnection('KPIDASHBOARD');
		$conn = $this->oracle->getConnection();
		
		$query = "SELECT * FROM USER WHERE ACTIVE = 1
				ORDER BY FIRSTNAME";
		
		$queryParse = oci_parse($conn, $query);
		oci_execute($queryParse);
		
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
	
	/**
	 *  Update TDM Tracker details
	 */
	
	public function updateTDMTrackerAdminRequestData($comments, $refNo, $status){		
		$this->oracle->openConnection('Siebeldata');
		$conn = $this->oracle->getConnection();
		
		$query = "UPDATE TDM_REQUESTS 
			 	SET COMMENTS = :comments, STATUS = :status WHERE REFERENCE_NUMBER = :refNum";
		
		$update = oci_parse($conn, $query);
	
		oci_bind_by_name($update, ':comments', $comments);
		oci_bind_by_name($update, ':status', $status);
		oci_bind_by_name($update, ':refNum', $refNo);		 
	
		oci_execute($update);
		oci_close($conn);
		return true;
	}
	
	/**
	 * Returns a list of Automated Machines
	 */
	public function getMachineList(){
		$this->oracle->openConnection('Siebeldata');
		$conn = $this->oracle->getConnection();
		$sysDetails = array();
		
		$query = "SELECT AM.*, TO_CHAR(COMMENTS) AS COMMENTS FROM AUTOMATION_MACHINEDETAILS AM ORDER BY HOSTNAME";
		$queryParse = oci_parse($conn,$query);
		oci_execute($queryParse);
		
		while($row = oci_fetch_array($queryParse)){
			$sysDetail = new SystemDetails();
			$sysDetail->setHostName($row['HOSTNAME']);
			$sysDetail->setIP($row['IP']);
			$sysDetail->setOwner($row['OWNER']);
			$sysDetail->setOS($row['OS']);
			$sysDetail->setVMHostName($row['VM_HOSTNAME']);
			$sysDetail->setVMIP($row['VM_IP']);
			$sysDetail->setVMOS($row['VM_OS']);
			$sysDetail->setVMAllocatedTo($row['VM_ALLOCATEDTO']);
			$sysDetail->setComments($row['COMMENTS']);
			
			$sysDetails[] = $sysDetail;
		}		
		
		$this->oracle->closeConnection();
		return $sysDetails;
	}
	
	/**
	 * Updates the system details for individual column
	 */
	
	public function updateSystemDetails($column, $value, $hostname){		
		$this->oracle->openConnection('Siebeldata');
		$conn = $this->oracle->getConnection();		
		
		$query = "UPDATE AUTOMATION_MACHINEDETAILS SET ". $column. " = '". $value. "' 
				  WHERE HOSTNAME = '". $hostname. "'";
		//echo $query;exit;
		$queryParse = oci_parse($conn, $query);
		/*$query = "UPDATE AUTOMATION_MACHINEDETAILS SET :column = :value WHERE HOSTNAME = :hostname ";
		oci_bind_by_name($queryParse, ':column', $column);
		oci_bind_by_name($queryParse, ':value', $value);
		oci_bind_by_name($queryParse, ':hostname', $hostname);*/
		oci_execute($queryParse);
		
		return true;
		
	}
	
	/**
	 * @return array
	 */
	public function getTDMRequestDetails(){
		$this->oracle->openConnection('Siebeldata');
		$conn = $this->oracle->getConnection();
		$requestCounts = array();
				
		$query1 = "SELECT COUNT(*) AS COUNT FROM TDM_REQUESTS WHERE STATUS = 'COMPLETE'";
		$query1Parse = oci_parse($conn, $query1);
		oci_define_by_name($query1Parse, 'COUNT', $completeCount);
		oci_execute($query1Parse);
		oci_fetch($query1Parse);
		
		// Total In-Progress TDM Requests
		$query2 = "SELECT COUNT(*) AS COUNT FROM TDM_REQUESTS WHERE STATUS = 'IN-PROGRESS'";
		$query2Parse = oci_parse($conn, $query2);
		oci_define_by_name($query2Parse, 'COUNT', $progressCount);
		oci_execute($query2Parse);
		oci_fetch($query2Parse);
		
		// Total Delayed TDM Requests
		$query3 = "SELECT COUNT(*) AS COUNT FROM TDM_REQUESTS WHERE STATUS = 'DELAYED'";
		$query3Parse = oci_parse($conn, $query3);
		oci_define_by_name($query3Parse, 'COUNT', $delayedCount);
		oci_execute($query3Parse);
		oci_fetch($query3Parse);
		
		// Total New TDM Requests
		$query4 = "SELECT COUNT(*) AS COUNT FROM TDM_REQUESTS WHERE STATUS = 'NEW'";
		$query4Parse = oci_parse($conn, $query4);
		oci_define_by_name($query4Parse, 'COUNT', $newCount);
		oci_execute($query4Parse);
		oci_fetch($query4Parse);
		
		$requestCounts['complete'] =  $completeCount;
		$requestCounts['inprogress'] =  $progressCount;
		$requestCounts['delay'] =  $delayedCount;
		$requestCounts['newrequest'] =  $newCount;	
		
		// Calculation for the Quarterly Dates 
		
		$currentYear = date('Y');		
		$q1Date1 = '01-JAN-'.$currentYear;
		$q1DateTemp = date('d-M-Y', strtotime("+3 months", strtotime($q1Date1)));
		$q1Date2 = date('d-M-Y', strtotime("-1 day", strtotime($q1DateTemp)));
		
		
		$query = "SELECT COUNT(*) AS Q1 FROM TDM_REQUESTS WHERE DATE_REQUESTED BETWEEN TO_DATE('".$q1Date1."', 'DD-MON-YYYY') AND TO_DATE('".$q1Date2."', 'DD-MON-YYYY')";
		$select = oci_parse($conn, $query);
		oci_define_by_name($select, 'Q1', $q1);
		oci_execute($select);
		oci_fetch($select);			
		
		
		
		$q2Date1 = date('d-M-Y', strtotime("+3 months", strtotime($q1DateTemp)));		
		$q2Date2 = date('d-M-Y', strtotime("-1 day", strtotime($q2Date1)));
		$query = "SELECT COUNT(*) AS Q2 FROM TDM_REQUESTS WHERE DATE_REQUESTED BETWEEN TO_DATE('".$q1DateTemp."', 'DD-MON-YYYY') AND TO_DATE('".$q2Date2."', 'DD-MON-YYYY')";
		
		$select = oci_parse($conn, $query);
		oci_define_by_name($select, 'Q2', $q2);
		oci_execute($select);
		oci_fetch($select);
		
		$q3Date1 = date('d-M-Y', strtotime("+3 months", strtotime($q2Date1)));
		$q3Date2 = date('d-M-Y', strtotime("-1 day", strtotime($q3Date1)));
		$query = "SELECT COUNT(*) AS Q3 FROM TDM_REQUESTS WHERE DATE_REQUESTED BETWEEN TO_DATE('".$q2Date1."', 'DD-MON-YYYY') AND TO_DATE('".$q3Date2."', 'DD-MON-YYYY')";
		
		$select = oci_parse($conn, $query);
		oci_define_by_name($select, 'Q3', $q3);
		oci_execute($select);
		oci_fetch($select);
		
		$q4Date1 = date('d-M-Y', strtotime("+3 months", strtotime($q3Date1)));
		$q4Date2 = date('d-M-Y', strtotime("-1 day", strtotime($q4Date1)));
		$query = "SELECT COUNT(*) AS Q4 FROM TDM_REQUESTS WHERE DATE_REQUESTED BETWEEN TO_DATE('".$q3Date1."', 'DD-MON-YYYY') AND TO_DATE('".$q4Date2."', 'DD-MON-YYYY')";
		
		$select = oci_parse($conn, $query);
		oci_define_by_name($select, 'Q4', $q4);
		oci_execute($select);
		oci_fetch($select);
		
		
		$requestCounts['Q1'] =  $q1;
		$requestCounts['Q2'] =  $q2;
		$requestCounts['Q3'] =  $q3;
		$requestCounts['Q4'] =  $q4;	
		
		
		oci_close($conn);
		return $requestCounts;
		
	}
	
	/**
	 * 
	 */
	
	public function createUser($email, $password, $firstname, $lastname, $role){
		$this->oracle->openConnection('Siebeldata');
		$conn = $this->oracle->getConnection();
		
		
		$query = "SELECT COUNT(*) AS COUNT FROM ART_USERS 
				WHERE EMAILID = :email AND ACTIVE = 1";		  
		$queryParse = oci_parse($conn, $query);
		oci_define_by_name($queryParse, 'COUNT', $count);
		oci_bind_by_name($queryParse, ':email', $email);
		oci_execute($queryParse);
		oci_fetch($queryParse);
		
		$response = array();		
		
		if($count > 0){
			return $response['duplicate'] = 0; 
		}
		else { 
			$password = password_hash($password, PASSWORD_DEFAULT);
			
			// USER ID COLUMN USES ARTUSERS_USERID.nextval FROM THE SEQUENCE CREATED IN ORACLE IN THE INSERT 
			//STATEMENT 
			
			$query = "INSERT INTO ART_USERS(EMAILID, PASSWORD, FIRSTNAME, LASTNAME, USERID, USERROLE) 
					VALUES(:email, :password, :firstname, :lastname, ARTUSERS_USERID.nextval, :role)";
			
			$queryParse = oci_parse($conn, $query);
			
			$objValidate = new Validate();
			$email = $objValidate->cv($email);
			$password = $objValidate->cv($password);
			$firstname = $objValidate->cv($firstname);
			$lastname = $objValidate->cv($lastname);			
			
			oci_bind_by_name($queryParse, ':email', $email);
			oci_bind_by_name($queryParse, ':password', $password);
			oci_bind_by_name($queryParse, ':firstname', $firstname);
			oci_bind_by_name($queryParse, ':lastname', $lastname);
			oci_bind_by_name($queryParse, ':role', $role);
			oci_execute($queryParse);
			
			return $response['duplicate'] = 1;
		}		
		
		
	}
	
	/**
	 * Gets a list of Project_Release
	 */
	
	public function getProjectRelease(){		
		$this->oracle->openConnection('Siebeldata');
		$conn = $this->oracle->getConnection();		
		
		$query = "SELECT DISTINCT PROJECT, RELEASE FROM AUTOMATION_DSR 
				WHERE RELEASE NOT LIKE '%COMPLETED%'
				AND PROJECT NOT LIKE '%COMPLETED%'";
		$queryParse = oci_parse($conn, $query);		
		oci_execute($queryParse);
		
		$executionDetails = array();
		
		while($row = oci_fetch_array($queryParse, OCI_RETURN_NULLS)){
			$executionDetail = new ExecutionDetails();
			
			$executionDetail->setProject($row['PROJECT']);
			$executionDetail->setRelease($row['RELEASE']);			
			
			$executionDetails[] = $executionDetail;
		}		
		
		$this->oracle->closeConnection($conn);
		return $executionDetails;
		
	}
	
	/**
	 * Gets details of Project and Test Cases Status
	 */
	
	public function getAutomationDSRData($project, $release){
		$this->oracle->openConnection('Siebeldata');
		$conn = $this->oracle->getConnection();
	
		$query = "SELECT A.*, TO_CHAR(A.COMMENTS) AS COMMENTS FROM AUTOMATION_DSR A WHERE PROJECT = :project AND RELEASE = :release ORDER BY CHANNEL ASC";		
		
		$queryParse = oci_parse($conn, $query);
		oci_bind_by_name($queryParse, ':project', $project);
		oci_bind_by_name($queryParse, ':release', $release);
		
		oci_execute($queryParse);
	
		$automationDetails = array();
		$testcases = array();
		
		while($row = oci_fetch_array($queryParse, OCI_ASSOC+OCI_RETURN_NULLS)){
			$testcase = new TestCase();			
		
			$testcase->setTestCaseName($row['TESTCASENAME']);			
			$testcase->setExecutionType($row['EXECUTIONTYPE']);			
			$testcase->setEnvironment($row['ENVIRONMENT']);
			$testcase->setChannel($row['CHANNEL']);
			$testcase->setAssignedTo($row['ASSIGNED_TO']);
			$testcase->setAssignedDate($row['ASSIGNED_DATE']);
			$testcase->setExecutionStatus($row['STATUS']);
			$testcase->setComments($row['COMMENTS']);			
				
			$testcases[] = $testcase;
		}
		
		$executionDetail = new ExecutionDetails();		
		$executionDetail->setTestCases($testcases);			

		$assignedDates = array();
		$completionDates = array();
		$assignedResults = array();
		$completionResults = array();
		
		/* Assigned Dates */
		$query = 'SELECT DISTINCT ASSIGNED_DATE FROM AUTOMATION_DSR WHERE PROJECT = :project AND RELEASE = :release AND ASSIGNED_DATE IS NOT NULL ORDER BY ASSIGNED_DATE DESC';
		$queryParse = oci_parse($conn, $query);
		oci_bind_by_name($queryParse, ':project', $project);
		oci_bind_by_name($queryParse, ':release', $release);
		
		oci_execute($queryParse);
		
		while (oci_fetch($queryParse)) {
			$result = oci_result($queryParse, 'ASSIGNED_DATE');
			array_push($assignedDates, $result);
		}
		
		
		/* Completion Dates */
		$query = 'SELECT DISTINCT COMPLETION_DATE FROM AUTOMATION_DSR WHERE PROJECT = :project AND RELEASE = :release AND COMPLETION_DATE IS NOT NULL ORDER BY COMPLETION_DATE DESC';
		$queryParse = oci_parse($conn, $query);
		oci_bind_by_name($queryParse, ':project', $project);
		oci_bind_by_name($queryParse, ':release', $release);
		
		oci_execute($queryParse);
		
		while (oci_fetch($queryParse)) {
			$result = oci_result($queryParse, 'COMPLETION_DATE');
			array_push($completionDates, $result);
		}
		
		
		/* Combine the two arrays of dates to create one array of unique dates */
		$dates = array_merge($assignedDates, $completionDates);
		$dates = array_unique($dates);
		usort($dates, array($this, 'sortFunction'));
	
		
		/* Get the count for each date from each list in order to create a comparison */
		foreach ($dates as $d) {
			$query1 = 'SELECT COUNT(*) AS COUNT FROM AUTOMATION_DSR WHERE PROJECT = :project AND RELEASE = :release AND ASSIGNED_DATE = :d';
			$select1 = oci_parse($conn, $query1);
			oci_bind_by_name($select1, ':project', $project);
			oci_bind_by_name($select1, ':release', $release);
			oci_bind_by_name($select1, ':d', $d);
			oci_define_by_name($select1, 'COUNT', $count);
		
			oci_execute($select1);
			oci_fetch($select1);
			array_push($assignedResults, $count);
		
			$query2 = 'SELECT COUNT(*) AS COUNT FROM AUTOMATION_DSR WHERE PROJECT = :project AND RELEASE = :release AND COMPLETION_DATE = :d';
			$select2 = oci_parse($conn, $query2);
			oci_bind_by_name($select2, ':project', $project);
			oci_bind_by_name($select2, ':release', $release);
			oci_bind_by_name($select2, ':d', $d);
			oci_define_by_name($select2, 'COUNT', $count);
		
			oci_execute($select2);
		
			while (oci_fetch($select2)) {
				$result2 = oci_result($select2, 'COUNT');
				array_push($completionResults, $result2);
			}
		}	
				
		$executionDetail->setTestCaseAssignedCount($assignedResults);
		$executionDetail->setTestCaseCompletedCount($completionResults);
		$executionDetail->setDateArray($dates);
		
		$this->oracle->closeConnection($conn);
		return $executionDetail;
	
	}
	
	/**
	 * Call back function for USORT
	 */
	
	private function sortFunction($a, $b) {
			return strtotime($a) - strtotime($b);
	}	
	
	
	/**
	 * @param string $app 
	 * @return array
	 */
	public function getAutomationMatricesData($app) {
		
		if($app == 'Fusion'){
			$this->oracle->openConnection('Soapui');
			$conn = $this->oracle->getConnection();
			
			$query = "SELECT DISTINCT UPPER(EXECUTIONTYPE) AS EXECUTIONTYPE, COUNT(*) AS COUNT FROM REPORT WHERE EXECUTIONTYPE != 'Regerssion' GROUP BY UPPER(EXECUTIONTYPE)";
			$queryParse = oci_parse($conn, $query);
			oci_execute($queryParse);
			
			while ($row = oci_fetch_array($queryParse, OCI_ASSOC+OCI_RETURN_NULLS)) {
				$autoMatrice = new AutomationMatrices();
				$autoMatrice->setExecutionType($row['EXECUTIONTYPE']);
				$autoMatrice->setExecutionTotal($row['COUNT']);
			
				$matriceDetails[] = $autoMatrice;			
			}				
			
		}
		else {
			
			// PIE CHART DATA
			
			$this->oracle->openConnection('Siebeldata');
			$conn = $this->oracle->getConnection();
			$this->setEnvStr($app);
			
			$query = "SELECT DISTINCT EXECUTIONTYPE, COUNT(*) AS COUNT FROM TOSCA_REPORTING 
					WHERE $this->envStr 
					AND EXECUTIONDATE IS NOT NULL GROUP BY EXECUTIONTYPE";		
			
			$queryParse = oci_parse($conn, $query);
			oci_execute($queryParse);
		
			$matriceDetails = array();
			
			
			while ($row = oci_fetch_array($queryParse, OCI_ASSOC+OCI_RETURN_NULLS)) {		
				$autoMatrice = new AutomationMatrices();
				$autoMatrice->setExecutionType($row['EXECUTIONTYPE']);
				$autoMatrice->setExecutionTotal($row['COUNT']); 
				
				$matriceDetails[] = $autoMatrice;				
			}		
			
			
			$query1 = "SELECT COUNT(*) AS COUNT FROM SIEBEL_CONNECT_ORDERS";
			$query1Parse = oci_parse($conn, $query1);		
			
			oci_execute($query1Parse);
			$row = oci_fetch_array($query1Parse);
			
			$autoMatrice = new AutomationMatrices();
			
			$autoMatrice->setExecutionType('TESTDATA');
			$autoMatrice->setExecutionTotal($row['COUNT']);
			array_push($matriceDetails, $autoMatrice);			
			
		}		
		
		oci_free_statement($queryParse);	
		$this->oracle->closeConnection($conn);	
		return $matriceDetails;
	}
	
	public function getBarChartData ($app, $from, $to){
		
		if($app == 'Fusion'){
			$this->oracle->openConnection('Soapui');
			$conn = $this->oracle->getConnection();		
			
		} 
		else {		
			
			$this->oracle->openConnection('Siebeldata');
			$conn = $this->oracle->getConnection();
			$this->setEnvStr($app);		
		}	
	
		$query = "SELECT MONTHS_BETWEEN('".$to."','".$from."') AS MONTHSCOUNT FROM Dual";
		$queryParse = oci_parse($conn, $query);
		oci_execute($queryParse);
		$row = oci_fetch_array($queryParse);
		$monthcount = $row['MONTHSCOUNT'];
		$executionDetail = array();
		
		if($app == 'Oracle')
			$executionType = array("SHAKEDOWN", "REGRESSION");
		else if($app == 'Siebel')
			$executionType = array("SHAKEDOWN", "REGRESSION", "CVT", "TESTDATA");
		else if($app == 'Fusion')
			$executionType = array("ALERTTESTING", "REGRESSION");
		
		for($i=0;$i<$monthcount;$i++){
			$executionDetails = array();			
			
			if($app == 'Oracle'){
				$fromOriginal = $from;
				$fromtemp = explode("/",$from);
				$from = $fromtemp[1]."%".$fromtemp[2]; // OMIT DATE AND KEEP MONTH AND YEAR TO COMPARE
				$executionDetails[] = $from;
				
				foreach ($executionType as $exec) {	

					$query = "SELECT COUNT(*) AS COUNT FROM TOSCA_REPORTING 
							WHERE $this->envStr AND EXECUTIONTYPE = '".$exec."'
							AND EXECUTIONDATE LIKE '%".$from."%' GROUP BY EXECUTIONTYPE ORDER BY EXECUTIONTYPE DESC";
					$queryParse = oci_parse($conn, $query);
					oci_execute($queryParse);
					
					$row = oci_fetch_array($queryParse, OCI_ASSOC+OCI_RETURN_NULLS);
					
					if($row['COUNT'] == '')
						$executionDetails[$exec] = 0;
					else
						$executionDetails[$exec] = $row['COUNT'];				
				
				}
			}
			else if($app == 'Siebel'){
				$fromOriginal = $from;
				$fromtemp = explode("/",$from);
				$from = $fromtemp[1]."%".$fromtemp[2]; // OMIT DATE AND KEEP MONTH AND YEAR TO COMPARE
				$executionDetails[] = $from;					
				$testdatadate = ucfirst(strtolower($fromtemp[1]))."%20".$fromtemp[2];
				
				foreach ($executionType as $exec) {
					if($exec == 'TESTDATA'){
						$query = "SELECT COUNT(*) AS COUNT FROM SIEBEL_CONNECT_ORDERS 
								WHERE CREATION_DATE LIKE '%".$testdatadate."%'";
					} else {
						$query = "SELECT COUNT(*) AS COUNT FROM TOSCA_REPORTING
						WHERE $this->envStr 
						AND EXECUTIONTYPE = '".$exec."' 
						AND EXECUTIONDATE LIKE '%".$from."%'";							
					}						

					$queryParse = oci_parse($conn, $query);
					oci_define_by_name($queryParse, 'COUNT', $count);
					oci_execute($queryParse);
					$row = oci_fetch_array($queryParse, OCI_ASSOC+OCI_RETURN_NULLS);						
				
					if($row['COUNT'] == '')
						$executionDetails[$exec] = 0;
					else
						$executionDetails[$exec] = $row['COUNT'];
								
				}
				
			} else if($app == 'Fusion'){
				$fromOriginal = $from;
				$fromtemp = explode("/",$from);
				$from = $fromtemp[1]."%".$fromtemp[2]; // OMIT DATE AND KEEP MONTH AND YEAR TO COMPARE
				$executionDetails[] = $from;
				$testdatadate = ucfirst(strtolower($fromtemp[1]))."%20".$fromtemp[2];
				
				foreach ($executionType as $exec) {
					$query = "SELECT COUNT(*) AS COUNT FROM REPORT
						WHERE EXECUTIONTYPE = '".$exec."'
						AND DATEOFEXECUTION LIKE '%".$from."%'";					
				
					$queryParse = oci_parse($conn, $query);
					oci_define_by_name($queryParse, 'COUNT', $count);
					oci_execute($queryParse);
					$row = oci_fetch_array($queryParse, OCI_ASSOC+OCI_RETURN_NULLS);
				
					if($row['COUNT'] == '')
						$executionDetails[$exec] = 0;
						else
							$executionDetails[$exec] = $row['COUNT'];
				
				}
			}
			
			
			// SWITCH TO NEXT MONTH
			$query = "SELECT ADD_MONTHS( '".$fromOriginal."', 1 ) AS MONTH FROM DUAL";//exit;
			$queryParse = oci_parse($conn, $query);
			oci_execute($queryParse);
			$row = oci_fetch_array($queryParse);
				
			$from = date('d/M/y', strtotime($row['MONTH']));
			$from = strtoupper($from);
				
			$executionDetail[] = $executionDetails;
		
		}
		
		//echo "<pre>";print_r($executionDetail);exit;
		oci_free_statement($queryParse);		
		$this->oracle->closeConnection($conn);
		
		return $executionDetail;
			
		
	}
	
	
	/**
	 *
	 * @param unknown $app
	 */
	
	private function setEnvStr($app) {
		switch ($app) {
			case 'Siebel':
				$this->envStr = "ENVIRONMENT NOT IN ('ORACLE', 'TALLYMAN')";
				break;
			case 'Oracle':
				$this->envStr = "ENVIRONMENT = 'ORACLE'";
				break;
			case 'Tallyman':
				$this->envStr = "ENVIRONMENT = 'TALLYMAN'";
				break;
		}
	}
}