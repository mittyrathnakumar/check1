<?php

namespace AppBundle\Repository;


use AppBundle\Service\OracleDatabaseService;
use AppBundle\Entity\DeliverySlippage;
use AppBundle\Entity\STAutomationEntity;
use AppBundle\Entity\KPI;
use AppBundle\Entity\Projects;
use AppBundle\Entity\Documents;


/**
 * @author Dhara Sheth
 */
class KPIRepository
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
	 * Returns an array containing all of the details related to the given Test Environment
	 * 
	 * @param string $env
	 * @return array
	 */
	public function getDefectDetails($postData = "") {
		
		/*
		if(!empty($postData['Month']))
			$month = $postData['Month'];
		
		$this->oracle->openConnection('KPIDASHBOARD');
		$conn = $this->oracle->getConnection();				
		
		if(empty($month))
			$month = strtoupper(date('M/y'));		 
			
		$query = "SELECT PROJECTID, QC_PROJECTNAME, P1_P2, P3_P4 
				FROM KPI_PROJECTS 
				WHERE TO_CHAR(ESTIMATED_PROD_LIVE_DATE, 'MON/YY') LIKE '%".$month."%'
				AND ACTIVE = 1		
				ORDER BY ESTIMATED_PROD_LIVE_DATE";
		
		//echo $query;exit;
		$queryParse = oci_parse($conn, $query);
		oci_execute($queryParse);	
		
		$KPIDetails = array();
		while ($row = oci_fetch_array($queryParse, OCI_ASSOC+OCI_RETURN_NULLS)) {
			$KPIDetail = new KPI();
			
			$KPIDetail->setProjectID($row['PROJECTID']);
			$KPIDetail->setProjectName($row['QC_PROJECTNAME']);
			$KPIDetail->setP1P2($row['P1_P2']);
			$KPIDetail->setP3P4($row['P3_P4']);			
		
			$KPIDetails[] = $KPIDetail;
			
		}
		
		//echo "<pre>";print_r($KPIDetails);exit;
		oci_free_statement($queryParse);
		$this->oracle->closeConnection();
		return $KPIDetails;
		*/	
	
		$this->oracle->openConnection('KPIDASHBOARD');
		$conn = $this->oracle->getConnection();
		
		for ($i = 0; $i <= 12; $i++) {
			$month = date("Y-m", strtotime( date( 'Y-m-01' )." -$i months"));
			$months[] = strtoupper(date("M/y", strtotime($month)));
		}
		$DefectsResult = array();
		
		foreach($months as $month){
			$DefectsResult[$month] = array();
			
			$query = "SELECT PROJECTID, PROJECTNAME, P1_P2, P3_P4 
					FROM KPI_PROJECTS WHERE 
					TO_CHAR(ESTIMATED_PROD_LIVE_DATE, 'MON/YY') LIKE '%".$month."%'
					AND ACTIVE = 1";
			
			//echo $query;
			$queryParse = oci_parse($conn, $query);
			oci_execute($queryParse);			
			$DefectsResultTemp = array();
			
			while ($Projects = oci_fetch_array($queryParse, OCI_ASSOC+OCI_RETURN_NULLS)) {				
				$DefectsDetailResult =  array();
				
				$DefectsDetailResult['PROJECTID'] = $Projects['PROJECTID'];
				$DefectsDetailResult['PROJECTNAME'] = $Projects['PROJECTNAME'];
				$DefectsDetailResult['P1_P2'] = $Projects['P1_P2'];
				$DefectsDetailResult['P3_P4'] = $Projects['P3_P4'];
				
				$DefectsResultTemp[] = $DefectsDetailResult;
			}
			$DefectsResult[$month] = $DefectsResultTemp;
		}
		
		//echo "<pre>";print_r($DefectsResult);exit;
		oci_free_statement($queryParse);
		$this->oracle->closeConnection();
		return $DefectsResult;
		
		
	}	
	
	public function updateDefectDetails($postData){
		
		$column = $postData['column'];
		$value = $postData['value'];
		$ProjectID = $postData['ProjectID'];
		
		$this->oracle->openConnection('KPIDASHBOARD');
		$conn = $this->oracle->getConnection();
		
		$query = "UPDATE KPI_PROJECTS SET
				".$column." = '".$value."'
				WHERE PROJECTID = ".$ProjectID;
		$queryParse = oci_parse($conn, $query);
		$row = oci_execute($queryParse);
		
		if($row == 1)
			$response = 'Details updated !!!';
		else
			$response = 'Some problem occurred, pls try again !!!';
					
		oci_free_statement($queryParse);
		$this->oracle->closeConnection();
		
		return $response;		
		
	}
	
	public function getmonthArray(){
		$currentYear = date("y");

		$months[0] = 'JAN/'.$currentYear;
		$months[1] = 'FEB/'.$currentYear;
		$months[2] = 'MAR/'.$currentYear;
		$months[3] = 'APR/'.$currentYear;
		$months[4] = 'MAY/'.$currentYear;
		$months[5] = 'JUN/'.$currentYear;
		$months[6] = 'JUL/'.$currentYear;
		$months[7] = 'AUG/'.$currentYear;
		$months[8] = 'SEP/'.$currentYear;
		$months[9] = 'OCT/'.$currentYear;
		$months[10] = 'NOV/'.$currentYear;
		$months[11] = 'DEC/'.$currentYear;
	
		return $months;	
	}
	
	public function getProcessDetails($IntakeID = ""){
		$this->oracle->openConnection('KPIDASHBOARD');
		$conn = $this->oracle->getConnection();
		$ProcessDetails = array();
		$i = 0;
		
		$query = "SELECT * FROM KPI_INTAKE_PROCESS WHERE ACTIVE = 1";
		
		if(!empty($IntakeID))
			$query .= " AND INTAKEID = ".$IntakeID;
		
		$queryParse = oci_parse($conn, $query);
		oci_execute($queryParse);
		
		while ($row = oci_fetch_array($queryParse, OCI_ASSOC+OCI_RETURN_NULLS)) {
			$ProcessDetail = array();
			$ProcessDetail['INTAKEID'] = $row['INTAKEID'];
			$ProcessDetail['PROGRAMME'] = $row['PROGRAMME'];
			$ProcessDetail['PROJECT_NAME'] = $row['PROJECT_NAME'];
			$ProcessDetail['PROPOSAL_TYPE'] = $row['PROPOSAL_TYPE'];
			$ProcessDetail['SOLUTION_COMPONENT'] = $row['SOLUTION_COMPONENT'];
			$ProcessDetail['PROJ_PROG_MGR'] = $row['PROJ_PROG_MGR'];
			$ProcessDetail['WP_PO_STATUS'] = $row['WP_PO_STATUS'];
			$ProcessDetail['STATUS'] = $row['STATUS'];
			$ProcessDetail['REQUEST_DATE'] = $row['REQUEST_DATE'];
			$ProcessDetail['SUBMISSION_DATE'] = $row['SUBMISSION_DATE'];
			$ProcessDetail['VALUE'] = $row['VALUE'];
			
			$ProcessDetails[$i] = $ProcessDetail;
			$i++;
		}		
		
		oci_free_statement($queryParse);
		$this->oracle->closeConnection();
		
		return $ProcessDetails;
	}
	
	
	public function addEditIntakeProcessDetails($postData, $IntakeID = ""){
		$this->oracle->openConnection('KPIDASHBOARD');
		$conn = $this->oracle->getConnection();		
		
		$dStart = new \DateTime($postData['RequestDate']);
		$dEnd  = new \DateTime($postData['SubmissionDate']);
		$dDiff = $dStart->diff($dEnd);
		$DateDiff = $dDiff->days;
		
		
		/* Add Data */
		
		if(empty($IntakeID)){
		
			$query = "INSERT INTO KPI_INTAKE_PROCESS 
					(INTAKEID, PROGRAMME, PROJECT_NAME, PROPOSAL_TYPE, SOLUTION_COMPONENT, PROJ_PROG_MGR, WP_PO_STATUS, 
					STATUS, REQUEST_DATE, SUBMISSION_DATE, VALUE, DIFF_DATES)
					VALUES(INTAKEPROCESS_ID.nextval, :programme, :project_name, :proposal_type,
					:solution_component, :proj_prog_mgr, :wp_po_status, 
					'".$postData['Status']."', '".$postData['RequestDate']."', '".$postData['SubmissionDate']."', 
					'".$postData['Value']."', '".$DateDiff."')";					
		
			
			$queryParse = oci_parse($conn, $query);
			
			$Programme = trim($postData['Programme']);
			$ProjectName = trim($postData['ProjectName']);
			$ProposalType = trim($postData['ProposalType']);
			$SolutionComponent = trim($postData['SolutionComponent']);
			$ProjProgMgr = trim($postData['ProjProgMgr']);
			$WpPoStatus = trim($postData['WpPoStatus']);
				
			oci_bind_by_name($queryParse, ':programme', $Programme);
			oci_bind_by_name($queryParse, ':project_name', $ProjectName);
			oci_bind_by_name($queryParse, ":proposal_type", $ProposalType);
			oci_bind_by_name($queryParse, ":solution_component", $SolutionComponent);
			oci_bind_by_name($queryParse, ":proj_prog_mgr", $ProjProgMgr);
			oci_bind_by_name($queryParse, ":wp_po_status", $WpPoStatus);
			
			$row = oci_execute($queryParse);
		} 
		
		/* Update Data */
		
		else {
			$query = "UPDATE KPI_INTAKE_PROCESS SET					
					PROGRAMME = :programme,					
					PROPOSAL_TYPE = :proposal_type,
					SOLUTION_COMPONENT = :solution_component,
					PROJ_PROG_MGR = :proj_prog_mgr,
					WP_PO_STATUS = :wp_po_status,
					STATUS = '".$postData['Status']."',
					REQUEST_DATE = '".$postData['RequestDate']."',
					SUBMISSION_DATE = '".$postData['SubmissionDate']."',
					VALUE = '".trim($postData['Value'])."',
					DIFF_DATES = '".$DateDiff."'
					WHERE INTAKEID = ".$IntakeID;		
			
			
			$queryParse = oci_parse($conn, $query);
			
			$Programme = trim($postData['Programme']);
			$ProposalType = trim($postData['ProposalType']);
			$SolutionComponent = trim($postData['SolutionComponent']);
			$ProjProgMgr = trim($postData['ProjProgMgr']);
			$WpPoStatus = trim($postData['WpPoStatus']);
			
			oci_bind_by_name($queryParse, ':programme', $Programme);
			oci_bind_by_name($queryParse, ":proposal_type", $ProposalType);
			oci_bind_by_name($queryParse, ":solution_component", $SolutionComponent);
			oci_bind_by_name($queryParse, ":proj_prog_mgr", $ProjProgMgr);
			oci_bind_by_name($queryParse, ":wp_po_status", $WpPoStatus);
			
			$row = oci_execute($queryParse);
			
		}
			
		if($row == 1)
			$response = 'Details Saved !!!';
		else
			$response = 'Some problem occurred, pls try again !!!';
				
		oci_free_statement($queryParse);
		$this->oracle->closeConnection();

		return $response;		
	}
	
	
	
	public function getAutoCompleteData($keyword){
		
		$this->oracle->openConnection('KPIDASHBOARD');
		$conn = $this->oracle->getConnection();
		
		$query = "SELECT PROJECTNAME FROM KPI_PROJECTS WHERE ACTIVE = 1";
		$queryParse = oci_parse($conn, $query);
		oci_execute($queryParse);
		//$i = 0;
		$Projects = array();
		
		while ($row = oci_fetch_array($queryParse, OCI_ASSOC+OCI_RETURN_NULLS)) {
			$Projects[] = $row['PROJECTNAME'];
		}		
		
		
		$q = strtolower($keyword);
		if (get_magic_quotes_gpc()) $q = stripslashes($q);	
		
		
		$result = array();
		foreach ($Projects as $key=>$value) {
			if (strpos(strtolower($value), $q) !== false) {
				array_push($result, array("id"=>$value, "label"=>$value, "value" => strip_tags($value)));
			}
			if (count($result) > 11)
				break;
		}		
		
		$result = json_encode($result);
		return $result;
	}
	
	public function getDocumentDetails($ID = ""){
		
		$this->oracle->openConnection('KPIDASHBOARD');
		$conn = $this->oracle->getConnection();
		$DocumentDetails = array();
		
		
		$query = "SELECT PROJECTID, PROJECTNAME, DELIVERABLE, DOCUMENT_NAME, DOCUMENT_TYPE, IS_TESTING, 
				 DELIVERY_DATE, SIGN_OFF_DATE, REPOSITORY_LINK 
				 FROM KPI_PROJECTS WHERE ACTIVE = 1 AND DELIVERABLE IS NOT NULL";
		
		if(!empty($ID))
			$query .= " AND PROJECTID = ".$ID;
		
		//echo $query;
		
		$queryParse = oci_parse($conn, $query);
		oci_execute($queryParse);
		
		
		while ($row = oci_fetch_array($queryParse, OCI_ASSOC+OCI_RETURN_NULLS)) {
			$Document = new Documents(); 
			
			$Document->setProjectID($row['PROJECTID']);
			$Document->setDeliverable($row['DELIVERABLE']);
			$Document->setProjectName($row['PROJECTNAME']);
			$Document->setDocumentName($row['DOCUMENT_NAME']);
			$Document->setDocumentType($row['DOCUMENT_TYPE']);
			$Document->setTesting($row['IS_TESTING']);
			$Document->setDeliveryDate($row['DELIVERY_DATE']);
			$Document->setSignOffDate($row['SIGN_OFF_DATE']);
			$Document->setRepositoryLink($row['REPOSITORY_LINK']);
			
			$DocumentDetails[] = $Document;
		
		}
		
		//echo "<pre>";print_r($ProjectDetails);exit;
		oci_free_statement($queryParse);
		$this->oracle->closeConnection();
		return $DocumentDetails;
		
	}
	
	public function addEditDocumentationDetails($postData, $ProjectID = ""){
		$this->oracle->openConnection('KPIDASHBOARD');
		$conn = $this->oracle->getConnection();
		
		//echo $IntakeID;exit;		
		
		//echo "<pre>";print_r($postData);exit;		
	
	
		/* Update(Add) New Data */
		
		if(empty($ProjectID)){
		
			$query = "UPDATE KPI_PROJECTS SET
					DELIVERABLE = '".$postData['Deliverable']."',				
					DOCUMENT_NAME = '".$postData['DocumentName']."',
					IS_TESTING = '".$ForTesting."',
					DOCUMENT_TYPE = '".$postData['DocumentType']."',
					DELIVERY_DATE = '".$postData['DeliveryDate']."',
					SIGN_OFF_DATE = '".$postData['SignoffDate']."',
					REPOSITORY_LINK = '".$postData['RepositoryLink']."'
					WHERE PROJECTID = ".$postData['Project'];
		
			//echo $query;exit;
		
			
		} 
		
		/* Update Existing Data */
		
		else {
			$query = "UPDATE KPI_PROJECTS SET
					DELIVERABLE = '".$postData['Deliverable']."',
					DOCUMENT_NAME = '".$postData['DocumentName']."',
					IS_TESTING = '".$ForTesting."',
					DOCUMENT_TYPE = '".$postData['DocumentType']."',
					DELIVERY_DATE = '".$postData['DeliveryDate']."',
					SIGN_OFF_DATE = '".$postData['SignoffDate']."',
					REPOSITORY_LINK = '".$postData['RepositoryLink']."'
					WHERE PROJECTID = ".$ProjectID;			
			
			//echo $query;exit;
			
		}
		
		
		$queryParse = oci_parse($conn, $query);
		$row = oci_execute($queryParse);
	
		if($row == 1)
			$response = 'Details Saved !!!';
		else
			$response = 'Some problem occurred, pls try again !!!';

		oci_free_statement($queryParse);
		$this->oracle->closeConnection();

		return $response;		
	}
	
	public  function deleteIntakeProcess($ID){
		$this->oracle->openConnection('KPIDASHBOARD');
		$conn = $this->oracle->getConnection();
		$response = array();		
		
		if(!empty($ID)){
			$query = "UPDATE KPI_INTAKE_PROCESS SET ACTIVE = 0 WHERE INTAKEID = ".$ID;
			$queryParse = oci_parse($conn, $query);
			$row = oci_execute($queryParse);
			
			if($row == 1)
				$response['status'] = 'Record Deleted !!!';
			else
				$response['status'] = 'Some problem occurred, pls try again !!!';
		
			oci_free_statement($queryParse);
			$this->oracle->closeConnection();
	
			return $response;
		} 
		else {
			$response['status'] = 'Some problem, Please select the process again and click on delete !!!';	
		}
		
		return $response;
	}	
	
	public function getEstimationDetails($ID = ""){
	
		$this->oracle->openConnection('KPIDASHBOARD');
		$conn = $this->oracle->getConnection();
		$ProjectDetails = array();
	
	
		$query = "SELECT *
				 FROM KPI_QUALITY_ESITMATION 
				 WHERE ACTIVE = 1";
	
		if(!empty($ID))
			$query .= " AND ID = ".$ID;
		
		$query .= " ORDER BY ENGAGEMENT_DATE";
	
		//echo $query;
	
		$queryParse = oci_parse($conn, $query);
		oci_execute($queryParse);
		$i = 0;

		while ($row = oci_fetch_array($queryParse, OCI_ASSOC+OCI_RETURN_NULLS)) {
			$ProjectDetail = array();
				
			$ProjectDetail['ID'] = $row['ID'];
			$ProjectDetail['PROJECT_NAME'] = $row['PROJECT_NAME'];
			$ProjectDetail['ENGAGEMENT_DATE'] = $row['ENGAGEMENT_DATE'];			
			$ProjectDetail['GATE1_ESTIMATION_DELIVERY_DATE'] = $row['GATE1_ESTIMATION_DELIVERY_DATE'];
			$ProjectDetail['DIFF_DATES'] = $row['DIFF_DATES'];
			$ProjectDetail['GATE1_ESTIMATION'] = $row['GATE1_ESTIMATION'];
			$ProjectDetail['GATE2_ESTIMATION'] = $row['GATE2_ESTIMATION'];
			$ProjectDetail['FINAL_ESTIMATION'] = $row['FINAL_ESTIMATION'];
			$ProjectDetail['GATE1_VARIANCE'] = $row['GATE1_VARIANCE'];
			$ProjectDetail['GATE2_VARIANCE'] = $row['GATE2_VARIANCE'];			
			
			$ProjectDetails[$i] = $ProjectDetail;
			$i++;
		}

		//echo "<pre>";print_r($ProjectDetails);exit;
		oci_free_statement($queryParse);
		$this->oracle->closeConnection();
		return $ProjectDetails;
	
	}
	
	public function addEditEstimationnDetails($postData, $ID = "", $userID){
		$this->oracle->openConnection('KPIDASHBOARD');
		$conn = $this->oracle->getConnection();
				
		//echo "<pre>";print_r($postData);
		$dStart = new \DateTime($postData['EngagementDate']);
		$dEnd  = new \DateTime($postData['Gate1EstimationDeliverydate']);
		$dDiff = $dStart->diff($dEnd);
		$DateDiff = $dDiff->days;
		
		if(!empty(trim($postData['Gate1Estimation'])) && !empty(trim($postData['FinalEstimation']))){
			$gate1_variance = ((trim($postData['FinalEstimation']) - trim($postData['Gate1Estimation'])) / trim($postData['Gate1Estimation']))*100;
			$gate1_variance = number_format($gate1_variance, 2);
		}
		else
			$gate1_variance = '';
		
		if(!empty(trim($postData['Gate2Estimation'])) && !empty(trim($postData['FinalEstimation']))){
			$gate2_variance = ((trim($postData['FinalEstimation']) - trim($postData['Gate2Estimation'])) / trim($postData['Gate2Estimation']))*100;
			$gate2_variance = number_format($gate2_variance, 2);
		}
		else
			$gate2_variance = '';
		
		
		/* Update(Add) New Data */
		
		if(empty($ID)){
		
			
			$query = "INSERT INTO KPI_QUALITY_ESITMATION 
					(ID, PROJECT_NAME, ENGAGEMENT_DATE, GATE1_ESTIMATION_DELIVERY_DATE, DIFF_DATES, 
					GATE1_ESTIMATION, GATE2_ESTIMATION, FINAL_ESTIMATION, GATE1_VARIANCE, GATE2_VARIANCE, ADDED_BY,
					ADDED_ON)
					VALUES(QUALITYESTIMATION_ID.nextval, :project_name, :engagement_date, :gate1_estimation_delivery_date,
					'".$DateDiff."', :gate1_Estimation, :gate2_Estimation, :final_estimation, 
					'".$gate1_variance."', '".$gate2_variance."', '".$userID."',
					'".strtoupper(date('d/M/y'))."')";	
			
			//echo $query;exit;
			
			$queryParse = oci_parse($conn, $query);			
			
			$ProjectName = trim($postData['ProjectName']);
			$EngagementDate = trim($postData['EngagementDate']);
			$Gate1EstimationDeliverydate = trim($postData['Gate1EstimationDeliverydate']);
			$Gate1Estimation = trim($postData['Gate1Estimation']);
			$Gate2Estimation = trim($postData['Gate2Estimation']);
			$FinalEstimation = trim($postData['FinalEstimation']);				
			
			oci_bind_by_name($queryParse, ':project_name', $ProjectName);
			oci_bind_by_name($queryParse, ':engagement_date', $EngagementDate);
			oci_bind_by_name($queryParse, ":gate1_estimation_delivery_date", $Gate1EstimationDeliverydate);
			oci_bind_by_name($queryParse, ":gate1_Estimation", $Gate1Estimation);
			oci_bind_by_name($queryParse, ":gate2_Estimation", $Gate2Estimation);
			oci_bind_by_name($queryParse, ":final_estimation", $FinalEstimation);		
			
			$row = oci_execute($queryParse);
				
		}
		
		/* Update Existing Data */
		
		else {
			$query = "UPDATE KPI_QUALITY_ESITMATION SET
					ENGAGEMENT_DATE = :engagement_date,
					GATE1_ESTIMATION_DELIVERY_DATE = :gate1_estimation_delivery_date,
					DIFF_DATES = '".$DateDiff."',
					GATE1_ESTIMATION = :gate1_Estimation,
					GATE2_ESTIMATION = :gate2_Estimation,
					FINAL_ESTIMATION = :final_estimation,
					GATE1_VARIANCE = '".$gate1_variance."',
					GATE2_VARIANCE = '".$gate2_variance."',
					EDITED_BY = '".$userID."',
					EDITED_ON = '".strtoupper(date('d/M/y'))."'							
					WHERE ID = ".$ID;
				
			//echo $query;exit;
			$queryParse = oci_parse($conn, $query);			
			
			$EngagementDate = trim($postData['EngagementDate']);
			$Gate1EstimationDeliverydate = trim($postData['Gate1EstimationDeliverydate']);
			$Gate1Estimation = trim($postData['Gate1Estimation']);
			$Gate2Estimation = trim($postData['Gate2Estimation']);
			$FinalEstimation = trim($postData['FinalEstimation']);				
			
			oci_bind_by_name($queryParse, ':engagement_date', $EngagementDate);
			oci_bind_by_name($queryParse, ':gate1_estimation_delivery_date', $Gate1EstimationDeliverydate);
			oci_bind_by_name($queryParse, ':gate1_Estimation', $Gate1Estimation);
			oci_bind_by_name($queryParse, ':gate2_Estimation', $Gate2Estimation);
			oci_bind_by_name($queryParse, ':final_estimation', $FinalEstimation);
			$row = oci_execute($queryParse);
				
		}	
		
		//echo $row;exit;
		
		if($row == 1)
			$response = 'Details Saved !!!';
		else
			$response = 'Some problem occurred, pls try again !!!';
	
		oci_free_statement($queryParse);
		$this->oracle->closeConnection();

		return $response;
			
	}
	
	/**
	 * Returns an array containing all of the delivery slippages
	 *
	 * @param string $Month
	 * @return array
	 */
	public function getDeliverySlippages($Month = "") {
		$this->oracle->openConnection ( 'KPIDASHBOARD' );
		$conn = $this->oracle->getConnection ();
		$deliverySlippages = array ();
	
		$currentMonth = date ( "M-y" );
		$currentMonth = strtoupper ( str_replace ( "-", "/", $currentMonth ) );
	
		if (! empty ( $postData ['Month'] ))
			$currentMonth = $postData ['Month'];
		else if (! empty ( $Month ))
			$currentMonth = $Month;
	
		$currentMonth = str_replace ( "-", "/", strtoupper ( trim ( $currentMonth ) ) );

		$query = "SELECT PROJECTID,PROJECTNAME,ESTIMATED_PROD_LIVE_DATE,ACTUAL_PROD_LIVE_DATE FROM KPI_PROJECTS WHERE ACTIVE = 1 AND TO_CHAR(ESTIMATED_PROD_LIVE_DATE, 'MON/YY') LIKE '%" . $currentMonth . "%'";

		// echo $query;//exit;
		$queryParse = oci_parse ( $conn, $query );
		oci_execute ( $queryParse );

		while ( $row = oci_fetch_array ( $queryParse, OCI_ASSOC + OCI_RETURN_NULLS ) ) {
				
			$deliverySlippage = new DeliverySlippage ();
			$deliverySlippage->setProjectId ( $row ['PROJECTID'] );
			$deliverySlippage->setProjectName ( $row ['PROJECTNAME'] );
			$deliverySlippage->setEstimatedProdLiveDate ( $row ['ESTIMATED_PROD_LIVE_DATE'] );
			$deliverySlippage->setDeliveryDate ( $row ['ACTUAL_PROD_LIVE_DATE'] );
			$date1 = new \DateTime ( $row ['ESTIMATED_PROD_LIVE_DATE'] );
			$date2 = new \DateTime ( $row ['ACTUAL_PROD_LIVE_DATE'] );
				
			$differenceInDate = $date2->diff ( $date1 );
				
			$deliverySlippage->setDifferenceInDate ( $differenceInDate->days );
			$deliverySlippages [] = $deliverySlippage;
		}
		
		return $deliverySlippages;
	}

	public function getSTAutomation($Month = "") {
		$this->oracle->openConnection ( 'KPIDASHBOARD' );
		$conn = $this->oracle->getConnection ();
		$STAutomation = array ();
	
		$currentMonth = date ( "M-y" );
		$currentMonth = strtoupper ( str_replace ( "-", "/", $currentMonth ) );
	
		if (! empty ( $postData ['Month'] ))
			$currentMonth = $postData ['Month'];
			else if (! empty ( $Month ))
				$currentMonth = $Month;
	
				$currentMonth = str_replace ( "-", "/", strtoupper ( trim ( $currentMonth ) ) );
	
				$query = "SELECT PROJECTID,PROJECTNAME,NO_OF_ST_AUTOMATED_TEST_CASES,TOTAL_NO_OF_ST_TEST_CASES,ST_AUTOMATION FROM KPI_PROJECTS WHERE ACTIVE = 1 AND TO_CHAR(ESTIMATED_PROD_LIVE_DATE, 'MON/YY') LIKE '%" . $currentMonth . "%'";
	
				
				$queryParse = oci_parse ( $conn, $query );
				oci_execute ( $queryParse );
	
				while ( $row = oci_fetch_array ( $queryParse, OCI_ASSOC + OCI_RETURN_NULLS ) ) {
	
					$stAutomationEntity = new STAutomationEntity();
					$stAutomationEntity->setProjectId ( $row ['PROJECTID'] );
					$stAutomationEntity->setProjectName ( $row ['PROJECTNAME'] );
					$stAutomationEntity->setSTAutomatedTestCases ( $row ['NO_OF_ST_AUTOMATED_TEST_CASES'] );
					$stAutomationEntity->setSTTotalTestCases ( $row ['TOTAL_NO_OF_ST_TEST_CASES'] );
					$stAutomation=round($row ['ST_AUTOMATION'],2);
					$stAutomationEntity->setSTAutomation( $stAutomation );
	
						
					$STAutomation [] = $stAutomationEntity;
				}
				
				return $STAutomation;
	}
	
	public function updateDate($newDate, $projectID, $action) {
		$this->oracle->openConnection ( 'KPIDASHBOARD' );
		$conn = $this->oracle->getConnection ();
	
		if (! empty ( $projectID )) {
			$formattedDate = str_replace ( "-", "/", strtoupper ( trim ( $newDate ) ) );
			$query = "SELECT COUNT(*) AS COUNT FROM KPI_PROJECTS WHERE PROJECTID = " . $projectID;
			$queryParse = oci_parse ( $conn, $query );
			oci_execute ( $queryParse );
			$row = oci_fetch_array ( $queryParse );
			if ($row ['COUNT'] > 0) {
				if ($action == 'estimatedProdLiveDate') {
					$query = "UPDATE KPI_PROJECTS SET ESTIMATED_PROD_LIVE_DATE ='" . $formattedDate . "' WHERE PROJECTID = " . $projectID;
				} else if ($action == 'deliveryDate') {
					$query = "UPDATE KPI_PROJECTS SET ACTUAL_PROD_LIVE_DATE ='" . $formattedDate . "' WHERE PROJECTID = " . $projectID;
				}
			}
				
			$queryParse = oci_parse ( $conn, $query );
			$row = oci_execute ( $queryParse );
			if ($row == 1) {
	
				$query = "SELECT ESTIMATED_PROD_LIVE_DATE,ACTUAL_PROD_LIVE_DATE FROM KPI_PROJECTS WHERE PROJECTID = " . $projectID;
				$queryParse = oci_parse ( $conn, $query );
				oci_execute ( $queryParse );
	
				while ( $row = oci_fetch_array ( $queryParse, OCI_ASSOC + OCI_RETURN_NULLS ) ) {
						
					$deliverySlippage = new DeliverySlippage ();
					$deliverySlippage->setEstimatedProdLiveDate ( $row ['ESTIMATED_PROD_LIVE_DATE'] );
					$deliverySlippage->setDeliveryDate ( $row ['ACTUAL_PROD_LIVE_DATE'] );
					$date1 = new \DateTime ( $row ['ESTIMATED_PROD_LIVE_DATE'] );
					$date2 = new \DateTime ( $row ['ACTUAL_PROD_LIVE_DATE'] );
						
					$differenceInDate = $date2->diff ( $date1 )->format ( "%a" );
					$response = 'Details updated-' . $differenceInDate;
				}
			} else {
				$response = 'Some problem occurred, pls try again !!!';
			}
				
			oci_free_statement ( $queryParse );
			$this->oracle->closeConnection ();
				
			return $response;
		}
	}
	
	public function updateSTAutomation($newValue, $projectID, $action) {
		$this->oracle->openConnection ( 'KPIDASHBOARD' );
		$conn = $this->oracle->getConnection ();
	
		if (! empty ( $projectID )) {
			$formattedValue =  trim ( $newValue )  ;
			$query = "SELECT NO_OF_ST_AUTOMATED_TEST_CASES, TOTAL_NO_OF_ST_TEST_CASES
					FROM KPI_PROJECTS
					WHERE PROJECTID = " . $projectID;
			$queryParse = oci_parse ( $conn, $query );
			oci_execute ( $queryParse );
			$row = oci_fetch_array ( $queryParse );
			//echo "<pre>";print_r($row);exit;
			if ($action == 'stAutomatedTestCases') {
				$stAutomation=$formattedValue/$row ['TOTAL_NO_OF_ST_TEST_CASES'];
				$query = "UPDATE KPI_PROJECTS SET NO_OF_ST_AUTOMATED_TEST_CASES ='" . $formattedValue . "', ST_AUTOMATION='" . $stAutomation . "'  WHERE PROJECTID = " . $projectID;
			} else if ($action == 'stTotalTestCases') {
				$stAutomation=$row ['NO_OF_ST_AUTOMATED_TEST_CASES']/$formattedValue;
				$query = "UPDATE KPI_PROJECTS SET TOTAL_NO_OF_ST_TEST_CASES ='" . $formattedValue . "', ST_AUTOMATION='" . $stAutomation . "' WHERE PROJECTID = " . $projectID;
			}
				
	
			$queryParse = oci_parse ( $conn, $query );
			$row = oci_execute ( $queryParse );
			if ($row == 1) {
	
				$query = "SELECT NO_OF_ST_AUTOMATED_TEST_CASES,TOTAL_NO_OF_ST_TEST_CASES,ST_AUTOMATION FROM KPI_PROJECTS WHERE PROJECTID = " . $projectID;
				$queryParse = oci_parse ( $conn, $query );
				oci_execute ( $queryParse );
	
				while ( $row = oci_fetch_array ( $queryParse, OCI_ASSOC + OCI_RETURN_NULLS ) ) {
	
					$deliverySlippage = new DeliverySlippage ();
					$deliverySlippage->setEstimatedProdLiveDate ( $row ['NO_OF_ST_AUTOMATED_TEST_CASES'] );
					$deliverySlippage->setDeliveryDate ( $row ['TOTAL_NO_OF_ST_TEST_CASES'] );
					$stAutomation=round($row ['ST_AUTOMATION'],2);
					//update st automation in db
					$response = 'Details updated-' . $stAutomation."%";
				}
			} else {
				$response = 'Some problem occurred, pls try again !!!';
			}
	
			oci_free_statement ( $queryParse );
			$this->oracle->closeConnection ();
	
			return $response;
		}
		
		spl_autoload_register();
	}
	
	
	
}