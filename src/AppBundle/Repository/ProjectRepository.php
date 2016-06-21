<?php

namespace AppBundle\Repository;

use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\Service\OracleDatabaseService;
use AppBundle\Entity\Projects;
use AppBundle\Service\Validate;


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
	
	public function getProjectDetails($ProjectID = "", $Month = "") {
		$this->oracle->openConnection('KPIDASHBOARD');
		$conn = $this->oracle->getConnection();
		$projectDetails = array();
		
	
		$query = "SELECT * FROM KPI_PROJECTS WHERE ACTIVE = 1";
				
		if(!empty($ProjectID))
			$query .= " AND PROJECTID = ".$ProjectID;
	
		//echo $Month;//exit;
		
		if(!empty($Month)){
			$Month = strtoupper(str_replace("-", "/", $Month));
			$query .= " AND TO_CHAR(ESTIMATED_PROD_LIVE_DATE, 'MON/YY') LIKE '%".$Month."%'";
			
		} else {
			
			$Month = strtoupper(date('M/y'));
			
			$month = date("Y-m", strtotime( date( 'Y-m-01' )." -1 months"));
			$Month1 = strtoupper(date("M/y", strtotime($month)));
			
			$query .= " AND (TO_CHAR(ESTIMATED_PROD_LIVE_DATE, 'MON/YY') LIKE '%".$Month."%' OR 
							TO_CHAR(ESTIMATED_PROD_LIVE_DATE, 'MON/YY') LIKE '%".$Month1."%') OR ESTIMATED_PROD_LIVE_DATE IS NULL ";
		}
		
		
					
		$query .= " ORDER BY ESTIMATED_PROD_LIVE_DATE";		
		
		//echo $query;//exit;
		$queryParse = oci_parse($conn, $query);
		oci_execute($queryParse);		
		
		
		if(!empty($ProjectID)){
			while($row = oci_fetch_array($queryParse, OCI_ASSOC+OCI_RETURN_NULLS)){
			
				/*$projectDetail = new Projects();
				$projectDetail->setProjectID($row['PROJECTID']);
				$projectDetail->setProjectName($row['PROJECTNAME']);
				$projectDetail->setQCProjectName($row['QC_PROJECTNAME']);
				$projectDetail->setQCTableName($row['QC_TABLENAME']);
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
				*/
				
				$projectDetail = array();
				
				$projectDetail['ProjectID'] = $row['PROJECTID'];
				$projectDetail['ProjectName'] = $row['PROJECTNAME'];
				$projectDetail['QCProjectName'] = $row['QC_PROJECTNAME'];				
				$projectDetail['POC'] = $row['POC'];
				$projectDetail['Domain'] = $row['DOMAIN'];		
				
				$projectDetail['CycleID'] = $row['CYCLE_ID'];
				$projectDetail['WP'] = $row['WP'];
				$projectDetail['EstimatedProdLiveDate'] = $row['ESTIMATED_PROD_LIVE_DATE'];
				$projectDetail['ActualProdLiveDate'] = $row['ACTUAL_PROD_LIVE_DATE'];
				$projectDetail['Reusability'] = $row['REUSABILITY'];
				
				$projectDetail['Scope'] = $row['SCOPE'];
				$projectDetail['stAutomatedTestCases'] = $row['NO_OF_ST_AUTOMATED_TEST_CASES'];
				$projectDetail['stTotalTestCases'] = $row['TOTAL_NO_OF_ST_TEST_CASES'];
				$projectDetail['uatApplicable'] = $row['UAT_APPLICABLE'];
						
	
				oci_free_statement($queryParse);
				$this->oracle->closeConnection();
				
				return $projectDetail;
			}
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
				$projectDetail->setSTAutomation($row ['ST_AUTOMATION']);
				$projectDetail->setUatApplicable($row['UAT_APPLICABLE']);				
				$projectDetail->setProjectAddedBy($row ['ADDED_BY']);
				$projectDetail->setProjectEditedBy($row['EDITED_BY']);				

				$projectDetails[] = $projectDetail;
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
		date_default_timezone_set('Australia/Sydney');
		
		$session = new Session();
		$userID = $session->get('UserID');
		
		$objrv = new Validate();
		//echo "<pre>";print_r($postData);exit;		
	
		if(!empty(trim($postData['Domain'])) && !empty(trim($postData['QCProjectName'])))
			$qc_table_name = trim($postData['Domain'])."_".trim($postData['QCProjectName'])."_DB";   		
		else 
			$qc_table_name = '';				
		
		
		if(empty(trim($postData['ActualProdLiveDate'])))
			$ActualProdLiveDate = strtoupper($objrv->rv('EstimatedProdLiveDate'));
		else
			$ActualProdLiveDate = strtoupper($objrv->rv('ActualProdLiveDate'));
						
							
		/* INSERT PROJECT */ 
		
		if(empty($postData['ProjectID'])){
		
			$query = "SELECT COUNT(*) AS COUNT FROM KPI_PROJECTS WHERE ACTIVE = 1 AND PROJECTNAME = :projectname";
			$queryParse = oci_parse($conn, $query);
			
			oci_bind_by_name($queryParse, ':projectname', $postData['ProjectName']);
			oci_execute($queryParse);
			
			$row = oci_fetch_array($queryParse);
		    $stAutomatedTestCases = $objrv->rv('stAutomatedTestCases');
		    $stTotalTestCases = $objrv->rv('stTotalTestCases');
		    
		    if($stTotalTestCases != 0)
		    	$stAutomation = round(($stAutomatedTestCases / $stTotalTestCases) * 100);
		    else
		    	$stAutomation = '';
		    		
		
			if($row['COUNT'] == 0){			
					
				$query = "INSERT INTO KPI_PROJECTS (PROJECTID, PROJECTNAME, QC_PROJECTNAME, QC_TABLENAME, POC, DOMAIN,
					CYCLE_ID, WP, SCOPE, ESTIMATED_PROD_LIVE_DATE, ACTUAL_PROD_LIVE_DATE, REUSABILITY,UAT_APPLICABLE,
					NO_OF_ST_AUTOMATED_TEST_CASES,TOTAL_NO_OF_ST_TEST_CASES,ST_AUTOMATION, ADDED_BY, ADDED_ON)
					VALUES (PROJECTS_PROJECTID_SEQ.nextval, '".$objrv->rv('ProjectName')."', '".$objrv->rv('QCProjectName')."',
							'".$qc_table_name."', '".$objrv->rv('POC')."', '".$objrv->rv('Domain')."',
							'".$objrv->rv('CycleID')."', '".$objrv->rv('WP')."', '".$objrv->rv('Scope')."', 
							'".$objrv->rv('EstimatedProdLiveDate')."', '".$ActualProdLiveDate."',
							'".$objrv->rv('Reusability')."','".$objrv->rv('UATApplicable')."',
							'".$objrv->rv('stAutomatedTestCases')."','".$objrv->rv('stTotalTestCases')."',
							'".$stAutomation."', ".$userID.", '".date("d/M/Y h:i:s A")."')";
				
				//echo $query;exit;
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
		
			/* UPDATE PROJECT */	
			
			$query = "SELECT COUNT(*) AS COUNT 
					FROM KPI_PROJECTS 
					WHERE ACTIVE = 1
					AND PROJECTNAME = :projectname
					AND PROJECTID != ".$postData['ProjectID'];
			
			$queryParse = oci_parse($conn, $query);
			
			$ProjName = $objrv->rv('ProjectName');
			oci_bind_by_name($queryParse, ':projectname', $ProjName);
			oci_execute($queryParse);
			
			$row = oci_fetch_array($queryParse);
			
			$stAutomatedTestCases = $objrv->rv('stAutomatedTestCases');
			$stTotalTestCases = $objrv->rv('stTotalTestCases');
			
			if($stTotalTestCases != 0)
				$stAutomation = round(($stAutomatedTestCases / $stTotalTestCases) * 100);
			else
				$stAutomation = '';
			
			if($row['COUNT'] == 0){
				
				$query = "UPDATE KPI_PROJECTS SET  					
						PROJECTNAME = '".$objrv->rv('ProjectName')."',
						QC_PROJECTNAME = '".$objrv->rv('QCProjectName')."',
						QC_TABLENAME = '".$qc_table_name."',
						POC = '".$objrv->rv('POC')."',
						DOMAIN = '".$objrv->rv('Domain')."',
						CYCLE_ID = '".$objrv->rv('CycleID')."',
						WP = '".$objrv->rv('WP')."',						
						ESTIMATED_PROD_LIVE_DATE = '".strtoupper($objrv->rv('EstimatedProdLiveDate'))."',
						ACTUAL_PROD_LIVE_DATE = '".$ActualProdLiveDate."',
						REUSABILITY = '".$objrv->rv('Reusability')."',
						SCOPE = '".$postData['Scope']."',
						UAT_APPLICABLE = '".$postData['uatApplicable']."',
						NO_OF_ST_AUTOMATED_TEST_CASES = '".$objrv->rv('stAutomatedTestCases')."',
						TOTAL_NO_OF_ST_TEST_CASES = '".$objrv->rv('stTotalTestCases')."',
						ST_AUTOMATION = '".$stAutomation."',
						EDITED_BY = ".$userID.",
						EDITED_ON = '".date("d/M/Y h:i:s A")."'
						WHERE PROJECTID = ".$postData['ProjectID'];
		
						
				//echo "<pre>";echo $query;exit;
					
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
	
	public function checkValidQCTableName($QcProjectName, $Domain) {              
              
              $this->oracle->openConnection('QC');
              $conn = $this->oracle->getConnection();
              
              $query = "SELECT DB_NAME 
              		FROM QCSITEADMIN_DB.PROJECTS 
              		WHERE UPPER(PROJECT_NAME) = '".strtoupper($QcProjectName)."'
              		AND UPPER(DOMAIN_NAME) = '".strtoupper($Domain)."'";          
              
             //echo $query;exit;
              
              $queryParse = oci_parse($conn, $query);
              oci_execute($queryParse);
              
              $row = oci_fetch_array($queryParse, OCI_ASSOC+OCI_RETURN_NULLS);
              
              if($row['DB_NAME']){

              	$query1 = "SELECT COUNT(*) AS COUNT 
              			FROM QCSITEADMIN_DB.PROJECTS
              			WHERE UPPER(DB_NAME) = '".strtoupper($row['DB_NAME'])."'";
              	
              	//echo $query1;exit;
              	
              	$query1Parse = oci_parse($conn, $query1);
              	oci_execute($query1Parse);
              	
              	$schemaresult = oci_fetch_array($query1Parse, OCI_ASSOC+OCI_RETURN_NULLS);
              	if($schemaresult['COUNT'] == 0)
              		$status = 0;
              	else
              		$status = 1;
              }
              
              else{
                $status = 0;
              }
              
              oci_free_statement($queryParse);
              $this->oracle->closeConnection();                 
		
			 return $status;
    }
	
}