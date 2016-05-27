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
	
		}
			
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
					
			/*$projectDetail = new Projects();
		
			$projectDetail->setProjectID($row['PROJECTID']);
			$projectDetail->setProjectName($row['PROJECTNAME']);
			$projectDetail->setPOC($row['POC']);
			$projectDetail->setEstimatedProdLiveDate($row['ESTIMATED_PROD_LIVE_DATE']);
			$projectDetail->setReusability($row['REUSABILITY']);
			$projectDetail->setDomain($row['DOMAIN']);
			*/
			$projectDetails[] = $row;
			//echo "<pre>";print_r($projectDetails);exit;
			oci_free_statement($queryParse);
			$this->oracle->closeConnection();
			return $projectDetails;
		}
		else {
			while ($row = oci_fetch_array($queryParse, OCI_ASSOC+OCI_RETURN_NULLS)) {
			
				$projectDetail = new Projects();
				
				$projectDetail->setProjectID($row['PROJECTID']);
				$projectDetail->setProjectName($row['PROJECTNAME']);
				$projectDetail->setQCProjectName($row['QC_PROJECTNAME']);
				$projectDetail->setPOC($row['POC']);
				$projectDetail->setEstimatedProdLiveDate($row['ESTIMATED_PROD_LIVE_DATE']);
				$projectDetail->setReusability($row['REUSABILITY']);
				$projectDetail->setDomain($row['DOMAIN']);
				$projectDetail->setScope($row['SCOPE']);
				$projectDetail->setGate1Variance($row['GATE1_VARIANCE']);
				$projectDetail->setGate2Variance($row['GATE2_VARIANCE']);
				
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
			
		if(!empty(trim($postData['Gate1Estimation']))){
			$gate1_variance = ((trim($postData['FinalEstimation']) - trim($postData['Gate1Estimation'])) / trim($postData['Gate1Estimation']))*100;
			$gate1_variance = number_format($gate1_variance, 2);
		}
		else
			$gate1_variance = '';
				
		if(!empty(trim($postData['Gate2Estimation']))){
			$gate2_variance = ((trim($postData['FinalEstimation']) - trim($postData['Gate2Estimation'])) / trim($postData['Gate2Estimation']))*100;
			$gate2_variance = number_format($gate2_variance, 2);
		}
		else 	
			$gate2_variance = '';
				
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
		
			//echo $row['COUNT'];exit;
			
		
			if($row['COUNT'] == 0){				
				
				/*$query = "INSERT INTO PROJECTS (PROJECTID, PROJECTNAME, QC_PROJECTNAME, QC_TABLENAME, POC, DOMAIN,
						CYCLE_ID, IN_QC, WP, SCOPE, ESTIMATED_PROD_LIVE_DATE, ACTUAL_PROD_LIVE_DATE, REUSABILITY, REMARK,
						ENGAGEMENT_DATE, GATE1_ESTIMATION_DELIVERY_DATE, DIFF_DATE, GATE1_ESTIMATION, GATE2_ESTIMATION,
						FINAL_ESTIMATION, GATE1_VARIANCE, GATE2_VARIANCE, DOCUMENT_NAME, DOCUMENT_TYPE, DELIVERY_DATE, SIGN_OFF_DATE, REPOSITORY_LINK)						
						VALUES (PROJECTS_PROJECTID_SEQ.nextval, :projectname, :qcprojectname, '".$qc_table_name."',
						:poc, :domain, '".$cycle_id."', :in_qc, :wp, :scope, :estimated_prod_date, :actual_prod_date, 
						:reusability, :remark, :engagement_date, :gate1_estimation_delivery_date,
						'".$diff_date."', :gate1_estimation, :gate2_estimation, '".$final_estimation."', '".$gate1_variance."',
						'".$gate2_variance."', :document_name, :document_type, :delivery_date, :signoff_date, :repo_link)";
				*/
					
				$query = "INSERT INTO KPI_PROJECTS (PROJECTID, PROJECTNAME, QC_PROJECTNAME, QC_TABLENAME, POC, DOMAIN,
					CYCLE_ID, WP, SCOPE, ESTIMATED_PROD_LIVE_DATE, ACTUAL_PROD_LIVE_DATE, REUSABILITY, REMARK,
					ENGAGEMENT_DATE, GATE1_ESTIMATION_DELIVERY_DATE, GATE1_ESTIMATION, GATE2_ESTIMATION,
					FINAL_ESTIMATION, GATE1_VARIANCE, GATE2_VARIANCE, DOCUMENT_NAME, DOCUMENT_TYPE, DELIVERY_DATE, SIGN_OFF_DATE, REPOSITORY_LINK)
					VALUES (PROJECTS_PROJECTID_SEQ.nextval, '".trim($postData['ProjectName'])."', '".trim($postData['QCProjectName'])."', '".$qc_table_name."',
					'".trim($postData['POC'])."', '".trim($postData['Domain'])."', '".$postData['CycleID']."', 
					'".trim($postData['WP'])."', '".trim($postData['Scope'])."', 
					'".trim(strtoupper($postData['EstimatedProdLiveDate']))."', '".$ActualProdLiveDate."',
					'".trim($postData['Reusability'])."', '".trim($postData['Remark'])."',
					'".trim(strtoupper($postData['EngagementDate']))."',
					'".trim(strtoupper($postData['Gate1EstimationDeliveryDate']))."', '".trim($postData['Gate1Estimation'])."',
					'".trim($postData['Gate2Estimation'])."', '".trim($postData['FinalEstimation'])."',
					'".$gate1_variance."', '".$gate2_variance."', '".trim($postData['DocumentName'])."',
					'".trim($postData['DocumentType'])."', '".trim(strtoupper($postData['DeliveryDate']))."',
					'".trim(strtoupper($postData['SignoffDate']))."', '".trim($postData['RepositoryLink'])."')";
					
				//echo $query;exit;
				$queryParse = oci_parse($conn, $query);	
				
					
				/*oci_bind_by_name($queryParse, ':email', trim(addslashes($postData['Email'])));
				oci_bind_by_name($queryParse, ':projectname', trim(addslashes($postData['ProjectName'])));
				oci_bind_by_name($queryParse, ':qcprojectname', trim(addslashes($postData['QCProjectName'])));
				oci_bind_by_name($queryParse, ':poc', trim(addslashes($postData['POC'])));
				oci_bind_by_name($queryParse, ':domain', trim(addslashes($postData['Domain'])));				
				oci_bind_by_name($queryParse, ':wp', trim(addslashes($postData['WP'])));
				oci_bind_by_name($queryParse, ':scope', $postData['Scope']);
				oci_bind_by_name($queryParse, ':estimated_prod_date', $postData['EstimatedProdLiveDate']);
				oci_bind_by_name($queryParse, ':actual_prod_date', $ActualProdLiveDate);
				oci_bind_by_name($queryParse, ':reusability', trim(addslashes($postData['Reusability'])));				
				oci_bind_by_name($queryParse, ':remark', trim(addslashes($postData['Remark'])));
				oci_bind_by_name($queryParse, ':engagement_date', $postData['EngagementDate']);
				oci_bind_by_name($queryParse, ':gate1_estimation_delivery_date', $postData['Gate1EstimationDeliveryDate']);
				oci_bind_by_name($queryParse, ':gate1_estimation', trim(addslashes($postData['Gate1Estimation'])));
				oci_bind_by_name($queryParse, ':gate2_estimation', trim(addslashes($postData['Gate2Estimation'])));				
				oci_bind_by_name($queryParse, ':final_estimation', trim(addslashes($postData['FinalEstimation'])));
				oci_bind_by_name($queryParse, ':document_name', trim(addslashes($postData['DocumentName'])));
				oci_bind_by_name($queryParse, ':document_type', trim(addslashes($postData['DocumentType'])));
				oci_bind_by_name($queryParse, ':delivery_date', $postData['DeliveryDate']);
				oci_bind_by_name($queryParse, ':signoff_date', $postData['SignoffDate']);
				oci_bind_by_name($queryParse, ':repo_link', trim(addslashes($postData['RepositoryLink'])));
				*/
				
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
						REMARK = '".trim($postData['Remark'])."',					
						ENGAGEMENT_DATE = '".trim(strtoupper($postData['EngagementDate']))."',
						GATE1_ESTIMATION_DELIVERY_DATE = '".trim(strtoupper($postData['Gate1EstimationDeliveryDate']))."',
						GATE1_ESTIMATION = '".trim($postData['Gate1Estimation'])."',
						GATE2_ESTIMATION = '".trim($postData['Gate2Estimation'])."',
						FINAL_ESTIMATION = '".trim($postData['FinalEstimation'])."',
						GATE1_VARIANCE = '".$gate1_variance."',
						GATE2_VARIANCE = '".$gate2_variance."',
						DOCUMENT_NAME = '".trim($postData['DocumentName'])."',
						DOCUMENT_TYPE = '".trim($postData['DocumentType'])."',
						DELIVERY_DATE = '".trim(strtoupper($postData['DeliveryDate']))."',
						SIGN_OFF_DATE = '".trim(strtoupper($postData['SignoffDate']))."',
						REPOSITORY_LINK = '".trim($postData['RepositoryLink'])."'
						WHERE PROJECTID = ".$postData['ProjectID'];		
					
				/*oci_bind_by_name($queryParse, ':email', trim(addslashes($postData['Email'])));
				 oci_bind_by_name($queryParse, ':projectname', trim(addslashes($postData['ProjectName'])));
				 oci_bind_by_name($queryParse, ':qcprojectname', trim(addslashes($postData['QCProjectName'])));
				 oci_bind_by_name($queryParse, ':poc', trim(addslashes($postData['POC'])));
				 oci_bind_by_name($queryParse, ':domain', trim(addslashes($postData['Domain'])));
				 oci_bind_by_name($queryParse, ':wp', trim(addslashes($postData['WP'])));
				 oci_bind_by_name($queryParse, ':scope', $postData['Scope']);
				 oci_bind_by_name($queryParse, ':estimated_prod_date', $postData['EstimatedProdLiveDate']);
				 oci_bind_by_name($queryParse, ':actual_prod_date', $ActualProdLiveDate);
				 oci_bind_by_name($queryParse, ':reusability', trim(addslashes($postData['Reusability'])));
				 oci_bind_by_name($queryParse, ':remark', trim(addslashes($postData['Remark'])));
				 oci_bind_by_name($queryParse, ':engagement_date', $postData['EngagementDate']);
				 oci_bind_by_name($queryParse, ':gate1_estimation_delivery_date', $postData['Gate1EstimationDeliveryDate']);
				 oci_bind_by_name($queryParse, ':gate1_estimation', trim(addslashes($postData['Gate1Estimation'])));
				 oci_bind_by_name($queryParse, ':gate2_estimation', trim(addslashes($postData['Gate2Estimation'])));
				 oci_bind_by_name($queryParse, ':final_estimation', trim(addslashes($postData['FinalEstimation'])));
				 oci_bind_by_name($queryParse, ':document_name', trim(addslashes($postData['DocumentName'])));
				 oci_bind_by_name($queryParse, ':document_type', trim(addslashes($postData['DocumentType'])));
				 oci_bind_by_name($queryParse, ':delivery_date', $postData['DeliveryDate']);
				 oci_bind_by_name($queryParse, ':signoff_date', $postData['SignoffDate']);
				 oci_bind_by_name($queryParse, ':repo_link', trim(addslashes($postData['RepositoryLink'])));
				 oci_bind_by_name($queryParse, ':project_id', trim(addslashes($postData['ProjectID'])));
				 
				 */
						
					//echo $query;exit;
					$queryParse = oci_parse($conn, $query);	
					
						
					/*oci_bind_by_name($queryParse, ':email', $postData['Email']);
					oci_bind_by_name($queryParse, ':password', $password);
					oci_bind_by_name($queryParse, ':firstname', $postData['FirstName']);
					oci_bind_by_name($queryParse, ':lastname', $postData['LastName']);
					oci_bind_by_name($queryParse, ':roleid', $postData['RoleID']);
					*/
						
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