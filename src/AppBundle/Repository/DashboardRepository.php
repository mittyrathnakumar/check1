<?php

namespace AppBundle\Repository;

use AppBundle\Service\OracleDatabaseService;
/*use AppBundle\Entity\AutomationSuite;
use AppBundle\Entity\SafeEnvironmentDetails;
use AppBundle\Entity\SafeEnvironmentServiceDetails;
use AppBundle\Entity\TestCase;
use AppBundle\Entity\ExecutionDetails;
*/

/**
 * @author James Whitehead
 */
class DashboardRepository
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
	 * @param string $app	 
	 * @return array
	 */
	
	public function getDistinctExecutionTypes($app, $executionType) {
		$this->oracle->openConnection('Siebeldata');		
		$conn = $this->oracle->getConnection();
		$executionTypes = array();		
		
		if($executionType != 'TESTDATA'){		
			$query = "SELECT DISTINCT EXECUTIONTYPE FROM TOSCA_EXECUTION_SUITE 
				WHERE APPLICATION = :app
				AND EXECUTIONTYPE !='TESTDATA' GROUP BY EXECUTIONTYPE ORDER BY APPLICATION";
			
		} else {			
			$query = "SELECT DISTINCT EXECUTIONTYPE FROM TOSCA_EXECUTION_SUITE
				WHERE APPLICATION = :app
				AND EXECUTIONTYPE ='TESTDATA' GROUP BY EXECUTIONTYPE ORDER BY APPLICATION";
		}
	
		$queryParse = oci_parse($conn, $query);
		oci_bind_by_name($queryParse, ':app', $app);		
		oci_execute($queryParse);	
		
	
		while ($row = oci_fetch_array($queryParse, OCI_ASSOC+OCI_RETURN_NULLS)) {
			if($executionType != $row['EXECUTIONTYPE'])
				$executionTypes[] = $row['EXECUTIONTYPE'];
		}	
		
		
		oci_free_statement($queryParse);		
		$this->oracle->closeConnection();		
		return $executionTypes;
	}
	
	/**
	 * @param string $app
	 * @param string $executionType
	 * @return array
	 */
	
	public function getCountResults($app, $executionType) {
		$this->oracle->openConnection('Siebeldata');
		$conn = $this->oracle->getConnection();
		$countResults = array();
	
			
		$query1 = "SELECT COUNT (DISTINCT (EXECUTIONLIST)) AS EXECUTIONLISTCOUNT, COUNT (TESTCASES) AS TESTCASECOUNT FROM TOSCA_EXECUTION_SUITE
				WHERE APPLICATION = :app
				AND EXECUTIONTYPE = :executionType";
	
		$query1Parse = oci_parse($conn, $query1);
		oci_bind_by_name($query1Parse, ':app', $app);
		oci_bind_by_name($query1Parse, ':executionType', $executionType);
		oci_execute($query1Parse);
		$row = oci_fetch_array($query1Parse, OCI_ASSOC+OCI_RETURN_NULLS);
		$countResults['EXECUTIONLISTCOUNT'] = $row['EXECUTIONLISTCOUNT'];
		$countResults['TESTCASECOUNT'] = $row['TESTCASECOUNT'];
		
		oci_free_statement($query1Parse);
		$this->oracle->closeConnection();
		return $countResults;
		
	}
	
	/**
	 * @param string $app
	 * @param string $executionType
	 * @return array
	 */
	
	public function getToscaAutomationResults($app, $executionType) {
		$this->oracle->openConnection('Siebeldata');
		$conn = $this->oracle->getConnection();		
		$automationResults = array();	
			
	
		$query1 = "SELECT DISTINCT EXECUTIONLIST, COUNT(TESTCASES) AS TESTCOUNT, CIPARAMETER, EXECUTIONLISTPATH FROM TOSCA_EXECUTION_SUITE
				WHERE APPLICATION = :app
				AND EXECUTIONTYPE = :executionType GROUP BY EXECUTIONLIST, CIPARAMETER, EXECUTIONLISTPATH ORDER BY LPAD(EXECUTIONLIST, 10)";		
		
		$query1Parse = oci_parse($conn, $query1);
		oci_bind_by_name($query1Parse, ':app', $app);
		oci_bind_by_name($query1Parse, ':executionType', $executionType);
		oci_execute($query1Parse);		
		
		while($row = oci_fetch_array($query1Parse, OCI_ASSOC+OCI_RETURN_NULLS)){
				$automationResult = new AutomationSuite();
				$automationResult->setSuiteName($row['EXECUTIONLIST']);
				$automationResult->setTestCount($row['TESTCOUNT']);
				$automationResult->setCIParameter($row['CIPARAMETER']);
				$automationResult->setExecutionListPath($row['EXECUTIONLISTPATH']);
				
				$automationResults[] = $automationResult;
		}
		
		oci_free_statement($query1Parse);		
		$this->oracle->closeConnection();
		return $automationResults;
	}
	
	/**
	 * @param string $app
	 * @param string $executionType
	 * @param string $exeList
	 * @return array
	 */
	
	public function getToscaAutomationTCResults($app, $executionType, $exeList) {
		$this->oracle->openConnection('Siebeldata');
		$conn = $this->oracle->getConnection();
		$automationTCResults = array();
			
		$query = "SELECT DISTINCT TESTCASES, EXECUTIONLISTPATH FROM TOSCA_EXECUTION_SUITE 
				WHERE EXECUTIONLIST = '". $exeList ."'
				AND APPLICATION='". $app ."'
				AND EXECUTIONTYPE='". $executionType ."'";		
		
		//echo $query;exit;
		$queryParse = oci_parse($conn, $query);
		//oci_bind_by_name($query1Parse, ':executionlistcount', $EXECUTIONLISTCOUNT);
		//oci_bind_by_name($query1Parse, ':exeType', $executionType);
		//oci_bind_by_name($query1Parse, ':exeList', $exelist);
		oci_execute($queryParse);
	
		while($row = oci_fetch_array($queryParse, OCI_ASSOC+OCI_RETURN_NULLS)){
			$automationTCResult = new AutomationSuite();
			$automationTCResult->setTestCase($row['TESTCASES']);
			$automationTCResult->setExecutionListPath($row['EXECUTIONLISTPATH']);
			
			$automationTCResults[] = $automationTCResult;
		}
	
		//echo $query2;
		//echo "<pre>";var_dump($automationTCResults);exit;
	
	
		oci_free_statement($queryParse);
		$this->oracle->closeConnection();
		return $automationTCResults;
	}
	
	
	public function getExecutionFusion($checkListArr, $updateqc, $release, $env){
		$this->oracle->openConnection('Soapui');
		$conn = $this->oracle->getConnection();		
	
		$query = "UPDATE TESTSUITE SET 
				EXECUTIONFLAG = 'N',
				QCINTFLAG = 'N',
				RELEASE = 'FUSION_ST_EXECUTION'";
		$queryParse = oci_parse($conn, $query);
		oci_execute($queryParse);	
			
		if (empty($release)) {
			$release = "FUSION_ST_EXECUTION";
		}
			
		foreach($checkListArr as $check) {
		
			/*$query = "UPDATE TESTSUITE SET EXECUTIONFLAG = 'Y' WHERE SERVICENAME = '". $check . "' AND ENVIRONMENT = '". $env ."'";
			$queryParse = oci_parse($conn, $query);
			oci_execute($queryParse);*/
		
			$query = "UPDATE TESTSUITE SET 
					EXECUTIONFLAG = 'Y',
					QCINTFLAG = '". $updateqc ."',
					RELEASE = '".$release."'
					WHERE SERVICENAME = '". $check . "' AND ENVIRONMENT = '". $env ."'";
			$queryParse = oci_parse($conn, $query);
			oci_execute($queryParse);	
			
		}
			
		$query = "UPDATE EXECUTIONSUITE SET
				QCINTFLAG = '" . $updateqc . "' WHERE TESTSUITE = 'Fusion_BFX_Testing'";
		$queryParse = oci_parse($conn, $query);
		oci_execute($queryParse);		
		
			
		/*$url = 'http://10.45.2.140:8080/jenkins/job/ControllerJob-SAFE/build';
		$fields = '';// 'token=1aa559bd32feb66873feecd00c26b619';
		$ch = curl_init( $url);
		//curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($ch);
		echo $response;
	
		echo shell_exec("curl -X POST http://theja:password@http://10.45.2.140:8080/jenkins/job/ControllerJob-SAFE/build");
		*/
		oci_free_statement($queryParse);
		$this->oracle->closeConnection();
		return 1;
	
	}
	
	
	/**
	 * @param string $app 
	 * @return array
	 */
	
	public function getExecutionTosca($app, $checkListArr) {
		$this->oracle->openConnection('Siebeldata');
		$conn = $this->oracle->getConnection();
		$automationTCResults = array();

		$query = "SELECT EXECUTIONLIST FROM TOSCA_EXECUTION_SUITE WHERE APPLICATION = '". $app . "' AND EXECUTE = 'YES'";		
		$queryParse = oci_parse($conn, $query);
		oci_execute($queryParse);
		$rows = oci_fetch_all($queryParse, $row);		
		
		//$checkListArr = array('01. Startup Settings','02. Open Current Period');
		//$rows = 0;
		if($rows == 0){
			foreach($checkListArr as $check) {
				
				$query1 = "UPDATE TOSCA_EXECUTION_SUITE SET EXECUTE = 'YES' WHERE EXECUTIONLIST = '". $check . "'";			
				$query1Parse = oci_parse($conn, $query1);
				oci_execute($query1Parse);
			}
			
			if ($app != 'ORACLE'){
				$url = 'http://10.45.2.140:8080/jenkins/job/ControllerJob-TOSCA/build';
			} else {
				$url = 'http://10.45.2.140:8080/jenkins/job/ControllerJob-TOSCA-ORACLE/build';
			}
			
			$fields = '';// 'token=1aa559bd32feb66873feecd00c26b619';
			/*$ch = curl_init( $url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			*/
		
			$status = 'done';			
			
		} else {
			$status = 'undone';
		}
		
		oci_free_statement($queryParse);
		$this->oracle->closeConnection();
		return $status;
		
	}

	/**
	 * @param string $app
	 * @return array
	 */
	
	public function getExecutionDoneTosca($app, $checkListArr) {
		$this->oracle->openConnection('Siebeldata');
		$conn = $this->oracle->getConnection();
		$automationTCResults = array();	

		$query = "UPDATE TOSCA_EXECUTION_SUITE SET EXECUTE = 'USED' WHERE APPLICATION = '". $app . "'";						
		$queryParse = oci_parse($conn, $query);	
		oci_execute($queryParse);			

		foreach($checkListArr as $check) {					
			
			$query1 = "UPDATE TOSCA_EXECUTION_SUITE SET EXECUTE='YES' WHERE executionlist='". $check . "'";						
			$query1Parse = oci_parse($conn, $query1);	
			oci_execute($query1Parse);	
		}	
			
		if ($app != 'ORACLE'){
			$url = 'http://10.45.2.140:8080/jenkins/job/ControllerJob-TOSCA/build';
		}else{
			$url = 'http://10.45.2.140:8080/jenkins/job/ControllerJob-TOSCA-ORACLE/build';
		}
		
		$fields = '';// 'token=1aa559bd32feb66873feecd00c26b619';
		
		/*$ch = curl_init( $url);							
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($ch);*/
		
		//echo $response;	
		oci_free_statement($queryParse);
		$this->oracle->closeConnection();
		return 'done';
	
	}
	
	/**
	 * @return env list array
	 */
	
	public function getEnvList(){
		$this->oracle->openConnection('Soapui');
		$conn = $this->oracle->getConnection();
		$envListResults = array();
		
		$query = "SELECT DISTINCT ENV FROM ENVIRONMENT WHERE ENV IS NOT NULL ORDER BY ENV";
		$queryParse = oci_parse($conn, $query);
		oci_execute($queryParse);
		
		while($row = oci_fetch_array($queryParse)){
			$envListResults[] = $row['ENV'];
		}		
		
		oci_free_statement($queryParse);
		$this->oracle->closeConnection($conn);
		return $envListResults;
	}
	
	/**
	 * @param $env
	 * @return array
	 */
	
	public function getEnvExecutionDetails($env){
		$this->oracle->openConnection('Soapui');
		$conn = $this->oracle->getConnection();
		$envExecutionResults = array();		
		
		$query = "SELECT HOST, PORT FROM ENVIRONMENT WHERE ENV = :env ORDER BY ENV";
		$queryParse = oci_parse($conn, $query);		
		
		oci_bind_by_name($queryParse, ':env', $env);
		oci_execute($queryParse);		
		
		$safeEnvironmentDetail = new SafeEnvironmentDetails();
		
		$row = oci_fetch_array($queryParse);			
			
		$safeEnvironmentDetail->setHost($row['HOST']);
		$safeEnvironmentDetail->setPort($row['PORT']);		
		
		$query1 = "SELECT SERVICENAME, PATH, HOST, PORT FROM BASEPATH 
				WHERE ENVIRONMENT = :env ORDER BY SERVICENAME";
		$query1Parse = oci_parse($conn, $query1);
		
		oci_bind_by_name($query1Parse, ':env', $env);
		oci_execute($query1Parse);		
		
		$envServiceDetails = array();
		
		while($row = oci_fetch_array($query1Parse)){				
			$safeEnvServiceDetail = new SafeEnvironmentServiceDetails();
			$safeEnvServiceDetail->setServiceName($row['SERVICENAME']);
			$safeEnvServiceDetail->setBasePath($row['PATH']);
			$safeEnvServiceDetail->setServiceHost($row['HOST']);
			$safeEnvServiceDetail->setServicePort($row['PORT']);
			
			$envServiceDetails[] = $safeEnvServiceDetail;		
			
		}		
		
		$safeEnvironmentDetail->setServiceDetailsArray($envServiceDetails);
		$this->oracle->closeConnection();		
		
		return $safeEnvironmentDetail;
		
		
	}
	
	/**
	 * Updates Environment Details
	 */
	
	public function updateEnvExecutionDetails($host, $port, $env){		
		$this->oracle->openConnection('Soapui');
		$conn = $this->oracle->getConnection();
		
		$query = "UPDATE ENVIRONMENT SET 
				HOST = :host,
				PORT = :port
				WHERE ENV = :env";
		
		$queryParse = oci_parse($conn, $query);
		oci_bind_by_name($queryParse, ':host', $host);
		oci_bind_by_name($queryParse, ':port', $port);
		oci_bind_by_name($queryParse, ':env', $env);
		
		$result = oci_execute($queryParse);		
	
		$this->oracle->closeConnection($conn);
		return $result; 
	}
	
	/**
	 * Updates Environment Service Details
	 */
	
	public function updateServiceExecutionDetails($hosts, $ports, $services, $env, $count){
		$this->oracle->openConnection('Soapui');
		$conn = $this->oracle->getConnection();
	
		for($i=0;$i<$count;$i++){
			$query = "UPDATE BASEPATH SET
					HOST = '".$hosts[$i]."',
					PORT = '".$ports[$i]."'
					WHERE SERVICENAME = '".$services[$i]."' AND ENVIRONMENT = '".$env."'";
		
			$queryParse = oci_parse($conn, $query);
			oci_execute($queryParse);
		}
	
		$this->oracle->closeConnection($conn);
		return 1;
	}
	
	public function getServiceList($envname){
		$this->oracle->openConnection('Soapui');
		$conn = $this->oracle->getConnection();
		
		$query = "SELECT DISTINCT SERVICENAME FROM TESTCASE 
				WHERE ENVIRONMENT = :envname ORDER BY SERVICENAME";
		$queryParse = oci_parse($conn, $query);
		
		oci_bind_by_name($queryParse, ':envname', $envname);
		oci_execute($queryParse);
		$services = array();
		
		while($row = oci_fetch_array($queryParse)){				
			$services[] = $row['SERVICENAME'];
		}
		
		$this->oracle->closeConnection();
		return $services;
	}
	
	/**
	 * @return env list array
	 */
	
	public function getTestCaseEnvList(){
		$this->oracle->openConnection('Soapui');
		$conn = $this->oracle->getConnection();
		$envListResults = array();
	
		$query = "SELECT DISTINCT ENVIRONMENT FROM TESTCASE WHERE ENVIRONMENT IS NOT NULL ORDER BY ENVIRONMENT";
		$queryParse = oci_parse($conn, $query);
		oci_execute($queryParse);
	
		while($row = oci_fetch_array($queryParse)){
			$envListResults[] = $row['ENVIRONMENT'];
		}
			
		$this->oracle->closeConnection();
		return $envListResults;
	}
	
	/**
	 * @return testcase list array
	 */
	
	public function getTestCaseList($envname, $servicename){
		$this->oracle->openConnection('Soapui');
		$conn = $this->oracle->getConnection();
		$testcaseListResults = array();
	
		$query = "SELECT DISTINCT TESTCASE FROM TESTCASE
				WHERE ENVIRONMENT = :envname
				AND SERVICENAME = :servicename  ORDER BY TESTCASE";
		$queryParse = oci_parse($conn, $query);
		
		oci_bind_by_name($queryParse, ':envname', $envname);
		oci_bind_by_name($queryParse, ':servicename', $servicename);
		
		oci_execute($queryParse);
	
	
		while($row = oci_fetch_array($queryParse)){
			$testcaseListResults[] = $row['TESTCASE'];
		}
			
		$this->oracle->closeConnection($conn);
		return $testcaseListResults;
	}
	
	public function getXML($envname, $servicename, $testcasename){
		$this->oracle->openConnection('Soapui');
		$conn = $this->oracle->getConnection();
		$xmlResults = array();
		
		$query = "SELECT DESCRIPTION, REQUESTXML, RESPONSEXML FROM TESTCASE
				WHERE SERVICENAME = :servicename
				AND OPERATIONNAME = :servicename
				AND TESTCASE = :testcasename
				AND ENVIRONMENT = :envname";	
		
		$queryParse = oci_parse($conn, $query);
		oci_bind_by_name($queryParse, ':servicename', $servicename);
		oci_bind_by_name($queryParse, ':envname', $envname);
		oci_bind_by_name($queryParse, ':testcasename', $testcasename);
		
		oci_execute($queryParse);
		
		$row = oci_fetch_array($queryParse);
		$xmlResults['DESCRIPTION'] = $row['DESCRIPTION'];
		$xmlResults['REQUESTXML'] = $row['REQUESTXML'];
		$xmlResults['RESPONSEXML'] = $row['RESPONSEXML'];		
			
		$this->oracle->closeConnection($conn);
		return $xmlResults;
		
		
	}
	
	public function insertTestCase($envname, $servicename, $testcasename, $description, $requestedxml, $responsexml){
		$this->oracle->openConnection('Soapui');
		$conn = $this->oracle->getConnection();
		
		$query = "INSERT INTO TESTCASE 
				(SERVICENAME, OPERATIONNAME, ENVIRONMENT, TESTCASE, DESCRIPTION, REQUESTXML, RESPONSEXML) 
				VALUES (:servicename,:servicename, :envname, :testcasename, :description, :requestxml, :responsexml)";
		$queryParse = oci_parse($conn,$query);
		
		oci_bind_by_name($queryParse, ':servicename', $servicename);
		oci_bind_by_name($queryParse, ':envname', $envname);
		oci_bind_by_name($queryParse, ':testcasename', $testcasename);
		oci_bind_by_name($queryParse, ':description', $description);
		oci_bind_by_name($queryParse, ':requestxml', $requestedxml);
		oci_bind_by_name($queryParse, ':responsexml', $responsexml);
		$result = oci_execute($queryParse);
		
		$this->oracle->closeConnection($conn);
		return $result;
		
	}
	
	public function editTestCase($envname, $servicename, $testcasename, $description, $requestedxml, $responsexml){
		$this->oracle->openConnection('Soapui');
		$conn = $this->oracle->getConnection();
		
		$query = "UPDATE TESTCASE SET 
				REQUESTXML = :requestxml,
				RESPONSEXML = :responsexml,
				DESCRIPTION = :description 
				WHERE SERVICENAME = :servicename AND OPERATIONNAME = :servicename 
				AND ENVIRONMENT = :envname AND TESTCASE = :testcasename";
	
		$queryParse = oci_parse($conn,$query);
		
		oci_bind_by_name($queryParse, ':servicename', $servicename);
		oci_bind_by_name($queryParse, ':envname', $envname);
		oci_bind_by_name($queryParse, ':testcasename', $testcasename);
		oci_bind_by_name($queryParse, ':description', $description);
		oci_bind_by_name($queryParse, ':requestxml', $requestedxml);
		oci_bind_by_name($queryParse, ':responsexml', $responsexml);
		$result = oci_execute($queryParse);
		
		$this->oracle->closeConnection($conn);
		return $result;
	
	}

	public function getEnvDetails($env){		
		$this->oracle->openConnection('Soapui');
		$conn = $this->oracle->getConnection();
		$envDetails = array();
		
		$query = "SELECT DISTINCT SERVICENAME, COUNT(TESTCASE) AS TOTALTESTCASE FROM TESTCASE
				WHERE ENVIRONMENT = :env GROUP BY SERVICENAME ORDER BY SERVICENAME";
		$queryParse = oci_parse($conn, $query);
		oci_bind_by_name($queryParse, ':env', $env);
		oci_execute($queryParse);
		$totalCount = 0;
		
		while($row = oci_fetch_array($queryParse)){
			$autosuite = new AutomationSuite();
			
			$autosuite->setServiceName($row['SERVICENAME']);
			$autosuite->setTestCount($row['TOTALTESTCASE']);	
			$envDetails[] = $autosuite;			
		}
		
	
		$this->oracle->closeConnection();
		return $envDetails;
		
	}
	
	public function getServiceTCDetails($servicename){
		
		$this->oracle->openConnection('Soapui');
		$conn = $this->oracle->getConnection();
		$tcDetails = array();
		
		$query = "SELECT DISTINCT TESTCASE FROM TESTCASE
				WHERE SERVICENAME = :servicename ORDER BY TESTCASE";
		$queryParse = oci_parse($conn, $query);
		oci_bind_by_name($queryParse, ':servicename', $servicename);
		oci_execute($queryParse);
		
		while($row = oci_fetch_array($queryParse)){
			$testcase = new TestCase();
			$testcase->setTestCaseName($row['TESTCASE']);
						
			
			if (strpos(strtoupper($row['TESTCASE']),'SUCCESS') !== false) {				
				$testcase->setTestCaseType('Positive');
			}
			else {				
				$testcase->setTestCaseType('Negative');
			}
			
			$tcDetails[] = $testcase;
		}		
		
		
		$this->oracle->closeConnection($conn);
		return $tcDetails;
		
	}
	
	public function getComparisonData(){		
		$this->oracle->openConnection('Soapui');
		$conn = $this->oracle->getConnection();
		$projectreleaseData = array();
		
		$query = "SELECT DISTINCT RELEASE, ITERATION, DATEOFEXECUTION FROM REPORT 
				ORDER BY DATEOFEXECUTION DESC,to_number(REGEXP_REPLACE(ITERATION, '\D')) DESC";
		$queryParse = oci_parse($conn, $query);
		oci_execute($queryParse);
		
		while($row = oci_fetch_array($queryParse)){
			$executionDetail = new ExecutionDetails();
			
			$executionDetail->setIteration($row['ITERATION']);
			$executionDetail->setRelease($row['RELEASE']);
			
			$projectreleaseData[] = $executionDetail;			
		}		
		
		$this->oracle->closeConnection($conn);
		return $projectreleaseData;
		
	}
	
	public function getServiceCount($release, $iteration){
		$this->oracle->openConnection('Soapui');
		$conn = $this->oracle->getConnection();
		
		$query = "SELECT SUM(COUNT(DISTINCT(SERVICENAME))) AS SCOUNT, SUM(COUNT(DISTINCT(TESTCASENAME))) AS TCOUNT FROM REPORT
				WHERE RELEASE = :release AND ITERATION = :iteration GROUP BY SERVICENAME";
		
		$queryParse = oci_parse($conn, $query);
		oci_bind_by_name($queryParse, ':release', $release);
		oci_bind_by_name($queryParse, ':iteration', $iteration);
		
		oci_execute($queryParse);
		$row = oci_fetch_array($queryParse);		 
		
		return $row;
		
	}
	
	public function getFetchServiceData($release, $iteration){		
		$this->oracle->openConnection('Soapui');
		$conn = $this->oracle->getConnection();
		$fetchserviceData = array();		
		
		$query = "SELECT COUNT(TESTCASENAME) AS TESTCASES, SERVICENAME FROM REPORT
				WHERE RELEASE = :release AND ITERATION = :iteration GROUP BY SERVICENAME ORDER BY SERVICENAME";
		$queryParse = oci_parse($conn, $query);
		oci_bind_by_name($queryParse, ':release', $release);
		oci_bind_by_name($queryParse, ':iteration', $iteration);
		
		oci_execute($queryParse);
		
		while($row = oci_fetch_array($queryParse)){
			$autosuite = new AutomationSuite();
			
			$autosuite->setServiceName($row['SERVICENAME']);
			$autosuite->setTestCount($row['TESTCASES']);
			
			$fetchserviceData[] = $autosuite;
			
		}		
		
		$this->oracle->closeConnection($conn);
		return $fetchserviceData;
		
	}
	
	public function checkDBforSafeComparison(){
		$this->oracle->openConnection('Soapui');
		$conn = $this->oracle->getConnection();		
		
		$query = "SELECT COUNT(COMPARISON) AS COUNT FROM REPORT 
				WHERE COMPARISON IS NOT NULL";
		$queryParse = oci_parse($conn, $query);
		oci_execute($queryParse);
		$row = oci_fetch_array($queryParse);
		
		$this->oracle->closeConnection($conn);
		
		if($row['COUNT'] > 0)				
			return 1;
		else
			return 0;		
	}
	
	public function updateDBRecordsforSafeComparison($checklistArr, $release, $iteration){		
		$this->oracle->openConnection('Soapui');
		$conn = $this->oracle->getConnection();
		
		// BASELINE UPDATE
		foreach($checklistArr as $service) {				
			$query = "UPDATE REPORT SET
					COMPARISON = 'Baseline'
					WHERE SERVICENAME = '". $service ."' 
					AND RELEASE = '". $release . "'
					AND ITERATION = '". $iteration . "'";
			$queryParse = oci_parse($conn, $query);			
			
			oci_execute($queryParse);
		}
		
		// ACTUAL UPDATE 
		foreach($checklistArr as $service) {
			$query_count = "SELECT COMPARISON AS COMP FROM REPORT 
					WHERE SERVICENAME = '". $service ."'
					AND RELEASE = '". $release . "'
					AND ITERATION = '". $iteration . "'";
			$stmt_count = oci_parse($conn, $query_count);
			oci_define_by_name($stmt_count, 'COMP', $ValueComp);
			oci_execute($stmt_count);
			oci_fetch($stmt_count);
				
				
			If ($ValueComp == "Baseline"){
				$query = "UPDATE REPORT SET COMPARISON = 'Baseline/Expected' 
						WHERE SERVICENAME = '". $service ."'
						AND RELEASE = '". $release . "'
						AND ITERATION = '". $iteration . "'";
			}else{
				$query = "UPDATE REPORT SET COMPARISON = 'Expected'
						WHERE SERVICENAME = '". $service ."'
						AND RELEASE = '". $release . "'
						AND ITERATION = '". $iteration . "'";
			}
			$stIteration = oci_parse($conn, $query);
			oci_execute($stIteration);
		}
		
		// TOTAL BASELINE AND ACTUAL SELECTED FOR EXECUTION 
		$query_count = "SELECT COUNT(COMPARISON) AS BASECOUNT FROM REPORT
					WHERE COMPARISON IN ('Baseline','Baseline/Expected')";
		$stmt_count = oci_parse($conn, $query_count);
		oci_define_by_name($stmt_count, 'BASECOUNT', $Basecount);
		oci_execute($stmt_count);
		oci_fetch($stmt_count);
		
		$query_count = "SELECT COUNT(COMPARISON) AS ACTCOUNT FROM REPORT 
					WHERE COMPARISON IN ('Expected','Baseline/Expected')";
		$stmt_count = oci_parse($conn, $query_count);
		oci_define_by_name($stmt_count, 'ACTCOUNT', $Actcount);
		oci_execute($stmt_count);
		oci_fetch($stmt_count);
		
		/*$url = 'http://10.45.2.140:8080/jenkins/job/ControllerJob-SAFEComparison/build';
		$fields = '';
		$ch = curl_init( $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($ch);*/
		
		$this->oracle->closeConnection($conn);
		$response['count'] = array(
			"Basecount" => $Basecount,
			"Actcount" => $Actcount
		);
		
		return $response;
		
	}
	
	function updateDBforSafeComparison(){
		$this->oracle->openConnection('Soapui');
		$conn = $this->oracle->getConnection();
		
		$query = "UPDATE REPORT SET COMPARISON = NULL";
		$queryParse = oci_parse($conn, $query);
		$row = oci_execute($queryParse);
		
		$this->oracle->closeConnection($conn);
		return $row;
	}
}
