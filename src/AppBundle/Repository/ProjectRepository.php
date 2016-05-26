<?php

namespace AppBundle\Repository;


use AppBundle\Service\OracleDatabaseService;
use AppBundle\Entity\Projects;


/**
 * @author Dhara Sheth
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
		
	public function getProjectNames() {
		$this->oracle->openConnection('KPIDASHBOARD');
		$conn = $this->oracle->getConnection();
		$projectDetails = array();
	
		$query = "SELECT PROJECTNAME, PROJECTID FROM KPI_PROJECTS WHERE ACTIVE = 1 ORDER BY PROJECTNAME";
	
			//echo $query;exit;
		$queryParse = oci_parse($conn, $query);
		oci_execute($queryParse);
	
		while ($row = oci_fetch_array($queryParse, OCI_ASSOC+OCI_RETURN_NULLS)) {
				
			$projectDetail = new Projects();

			$projectDetail->setProjectID($row['PROJECTID']);
			$projectDetail->setProjectName($row['PROJECTNAME']);		
			
			$projectDetails[] = $projectDetail;
			//echo "<pre>";print_r($projectDetails);
		}
			
		//exit;
		//echo "<pre>";print_r($projectDetails);exit;
		oci_free_statement($queryParse);
		$this->oracle->closeConnection();
		return $projectDetails;
			
	}
	
	/**
	 * Returns an array containing all of the details related to the given Test Environment
	 * 
	 * @param int $ProjectID
	 * @return array
	 */
	
	public function getProjectDetails($ProjectID = "") {
		$this->oracle->openConnection('KPIDASHBOARD');
		$conn = $this->oracle->getConnection();
		$projectDetails = array();
		
		$query = "SELECT * FROM KPI_PROJECTS WHERE ACTIVE = 1";
				
		if(!empty($ProjectID))
			$query .= " AND ProjectID = ".$ProjectID;
		
		$query .= " ORDER BY ESTIMATED_PROD_LIVE_DATE";		
		
		//echo $query;exit;
		$queryParse = oci_parse($conn, $query);
		oci_execute($queryParse);		
		
		
		if(!empty($ProjectID)){
			$row = oci_fetch_array($queryParse, OCI_ASSOC+OCI_RETURN_NULLS);
				$projectDetail = new Projects();
				$projectDetail->setProjectID($row['PROJECTID']);
				$projectDetail->setProjectName($row['PROJECTNAME']);
				$projectDetail->setQCProjectName($row['QC_PROJECTNAME']);
				$projectDetail->setCycleID($row['CYCLE_ID']);
				$projectDetail->setPOC($row['POC']);
				$projectDetail->setWP($row['WP']);
				$projectDetail->setEstimatedProdLiveDate($row['ESTIMATED_PROD_LIVE_DATE']);
				$projectDetail->setActualProdLiveDate($row['ACTUAL_PROD_LIVE_DATE']);
				$projectDetail->setReusability($row['REUSABILITY']);
				$projectDetail->setDomain($row['DOMAIN']);
				$projectDetail->setScope($row['SCOPE']);
				$projectDetail->setSTAutomatedTestCases($row['NO_OF_ST_AUTOMATED_TEST_CASES']);
				$projectDetail->setSTTotalTestCases($row['TOTAL_NO_OF_ST_TEST_CASES']);
				$projectDetail->setUatApplicable($row['UAT_APPLICABLE']);
			//echo "<pre>";print_r($row['UAT_APPLICABLE']);EXIT;
			oci_free_statement($queryParse);
			$this->oracle->closeConnection();
			return $projectDetail;
		}
		else {
			while ($row = oci_fetch_array($queryParse, OCI_ASSOC+OCI_RETURN_NULLS)) {
			
				$projectDetail = new Projects();
				$projectDetail->setProjectID($row['PROJECTID']);
				$projectDetail->setProjectName($row['PROJECTNAME']);
				$projectDetail->setQCProjectName($row['QC_PROJECTNAME']);
				$projectDetail->setCycleID($row['CYCLE_ID']);
				$projectDetail->setPOC($row['POC']);
				$projectDetail->setEstimatedProdLiveDate($row['ESTIMATED_PROD_LIVE_DATE']);
				$projectDetail->setReusability($row['REUSABILITY']);
				$projectDetail->setDomain($row['DOMAIN']);
				$projectDetail->setScope($row['SCOPE']);
				$stAutomation=round($row ['ST_AUTOMATION'],2);
				$projectDetail->setSTAutomation($stAutomation);
				$projectDetail->setUatApplicable($row['UAT_APPLICABLE']);
				//echo $check;exit;
				$projectDetails[] = $projectDetail;
				//echo "<pre>";print_r($projectDetails);
			}
			
			//exit;
			//echo "<pre>";print_r($projectDetails);exit;
			oci_free_statement($queryParse);		
			$this->oracle->closeConnection();		
			return $projectDetails;
		}
	}
	
	public function addEditProject($postData){
		$this->oracle->openConnection('KPIDASHBOARD');
		$conn = $this->oracle->getConnection();
		
		//echo "<pre>";print_r($postData);exit;
		if(!empty(trim($postData['Domain'])) && !empty(trim($postData['QCProjectName'])))
			$qc_table_name = trim($postData['Domain'])."_".trim($postData['QCProjectName'])."_DB";   		
		else 
			$qc_table_name = '';				
				
		if(empty(trim($postData['ActualProdLiveDate'])))
			$ActualProdLiveDate = trim(strtoupper($postData['EstimatedProdLiveDate']));
		else
			$ActualProdLiveDate = trim(strtoupper($postData['ActualProdLiveDate']));
						
							
		// INSERT PROJECT 
		
		if(empty($postData['ProjectID'])){
		
			$query = "SELECT COUNT(*) AS COUNT FROM KPI_PROJECTS WHERE ACTIVE = 1 AND PROJECTNAME = :projectname";
			$queryParse = oci_parse($conn, $query);
			oci_bind_by_name($queryParse, ':projectname', $postData['ProjectName']);
			oci_execute($queryParse);
			$row = oci_fetch_array($queryParse);
		    $stAutomatedTestCases=$postData['stAutomatedTestCases'];
		    $stTotalTestCases=$postData['stTotalTestCases'];
		    $stAutomation=$stAutomatedTestCases/$stTotalTestCases;
			//echo $row['COUNT'];exit;
			
		
			if($row['COUNT'] == 0){	
					
				$query = "INSERT INTO KPI_PROJECTS (PROJECTID, PROJECTNAME, QC_PROJECTNAME, QC_TABLENAME, POC, DOMAIN,
					CYCLE_ID, WP, SCOPE, ESTIMATED_PROD_LIVE_DATE, ACTUAL_PROD_LIVE_DATE, REUSABILITY,UAT_APPLICABLE, NO_OF_ST_AUTOMATED_TEST_CASES,TOTAL_NO_OF_ST_TEST_CASES,ST_AUTOMATION)
					VALUES (PROJECTS_PROJECTID_SEQ.nextval, '".trim($postData['ProjectName'])."', '".trim($postData['QCProjectName'])."', '".$qc_table_name."',
					'".trim($postData['POC'])."', '".trim($postData['Domain'])."', '".$postData['CycleID']."', 
					'".trim($postData['WP'])."', '".trim($postData['Scope'])."', 
					'".trim(strtoupper($postData['EstimatedProdLiveDate']))."', '".$ActualProdLiveDate."',
					'".trim($postData['Reusability'])."','".trim($postData['uatApplicable'])."',
					'".trim($postData['stAutomatedTestCases'])."','".trim($postData['stTotalTestCases'])."',$stAutomation )";
				$queryParse = oci_parse($conn, $query);	
				$row = oci_execute($queryParse);
					
				if($row)
					return $status = 'Project Added !!!';
				else
					return $status = 'Some problem occurred, try again !!!';
							
			} else {
				return $status = 'Given Project name already exists, try using other name !!!';
			}
		}
		
		else {
		
			// UPDATE PROJECT			
			$query = "SELECT COUNT(*) AS COUNT FROM KPI_PROJECTS WHERE ACTIVE = 1 AND PROJECTNAME = :projectname AND PROJECTID != ".$postData['ProjectID'];
			$queryParse = oci_parse($conn, $query);
			oci_bind_by_name($queryParse, ':projectname', $postData['ProjectName']);
			oci_execute($queryParse);
			$row = oci_fetch_array($queryParse);
			$stAutomatedTestCases=$postData['stAutomatedTestCases'];
			$stTotalTestCases=$postData['stTotalTestCases'];
			$stAutomation=$stAutomatedTestCases/$stTotalTestCases;
			
			if($row['COUNT'] == 0){
				
				$query = "UPDATE KPI_PROJECTS SET 					
						PROJECTNAME = '".trim($postData['ProjectName'])."',
						QC_PROJECTNAME = '".trim($postData['QCProjectName'])."',
						QC_TABLENAME = '".$qc_table_name."',
						POC = '".trim($postData['POC'])."',
						DOMAIN = '".trim($postData['Domain'])."',
						CYCLE_ID = '".trim($postData['CycleID'])."',
						WP = '".trim($postData['WP'])."',
						SCOPE = '".trim($postData['Scope'])."',
						ESTIMATED_PROD_LIVE_DATE = '".trim(strtoupper($postData['EstimatedProdLiveDate']))."',
						ACTUAL_PROD_LIVE_DATE = '".$ActualProdLiveDate."',
						REUSABILITY = '".trim($postData['Reusability'])."',
						UAT_APPLICABLE='".trim($postData['uatApplicable'])."',
						NO_OF_ST_AUTOMATED_TEST_CASES='".trim($postData['stAutomatedTestCases'])."',
						TOTAL_NO_OF_ST_TEST_CASES='".trim($postData['stTotalTestCases'])."',
						ST_AUTOMATION=	$stAutomation	
						WHERE PROJECTID = ".$postData['ProjectID'];		
						
					//echo $query;exit;
					$queryParse = oci_parse($conn, $query);	
					$row = oci_execute($queryParse);
						
					if($row)
						return $status = 'Project Updated !!!';
					else
						return $status = 'Some problem occurred, try again !!!';
			} else {
				
				return $status = 'Given project name is already in use !!!';
				
			}
		}
	}
}