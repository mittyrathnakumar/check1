<?php

namespace AppBundle\Repository;

use AppBundle\Service\OracleDatabaseService;
use AppBundle\Service\Constants;
use AppBundle\Entity\Projects;
use AppBundle\Entity\KPI;

/**
 * @author Dhara Sheth
 */
class DashboardRepository
{
	/**
	 * @var OracleDatabaseService
	 */
	private $oracle;
	
	/**
	 * @var Constants
	 */
	
	private $constants;
	
	/**
	 * 
	 */
	public function __construct() {
		$this->oracle = new OracleDatabaseService();
		$this->constants = new Constants();
	}	
	
	/**
	 * Returns an array of KPI and SLAs defined in the database
	 * 
	 */
	
	public function getSLAs(){	
		$this->oracle->openConnection('KPIDASHBOARD');
		$conn = $this->oracle->getConnection();
		
		$query = "SELECT * FROM KPI_PARAMETER WHERE ACTIVE = 1";
		$queryParse = oci_parse($conn, $query);
		oci_execute($queryParse);
		$KPIs = array();
		
		while ($row = oci_fetch_array($queryParse, OCI_ASSOC+OCI_RETURN_NULLS)) {
			$KPI = new KPI();
			
			$KPI->setKPIName($row['KPI_NAME']);
			$KPI->setKPIID($row['KPI_ID']);
			$KPI->setOperator($row['OPERATOR']);
			$KPI->setSLAValue($row['SLA_VALUE']);
			$KPI->setInQC($row['QC']);
			$KPI->setKPIShortName($row['KPI_SHORT_NAME']);
			$KPI->setScope($row['SCOPE']);
			$KPI->setCaption($this->constants->getKPICaptions($row['KPI_NAME']));
			
			$KPIs[] = $KPI;			
		}
		
		//echo "<pre>";print_r($KPIs);exit;
		oci_free_statement($queryParse);
		$this->oracle->closeConnection();
		return $KPIs;
	}
	
	
	public function getmonthYearArray(){		
		$currentYear = date("M-y");
		$nextYear = strtotime(date("M-y", strtotime($currentYear)) . " +1 year");
		$nextYear = date("M-y", $nextYear);
		
		$currentYear = explode("-", $currentYear);
		$currentYear = $currentYear[1];
		
		$nextYear = explode("-", $nextYear);
		$nextYear = $nextYear[1];
		
		$Years = array($currentYear, $nextYear);
		$months[] = array();
		
		for($i=0;$i<count($Years);$i++){			
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
			
			$months[11] = 'JAN-'.$nextYear;
			$months[12] = 'FEB-'.$nextYear;
			$months[13] = 'MAR-'.$nextYear;
			$months[14] = 'APR-'.$nextYear;
			$months[15] = 'MAY-'.$nextYear;
			$months[16] = 'JUN-'.$nextYear;
			$months[17] = 'JUL-'.$nextYear;
			$months[18] = 'AUG-'.$nextYear;
			$months[19] = 'SEP-'.$nextYear;
			$months[20] = 'OCT-'.$nextYear;
			$months[21] = 'NOV-'.$nextYear;
			$months[22] = 'DEC-'.$nextYear;			
		}		
		
		return $months;		
		
	}
	
	public  function getKPIData($KPIID = ""){
		$this->oracle->openConnection('KPIDASHBOARD');
		$conn = $this->oracle->getConnection();
		

		$queryKPIData = "SELECT * FROM KPI_PARAMETER WHERE KPI_ID = '".$KPIID."'";
		$queryKPIDataParse = oci_parse($conn, $queryKPIData);			
		
		oci_execute($queryKPIDataParse);
		
		$KPIData = oci_fetch_array($queryKPIDataParse);
		oci_free_statement($queryKPIDataParse);
		$this->oracle->closeConnection();
		
		return $KPIData;		
	}
	
	/**
	 * @param string $postData
	 * returns KPI based result for all the projects 
	 */
	
	public function getKPIResults($month = "", $KPIID = ""){		
		if(empty($month))
			$month = strtoupper(date('M/y'));	
		else
			$month = str_replace("-", "/", strtoupper($month));

		$KPITotals = array();
		$KPIResults = array();
		$KPIs = array();
		
		
		
		/* Dashboard data */ 
		
		if(empty($KPIID)){			
			
			
			//echo 'in if===';exit;
			$KPIs = $this->getSLAs();
			//echo "<pre>";print_r($KPIs);exit;
			
			foreach($KPIs as $KPI){
				
				$ProjectScope = '';
				$count1 = 0;
				$count2 = 0;
				$sumCount1 = 0;
				$sumCount2 = 0;
				
				/* QC Based KPI Calculation */
				
				if($KPI->getInQC() == 1){

						//echo $KPI->getKPIShortName()."</br></br>";
					//if(empty($conn)){
						//echo 'in if===';exit;
					$this->oracle->openConnection('KPIDASHBOARD');
					$conn = $this->oracle->getConnection();
					//}					
						//while($ProjectResult){
					if($KPI->getScope() != 'Both'){
						if($KPI->getScope() == 'Dev')
							$KPIProjectScope = 'Dev';
						elseif($KPI->getScope() == 'Testing')
							$KPIProjectScope = 'Testing';			
							
					} else
						$KPIProjectScope = 'Both';
							
					$queryProjects = "SELECT DISTINCT(PROJECTNAME) AS PROJECTNAME, PROJECTID, CYCLE_ID, QC_TABLENAME,
						REUSABILITY, SCOPE, ESTIMATED_PROD_LIVE_DATE FROM KPI_PROJECTS WHERE ACTIVE = 1 AND CYCLE_ID IS NOT NULL
						AND TO_CHAR(ESTIMATED_PROD_LIVE_DATE, 'MON/YY') LIKE '%".$month."%'";
					
					/*if($ProjectScope == 'Both')
						$queryProjects .= " AND SCOPE = 'Both' ";
					else
						$queryProjects .= " AND (SCOPE = 'Both' OR SCOPE = '".$ProjectScope."') ";
					*/
					
					$queryProjects .= " ORDER BY ESTIMATED_PROD_LIVE_DATE";
					
					//echo $queryProjects;exit;
					$queryProjectsParse = oci_parse($conn, $queryProjects);
					oci_execute($queryProjectsParse);
					$ProjectResult = array();
					
					while($ProjectResultTemp = oci_fetch_array($queryProjectsParse, OCI_ASSOC+OCI_RETURN_NULLS)){
						$ProjectResult[] = $ProjectResultTemp;
					}
					
					if(count($ProjectResult) > 0){
						
						if(!empty($conn))
							oci_close($conn);
						
						for($i=0;$i<count($ProjectResult);$i++){
							
							//echo $ProjectResult['QC_TABLENAME']."****".$ProjectResult['CYCLE_ID']."====<BR>";				
							if($ProjectResult[$i]['CYCLE_ID'] != null ){								
								
								$count1Arr = array();
								$count2Arr = array();
								
								 // Closes previous connection with KPIDASHBOARD
								//if(empty($conn)){
									$this->oracle->openConnection('QC');
									$conn = $this->oracle->getConnection();
								//}
								
									/* Condition to ignore non-matching Scope projects in the Calculation */
									
								if($KPIProjectScope == $ProjectResult[$i]['SCOPE'] ||
									($KPIProjectScope == 'Both' && ($ProjectResult[$i]['SCOPE'] == 'Dev' || $ProjectResult[$i]['SCOPE'] == 'Testing')) ||
									($ProjectResult[$i]['SCOPE'] == 'Both' && ($KPIProjectScope == 'Dev' || $KPIProjectScope == 'Testing'))	){
									
										$query1 = $this->constants->getKPIQuery($KPI->getKPIShortName(), $ProjectResult[$i]['QC_TABLENAME'], trim($ProjectResult[$i]['CYCLE_ID']), 1);
										$query2 = $this->constants->getKPIQuery($KPI->getKPIShortName(), $ProjectResult[$i]['QC_TABLENAME'], trim($ProjectResult[$i]['CYCLE_ID']), 2);
										
										//echo $query1."</br></br>";//exit;
										//echo $query2."</br></br>";
										
										//echo "</br></br></br></br>";
										$queryParse1 = oci_parse($conn, $query1);
										oci_execute($queryParse1);
										$count1Arr = oci_fetch_array($queryParse1);
										//print_r($count1Arr);exit;
										$count1 = $count1Arr[0];//exit;
										
										$sumCount1 = $sumCount1 + $count1;
										
										$queryParse2 = oci_parse($conn, $query2);
										oci_execute($queryParse2);
										$count2Arr = oci_fetch_array($queryParse2);
										$count2 = $count2Arr[0];
										
										$sumCount2 = $sumCount2 + $count2;
										
										
								}
								//echo $sumCount2."==".$sumCount1."</br></br>";
							}			
													
						}
					}
					
					//exit;
					//echo $sumCount2."==".$sumCount1."</br></br>";exit;
					$KPITotals['KPI'] = $KPI->getKPIName();
					$KPITotals['KPIID'] = $KPI->getKPIID();
					$KPITotals['Target'] = $KPI->getSLAValue();
					$KPITotals['Operator'] = $KPI->getOperator();
					$KPITotals['Caption'] = $KPI->getCaption();					
					
					if($sumCount2 != 0){
						$KPITemp = ($sumCount1 / $sumCount2) * 100;						
						$KPITotals['Actual'] = number_format($KPITemp, 2);						
					}
					else {
						$KPITotals['Actual'] = 0;
					}
					
				}
				
				// NON-QC PROJECTS RESULT
				
				else {				
						$KPITotals['KPI'] = $KPI->getKPIName();
						$KPITotals['KPIID'] = $KPI->getKPIID();
						$KPITotals['Target'] = $KPI->getSLAValue();
						$KPITotals['Operator'] = $KPI->getOperator();
						$KPITotals['Caption'] = $KPI->getCaption();
						$KPITotals['Actual'] = 'N/A';	
						
				}		
				
				//echo "<pre>";print_r($KPITotals);echo "</br>";echo "</br>";exit;
				$KPIResults[] = $KPITotals;		
			
			//echo $sumCount1."==".$sumCount2."</br></br>";
			
			}
			
			//echo "<pre>";print_r($KPIResults);echo "</br>";echo "</br>";exit;
			
		if(!empty($conn))
			oci_close($conn);
			
			
		}
		
		/* KPI Based Projects Data */
		
		else {	
			
			
		//	if(!conn){
			$this->oracle->openConnection('KPIDASHBOARD');
			$conn = $this->oracle->getConnection();
			//}
			
			$KPIResult = array();
			$KPIDetailResults = array();
			
			$queryKPIParameter = "SELECT * FROM KPI_PARAMETER WHERE KPI_ID = '".$KPIID."'";
			$queryKPIParameterParse = oci_parse($conn, $queryKPIParameter);
			oci_execute($queryKPIParameterParse);
			
			$KPIData = oci_fetch_array($queryKPIParameterParse);
			
			$KPIResults = $this->getKPIProjectData($KPIData, $month);
			
			
		}
		
		//echo "<pre>";print_r($KPIResults);exit;
		
		
		//oci_free_statement($queryParse1);
		//oci_free_statement($queryParse2);		
		
		//$this->oracle->closeConnection($conn);
		//oci_close($conn);
		return $KPIResults;
		
	}	
	
	
	private function getKPIProjectData($KPIResult, $Month){
		
		$conn = $this->oracle->getConnection();
		
		/* QC PROJECTS RESULT */			
		
		if($KPIResult['QC'] == 1){
		
			if($KPIResult['SCOPE'] != 'Both'){
				if($KPIResult['SCOPE'] == 'Dev')
					$KPIProjectScope = 'Dev';
				else if($KPIResult['SCOPE'] == 'Testing')
					$KPIProjectScope = 'Testing';
			} else
				$KPIProjectScope = 'Both';
					
				$queryProjects = "SELECT DISTINCT(PROJECTNAME) AS PROJECTNAME, PROJECTID, CYCLE_ID, QC_TABLENAME,
							REUSABILITY, SCOPE, ESTIMATED_PROD_LIVE_DATE  FROM KPI_PROJECTS WHERE ACTIVE = 1 AND CYCLE_ID IS NOT NULL
							AND TO_CHAR(ESTIMATED_PROD_LIVE_DATE, 'MON/YY') LIKE '%".$Month."%'";
				 $queryProjects .= " ORDER BY ESTIMATED_PROD_LIVE_DATE";
				 	

				 $queryProjectsParse = oci_parse($conn, $queryProjects);
				 oci_execute($queryProjectsParse);
				 $ProjectResult = array();
				 	
				 while($ProjectResultTemp = oci_fetch_array($queryProjectsParse, OCI_ASSOC+OCI_RETURN_NULLS)){
				 	$ProjectResult[] = $ProjectResultTemp;
				 }
				 	
				 if(count($ProjectResult) > 0){
				 		
				 	if(!empty($conn))
				 		oci_close($conn);
				 			
					for($i=0;$i<count($ProjectResult);$i++){

						$count1 = 0;
						$count2 = 0;
						$sumCount1 = 0;
						$sumCount2 = 0;
							
							
						if($ProjectResult[$i]['CYCLE_ID'] != null ){
							$count1Arr = array();
							$count2Arr = array();
								
							//if(!conn){
							$this->oracle->openConnection('QC');
							$conn = $this->oracle->getConnection();
							//}
								
							$this->oracle->openConnection('QC');
							$conn = $this->oracle->getConnection();
								
							$query1 = $this->constants->getKPIQuery($KPIResult['KPI_SHORT_NAME'], $ProjectResult[$i]['QC_TABLENAME'], trim($ProjectResult[$i]['CYCLE_ID']), 1);
							$query2 = $this->constants->getKPIQuery($KPIResult['KPI_SHORT_NAME'], $ProjectResult[$i]['QC_TABLENAME'], trim($ProjectResult[$i]['CYCLE_ID']), 2);
								
							//echo $query1."</br></br>";
							//echo $query2."</br></br>";
								
							//echo "</br></br></br></br>";
								
							$queryParse1 = oci_parse($conn, $query1);
							oci_execute($queryParse1);
							$count1Arr = oci_fetch_array($queryParse1);
							//print_r($count1Arr);exit;
							$count1 = $count1Arr[0];//exit;
								
							$sumCount1 = $sumCount1 + $count1;
								
								
							$queryParse2 = oci_parse($conn, $query2);
							oci_execute($queryParse2);
							$count2Arr = oci_fetch_array($queryParse2);
							$count2 = $count2Arr[0];
								
							$sumCount2 = $sumCount2 + $count2;
								
						}

						//echo $count1."==".$count2."</br></br>";//exit;
						/*
						 if(!empty($conn))
						 	oci_close($conn);


						 	$this->oracle->openConnection('KPIDASHBOARD');
						 	$conn = $this->oracle->getConnection();

						 	$KPIProjectRelation = array();
						 	$queryKPIProject = "SELECT CAUSE, ACTION FROM KPI_PROJECT_RELATION
								WHERE PROJECT_ID = ".$ProjectResult[$i]['PROJECTID']." AND KPI_ID = ".$KPIID;

								//echo $queryKPIProject;exit;
								$queryKPIProjectParse = oci_parse($conn, $queryKPIProject);
								oci_execute($queryKPIProjectParse);

								$KPIProjectRelation = oci_fetch_array($queryKPIProjectParse);
								*/
						 	
						 $KPITotals['Project'] = $ProjectResult[$i]['PROJECTNAME'];
						 $KPITotals['ProjectID'] = $ProjectResult[$i]['PROJECTID'];
						 $KPITotals['Target'] = $KPIResult['SLA_VALUE'];
						 $KPITotals['Operator'] = $KPIResult['OPERATOR'];

						 //$KPITotals['Cause'] = $KPIProjectRelation['CAUSE'];
						 //$KPITotals['Action'] = $KPIProjectRelation['ACTION'];

						 $Values = $this->constants->getKPINomDenom($KPIResult['KPI_NAME']);
						 $KPITotals['Formula'] = html_entity_decode("Numerator-".$Values[0]." - (".$sumCount1.")&#013;Denominator-".$Values[1]." - (".$sumCount2.")");


						 /* Condition to ignore non-matching Scope projects in the Calculation */

						 if($KPIProjectScope == $ProjectResult[$i]['SCOPE'] ||
						 		($KPIProjectScope == 'Both' && ($ProjectResult[$i]['SCOPE'] == 'Dev' || $ProjectResult[$i]['SCOPE'] == 'Testing')) ||
						 		($ProjectResult[$i]['SCOPE'] == 'Both' && ($KPIProjectScope == 'Dev' || $KPIProjectScope == 'Testing'))	){

						 			if($sumCount2 != 0){

						 				$KPITemp = ($sumCount1 / $sumCount2) * 100;
						 				$KPITotals['Actual'] = number_format($KPITemp, 2);

						 			}	else {

						 				$KPITotals['Actual'] = 0;

						 			}
						 } else
						 	$KPITotals['Actual'] = 'N/A';

						 		
						 	//echo $sumCount2."==".$sumCount1."</br></br>";
						 	//print_r($KPITotals);exit;
						 	$KPIResults[] = $KPITotals;

					}
				 }
		
				if(!empty($conn))
					oci_close($conn);
							
		}
			
		/* NON - QC PROJECTS RESULT */
			
		else {
		
			$this->oracle->openConnection('KPIDASHBOARD');
			$conn = $this->oracle->getConnection();
		
			/*
			 P1 & P2 - 6
			 P3 & P4 - 7
			 P1 & P2 + 60 days - 17
			 P3 & P4 + 60 days - 18
			 P1 & P2 + 61 days - 365 days  - 19
			 P3 & P4 + 61 days - 365 days - 20
			 */
		
			$KPID = $KPIResult['KPI_ID'];
			//echo $Month;exit;
		
			switch($KPID){
				
				case "5" :
						
					break;
						
				case "8" :
						
					break;
						
				case "9" :
						
					break;
						
				case "10" :
						
					break;
						
				case "11" :
						
					break;
						
				case "12" :
						
					break;
				case "13" :
						
					break;
						
				case "14" :
						
					break;
						
				case "15" :
		
					break;
		
				case "16" :
		
					break;
						
				/* Defect free deliveries (P1 and P2) */
				case "6" :
					$KPIResults = $this->getP1P2KPIResult($KPID, $Month);
					//echo "<pre>";print_r($DefectbasedKPIResult);exit;					
					break;
						
					/* # of Defect (P3 and P4) */
				case "7" :
					$KPIResults = $this->getP3P4KPIResult($KPID, $Month);
					//echo "<pre>";print_r($DefectbasedKPIResult);exit;
					break;					
		
					/* Defect Density (P1 and P2) - Production + 60 days) */
				case "17" :
					$KPIResults = $this->get60KPIResult($KPID, $Month, "P1_P2");
					break;
		
					/* Defect Density (P3 and P4) - Production + 60 days */
				case "18" :
					$KPIResults = $this->get60KPIResult($KPID, $Month, "P3_P4");
					break;
						
					/* Defect Density (P1 and P2) - Production + 61 days to 365 days */
				case "19" :
						
					break;
						
					/* Defect Density (P3 and P4) - Production + 61 days to 365 days */
				case "20" :
		
					break;
						
			}
			
			
		
		}
		
		//echo "<pre>";print_r($KPIResults);exit;
		return $KPIResults;
		
	}	
	
	private function get60KPIResult($KPID, $Month, $DefectType){
		$conn = $this->oracle->getConnection();
		
		if(!empty($Month)){
			
			/* Get previous 2 months for production + 60 days calculation */
			/*$monthSplit = explode("-", $Month);
			$month = date("Y-m", strtotime( date( 'Y-m-01' )));
			*/
			
			$month = date("Y-m", strtotime( date( 'Y-m-01' )." -1 months"));
			$month1 = strtoupper(date("M/y", strtotime($month)));
			
			$month = date("Y-m", strtotime( date( 'Y-m-01' )." -2 months"));
			$month2 = strtoupper(date("M/y", strtotime($month)));				
			
			$months = array($month1, $month2);
			//print_r($months);exit;
			
			if($DefectType == 'P1_P2')
				$fetchColumn = 'P1_P2';
			else
				$fetchColumn = 'P3_P4';
			
			//for($i=0;$i<count($months);$i++){
			foreach($months as $month){				
				$query = "SELECT PROJECTID, PROJECTNAME, SCOPE, ".$fetchColumn."
						FROM KPI_PROJECTS WHERE
						TO_CHAR(ESTIMATED_PROD_LIVE_DATE, 'MON/YY') LIKE '%".$month."%'
						AND ACTIVE = 1";	
				
				//echo $query."<br>";

				$queryParse = oci_parse($conn, $query);
				oci_execute($queryParse);
				
				$DefectsResult[$month] = array();
				$DefectsResultTemp = array();
				$ProjectsTemp = array();
				
				while ($Projects = oci_fetch_array($queryParse, OCI_ASSOC+OCI_RETURN_NULLS)) {					
					
					/* This KPI considers only Projects within DEV scope */
					
					$ProjectsTemp['PROJECTID'] = $Projects['PROJECTID'];
					$ProjectsTemp['PROJECTNAME'] = $Projects['PROJECTNAME'];
					
					
					if($Projects['SCOPE'] == 'Dev' || $Projects['SCOPE'] == 'Both' ){
						$ProjectsTemp[$fetchColumn] = $Projects[$fetchColumn];
					} else {
						$ProjectsTemp[$fetchColumn] = 'N/A';
					}
					$DefectsResultTemp[] = $ProjectsTemp;
				}
				
				$DefectsResult[$month] = $DefectsResultTemp;				
				
			}
			
		}
		
		//echo "<pre>";print_r($DefectsResult);exit;
		
		return $DefectsResult;
		
	}
	
	private function getP1P2KPIResult($KPID, $Month){		
		
		$conn = $this->oracle->getConnection();
		
		$query = "SELECT PROJECTID, PROJECTNAME, P1_P2
					FROM KPI_PROJECTS WHERE
					TO_CHAR(ESTIMATED_PROD_LIVE_DATE, 'MON/YY') LIKE '%".$Month."%'
					AND ACTIVE = 1";
			
		
		$queryParse = oci_parse($conn, $query);
		oci_execute($queryParse);
		$DefectsResult[$Month] = array();
		$DefectsResultTemp = array();
		$ProjectsTemp = array();
			
		while ($Projects = oci_fetch_array($queryParse, OCI_ASSOC+OCI_RETURN_NULLS)) {
			$ProjectsTemp['PROJECTID'] = $Projects['PROJECTID'];
			$ProjectsTemp['PROJECTNAME'] = $Projects['PROJECTNAME'];
			$ProjectsTemp['P1_P2'] = $Projects['P1_P2'];			
			$DefectsResultTemp[] = $ProjectsTemp;			
		}
		
		$DefectsResult[$Month] = $DefectsResultTemp;
		
		return $DefectsResult;
		
	}
	
	private function getP3P4KPIResult($KPID, $Month){	
		
		$conn = $this->oracle->getConnection();
		
		$query = "SELECT PROJECTID, PROJECTNAME, P3_P4
					FROM KPI_PROJECTS WHERE
					TO_CHAR(ESTIMATED_PROD_LIVE_DATE, 'MON/YY') LIKE '%".$Month."%'
					AND ACTIVE = 1";			
		
		$queryParse = oci_parse($conn, $query);
		oci_execute($queryParse);
		$DefectsResult[$Month] = array();
		$DefectsResultTemp = array();
		$ProjectsTemp = array();
			
		while ($Projects = oci_fetch_array($queryParse, OCI_ASSOC+OCI_RETURN_NULLS)) {
			$ProjectsTemp['PROJECTID'] = $Projects['PROJECTID'];
			$ProjectsTemp['PROJECTNAME'] = $Projects['PROJECTNAME'];
			$ProjectsTemp['P3_P4'] = $Projects['P3_P4'];
			$DefectsResultTemp[] = $ProjectsTemp;
		}
		
		$DefectsResult[$Month] = $DefectsResultTemp;		
		return $DefectsResult;
	
	}
		
	//public function updateKPIAction($userID, $column, $newVal, $KPIID, $ProjectID){
	public function updateKPIAction($userID, $newVal, $KPIID, $month){
		
		$this->oracle->openConnection('KPIDASHBOARD');
		$conn = $this->oracle->getConnection();
		
		$query = "SELECT COUNT(*) AS COUNT FROM KPI_CAUSE_ACTION
				WHERE KPI_ID = ".$KPIID." AND ADDED_ON LIKE '%".$month."%'";
		$queryParse = oci_parse($conn, $query);		
		oci_execute($queryParse);
		$row = oci_fetch_array($queryParse);
		
		if($row['COUNT'] > 0){		
			$query = "UPDATE KPI_CAUSE_ACTION SET
					".$column." = '".trim(addslashes($newVal))."',
					USERID = ".$userID."
					WHERE KPI_ID = ".$KPIID." AND ADDED_ON LIKE '%".$month."%'";
			
		}
		else {
			$query = "INSERT INTO KPI_CAUSE_ACTION (".$column.", KPI_ID, USERID, ADDED_ON)
					VALUES ('".trim(addslashes($newVal))."', ".$KPIID.", ".$userID.", ".$month.")";
		
			//echo $query;exit;
		}
		
		//echo $query;
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
	
	
}
