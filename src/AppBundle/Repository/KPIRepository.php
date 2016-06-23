<?php

namespace AppBundle\Repository;

use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\Service\OracleDatabaseService;
use AppBundle\Entity\DeliverySlippage;
use AppBundle\Entity\STAutomationEntity;
use AppBundle\Entity\KPI;
use AppBundle\Entity\Projects;
use AppBundle\Entity\Documents;
use AppBundle\Entity\ProdTestAccountsEntity;


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
	public function getDefectDetails() {		
	
		$this->oracle->openConnection('KPIDASHBOARD');
		$conn = $this->oracle->getConnection();
		
		/* List last one year Project defects */
		
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
				".$column." = :value
				WHERE PROJECTID = ".$ProjectID;
		$queryParse = oci_parse($conn, $query);
		
		$value = trim($value);
		oci_bind_by_name($queryParse, ':value', $value);
		$row = oci_execute($queryParse);
		
		if($row == 1)
			$response = 'Details updated !!!';
		else
			$response = 'Some problem occurred, pls try again !!!';
					
		oci_free_statement($queryParse);
		$this->oracle->closeConnection();
		
		return $response;		
		
	}
	
	/*
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
	*/
	
	public function getmonthArray(){
		$currentYear = date("y");
	
		$months[0] = 'JAN-'.$currentYear;
		$months[1] = 'FEB-'.$currentYear;
		$months[2] = 'MAR-'.$currentYear;
		$months[3] = 'APR-'.$currentYear;
		$months[4] = 'MAY-'.$currentYear;
		$months[5] = 'JUN-'.$currentYear;
		$months[6] = 'JUL-'.$currentYear;
		$months[7] = 'AUG-'.$currentYear;
		$months[8] = 'SEP-'.$currentYear;
		$months[9] = 'OCT-'.$currentYear;
		$months[10] = 'NOV-'.$currentYear;
		$months[11] = 'DEC-'.$currentYear;
	
		return $months;
	}
	
	public function getProcessDetails($Month, $postData, $IntakeID = ""){
		$this->oracle->openConnection('KPIDASHBOARD');
		$conn = $this->oracle->getConnection();
		$ProcessDetails = array();
		$formattedcurrentMonth = '';
		$i = 0;	
	
		if(!empty($Month))
			$formattedcurrentMonth = str_replace ( "-", "/", $Month );
		else if (! empty ( $postData ['Month'] )){
			$currentMonth = $postData ['Month'];
			$formattedcurrentMonth = strtoupper ( str_replace ( "-", "/", $currentMonth ) );
		}
		
		$query = "SELECT * FROM KPI_INTAKE_PROCESS WHERE ACTIVE = 1 AND MONTH = '" . $formattedcurrentMonth."'";
		
		if(!empty($IntakeID))
			$query .= " AND INTAKEID = ".$IntakeID;		

		
		$queryParse = oci_parse($conn, $query);
		oci_execute($queryParse);
		
		while ($row = oci_fetch_array($queryParse, OCI_ASSOC+OCI_RETURN_NULLS)) {
			$ProcessDetail = array();
			
			$ProcessDetail['INTAKEID'] = $row['INTAKEID'];
			$formattedMonth = $row['MONTH'];
			$ProcessDetail['MONTH'] = $formattedMonth;
			$ProcessDetail['PROGRAMME'] = $row['PROGRAMME'];
			$ProcessDetail['PROJECT_NAME'] = $row['PROJECT_NAME'];
			$ProcessDetail['PROPOSAL_TYPE'] = $row['PROPOSAL_TYPE'];
			$ProcessDetail['SOLUTION_COMPONENT'] = $row['SOLUTION_COMPONENT'];
			$ProcessDetail['PROJ_PROG_MGR'] = $row['PROJ_PROG_MGR'];
			$ProcessDetail['WP_PO_STATUS'] = $row['WP_PO_STATUS'];
			$ProcessDetail['STATUS'] = $row['STATUS'];
			$ProcessDetail['REQUEST_DATE'] = $row['REQUEST_DATE'];
			$ProcessDetail['SUBMISSION_DATE'] = $row['SUBMISSION_DATE'];			
			$ProcessDetail['DIFF_DATES'] = $row['DIFF_DATES'];
			
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
		
		$session = new Session();
		$userID = $session->get('UserID');
		
		$dStart = new \DateTime($postData['RequestDate']);
		$dEnd  = new \DateTime($postData['SubmissionDate']);
		$dDiff = $dStart->diff($dEnd);		

		
		if($dEnd > $dStart || $dEnd == $dStart){
			$DateDiff =  $dDiff->days;		
		}
		else {
			$DateDiff =  "-".$dDiff->days;
		}
			
		
		/* Add Data */
		
		if(empty($IntakeID)){
		
			/*
			$query = "INSERT INTO KPI_INTAKE_PROCESS 
					(INTAKEID, PROGRAMME, PROJECT_NAME, PROPOSAL_TYPE, SOLUTION_COMPONENT, PROJ_PROG_MGR, WP_PO_STATUS, 
					STATUS, REQUEST_DATE, SUBMISSION_DATE, DIFF_DATES)
					VALUES(INTAKEPROCESS_ID.nextval, :programme, :project_name, :proposal_type,
					:solution_component, :proj_prog_mgr, :wp_po_status, 
					'".$postData['Status']."', '".$postData['RequestDate']."', '".$postData['SubmissionDate']."', '".$DateDiff."')";					
			*/
			
			$query = "INSERT INTO KPI_INTAKE_PROCESS
					(INTAKEID, PROGRAMME, PROJECT_NAME, PROPOSAL_TYPE, SOLUTION_COMPONENT, PROJ_PROG_MGR, WP_PO_STATUS,
					STATUS, REQUEST_DATE, SUBMISSION_DATE, DIFF_DATES, MONTH, ADDED_BY, ADDED_ON)
					VALUES(INTAKEPROCESS_ID.nextval, :programme, :project_name, :proposal_type,
					:solution_component, :proj_prog_mgr, :wp_po_status, :status, :request_date, :submission_date, 
					'".$DateDiff."', :month, '".$userID."', '".strtoupper(date('d/M/y'))."')";
			
			
			$queryParse = oci_parse($conn, $query);
			
			$Month = trim(str_replace("-", "/", strtoupper(trim($postData['month']))));
			$Programme = trim($postData['Programme']);
			$ProjectName = trim($postData['ProjectName']);
			$ProposalType = trim($postData['ProposalType']);
			$SolutionComponent = trim($postData['SolutionComponent']);
			$ProjProgMgr = trim($postData['ProjProgMgr']);
			$WpPoStatus = trim($postData['WpPoStatus']);			
			$Status = trim($postData['Status']);
			$RequestDate = trim($postData['RequestDate']);
			$SubmissionDate = trim($postData['SubmissionDate']);
			
			oci_bind_by_name($queryParse, ':month', $Month);
			oci_bind_by_name($queryParse, ':programme', $Programme);
			oci_bind_by_name($queryParse, ':project_name', $ProjectName);
			oci_bind_by_name($queryParse, ":proposal_type", $ProposalType);
			oci_bind_by_name($queryParse, ":solution_component", $SolutionComponent);
			oci_bind_by_name($queryParse, ":proj_prog_mgr", $ProjProgMgr);
			oci_bind_by_name($queryParse, ":wp_po_status", $WpPoStatus);			
			oci_bind_by_name($queryParse, ":status", $Status);
			oci_bind_by_name($queryParse, ":request_date", $RequestDate);
			oci_bind_by_name($queryParse, ":submission_date", $SubmissionDate);			
			
		} 
		
		/* Update Data */
		
		else {
			$query = "UPDATE KPI_INTAKE_PROCESS SET	
					MONTH = :month,
					PROGRAMME = :programme,		
					PROJECT_NAME  =:project_name,
					PROPOSAL_TYPE = :proposal_type,
					SOLUTION_COMPONENT = :solution_component,
					PROJ_PROG_MGR = :proj_prog_mgr,
					WP_PO_STATUS = :wp_po_status,
					STATUS = :status,
					REQUEST_DATE = :request_date,
					SUBMISSION_DATE = :submission_date,
					DIFF_DATES = '".$DateDiff."',
					EDITED_BY = '".$userID."',
					EDITED_ON = '".strtoupper(date('d/M/y'))."'
					WHERE INTAKEID = ".$IntakeID;		
			
			
			$queryParse = oci_parse($conn, $query);
			
			$Month = trim(str_replace("-", "/", strtoupper(trim($postData['month']))));
			$Programme = trim($postData['Programme']);
			$ProjectName = trim($postData['ProjectName']);
			$ProposalType = trim($postData['ProposalType']);
			$SolutionComponent = trim($postData['SolutionComponent']);
			$ProjProgMgr = trim($postData['ProjProgMgr']);
			$WpPoStatus = trim($postData['WpPoStatus']);
			$Status = trim($postData['Status']);
			$RequestDate = trim($postData['RequestDate']);
			$SubmissionDate = trim($postData['SubmissionDate']);
			
			oci_bind_by_name($queryParse, ':month', $Month);
			oci_bind_by_name($queryParse, ':programme', $Programme);
			oci_bind_by_name($queryParse, ':project_name', $ProjectName);
			oci_bind_by_name($queryParse, ":proposal_type", $ProposalType);
			oci_bind_by_name($queryParse, ":solution_component", $SolutionComponent);
			oci_bind_by_name($queryParse, ":proj_prog_mgr", $ProjProgMgr);
			oci_bind_by_name($queryParse, ":wp_po_status", $WpPoStatus);
			oci_bind_by_name($queryParse, ":status", $Status);
			oci_bind_by_name($queryParse, ":request_date", $RequestDate);
			oci_bind_by_name($queryParse, ":submission_date", $SubmissionDate);		
			
		}
		
		$row = oci_execute($queryParse);
			
		if($row == 1)
			$response = 'Details Saved !!!';
		else
			$response = 'Some problem occurred, pls try again !!!';
				
		oci_free_statement($queryParse);
		$this->oracle->closeConnection();

		return $response;		
	}
	
	
	
	public function getAutoCompleteData($keyword, $doc = ""){
		
		$this->oracle->openConnection('KPIDASHBOARD');
		$conn = $this->oracle->getConnection();
		
		$query = "SELECT PROJECTNAME FROM KPI_PROJECTS WHERE ACTIVE = 1";
		$queryParse = oci_parse($conn, $query);
		oci_execute($queryParse);
		
		$Projects = array();
		
		while ($row = oci_fetch_array($queryParse, OCI_ASSOC+OCI_RETURN_NULLS)) {
			$Projects[] = $row['PROJECTNAME'];
		}
		
		/* For Documents merge Document related Projects */
		
		if($doc == 1){
		
			$query1 = "SELECT PROJECTNAME FROM KPI_DOCUMENT_PROJECTS WHERE ACTIVE = 1";
			$query1Parse = oci_parse($conn, $query1);
			oci_execute($query1Parse);
			
			$DocProjects = array();
			
			while ($result = oci_fetch_array($query1Parse, OCI_ASSOC+OCI_RETURN_NULLS)) {
				$DocProjects[] = $result['PROJECTNAME'];
			}
			
			$Projects = array_merge($Projects, $DocProjects);
			
		}
		
		/* === */	
		
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
	
	public function getDocumentDetails($Month, $postData = "", $ID = ""){
		
		$this->oracle->openConnection('KPIDASHBOARD');
		$conn = $this->oracle->getConnection();
		$DocumentDetails = array();
		
		
		/*$query = "SELECT PROJECTID, PROJECTNAME, DELIVERABLE, DOCUMENT_NAME, DOCUMENT_TYPE, IS_TESTING, 
				 DELIVERY_DATE, SIGN_OFF_DATE, REPOSITORY_LINK 
				 FROM KPI_PROJECTS WHERE ACTIVE = 1 AND DELIVERABLE IS NOT NULL";
		
		if(!empty($ID))
			$query .= " AND PROJECTID = ".$ID;
		*/
		
		if(!empty($Month))
			$Month = str_replace ( "-", "/", $Month );
		else if (! empty ( $postData ['Month'] )){
			$currentMonth = $postData ['Month'];
			$Month = strtoupper ( str_replace ( "-", "/", $currentMonth ) );
		}		
		$query = "SELECT DP.PROJECTID, DP.PROJECTNAME, D.* FROM
				KPI_DOCUMENT_PROJECTS DP, KPI_DOCUMENTS D 
				WHERE DP.PROJECTID = D.PROJECTID AND D.ACTIVE = 1 AND DP.ACTIVE = 1
				AND MONTH = '".$Month."'";
		
		if(!empty($ID))
			$query .= " AND DOCUMENT_ID = ".$ID;
		
		//echo $query;exit;		
		$queryParse = oci_parse($conn, $query);
		oci_execute($queryParse);
		
		
		while ($row = oci_fetch_array($queryParse, OCI_ASSOC+OCI_RETURN_NULLS)) {
			$Document = new Documents();			
			
			$Document->setDocumentID($row['DOCUMENT_ID']);
			$Document->setDeliverable($row['DELIVERABLE']);
			$Document->setProjectName($row['PROJECTNAME']);
			$Document->setDocumentName($row['DOCUMENT_NAME']);
			$Document->setDocumentType($row['DOCUMENT_TYPE']);
			$Document->setTesting($row['IS_TESTING']);
			$Document->setDeliveryDate($row['DELIVERY_DATE']);
			$Document->setSignOffDate($row['SIGN_OFF_DATE']);
			$Document->setRepositoryLink($row['REPOSITORY_LINK']);
			$Document->setDocumentMonth($row['MONTH']);
			
			$DocumentDetails[] = $Document;
		
		}
		
		//echo "<pre>";print_r($DocumentDetails);exit;
		oci_free_statement($queryParse);
		$this->oracle->closeConnection();
		return $DocumentDetails;
		
	}
	
	public function addEditDocumentationDetails($postData, $DocumentID = ""){
		$this->oracle->openConnection('KPIDASHBOARD');
		$conn = $this->oracle->getConnection();
		
		$session = new Session();
		$userID = $session->get('UserID');
		
		if(!empty($postData['ForTesting']) && $postData['ForTesting'] == 1)
			$ForTesting = 1;
		else 
			$ForTesting = '';
	
		$Month = strtoupper(str_replace("-", "/", $postData['month']));
		
		/* Add New Document Data */
		
		if(empty($DocumentID)){
			
			$query = "SELECT PROJECTID FROM KPI_DOCUMENT_PROJECTS WHERE PROJECTNAME = :project_name";
			$queryParse = oci_parse($conn, $query);		
		
			$ProjectName = trim($postData['ProjectName']);
			oci_bind_by_name($queryParse, ':project_name', $ProjectName);
			oci_execute($queryParse);
			
			$row = oci_fetch_array($queryParse);
			
			if($row['PROJECTID'] == 0){
				$query = "INSERT INTO KPI_DOCUMENT_PROJECTS (PROJECTID, PROJECTNAME) VALUES
						(DOCUMENT_PROJECTID_SEQ.nextval, :project_name )";
				$queryParse = oci_parse($conn, $query);
				
				$ProjectName = trim($postData['ProjectName']);
				oci_bind_by_name($queryParse, ':project_name', $ProjectName);
				oci_execute($queryParse);
				
				$query = "SELECT MAX(PROJECTID) AS PROJECTID FROM KPI_DOCUMENT_PROJECTS";
				$queryParse = oci_parse($conn, $query);
				oci_execute($queryParse);
				$row = oci_fetch_array($queryParse);				
				
				$ProjectID = $row['PROJECTID'];
				
			} else {
				$ProjectID = $row['PROJECTID'];
			}			
			
				
			$query = "INSERT INTO KPI_DOCUMENTS (DOCUMENT_ID, PROJECTID, DELIVERABLE, DOCUMENT_NAME, DOCUMENT_TYPE,
					IS_TESTING, DELIVERY_DATE, SIGN_OFF_DATE, REPOSITORY_LINK, MONTH, ADDED_BY, ADDED_ON) VALUES
					(DOCUMENTID_SEQ.nextval, ".$ProjectID.", :deliverable, :document_name, :document_type,
					'".$ForTesting."', :delivery_date, :sign_off_date, :repo_link, '".$Month."',
					'".$userID."', '".strtoupper(date('d/M/y'))."')";
			
		} 
		
		/* Update Existing Data */
		
		else {
			$query = "UPDATE KPI_DOCUMENTS SET
					DELIVERABLE = :deliverable,
					DOCUMENT_NAME = :document_name,
					IS_TESTING = '".$ForTesting."',
					DOCUMENT_TYPE = :document_type,
					DELIVERY_DATE = :delivery_date,
					SIGN_OFF_DATE = :sign_off_date,
					REPOSITORY_LINK = :repo_link,
					MONTH = '".$Month."',
					EDITED_ON = '".strtoupper(date('d/M/y'))."',
					EDITED_BY = '".$userID."'
					WHERE DOCUMENT_ID = ".$DocumentID;
			
		}
		
		//echo $query;exit;		
		$queryParse = oci_parse($conn, $query);
		
		//echo "<pre>";print_r($postData);exit;
		
		$Deliverable = trim($postData['Deliverable']);
		$DocumentName = trim($postData['DocumentName']);
		$DocumentType = trim($postData['DocumentType']);
		$DeliveryDate = trim($postData['DeliveryDate']);
		$SignoffDate = trim($postData['SignoffDate']);
		$RepositoryLink = trim($postData['RepositoryLink']);
		
		oci_bind_by_name($queryParse, ':deliverable', $Deliverable);
		oci_bind_by_name($queryParse, ':document_name', $DocumentName);
		oci_bind_by_name($queryParse, ':document_type', $DocumentType);
		oci_bind_by_name($queryParse, ':delivery_date', $DeliveryDate);
		oci_bind_by_name($queryParse, ':sign_off_date', $SignoffDate);
		oci_bind_by_name($queryParse, ':repo_link', $RepositoryLink);		
		
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
	
	public function getEstimationDetails($Month = "", $postData, $ID = ""){
	
		$this->oracle->openConnection('KPIDASHBOARD');
		$conn = $this->oracle->getConnection();
		$ProjectDetails = array();
	
		$currentMonth = date ( "M-y" );
		$formattedcurrentMonth = strtoupper ( str_replace ( "-", "/", $currentMonth ) );
		
		if(!empty($Month))
			$formattedcurrentMonth = str_replace ( "-", "/", $Month );
		else if (! empty ( $postData ['Month'] )){
			$currentMonth = $postData ['Month'];
			$formattedcurrentMonth = strtoupper ( str_replace ( "-", "/", $currentMonth ) );
		}
		
		$query = "SELECT *
				 FROM KPI_QUALITY_ESITMATION
				 WHERE ACTIVE = 1 AND MONTH = '" . $formattedcurrentMonth."'";
	
		if(!empty($ID))
			$query .= " AND ID = ".$ID;

		$query .= " ORDER BY ENGAGEMENT_DATE";

	
		$queryParse = oci_parse($conn, $query);
		oci_execute($queryParse);
		$i = 0;

		while ($row = oci_fetch_array($queryParse, OCI_ASSOC+OCI_RETURN_NULLS)) {
			$ProjectDetail = array();

			$ProjectDetail['ID'] = $row['ID'];
			$ProjectDetail['PROJECT_NAME'] = $row['PROJECT_NAME'];
			$formattedMonth = strtoupper ( str_replace ( "/", "-", $row['MONTH'] ) );
			$ProjectDetail['MONTH'] = $formattedMonth;
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

		oci_free_statement($queryParse);
		$this->oracle->closeConnection();
		return $ProjectDetails;
	
	}
	
	public function addEditEstimationnDetails($postData, $ID = "", $userID){
		$this->oracle->openConnection('KPIDASHBOARD');
		$conn = $this->oracle->getConnection();
				
		
		$dStart = new \DateTime($postData['EngagementDate']);
		$dEnd  = new \DateTime($postData['Gate1EstimationDeliverydate']);
		$dDiff = $dStart->diff($dEnd);
		$DateDiff = $dDiff->days;
		
		$month = $postData['month'];
		$formattedMonth = str_replace ( "-", "/", strtoupper ( trim ( $month ) ) );
		
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
						(ID, PROJECT_NAME,MONTH, ENGAGEMENT_DATE, GATE1_ESTIMATION_DELIVERY_DATE, DIFF_DATES, 
						GATE1_ESTIMATION, GATE2_ESTIMATION, FINAL_ESTIMATION, GATE1_VARIANCE, GATE2_VARIANCE, ADDED_BY,
						ADDED_ON)
					VALUES(QUALITYESTIMATION_ID.nextval, :project_name,'".$formattedMonth."', :engagement_date,
						  :gate1_estimation_delivery_date,
						  '".$DateDiff."', :gate1_Estimation, :gate2_Estimation, :final_estimation, 
					      :gate1_variance, :gate2_variance, '".$userID."', '".strtoupper(date('d/M/y'))."')";	
			
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
			oci_bind_by_name($queryParse, ":gate1_variance", $gate1_variance);
			oci_bind_by_name($queryParse, ":gate2_variance", $gate2_variance);				
	
				
		}
		
		/* Update Existing Data */
		
		else {
			$query = "UPDATE KPI_QUALITY_ESITMATION SET
					MONTH='".$formattedMonth."',
					ENGAGEMENT_DATE = :engagement_date,
					GATE1_ESTIMATION_DELIVERY_DATE = :gate1_estimation_delivery_date,
					DIFF_DATES = '".$DateDiff."',
					GATE1_ESTIMATION = :gate1_Estimation,
					GATE2_ESTIMATION = :gate2_Estimation,
					FINAL_ESTIMATION = :final_estimation,
					GATE1_VARIANCE = :gate1_variance,
					GATE2_VARIANCE = :gate2_variance,
					EDITED_BY = '".$userID."',
					EDITED_ON = '".strtoupper(date('d/M/y'))."'							
					WHERE ID = ".$ID;				
		
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
			oci_bind_by_name($queryParse, ":gate1_variance", $gate1_variance);
			oci_bind_by_name($queryParse, ":gate2_variance", $gate2_variance);		
				
		}	
		
		$row = oci_execute($queryParse);
		
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

		$query = "SELECT PROJECTID, PROJECTNAME, ESTIMATED_PROD_LIVE_DATE, ACTUAL_PROD_LIVE_DATE, DIFF_DATE_DELIVERY 
				FROM KPI_PROJECTS
				WHERE ACTIVE = 1
				AND TO_CHAR(ESTIMATED_PROD_LIVE_DATE, 'MON/YY') LIKE '%" . $currentMonth . "%'";

		// echo $query;//exit;
		$queryParse = oci_parse ( $conn, $query );
		oci_execute ( $queryParse );

		while ( $row = oci_fetch_array ( $queryParse, OCI_ASSOC + OCI_RETURN_NULLS ) ) {
				
			$deliverySlippage = new DeliverySlippage ();
			$deliverySlippage->setProjectId ( $row ['PROJECTID'] );
			$deliverySlippage->setProjectName ( $row ['PROJECTNAME'] );
			$deliverySlippage->setEstimatedProdLiveDate ( $row ['ESTIMATED_PROD_LIVE_DATE'] );
			$deliverySlippage->setDeliveryDate ( $row ['ACTUAL_PROD_LIVE_DATE'] );
			$deliverySlippage->setDifferenceInDate ( $row['DIFF_DATE_DELIVERY'] );
	
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
	
		$query = "SELECT PROJECTID, PROJECTNAME, NO_OF_ST_AUTOMATED_TEST_CASES, TOTAL_NO_OF_ST_TEST_CASES,
				ST_AUTOMATION 
				FROM KPI_PROJECTS 
				WHERE ACTIVE = 1 
				AND ST_AUTOMATION_APPLICABLE = 'Yes'
				AND TO_CHAR(ESTIMATED_PROD_LIVE_DATE, 'MON/YY') LIKE '%" . $currentMonth . "%'";	

		
		$queryParse = oci_parse ( $conn, $query );
		oci_execute ( $queryParse );

		while ( $row = oci_fetch_array ( $queryParse, OCI_ASSOC + OCI_RETURN_NULLS ) ) {

			$stAutomationEntity = new STAutomationEntity();
			$stAutomationEntity->setProjectId ( $row ['PROJECTID'] );
			$stAutomationEntity->setProjectName ( $row ['PROJECTNAME'] );
			$stAutomationEntity->setSTAutomatedTestCases ( $row ['NO_OF_ST_AUTOMATED_TEST_CASES'] );
			$stAutomationEntity->setSTTotalTestCases ( $row ['TOTAL_NO_OF_ST_TEST_CASES'] );			
			$stAutomationEntity->setSTAutomation( $row ['ST_AUTOMATION'] );
				
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
					$query = "UPDATE KPI_PROJECTS SET 
							ESTIMATED_PROD_LIVE_DATE ='" . $formattedDate . "'
							WHERE PROJECTID = " . $projectID;
				} else if ($action == 'deliveryDate') {
					$query = "UPDATE KPI_PROJECTS SET 
							ACTUAL_PROD_LIVE_DATE = '" . $formattedDate . "'
							WHERE PROJECTID = " . $projectID;
				}
			}			
			
			$queryParse = oci_parse ( $conn, $query );
			$row = oci_execute ( $queryParse );
			if ($row == 1) {
	
				$query1 = "SELECT ESTIMATED_PROD_LIVE_DATE, ACTUAL_PROD_LIVE_DATE 
						FROM KPI_PROJECTS 
						WHERE PROJECTID = " . $projectID;
				$queryParse1 = oci_parse ( $conn, $query1 );
				oci_execute ( $queryParse1 );
	
				while ( $row = oci_fetch_array ( $queryParse1, OCI_ASSOC + OCI_RETURN_NULLS ) ) {
						
					$deliverySlippage = new DeliverySlippage ();
					$deliverySlippage->setEstimatedProdLiveDate ( $row ['ESTIMATED_PROD_LIVE_DATE'] );
					$deliverySlippage->setDeliveryDate ( $row ['ACTUAL_PROD_LIVE_DATE'] );
					$date1 = new \DateTime ( $row ['ESTIMATED_PROD_LIVE_DATE'] );
					$date2 = new \DateTime ( $row ['ACTUAL_PROD_LIVE_DATE'] );
						
					$differenceInDate = $date2->diff ( $date1 );
					
					if($date2 > $date1 || $date2 == $date1){
						$response = 'Details updated_' . $differenceInDate->days;
						$date_diff_delivery = $differenceInDate->days;
					}
					else {
						$response = 'Details updated_' . "-".$differenceInDate->days;
						$date_diff_delivery = "-".$differenceInDate->days;
					}
					
					$query2 = "UPDATE KPI_PROJECTS SET
							DIFF_DATE_DELIVERY = '" . $date_diff_delivery . "'
							WHERE PROJECTID = " . $projectID;
					$queryParse2 = oci_parse ( $conn, $query2 );
					oci_execute ( $queryParse2 );
					
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
			
			$totalSTTestCases = '';
			$automatedSTTestCases = '';
			$formattedValue = trim($formattedValue);
			
			if ($action == 'stAutomatedTestCases') {				
				
				if($formattedValue == 'na' || $formattedValue == 'n/a' ||
				   $formattedValue == 'NA' || $formattedValue == 'N/A' ) {
					
					$stAutomation = 'N/A';
					$formattedValue = 'N/A';
					$totalSTTestCases = 'N/A';
				}
				else {					
					if($formattedValue == ''){
						$stAutomation = '';
					} else {
						if($row ['TOTAL_NO_OF_ST_TEST_CASES'] != "" && $row ['TOTAL_NO_OF_ST_TEST_CASES'] != 0){					
							$stAutomation = round((($formattedValue / $row ['TOTAL_NO_OF_ST_TEST_CASES'] ) * 100), 2);
						}
						else if($row ['TOTAL_NO_OF_ST_TEST_CASES'] == 0){
							$stAutomation = 0;
						}
						else {					
							$stAutomation = '';
						}
					}
				}								
				
				$query = "UPDATE KPI_PROJECTS SET 
						NO_OF_ST_AUTOMATED_TEST_CASES = '" . $formattedValue . "', 
						ST_AUTOMATION = '" . $stAutomation . "'";
				
				if(!empty($totalSTTestCases))
					$query .= ", TOTAL_NO_OF_ST_TEST_CASES = '".$totalSTTestCases."'";
				
				$query .= " WHERE PROJECTID = ".$projectID;
				
			} else if ($action == 'stTotalTestCases') {
				
				if($formattedValue == 'na' || $formattedValue == 'n/a' ||
				   $formattedValue == 'NA' || $formattedValue == 'N/A' ) {
				   	$stAutomation = 'N/A';
					$formattedValue = 'N/A';
					$automatedSTTestCases = 'N/A';
				}
				else {
					if($formattedValue == ''){
						$stAutomation = '';
					} else {
						if($formattedValue != "" && $formattedValue != 0){					
							$stAutomation = round((($row ['NO_OF_ST_AUTOMATED_TEST_CASES'] / $formattedValue ) * 100), 2);					
						}
						elseif($formattedValue == 0){
							$stAutomation = 0;
						}
						else {					
							$stAutomation = '';
						}
					}
				}				
				
				$query = "UPDATE KPI_PROJECTS SET 
						TOTAL_NO_OF_ST_TEST_CASES = '" . $formattedValue . "',
						ST_AUTOMATION = '" . $stAutomation . "'";
				
				if(!empty($automatedSTTestCases))
					$query .= ", NO_OF_ST_AUTOMATED_TEST_CASES = '".$automatedSTTestCases."'";
				
				$query .= "	WHERE PROJECTID = " . $projectID;
			}			
			
		
			$queryParse = oci_parse ( $conn, $query );
			$row = oci_execute ( $queryParse );
			if ($row == 1) {
	
				$query = "SELECT NO_OF_ST_AUTOMATED_TEST_CASES, TOTAL_NO_OF_ST_TEST_CASES, ST_AUTOMATION 
						FROM KPI_PROJECTS 
						WHERE PROJECTID = ".$projectID;
				$queryParse = oci_parse ( $conn, $query );
				oci_execute ( $queryParse );
	
				while ( $row = oci_fetch_array ( $queryParse, OCI_ASSOC + OCI_RETURN_NULLS ) ) {
	
					$deliverySlippage = new DeliverySlippage ();
					$deliverySlippage->setEstimatedProdLiveDate ( $row ['NO_OF_ST_AUTOMATED_TEST_CASES'] );
					$deliverySlippage->setDeliveryDate ( $row ['TOTAL_NO_OF_ST_TEST_CASES'] );
					$stAutomation = $row ['ST_AUTOMATION'];					

					$response = 'Details updated-' . $stAutomation."%";
				}
			} else {
				$response = 'Some problem occurred, pls try again !!!';
			}
	
			oci_free_statement ( $queryParse );
			$this->oracle->closeConnection ();
	
			return $response;
		}		
		
	}
	
	public function addProdTestAccounts($postData) {
		
		$this->oracle->openConnection ( 'KPIDASHBOARD' );
		$conn = $this->oracle->getConnection ();
		
		$session = new Session();
		$userID = $session->get('UserID');
		
		$totalcustaccounts = $postData['custAccounts'] + $postData['onlineCustAccounts'];
		$totalbillaccounts = $postData['billAccounts'] + $postData['onlineBillAccounts'];
		$totaldeviceorders = $postData['deviceOrders'] + $postData['onlineDeviceOrders'];
		$totalaccounts = $totalcustaccounts + $totalbillaccounts + $totaldeviceorders;
	
		$custaddresscomplaint = $totalcustaccounts - ( $postData['custAddrAccounts'] + $postData['onlineCustAddrAccounts'] );
		$billaddresscomplaint = $totalbillaccounts - ( $postData['billAddrAccounts'] + $postData['onlineBillAddrAccounts'] );
		$orderaddresscomplaint = $totaldeviceorders;
	
		$namecomplaint = $totalcustaccounts - ( $postData['nameAccounts'] + $postData['onlineCustNameAccounts'] );	
		$billprofilecomplaint = $totalbillaccounts - ( $postData['incorrectBillAccounts'] + $postData['onlineIncorrectBillAccounts'] );	
		$billdetailshared = $totalbillaccounts -( $postData['notSharedAccounts'] + $postData['onlineNotSharedAccounts'] );
		$orderdetailshared = $totaldeviceorders;
	
		$consolidatedcustaccounts = ($custaddresscomplaint + $namecomplaint) / 2;
		$consolidatedbillaccounts = ($billaddresscomplaint + $billprofilecomplaint + $billdetailshared) / 3;
		$consolidatedorders = $totaldeviceorders;
		$consolidatedtotal = $consolidatedcustaccounts+$consolidatedbillaccounts+$consolidatedorders;
		$prodtestaccounts = $consolidatedtotal/$totalaccounts;
		$formatedprodtestaccounts = round($prodtestaccounts, 2);
		$formatedprodtestaccounts1 = $formatedprodtestaccounts * 100;
		$month = $postData['month'];
		$formattedMonth = strtoupper ( str_replace ( "-", "/", $month ) );
		
		$query = "SELECT COUNT(*) AS COUNT 
				FROM KPI_PROD_TEST_ACCOUNTS 
				WHERE MONTH_PROD_TEST_ACCOUNTS = '" .$formattedMonth ."'";

		$queryParse = oci_parse ( $conn, $query );
		oci_execute ( $queryParse );
		$row = oci_fetch_array ( $queryParse );

		if ($row ['COUNT'] == 0) {
			
			$query = "INSERT INTO KPI_PROD_TEST_ACCOUNTS
				(ROW_ID, MONTH_PROD_TEST_ACCOUNTS, CUSTACCOUNTS, ONLINECUSTACCOUNTS,
				CUSTADDRACCOUNTS,ONLINECUSTADDRACCOUNTS,NAMEACCOUNTS,ONLINECUSTNAMEACCOUNTS,
				BILLACCOUNTS,ONLINEBILLACCOUNTS,BILLADDRACCOUNTS,ONLINEBILLADDRACCOUNTS,
				INCORRECTBILLACCOUNTS,ONLINEINCORRECTBILLACCOUNTS,NOTSHAREDACCOUNTS,ONLINENOTSHAREDACCOUNTS,
				DEVICEORDERS,ONLINEDEVICEORDERS,TOTAL_CUST_ACCOUNTS,TOTAL_BILL_ACCOUNTS,TOTAL_DEVICE_ORDERS,
				TOTAL_ACCOUNTS,ADDR_COMPLAINT_CUST_ACCOUNTS,ADDR_COMPLAINT_BILL_ACCOUNTS,ADDR_COMPLAINT_DEVICE_ORDERS,
				NAME_COMPLAINT_CUST_ACCOUNTS,BILL_COMPLAINT_BILL_ACCOUNTS,DETAILS_SHARED_BILL_ACCOUNTS,
				DETAILS_SHARED_DEVICE_ORDERS,CONSOLIDATED_CUST_ACCOUNTS,CONSOLIDATED_BILL_ACCOUNTS,
				CONSOLIDATED_DEVICE_ORDERS,CONSOLIDATED_TOTAL,PRODUCTION_TEST_ACCOUNTS, ADDED_ON, ADDED_BY) VALUES (PROD_TEST_ACCOUNTS_ID.nextval,
				'".trim($formattedMonth)."',
				'".trim($postData['custAccounts'])."','".trim($postData['onlineCustAccounts'])."',
				'".trim($postData['custAddrAccounts'])."','".trim($postData['onlineCustAddrAccounts'])."',
				'".trim($postData['nameAccounts'])."','".trim($postData['onlineCustNameAccounts'])."',
				'".trim($postData['billAccounts'])."','".trim($postData['onlineBillAccounts'])."',
				'".trim($postData['billAddrAccounts'])."','".trim($postData['onlineBillAddrAccounts'])."',
				'".trim($postData['incorrectBillAccounts'])."','".trim($postData['onlineIncorrectBillAccounts'])."',
				'".trim($postData['notSharedAccounts'])."','".trim($postData['onlineNotSharedAccounts'])."',
				'".trim($postData['deviceOrders'])."','".trim($postData['onlineDeviceOrders'])."',
				$totalcustaccounts,$totalbillaccounts,$totaldeviceorders,
				$totalaccounts,$custaddresscomplaint,$billaddresscomplaint,$orderaddresscomplaint,
				$namecomplaint,$billprofilecomplaint,$billdetailshared,$orderdetailshared,
				$consolidatedcustaccounts,$consolidatedbillaccounts,$consolidatedorders,
				$consolidatedtotal,$formatedprodtestaccounts1, '".strtoupper(date('d/M/y'))."', $userID)";
			
			$queryParse = oci_parse ( $conn, $query );
			$row = oci_execute ( $queryParse );

			if ($row)
				return $status = 'Data Added !!!';
			else
				return $status = 'Some problem occurred, try again !!!';
			
		}
		else {
			return $status = 'Data already present for the entered month!';
		}
	}
	
	public function viewProdTestAccounts() {
		
		$this->oracle->openConnection ( 'KPIDASHBOARD' );
		$conn = $this->oracle->getConnection ();
		
		$prodTestAccountsEntitys = array ();
		$query = "SELECT ROW_ID, MONTH_PROD_TEST_ACCOUNTS,TOTAL_ACCOUNTS,CONSOLIDATED_TOTAL,PRODUCTION_TEST_ACCOUNTS 
				FROM KPI_PROD_TEST_ACCOUNTS WHERE ACTIVE = 1";

		$queryParse = oci_parse ( $conn, $query );
		oci_execute ( $queryParse );
	
		while ( $row = oci_fetch_array ( $queryParse, OCI_ASSOC + OCI_RETURN_NULLS ) ) {
			$month = $row['MONTH_PROD_TEST_ACCOUNTS'];
			$formattedMonth = strtoupper ( str_replace ( "/", "-", $month ) );
			$prodTestAccountsEntity = new ProdTestAccountsEntity();
			$prodTestAccountsEntity->setRowId($row['ROW_ID']);
			$prodTestAccountsEntity->setMonth($formattedMonth);
			$prodTestAccountsEntity->setTotalAccounts($row['TOTAL_ACCOUNTS']);
			$prodTestAccountsEntity->setConsolidatedTotal($row['CONSOLIDATED_TOTAL']);
			$prodTestAccountsEntity->setProdTestAccounts($row['PRODUCTION_TEST_ACCOUNTS']);
			$prodTestAccountsEntitys[] = $prodTestAccountsEntity;
		}

		return $prodTestAccountsEntitys;
	
	}
	
	public  function deleteProdTestAccData($ID){
		$this->oracle->openConnection('KPIDASHBOARD');
		$conn = $this->oracle->getConnection();
		$response = array();
		if(!empty($ID)){
				
			$query = "UPDATE KPI_PROD_TEST_ACCOUNTS SET ACTIVE = 0 WHERE ID = ".$ID;
			$queryParse = oci_parse($conn, $query);
				
			//echo $query;exit;
			$row = oci_execute($queryParse);
	
			if($row == 1)
				$response['status'] = 'Record Deleted !!!';
				else
					$response['status'] = 'Some problem occurred, pls try again !!!';
	
					oci_free_statement($queryParse);
					$this->oracle->closeConnection();
						
		}
		else {
			$response['status'] = 'Some problem, Please select the process again and click on delete !!!';
		}
	
		return $response;
	}
	
	
	
	
	public function viewProdTestAccountsForEdit($month) {
		
		$this->oracle->openConnection ( 'KPIDASHBOARD' );	
		$conn = $this->oracle->getConnection ();
		
		$formattedMonth = strtoupper ( str_replace ( "-", "/", $month ) );
		
		$query = "SELECT * 
				FROM KPI_PROD_TEST_ACCOUNTS 
				WHERE MONTH_PROD_TEST_ACCOUNTS = '".$formattedMonth."'";

		$queryParse = oci_parse ( $conn, $query );
		oci_execute ( $queryParse );		
		
		while ( $row = oci_fetch_array ( $queryParse, OCI_ASSOC + OCI_RETURN_NULLS ) ) {
			
			$prodTestAccountsEntity = new ProdTestAccountsEntity();
			$month = $row['MONTH_PROD_TEST_ACCOUNTS'];
			$formattedMonth = strtoupper ( str_replace ( "/", "-", $month ) );
			
			$prodTestAccountsEntity->setRowId($row['ROW_ID']);
			$prodTestAccountsEntity->setMonth($formattedMonth);
			$prodTestAccountsEntity->setCustAccounts($row['CUSTACCOUNTS']);
			$prodTestAccountsEntity->setOnlineCustAccounts($row['ONLINECUSTACCOUNTS']);
			$prodTestAccountsEntity->setCustAddrAccounts($row['CUSTADDRACCOUNTS']);
			$prodTestAccountsEntity->setOnlineCustAddrAccounts($row['ONLINECUSTADDRACCOUNTS']);
			$prodTestAccountsEntity->setNameAccounts($row['NAMEACCOUNTS']);
			$prodTestAccountsEntity->setOnlineCustNameAccounts($row['ONLINECUSTNAMEACCOUNTS']);
			$prodTestAccountsEntity->setBillAccounts($row['BILLACCOUNTS']);
			$prodTestAccountsEntity->setOnlineBillAccounts($row['ONLINEBILLACCOUNTS']);
			$prodTestAccountsEntity->setBillAddrAccounts($row['BILLADDRACCOUNTS']);
			$prodTestAccountsEntity->setOnlineBillAddrAccounts($row['ONLINEBILLADDRACCOUNTS']);
			$prodTestAccountsEntity->setIncorrectBillAccounts($row['INCORRECTBILLACCOUNTS']);
			$prodTestAccountsEntity->setOnlineIncorrectBillAccounts($row['ONLINEINCORRECTBILLACCOUNTS']);
			$prodTestAccountsEntity->setNotSharedAccounts($row['NOTSHAREDACCOUNTS']);
			$prodTestAccountsEntity->setOnlineNotSharedAccounts($row['ONLINENOTSHAREDACCOUNTS']);
			$prodTestAccountsEntity->setDeviceOrders($row['DEVICEORDERS']);
			$prodTestAccountsEntity->setOnlineDeviceOrders($row['ONLINEDEVICEORDERS']);
				
		}

		return $prodTestAccountsEntity;
	
	}
	public function updateProdTestAccounts($postData) {
		
		$this->oracle->openConnection ( 'KPIDASHBOARD' );
		$conn = $this->oracle->getConnection ();
		
		$session = new Session();
		$userID = $session->get('UserID');
		
		$prodTestAccountsEntitys = array ();
		
		if (! empty ( $postData )) {
			
			$query = "SELECT COUNT(*) AS COUNT 
					FROM KPI_PROD_TEST_ACCOUNTS 
					WHERE ROW_ID =" . $postData['rowId'];
			$queryParse = oci_parse ( $conn, $query );
			oci_execute ( $queryParse );
			$row1 = oci_fetch_array ( $queryParse );
	
			//CHECK IF SAME MONTH----------------------------------------------------------------------
			
			$totalcustaccounts = $postData['custAccounts'] + $postData['onlineCustAccounts'];
			$totalbillaccounts = $postData['billAccounts'] + $postData['onlineBillAccounts'];
			$totaldeviceorders = $postData['deviceOrders'] + $postData['onlineDeviceOrders'];
			$totalaccounts = $totalcustaccounts + $totalbillaccounts+$totaldeviceorders;
				
			$custaddresscomplaint = $totalcustaccounts - ($postData['custAddrAccounts'] + $postData['onlineCustAddrAccounts']);
			$billaddresscomplaint = $totalbillaccounts - ($postData['billAddrAccounts'] + $postData['onlineBillAddrAccounts']);
			$orderaddresscomplaint = $totaldeviceorders;
				
			$namecomplaint = $totalcustaccounts -($postData['nameAccounts'] + $postData['onlineCustNameAccounts']);
				
			$billprofilecomplaint = $totalbillaccounts - ($postData['incorrectBillAccounts'] + $postData['onlineIncorrectBillAccounts']);
				
			$billdetailshared = $totalbillaccounts -($postData['notSharedAccounts'] + $postData['onlineNotSharedAccounts']);
			$orderdetailshared = $totaldeviceorders;
				
			$consolidatedcustaccounts = ($custaddresscomplaint + $namecomplaint) / 2;
			$consolidatedbillaccounts = ($billaddresscomplaint+$billprofilecomplaint + $billdetailshared) / 3;
			$consolidatedorders = $totaldeviceorders;
			$consolidatedtotal = $consolidatedcustaccounts + $consolidatedbillaccounts + $consolidatedorders;
			$prodtestaccounts = $consolidatedtotal / $totalaccounts;
			$formatedprodtestaccounts = round($prodtestaccounts, 2);
			$formatedprodtestaccounts1 = $formatedprodtestaccounts * 100;
			$month = $postData['month'];
			$formattedMonth = strtoupper ( str_replace ( "-", "/", $month ) );
			
			$query = "SELECT COUNT(*) AS COUNT 
					FROM KPI_PROD_TEST_ACCOUNTS 
					WHERE ROW_ID !=" . $postData['rowId'] ."
					AND MONTH_PROD_TEST_ACCOUNTS ='". $formattedMonth."'";

			$queryParse = oci_parse ( $conn, $query );
			oci_execute ( $queryParse );
			$row2 = oci_fetch_array ( $queryParse );
			
			if ($row2 ['COUNT'] > 0) {
				$response = "Data  already present for this month !!!";
			}
			else{
				
				if ($row1 ['COUNT'] > 0) {
					$query = "UPDATE KPI_PROD_TEST_ACCOUNTS SET 
							MONTH_PROD_TEST_ACCOUNTS = '". $formattedMonth."',
							CUSTACCOUNTS = ".$postData['custAccounts'].",
							ONLINECUSTACCOUNTS = ".$postData['onlineCustAccounts'].",
							CUSTADDRACCOUNTS = ".$postData['custAddrAccounts'].",
							ONLINECUSTADDRACCOUNTS = ".$postData['onlineCustAddrAccounts'].",
							NAMEACCOUNTS = ".$postData['nameAccounts'].",
							ONLINECUSTNAMEACCOUNTS = ".$postData['onlineCustNameAccounts'].",
							BILLACCOUNTS = ".$postData['billAccounts'].",
							ONLINEBILLACCOUNTS = ".$postData['onlineBillAccounts'].",
							BILLADDRACCOUNTS = ".$postData['billAddrAccounts'].",
							ONLINEBILLADDRACCOUNTS = ".$postData['onlineBillAddrAccounts'].",
							INCORRECTBILLACCOUNTS = ".$postData['incorrectBillAccounts'].",
							ONLINEINCORRECTBILLACCOUNTS = ".$postData['onlineIncorrectBillAccounts'].",
							NOTSHAREDACCOUNTS = ".$postData['notSharedAccounts'].",
							ONLINENOTSHAREDACCOUNTS = ".$postData['onlineNotSharedAccounts'].",
							DEVICEORDERS = ".$postData['deviceOrders'].",
							ONLINEDEVICEORDERS = ".$postData['onlineDeviceOrders'].",
							TOTAL_CUST_ACCOUNTS = ".$totalcustaccounts.",
							TOTAL_BILL_ACCOUNTS = ".$totalbillaccounts.",
							TOTAL_DEVICE_ORDERS = ".$totaldeviceorders.",
							TOTAL_ACCOUNTS = ".$totalaccounts.",
							ADDR_COMPLAINT_CUST_ACCOUNTS = ".$custaddresscomplaint.",
							ADDR_COMPLAINT_BILL_ACCOUNTS = ".$billaddresscomplaint.",
							ADDR_COMPLAINT_DEVICE_ORDERS = ".$orderaddresscomplaint.",
							NAME_COMPLAINT_CUST_ACCOUNTS = ".$namecomplaint.",
							BILL_COMPLAINT_BILL_ACCOUNTS = ".$billprofilecomplaint.",
							DETAILS_SHARED_BILL_ACCOUNTS = ".$orderdetailshared.",
							DETAILS_SHARED_DEVICE_ORDERS = ".$orderdetailshared.",
							CONSOLIDATED_CUST_ACCOUNTS = ".$consolidatedcustaccounts.",
							CONSOLIDATED_BILL_ACCOUNTS = ".$consolidatedbillaccounts.",
							CONSOLIDATED_DEVICE_ORDERS = ".$consolidatedorders.",
							CONSOLIDATED_TOTAL = ".$consolidatedtotal.",
							PRODUCTION_TEST_ACCOUNTS = ".$formatedprodtestaccounts1.",
							EDITED_ON = '".strtoupper(date('d/M/y'))."',
							EDITED_BY = '".$userID."'
							WHERE ROW_ID=". $postData['rowId'];
				}
				else{
					$response = "Some probelm occured.Please try again !!!";
				}

				$queryParse = oci_parse ( $conn, $query );
				$row = oci_execute ( $queryParse );
				if ($row == 1) {
					$response = "Details Updated !!!";
				}
			}
			return $response;
		}
	}
	
	public  function deleteQualityOfEstimation($ID){
		$this->oracle->openConnection('KPIDASHBOARD');
		$conn = $this->oracle->getConnection();
		$response = array();
		if(!empty($ID)){
			
			$query = "UPDATE KPI_QUALITY_ESITMATION SET ACTIVE = 0 WHERE ID = ".$ID;
			$queryParse = oci_parse($conn, $query);
			
			//echo $query;exit;
			$row = oci_execute($queryParse);
	
			if($row == 1)
				$response['status'] = 'Record Deleted !!!';
				else
			$response['status'] = 'Some problem occurred, pls try again !!!';
	
			oci_free_statement($queryParse);
			$this->oracle->closeConnection();
			
		}
		else {
			$response['status'] = 'Some problem, Please select the process again and click on delete !!!';
		}
	
		return $response;
	}
	
	public function deleteDocument($ID){

		$this->oracle->openConnection('KPIDASHBOARD');
		$conn = $this->oracle->getConnection();
		$response = array();
		
		if(!empty($ID)){
			//$query = "UPDATE KPI_DOCUMENTS SET ACTIVE = 0 WHERE DOCUMENT_ID = ".$ID;
			
			$query = "DELETE FROM KPI_DOCUMENTS WHERE DOCUMENT_ID = ".$ID;
			$queryParse = oci_parse($conn, $query);
			
			//echo $query;exit;
			$row = oci_execute($queryParse);
				
			if($row == 1)
				$response['status'] = 'Record Deleted !!!';
			else
				$response['status'] = 'Some problem occurred, pls try again !!!';
		
			oci_free_statement($queryParse);
			$this->oracle->closeConnection();
			
		}
		else {
			$response['status'] = 'Some problem, Please select the process again and click on delete !!!';
		}
		
		return $response;
				
	}
	
	
}