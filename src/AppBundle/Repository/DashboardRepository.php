<?php

namespace AppBundle\Repository;

use AppBundle\Entity\KPI;
use AppBundle\Entity\Projects;
use AppBundle\Service\Constants;
use AppBundle\Service\OracleDatabaseService;
use Symfony\Component\Config\Definition\Exception\Exception;

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
	
	public function getSLAs($Weekly = ""){	
		$this->oracle->openConnection('KPIDASHBOARD');
		$conn = $this->oracle->getConnection();
		
		$query = "SELECT * FROM KPI_PARAMETER WHERE ACTIVE = 1";
				
		if(isset($Weekly) && $Weekly == 1)
			$query .= " AND WEEKLY = 1";
				
		$query .= " ORDER BY DISPLAY_ORDER";
		
		//echo $query;exit;
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
	
	public function getKPIResults($month = "", $KPIID = "", $Weekly = ""){
		
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
			//$KPIs = $this->getSLAs($Weekly);
			$KPIs = $this->getSLAs(1);
			//echo "<pre>";print_r($KPIs);exit;
			
			foreach($KPIs as $KPI){
				
				$ProjectScope = '';
				$count1 = 0;
				$count2 = 0;
				$sumCount1 = 0;
				$sumCount2 = 0;
				
				$this->oracle->openConnection('KPIDASHBOARD');
				$conn = $this->oracle->getConnection();
				
				/* QC Based KPI Calculation */
				
				if($KPI->getInQC() == 1){

						//echo $KPI->getKPIShortName()."</br></br>";
					//if(empty($conn)){
						//echo 'in if===';exit;
				
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
						REUSABILITY, SCOPE, ESTIMATED_PROD_LIVE_DATE, UAT_APPLICABLE 
						FROM KPI_PROJECTS WHERE ACTIVE = 1 AND CYCLE_ID IS NOT NULL
						AND TO_CHAR(ESTIMATED_PROD_LIVE_DATE, 'MON/YY') LIKE '%".$month."%'";				
					
					$queryProjects .= " ORDER BY PROJECTNAME";
					
					//echo $queryProjects;exit;
					$queryProjectsParse = oci_parse($conn, $queryProjects);
					oci_execute($queryProjectsParse);
					$ProjectResult = array();
					
					while($ProjectResultTemp = oci_fetch_array($queryProjectsParse, OCI_ASSOC+OCI_RETURN_NULLS)){
						$ProjectResult[] = $ProjectResultTemp;
					}
					
					$ProjNAApplicable = '';
					
					if(count($ProjectResult) > 0){
						
						if(!empty($conn))
							oci_close($conn);
						
						for($i=0;$i<count($ProjectResult);$i++){							
							
							//echo $ProjectResult['QC_TABLENAME']."****".$ProjectResult['CYCLE_ID']."====<BR>";				
							if($ProjectResult[$i]['CYCLE_ID'] != null ){								
								
								$count1Arr = array();
								$count2Arr = array();
								
								$this->oracle->openConnection('QC');
								$conn = $this->oracle->getConnection();

								/* Consider Project only if scope matches to KPI scope */
								
								if($KPIProjectScope == $ProjectResult[$i]['SCOPE'] ||
										($KPIProjectScope == 'Both' && ($ProjectResult[$i]['SCOPE'] == 'Dev' || $ProjectResult[$i]['SCOPE'] == 'Testing')) ||
										($ProjectResult[$i]['SCOPE'] == 'Both' && ($KPIProjectScope == 'Dev' || $KPIProjectScope == 'Testing'))	){
									$ProjNAApplicable = 0;
								} else {
									$ProjNAApplicable = 1;
									$ProjNAReason = 'Project Scope does not match to KPI Scope !!!';
								}
								
								/* Consider Project for UAT Defect Density only if UAT Applicable is Yes */
								
								if($KPI->getKPIID() == 4){
									if($ProjectResult[$i]['UAT_APPLICABLE'] == 'Yes')
										$ProjNAApplicable = 0;
									else {
										$ProjNAApplicable = 1;
										$ProjNAReason = 'Project is not set for UAT Applicable !!!';
									}								
								}
								
								
								/* Condition to ignore non-matching Scope projects in the Calculation */
								
								if($ProjNAApplicable == 0){		
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
								
							}			
													
						}
						
				
						//echo $sumCount1."==".$sumCount2."</br></br>";
						
						$KPITotals['KPI'] = $KPI->getKPIName();
						$KPITotals['KPIID'] = $KPI->getKPIID();
						$KPITotals['Target'] = $KPI->getSLAValue();
						$KPITotals['Operator'] = $KPI->getOperator();
						$KPITotals['Caption'] = $KPI->getCaption();
							
						//if($ProjNAApplicable == 0){
							if($sumCount2 != 0){
								$KPITemp = ($sumCount1 / $sumCount2) * 100;
								$KPITotals['Actual'] = number_format($KPITemp, 2)."%";
							}
							else {
								$KPITotals['Actual'] = '0.00%';
							}
						/*} else
							$KPITotals['Actual'] = 'N/A';
						*/
						
						$this->oracle->closeConnection();
						
						$this->oracle->openConnection('KPIDASHBOARD');
						$conn = $this->oracle->getConnection();
							
						/* Fetch Cause/Action for this KPI for this month */
							
						$queryKPIProject = "SELECT CAUSE, ACTION
						FROM KPI_CAUSE_ACTION
						WHERE KPI_ID = ".$KPI->getKPIID()."
						AND MONTHLY = 1
						AND MONTH = '".$month."'";
					
						//echo $queryKPIProject;exit;
						$queryKPIProjectParse = oci_parse($conn, $queryKPIProject);
						oci_execute($queryKPIProjectParse);
					
						$KPICauseAction = oci_fetch_array($queryKPIProjectParse);
							
						$KPITotals['CAUSE'] = $KPICauseAction['CAUSE'];
						$KPITotals['ACTION'] = $KPICauseAction['ACTION'];
							
					} else {
						
						//echo 'in else';exit;
						$KPITotals['KPI'] = $KPI->getKPIName();
						$KPITotals['KPIID'] = $KPI->getKPIID();
						$KPITotals['Target'] = $KPI->getSLAValue();
						$KPITotals['Operator'] = $KPI->getOperator();
						$KPITotals['Caption'] = $KPI->getCaption();
						$KPITotals['Actual'] = 'Unknown';
						
						//print_r($KPITotals);exit;
						
					}
					
					//exit;
					//echo $sumCount2."==".$sumCount1."</br></br>";exit;
					
				}
				
				// NON-QC PROJECTS RESULT
				
				else {				
						$KPITotals['KPI'] = $KPI->getKPIName();
						$KPITotals['KPIID'] = $KPI->getKPIID();
						$KPITotals['Target'] = $KPI->getSLAValue();
						$KPITotals['Operator'] = $KPI->getOperator();
						$KPITotals['Caption'] = $KPI->getCaption();
						$KPID = $KPI->getKPIID();
						
						/* Fetch Cause/Action for this KPI for this month */
								
						$queryKPIProject = "SELECT CAUSE, ACTION
							FROM KPI_CAUSE_ACTION
							WHERE KPI_ID = ".$KPID."
							AND MONTHLY = 1
							AND MONTH = '".$month."'";
						
						//echo $queryKPIProject;exit;
						$queryKPIProjectParse = oci_parse($conn, $queryKPIProject);
						oci_execute($queryKPIProjectParse);
					
						$KPICauseAction = oci_fetch_array($queryKPIProjectParse);
							
						$KPITotals['CAUSE'] = $KPICauseAction['CAUSE'];
						$KPITotals['ACTION'] = $KPICauseAction['ACTION'];
						
						/* === */
						
						switch($KPID){
						
							/* Delivery Slippage */
							case "5" :
								$KPITotals['Actual'] = $this->getDeliverySlippageResult($KPID, $month, 1);								
								break;
								
							case "8" :
								$KPITotals['Actual'] = $this->getTDCEResult($KPID, $month, 1);
								break;
								
							/*case "9" :
								$KPIResults = $this->getAutomationResult($KPID, $month, 1);
								break;
							*/
							case "10" :
								$KPITotals['Actual'] = $this->getReusabilityResult($KPID, $month, 1);
								break;
						
							/* Intake Process */
							case "11" :
								$KPITotals['Actual'] = $this->getIntakeProcessResult($KPID, $month, 1);
								break;
									
							/* Quality Estimation */
							case "12" :
								$KPITotals['Actual'] = $this->getQualityEstimationResult($KPID, $month, 1);
								break;
									
							/* ST Automation */
							case "13" :
								$KPITotals['Actual'] = $this->getSTAutomationResult($KPID, $month, 1);
								break;
						
							/* Production Test Accounts */
							case "14" :
								$KPITotals['Actual'] = $this->getProductionTestAccountResult($KPID, $month, 1);
								break;
						
							/* Documentation */
							case "15" :
								$KPITotals['Actual'] = $this->getDocumentationResult($KPID, $month, 1);
								break;
						
							/* Knowledge Management */
							case "16" :
								$KPITotals['Actual'] = $this->getDocumentationResult($KPID, $month, 1, 1);
								break;
						
							/* Defect free deliveries (P1 and P2) */
							case "6" :
								$KPITotals['Actual'] = $this->getP1P2KPIResult($KPID, $month, 1);								
								break;
						
							/* # of Defect (P3 and P4) */
							case "7" :
								$KPITotals['Actual'] = $this->getP3P4KPIResult($KPID, $month, 1);
								break;
						
							/* Defect Density (P1 and P2) - Production + 60 days) */
							case "17" :
								$KPITotals['Actual'] = $this->get60KPIResult($KPID, $month, "P1_P2", 1);
								break;
						
							/* Defect Density (P3 and P4) - Production + 60 days */
							case "18" :
								$KPITotals['Actual'] = $this->get60KPIResult($KPID, $month, "P3_P4", 1);
								break;
						
							/* Defect Density (P1 and P2) - Production + 61 days to 365 days */
							case "19" :
								$KPITotals['Actual'] = $this->get365KPIResult($KPID, $month, "P1_P2", 1);
								break;
						
							/* Defect Density (P3 and P4) - Production + 61 days to 365 days */
							case "20" :
								$KPITotals['Actual'] = $this->get365KPIResult($KPID, $month, "P3_P4", 1);
								break; 
							
							default :
								$KPITotals['Actual'] = 'Unknown';
								break;
						
						}
							
						
						
				}		
				
				
				$KPIResults[] = $KPITotals;		
			
			//echo $sumCount1."==".$sumCount2."</br></br>";
			
			}
			
			//echo "<pre>";print_r($KPIResults);echo "</br>";echo "</br>";exit;
			
		if(!empty($conn))
			oci_close($conn);
			
		
		
		}
		
		/* KPI Based Projects Data */
		
		else {	
			
			$this->oracle->openConnection('KPIDASHBOARD');
			$conn = $this->oracle->getConnection();
			
			$KPIResult = array();
			$KPIDetailResults = array();
			
			$queryKPIParameter = "SELECT * FROM KPI_PARAMETER WHERE KPI_ID = '".$KPIID."'";
			$queryKPIParameterParse = oci_parse($conn, $queryKPIParameter);
			oci_execute($queryKPIParameterParse);
			
			$KPIData = oci_fetch_array($queryKPIParameterParse);
			
			$KPIResults = $this->getKPIProjectData($KPIData, $month);
			
			
		}		
	
		return $KPIResults;
		
	}	
	

	
	private function getKPIProjectData($KPIResult, $Month){
		
		$conn = $this->oracle->getConnection();
		$KPIResults = array();		
		
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
							REUSABILITY, SCOPE, ESTIMATED_PROD_LIVE_DATE, UAT_APPLICABLE  
							FROM KPI_PROJECTS 
							WHERE ACTIVE = 1
							AND CYCLE_ID IS NOT NULL
							AND TO_CHAR(ESTIMATED_PROD_LIVE_DATE, 'MON/YY') LIKE '%".$Month."%'
							ORDER BY PROJECTNAME";				 
				 	

				 $queryProjectsParse = oci_parse($conn, $queryProjects);
				 oci_execute($queryProjectsParse);
				 $ProjectResult = array();
				 	
				 while($ProjectResultTemp = oci_fetch_array($queryProjectsParse, OCI_ASSOC+OCI_RETURN_NULLS)){
				 	$ProjectResult[] = $ProjectResultTemp;
				 }
				 
				 //print_r($ProjectResult);exit;
				 if(count($ProjectResult) > 0){
				 		
				 	if(!empty($conn))
				 		oci_close($conn);
				 			
					for($i=0;$i<count($ProjectResult);$i++){
						$ProjNAApplicable = '';
						$ProjNAReason = '';
						
						$count1 = 0;
						$count2 = 0;
						$sumCount1 = 0;
						$sumCount2 = 0;
						
						/* Consider Project only if scope matches to KPI scope */
						
						if($KPIProjectScope == $ProjectResult[$i]['SCOPE'] ||
								($KPIProjectScope == 'Both' && ($ProjectResult[$i]['SCOPE'] == 'Dev' || $ProjectResult[$i]['SCOPE'] == 'Testing')) ||
								($ProjectResult[$i]['SCOPE'] == 'Both' && ($KPIProjectScope == 'Dev' || $KPIProjectScope == 'Testing'))	){
							$ProjNAApplicable = 0;
						} else {
							$ProjNAApplicable = 1;
							$ProjNAReason = 'Project Scope does not match to KPI Scope !!!';
						}
						
						/* Consider Project for KPI - UAT Defect Density only if UAT Applicable is Yes */
						
						if($KPIResult['KPI_ID'] == 4){
							if($ProjectResult[$i]['UAT_APPLICABLE'] == 'Yes')
								$ProjNAApplicable = 0;
							else {
								$ProjNAApplicable = 1;
								$ProjNAReason = 'Project is not set for UAT Applicable !!!';
							}
						}
						
						if($ProjNAApplicable == 0){
							
							if($ProjectResult[$i]['CYCLE_ID'] != null ){
								$count1Arr = array();
								$count2Arr = array();						
									
								$this->oracle->openConnection('QC');
								$conn = $this->oracle->getConnection();
								
								/* Fetch QC data only if scope matches else NA will be applied */
								
								//if($ProjNAApplicable == 0){
									
									$query1 = $this->constants->getKPIQuery($KPIResult['KPI_SHORT_NAME'], $ProjectResult[$i]['QC_TABLENAME'], trim($ProjectResult[$i]['CYCLE_ID']), 1);
									$query2 = $this->constants->getKPIQuery($KPIResult['KPI_SHORT_NAME'], $ProjectResult[$i]['QC_TABLENAME'], trim($ProjectResult[$i]['CYCLE_ID']), 2);
										
									//echo $ProjectResult[$i]['PROJECTNAME']."</br></br>";
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
								//}
									
							}
	
							//echo $count1."==".$count2."</br></br>";//exit;
							
							if(!empty($conn))
								oci_close($conn);
	
	
						 	$this->oracle->openConnection('KPIDASHBOARD');
						 	$conn = $this->oracle->getConnection();
	
						 	$KPIProjectRelation = array();
						 	$queryKPIProject = "SELECT CAUSE, ACTION FROM KPI_CAUSE_ACTION
								WHERE PROJECT_ID = ".$ProjectResult[$i]['PROJECTID']." AND KPI_ID = ".$KPIResult['KPI_ID'];
	
								//echo $queryKPIProject;exit;
							$queryKPIProjectParse = oci_parse($conn, $queryKPIProject);
							oci_execute($queryKPIProjectParse);
	
							$KPIProjectRelation = oci_fetch_array($queryKPIProjectParse);
								
							 	
							 $KPITotals['Project'] = $ProjectResult[$i]['PROJECTNAME'];
							 $KPITotals['ProjectID'] = $ProjectResult[$i]['PROJECTID'];
							 $KPITotals['Target'] = $KPIResult['SLA_VALUE'];
							 $KPITotals['Operator'] = $KPIResult['OPERATOR'];
	
							 $KPITotals['Cause'] = $KPIProjectRelation['CAUSE'];
							 $KPITotals['Action'] = $KPIProjectRelation['ACTION'];
	
							 $Values = $this->constants->getKPINomDenom($KPIResult['KPI_NAME']);
							 
							 //$KPITotals['Numerator'] = $Values[0];
							// $KPITotals['Denominator'] = $Values[1];
							 
							 $KPITotals['NumeratorValue'] = $sumCount1;
							 $KPITotals['DenominatorValue'] = $sumCount2;						 
							 
							 	
							 if(!empty($ProjNAReason)){
							 	$KPITotals['Formula'] = html_entity_decode("Numerator-".$Values[0]." - (".$sumCount1.")&#013;Denominator-".$Values[1]." - (".$sumCount2.")&#013;Why N/A - ".$ProjNAReason);
							 } else {
							 	$KPITotals['Formula'] = html_entity_decode("Numerator-".$Values[0]." - (".$sumCount1.")&#013;Denominator-".$Values[1]." - (".$sumCount2.")");
							 }
	
							 /* Condition to ignore NA applicable projects in the Calculation */
	
							 //if($ProjNAApplicable == 0){
							 	
						 			if($sumCount2 != 0){
						 				$KPITemp = ($sumCount1 / $sumCount2) * 100;
						 				$KPITotals['Actual'] = number_format($KPITemp, 2);
						 			}	else {
						 				$KPITotals['Actual'] = '0.00';
						 			}
						 			
							// } 
						 	$KPITotals['Reason'] = '';
						} else {
							
							$KPITotals['Project'] = $ProjectResult[$i]['PROJECTNAME'];
							$KPITotals['ProjectID'] = $ProjectResult[$i]['PROJECTID'];
							$KPITotals['Target'] = $KPIResult['SLA_VALUE'];
							$KPITotals['Operator'] = $KPIResult['OPERATOR'];
							
						 	$KPITotals['Actual'] = 'N/A';
						 	$KPITotals['NumeratorValue'] = 'N/A';
						 	$KPITotals['DenominatorValue'] = 'N/A';
						 	$KPITotals['Cause'] = 'N/A';
						 	$KPITotals['Action'] = 'N/A';
						 	$KPITotals['Formula'] = 'N/A';
						 	$KPITotals['Reason'] = 'Project is not within this KPI Scope.';
						}

						 		
						 	//echo $sumCount2."==".$sumCount1."</br></br>";
						 	//echo "<pre>";print_r($KPITotals);//exit;
						 	$KPIResults[] = $KPITotals;

					}
				 } else 
				 	$ProjectResult = array();
		
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
				
				/* Delivery Slippage */
				case "5" :						
					$KPIResults = $this->getDeliverySlippageResult($KPID, $Month);										
					break;
						
				case "8" :
					$KPIResults = $this->getTDCEResult($KPID, $Month);
					break;
						
				/*case "9" :
					$KPIResults = $this->getAutomationResult($KPID, $Month);					
					break;
					*/	
				case "10" :
					$KPIResults = $this->getReusabilityResult($KPID, $Month);
					break;			
	
				/* Intake Process */		
				case "11" :
					$KPIResults = $this->getIntakeProcessResult($KPID, $Month);
					break;
					
				/* Quality Estimation */
				case "12" :
					$KPIResults = $this->getQualityEstimationResult($KPID, $Month);
					break;
					
				/* ST Automation */
				case "13" :
					$KPIResults = $this->getSTAutomationResult($KPID, $Month);
					break;
				
				/* Production Test Accounts */
				case "14" :
					$KPIResults = $this->getProductionTestAccountResult($KPID, $Month);					
					break;
						
				/* Documentation */
				case "15" :
					$KPIResults = $this->getDocumentationResult($KPID, $Month);
					break;
		
				/* Knowledge Management */
				case "16" :
					$KPIResults = $this->getDocumentationResult($KPID, $Month, "", 1);
					break;
						
				/* Defect free deliveries (P1 and P2) */
				case "6" :
					$KPIResults = $this->getP1P2KPIResult($KPID, $Month);										
					break;
						
				/* # of Defect (P3 and P4) */
				case "7" :
					$KPIResults = $this->getP3P4KPIResult($KPID, $Month);
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
					$KPIResults = $this->get365KPIResult($KPID, $Month, "P1_P2");
					break;
						
				/* Defect Density (P3 and P4) - Production + 61 days to 365 days */
				case "20" :
					$KPIResults = $this->get365KPIResult($KPID, $Month, "P3_P4");
					break;
					
				default :
					return 'Unknown';
				break;
			}
			
			
		
		}
		
		//echo "<pre>";print_r($KPIResults);exit;
		if(!empty($conn))	
			oci_close($conn);
		
		return $KPIResults;
		
	}	
	private function getSTAutomationResult($KPID, $Month, $Monthly = ""){
		
		if(isset($conn))
			$conn = $this->oracle->getConnection();
		else {
			$this->oracle->openConnection('KPIDASHBOARD');
			$conn = $this->oracle->getConnection();
		}
		
		if(!empty($Month)){
			$STAutoResult[$Month] = array();
			$STAutoResultTemp = array();			
			
			$NoofAutomatedTC = 0;
			$TotalAutomatecTC = 0;
			$dataCount = 0;
			
			$NoofAutomatedTCArr = array();
			$TotalAutomatecTCArr = array();
			
			$KPIData = $this->getKPIData($KPID);
			$KPIOperator = $KPIData['OPERATOR'];
			$KPISLA = $KPIData['SLA_VALUE'];			
			
			$query = "SELECT PROJECTID, PROJECTNAME, NO_OF_ST_AUTOMATED_TEST_CASES, TOTAL_NO_OF_ST_TEST_CASES, 
					ST_AUTOMATION, SCOPE, ST_AUTOMATION_APPLICABLE 
					FROM KPI_PROJECTS 
					WHERE ACTIVE = 1 AND TO_CHAR(ESTIMATED_PROD_LIVE_DATE, 'MON/YY') LIKE '%".$Month."%' ";
		
			$queryParse = oci_parse($conn, $query);
			oci_execute($queryParse);
		
			while ($row = oci_fetch_array($queryParse, OCI_ASSOC+OCI_RETURN_NULLS)) {
				
				$STAutoTemp = array();
				
				$STAutoTemp['PROJECTID'] =  $row ['PROJECTID'];
				$STAutoTemp['PROJECTNAME'] =  $row ['PROJECTNAME'];			
								
		
				if($row['SCOPE'] == 'Dev' || $row['SCOPE'] == 'Both' ){
					
					if($row['ST_AUTOMATION_APPLICABLE'] == 'Yes'){
						$STAutoTemp['NO_OF_ST_AUTOMATED_TEST_CASES'] = $row['NO_OF_ST_AUTOMATED_TEST_CASES'];
						$STAutoTemp['TOTAL_NO_OF_ST_TEST_CASES'] = $row['TOTAL_NO_OF_ST_TEST_CASES'];
							
						/*  Get Cause/Action for KPI for this Project` for this Month */
							
						$query1 = "SELECT CAUSE, ACTION
							FROM KPI_CAUSE_ACTION
							WHERE KPI_ID = ".$KPID."
							AND PROJECT_NAME = '".$row ['PROJECTNAME']."'
							AND MONTHLY IS NULL";
							
						$queryParse1 = oci_parse ( $conn, $query1 );
						oci_execute ( $queryParse1 );
						$resultCauseAction = oci_fetch_array($queryParse1);
							
						$STAutoTemp['CAUSE'] = $resultCauseAction['CAUSE'];
						$STAutoTemp['ACTION'] = $resultCauseAction['ACTION'];
						
						/* */
							
						$STAutoTemp['ST_AUTOMATION'] = $row['ST_AUTOMATION'];
						
						if(!empty($STAutoTemp['TOTAL_NO_OF_ST_TEST_CASES'])){
								
							$NoofAutomatedTCArr[] = $STAutoTemp['NO_OF_ST_AUTOMATED_TEST_CASES'];
							$TotalAutomatecTCArr[] = $STAutoTemp['TOTAL_NO_OF_ST_TEST_CASES'];
								
							if($KPIOperator == '>='){
								if($STAutoTemp['ST_AUTOMATION'] >= $KPISLA){
									$STAutoTemp['RAG'] = 'green';
								}
								else {
									$STAutoTemp['RAG'] = 'red';
								}
							}
							else if($KPIOperator == '<='){
								if($STAutoTemp['ST_AUTOMATION'] <= $KPISLA){
									$STAutoTemp['RAG'] = 'green';
								}
								else {
									$STAutoTemp['RAG'] = 'red';
								}
							}
								
						} else
							$STAutoTemp['RAG'] = 'yellow';
						
					} elseif ($row['ST_AUTOMATION_APPLICABLE'] == 'No') {						

						$STAutoTemp['NO_OF_ST_AUTOMATED_TEST_CASES'] = 'N/A';
						$STAutoTemp['TOTAL_NO_OF_ST_TEST_CASES'] = 'N/A';
						$STAutoTemp['ST_AUTOMATION'] = 'N/A';
						$STAutoTemp['RAG'] = 'n/a';
						
					} else {
						$STAutoTemp['RAG'] = 'yellow';
						$STAutoTemp['ST_AUTOMATION'] = '';
						$STAutoTemp['NO_OF_ST_AUTOMATED_TEST_CASES'] = '';
						$STAutoTemp['TOTAL_NO_OF_ST_TEST_CASES'] = '';
					}
					
					
					
					
// 					$STAutoTemp['NO_OF_ST_AUTOMATED_TEST_CASES'] = $row['NO_OF_ST_AUTOMATED_TEST_CASES'];
// 					$STAutoTemp['TOTAL_NO_OF_ST_TEST_CASES'] = $row['TOTAL_NO_OF_ST_TEST_CASES'];
					
// 					/*  Get Cause/Action for KPI for this Project` for this Month */
					
// 					$query1 = "SELECT CAUSE, ACTION
// 					FROM KPI_CAUSE_ACTION
// 					WHERE KPI_ID = ".$KPID."
// 					AND PROJECT_NAME = '".$row ['PROJECTNAME']."'
// 					AND MONTHLY IS NULL";
					
// 					$queryParse1 = oci_parse ( $conn, $query1 );
// 					oci_execute ( $queryParse1 );
// 					$resultCauseAction = oci_fetch_array($queryParse1);
					
// 					$STAutoTemp['CAUSE'] = $resultCauseAction['CAUSE'];
// 					$STAutoTemp['ACTION'] = $resultCauseAction['ACTION'];
						
// 					/* */
					
// 					$STAutoTemp['ST_AUTOMATION'] = $row['ST_AUTOMATION'];
					
// 					if($STAutoTemp['ST_AUTOMATION'] == 'N/A')
// 						$STAutoTemp['RAG'] = 'N/A';
// 					else {
					
// 						if(!empty($STAutoTemp['TOTAL_NO_OF_ST_TEST_CASES'])){
							
// 							$NoofAutomatedTCArr[] = $STAutoTemp['NO_OF_ST_AUTOMATED_TEST_CASES'];
// 							$TotalAutomatecTCArr[] = $STAutoTemp['TOTAL_NO_OF_ST_TEST_CASES'];
							
// 							if($KPIOperator == '>='){
// 								if($STAutoTemp['ST_AUTOMATION'] >= $KPISLA){
// 									$STAutoTemp['RAG'] = 'green';
// 								}
// 								else { 
// 									$STAutoTemp['RAG'] = 'red';
// 								}
// 							}
// 							else if($KPIOperator == '<='){
// 								if($STAutoTemp['ST_AUTOMATION'] <= $KPISLA){
// 									$STAutoTemp['RAG'] = 'green';
// 								}
// 								else {
// 									$STAutoTemp['RAG'] = 'red';
// 								}
// 							}
							
// 						} else
// 							$STAutoTemp['RAG'] = 'yellow';
// 					}
					
					$dataCount++;
					
				}				
				else {
					$STAutoTemp['NO_OF_ST_AUTOMATED_TEST_CASES'] = 'N/A';
					$STAutoTemp['TOTAL_NO_OF_ST_TEST_CASES'] = 'N/A';
					$STAutoTemp['ST_AUTOMATION'] = 'N/A';
					$STAutoTemp['RAG'] = 'n/a';
				}
				
				$STAutoResultTemp[] = $STAutoTemp;				
			}
		
			$STAutoResult[$Month] = $STAutoResultTemp;
			
		}		
		else
			$STAutoResult = array();
		
		//echo $dataCount;exit;
			//echo "<pre>";
			//print_r($NoofAutomatedTCArr);
			//echo "===";
			//print_r($TotalAutomatecTCArr);
			//exit;
		if($Monthly == 1){
			
			if(count($NoofAutomatedTCArr) > 0 && count($TotalAutomatecTCArr) > 0){
				
				//if(count($NoofAutomatedTCArr) > 0 && count($TotalAutomatecTCArr) > 0){
				$NoofAutomatedTC = array_sum($NoofAutomatedTCArr);
				$TotalAutomatecTC = array_sum($TotalAutomatecTCArr);
			
				if($TotalAutomatecTC != 0){
					$MonthlyResult = number_format((($NoofAutomatedTC / $TotalAutomatecTC) * 100), 2)."%";
				} else
					$MonthlyResult = '0%';
			} else
				
				$MonthlyResult = 'Unknown';
			
			return $MonthlyResult;
		}
		else {
			return $STAutoResult;
		}
	
	}
	
	private function getProductionTestAccountResult($KPID, $Month, $Monthly = ""){
		
		if(isset($conn))
			$conn = $this->oracle->getConnection();
		else {
			$this->oracle->openConnection('KPIDASHBOARD');
			$conn = $this->oracle->getConnection();
		}		
		
		$AccountResults[$Month] = array();
		$AccountResultTemp = array();
		$dataCount = 0;
		
		if(!empty($Month)){			
			
			$query = "SELECT *
					FROM KPI_PROD_TEST_ACCOUNTS
					WHERE MONTH_PROD_TEST_ACCOUNTS = '".$Month."'";
			
			$queryParse = oci_parse ( $conn, $query );
			oci_execute ( $queryParse );
			
			while ( $row = oci_fetch_array ( $queryParse, OCI_ASSOC + OCI_RETURN_NULLS ) ) {	
				
				$AccountResultTemp['TOTAL_ACCOUNTS'] = $row['TOTAL_ACCOUNTS'];
				$AccountResultTemp['CONSOLIDATED_TOTAL'] = $row['CONSOLIDATED_TOTAL'];
				$AccountResultTemp['PRODUCTION_TEST_ACCOUNTS'] = $row['PRODUCTION_TEST_ACCOUNTS'];
				
				if($AccountResultTemp['PRODUCTION_TEST_ACCOUNTS'] != 100){
					$AccountResultTemp['RAG'] = 'red';
				} else {
					$AccountResultTemp['RAG'] = 'green';
				}
				
				$dataCount++;
			}
			
 						
		}
		
		//echo "<pre>";print_r($AccountResultTemp);exit;

		if($Monthly == 1){
			
			if($dataCount > 0){				
				if($AccountResultTemp['PRODUCTION_TEST_ACCOUNTS'] != 100){
					$MonthlyResult = $AccountResultTemp['PRODUCTION_TEST_ACCOUNTS']."%";
				} else {
					$MonthlyResult = '100.00%';
				}				
			} else
				$MonthlyResult = 'Unknown';
		
			return $MonthlyResult;
		}
		else {
			return $AccountResultTemp;
		}
	}
	
	private function getDocumentationResult($KPID, $Month, $Monthly = "", $k = ""){
		
		if(isset($conn))
			$conn = $this->oracle->getConnection();
		else {
			$this->oracle->openConnection('KPIDASHBOARD');
			$conn = $this->oracle->getConnection();
		}
		
		if(!empty($Month)){
			$DocumentResult[$Month] = array();
			$DocumentResultTemp = array();			
			
			$nominator = 0;
			$denominator = 0;
			$anytestingdoc = 0;
			$dataCount = 0;
		
			/*
			$query = "SELECT PROJECTID, PROJECTNAME, SCOPE, DELIVERABLE, DOCUMENT_NAME, DOCUMENT_TYPE, IS_TESTING, 
				 DELIVERY_DATE, SIGN_OFF_DATE, REPOSITORY_LINK 
				 FROM KPI_PROJECTS 
				 WHERE (TO_CHAR(DELIVERY_DATE, 'MON/YY') LIKE '%".$Month."%'
				 OR TO_CHAR(SIGN_OFF_DATE, 'MON/YY') LIKE '%".$Month."%')
				 AND ACTIVE = 1 AND DELIVERABLE IS NOT NULL";
			*/
			
			$query = "SELECT DP.PROJECTID, DP.PROJECTNAME, D.* FROM
				KPI_DOCUMENT_PROJECTS DP, KPI_DOCUMENTS D 
				WHERE MONTH = '".$Month."' 
				AND DP.PROJECTID = D.PROJECTID AND D.ACTIVE = 1 AND DP.ACTIVE = 1";
		
			//echo $query;exit;
			$queryParse = oci_parse($conn, $query);
			oci_execute($queryParse);
		
			while ($row = oci_fetch_array($queryParse, OCI_ASSOC+OCI_RETURN_NULLS)) {
				
				$DocumentTemp = array();
				
				$DocumentTemp['PROJECTID'] =  $row ['PROJECTID'];
				$DocumentTemp['PROJECTNAME'] =  $row ['PROJECTNAME'];
				$DocumentTemp['DELIVERABLE'] = $row['DELIVERABLE'];								
				$DocumentTemp['DOCUMENT_NAME'] = $row['DOCUMENT_NAME'];
				$DocumentTemp['DOCUMENT_TYPE'] = $row['DOCUMENT_TYPE'];
				$DocumentTemp['IS_TESTING'] = $row['IS_TESTING'];
				$DocumentTemp['DELIVERY_DATE'] = $row['DELIVERY_DATE'];
				$DocumentTemp['SIGN_OFF_DATE'] = $row['SIGN_OFF_DATE'];
				$DocumentTemp['REPOSITORY_LINK'] = $row['REPOSITORY_LINK'];
				

				/*  Get Cause/Action for KPI for this Project` for this Month */
				
				$query1 = "SELECT CAUSE, ACTION
					FROM KPI_CAUSE_ACTION
					WHERE KPI_ID = ".$KPID."
					AND PROJECT_ID = ".$row ['PROJECTID']."
					AND MONTHLY IS NULL";
				
				$queryParse1 = oci_parse ( $conn, $query1 );
				oci_execute ( $queryParse1 );
				$resultCauseAction = oci_fetch_array($queryParse1);
				
				$DocumentTemp['CAUSE'] = $resultCauseAction['CAUSE'];
				$DocumentTemp['ACTION'] = $resultCauseAction['ACTION'];
					
				/* */			
				
				/* Knowledge Management KPI */
				
				if($k == 1){
					//$nominator++;
					$denominator++;
						
					if($DocumentTemp['REPOSITORY_LINK'] != ""){
						//$denominator++;
						$nominator++;
						$DocumentTemp['RAG'] = 'green';
						$DocumentTemp['RedReason'] = '';
					} else {
						$DocumentTemp['RAG'] = 'red';
						$DocumentTemp['RedReason'] = 'Repository Link has not been provided !!!';
					}
					
					//$DocumentTemp['SCOPE'] = $row['SCOPE'];
				} 
				
				/* Documentation KPI */
				
				else {
					
					if($DocumentTemp['IS_TESTING'] == 1){
						
						if($DocumentTemp['SIGN_OFF_DATE'] != ""){
							$nominator++;
							$DocumentTemp['RAG'] = 'green';
							$DocumentTemp['RedReason'] = '';
						} else {
							$DocumentTemp['RAG'] = 'red';
							$DocumentTemp['RedReason'] = 'Sign off date has not been provided !!!';
						}
					
						if($DocumentTemp['REPOSITORY_LINK'] != ""){
							$denominator++;
							
						}
					
						$anytestingdoc++;
						
					} else {
						$DocumentTemp['RAG'] = 'green';
						$DocumentTemp['RedReason'] = '';
					}
					
					/*
					
					if($row['SCOPE'] == 'Testing' || $row['SCOPE'] == 'Both' ){						
						$DocumentTemp['SCOPE'] = $row['SCOPE'];
					
					} else {
						$DocumentTemp['SCOPE'] = 'N/A ('.$row['SCOPE'].') '; 
					}
					
					*/
				}
					
				/* */		
				
				
				
				$DocumentResultTemp[] = $DocumentTemp;
				
				$dataCount++;
			}
				
			$DocumentResult[$Month] = $DocumentResultTemp;
		}
		
		else
			$DocumentResult = array();		
		
		//echo "final==".$nominator."==".$denominator;
		//echo "<pre>";print_r($DocumentResult);//exit;
		
		//echo $nominator."=".$denominator;
		if($Monthly == 1){
			if($dataCount > 0){
				/* Knowledge Management */
				if($k == 1){
					if($denominator != 0){
						$MonthlyResult = number_format((($nominator / $denominator) * 100), 2)."%";
					} else
						$MonthlyResult = '0%';
				}
				else {
					if($anytestingdoc != 0){					
						if($denominator != 0){
							$MonthlyResult = number_format((($nominator / $denominator) * 100), 2)."%";
						} else
							$MonthlyResult = '0%';
					} else 
						$MonthlyResult = '100.00%';
				}
			} else
				$MonthlyResult = '100.00%';
				
			return $MonthlyResult;
		}
		else {
			return $DocumentResult;
		}
		
		
	}
	
	private function getQualityEstimationResult($KPID, $Month, $Monthly = ""){
		
		if(isset($conn))
			$conn = $this->oracle->getConnection();
		else {
			$this->oracle->openConnection('KPIDASHBOARD');
			$conn = $this->oracle->getConnection();
		}
	
		if(!empty($Month)){
			$EstimationResult[$Month] = array();
			$EstimationResultTemp = array();				
			$MonthlyResult = '';
			$SLAMeetProjects = 0;
			$TotalProjects = 0;
		
			$query = "SELECT * FROM KPI_QUALITY_ESITMATION
					WHERE ACTIVE = 1
					AND MONTH = '".$Month."'
					ORDER BY ENGAGEMENT_DATE";	
		
			//echo $query;exit;
			
			$queryParse = oci_parse($conn, $query);
			oci_execute($queryParse);		
		
			while ($row = oci_fetch_array($queryParse, OCI_ASSOC+OCI_RETURN_NULLS)) {
				
				$EstimationTemp = array();
				$GateVariance = '';
				
				$EstimationTemp['PROJECTNAME'] =  $row ['PROJECT_NAME'];
				$EstimationTemp['ENGAGEMENT_DATE'] = $row['ENGAGEMENT_DATE'];								
				$EstimationTemp['GATE1_ESTIMATION_DELIVERY_DATE'] = $row['GATE1_ESTIMATION_DELIVERY_DATE'];
				$EstimationTemp['GATE1_ESTIMATION'] = $row['GATE1_ESTIMATION'];
				$EstimationTemp['GATE2_ESTIMATION'] = $row['GATE2_ESTIMATION'];
				$EstimationTemp['FINAL_ESTIMATION'] = $row['FINAL_ESTIMATION'];
				$EstimationTemp['GATE1_VARIANCE'] = $row['GATE1_VARIANCE'];
				$EstimationTemp['GATE2_VARIANCE'] = $row['GATE2_VARIANCE'];
				
				/*  Get Cause/Action for KPI for this Project` for this Month */
				
				$query1 = "SELECT CAUSE, ACTION
					FROM KPI_CAUSE_ACTION
					WHERE KPI_ID = ".$KPID."
					AND PROJECT_NAME = '".$row ['PROJECT_NAME']."'
					AND QUALITYESTIMATION = 1
					AND MONTHLY IS NULL";
				
				$queryParse1 = oci_parse ( $conn, $query1 );
				oci_execute ( $queryParse1 );
				$resultCauseAction = oci_fetch_array($queryParse1);
				
				$EstimationTemp['CAUSE'] = $resultCauseAction['CAUSE'];
				$EstimationTemp['ACTION'] = $resultCauseAction['ACTION'];
					
				/* === */

				if(!empty($EstimationTemp['GATE1_VARIANCE']) || !empty($EstimationTemp['GATE2_VARIANCE'])){					
					$Gate1Variance = trim($EstimationTemp['GATE1_VARIANCE'], "-");
					$Gate2Variance = trim($EstimationTemp['GATE2_VARIANCE'], "-");
					
					if($Gate1Variance <= 25 || $Gate1Variance <= 5) {
						$GateVariance = '1';
						$SLAMeetProjects++;
					} else {
						$GateVariance = '0';
					}
					
					if($GateVariance == 1) {
						$EstimationTemp['RAG'] = 'green';
						$EstimationTemp['RedReason'] = '';
					}
					else {
						$EstimationTemp['RAG'] = 'red';
						$EstimationTemp['RedReason'] = 'Either Gate1Variance or Gate2Variance does not meet SLA !!!';
					}
					
				} else {
					$EstimationTemp['RAG'] = 'green';
					$EstimationTemp['Actual'] = 'green';
					$EstimationTemp['RedReason'] = '';
				}								
				
				$EstimationResultTemp[] = $EstimationTemp;
				$TotalProjects++;
			}			
		
			$EstimationResult[$Month] = $EstimationResultTemp;
			oci_free_statement($queryParse);
			$this->oracle->closeConnection();
		}
		
		else
			$EstimationResult = array();		
	
		
		//echo "<pre>";print_r($EstimationResult);exit;
		if($Monthly == 1){
			if($SLAMeetProjects > 0){
				$MonthlyResult = ($SLAMeetProjects / $TotalProjects) * 100;
				$MonthlyResult = number_format($MonthlyResult, 2)."%";
			} else 
			 $MonthlyResult = '100.00%';

			$MonthlyResult = '100.00%';
			return $MonthlyResult;
			
		} else
			return $EstimationResult;
		
	}
	
	private function getIntakeProcessResult($KPID, $Month, $Monthly = ""){		
		
		if(isset($conn))
			 $conn = $this->oracle->getConnection();
		else {
			$this->oracle->openConnection('KPIDASHBOARD');
			$conn = $this->oracle->getConnection();
		}					
		
		if(!empty($Month)){
			$ProcessResult[$Month] = array();
			$ProcessResultTemp = array();			
			
			$nominator = 0;
			$denominator = 0;
			//$dataCount = 0;
			
			/*
			$query = "SELECT * FROM KPI_INTAKE_PROCESS 
					WHERE ACTIVE = 1 
					AND (TO_CHAR(REQUEST_DATE, 'MON/YY') LIKE '%".$Month."%'
					OR TO_CHAR(SUBMISSION_DATE, 'MON/YY') LIKE '%".$Month."%')
					ORDER BY PROJECT_NAME, PROGRAMME";
			*/
			
			$query = "SELECT * FROM KPI_INTAKE_PROCESS
					WHERE ACTIVE = 1
					AND MONTH = '".$Month."'					
					ORDER BY PROJECT_NAME, PROGRAMME";
			
			$queryParse = oci_parse($conn, $query);
			oci_execute($queryParse);
	
			while ($row = oci_fetch_array($queryParse, OCI_ASSOC+OCI_RETURN_NULLS)) {
				
				$ProcessTemp = array();
				
				$ProcessTemp['PROGRAMME'] = $row['PROGRAMME'];
				$ProcessTemp['PROJECTNAME'] =  $row ['PROJECT_NAME'];				
				$ProcessTemp['PROPOSAL_TYPE'] = $row['PROPOSAL_TYPE'];
				$ProcessTemp['SOLUTION_COMPONENT'] = $row['SOLUTION_COMPONENT'];
				$ProcessTemp['PROJ_PROG_MGR'] = $row['PROJ_PROG_MGR'];
				$ProcessTemp['WP_PO_STATUS'] = $row['WP_PO_STATUS'];
				$ProcessTemp['STATUS'] = $row['STATUS'];
				$ProcessTemp['REQUEST_DATE'] = $row['REQUEST_DATE'];
				$ProcessTemp['SUBMISSION_DATE'] = $row['SUBMISSION_DATE'];
				$ProcessTemp['VALUE'] = $row['VALUE'];				
				$ProcessTemp['DATE_DIFF'] = $row['DIFF_DATES'];
				
				/*  Get Cause/Action for KPI for this Project` for this Month */
					
				$query1 = "SELECT CAUSE, ACTION
					FROM KPI_CAUSE_ACTION
					WHERE KPI_ID = ".$KPID."
					AND PROJECT_NAME = '".$row ['PROJECT_NAME']."'
					AND INTAKEPROCESS = 1
					AND MONTHLY IS NULL";
					
				$queryParse1 = oci_parse ( $conn, $query1 );
				oci_execute ( $queryParse1 );
				$resultCauseAction = oci_fetch_array($queryParse1);
					
				$ProcessTemp['CAUSE'] = $resultCauseAction['CAUSE'];
				$ProcessTemp['ACTION'] = $resultCauseAction['ACTION'];
				
				/* */
				
				/* RAG Calculation */
				
				if($ProcessTemp['DATE_DIFF'] <= 10){
					$ProcessTemp['RAG'] = 'green';
					$nominator++;
				}
				else
					$ProcessTemp['RAG'] = 'red';
				
				/* */
					
				$ProcessResultTemp[] = $ProcessTemp;				
				$denominator++;
				//$dataCount++;
			}
			
			$ProcessResult[$Month] = $ProcessResultTemp;			
			oci_free_statement($queryParse);						
		}	
		else 
			$ProcessResult[] = array();
		
		//echo "<pre>";print_r($ProcessResult);exit;
		//echo $nominator."==".$denominator;//exit;
		if($Monthly == 1){
				if($denominator > 0){
					if($denominator != 0){
						$MonthlyResult = ($nominator/$denominator) * 100;
						$MonthlyResult = number_format($MonthlyResult, 2)."%";
					}
					else
						$MonthlyResult = '0%';
				} else 
					$MonthlyResult = 'Unknown';
			
		
			//echo "monthly===".$MonthlyResult;exit;
		
			return $MonthlyResult;
		}
		else {
			return $ProcessResult;
		}

	
		
	}
	
	private function get60KPIResult($KPID, $Month, $DefectType, $Monthly = ""){
		
		$this->oracle->openConnection('KPIDASHBOARD');
		$conn = $this->oracle->getConnection();
		
		$MonthlyResult = '';
		$defectsum = 0;
		$dataCount = 0;
		$numerator = 0;
		$denominator = 0;
		$TotalTestCaseCount = 0;
	
		
		if(!empty($Month)){
			
			/* Get previous 2 months for production + 60 days calculation */

			$monthTemp = str_replace("/", "-", $Month);
			
			$date = date_create('01-'.$monthTemp);
			date_sub($date, date_interval_create_from_date_string('1 month'));			
			
			$month1Temp = date_format($date, 'M-y');//exit;
			$month1 = strtoupper(str_replace("-", "/", $month1Temp));
			
			$date = date_create('01-'.$monthTemp);
			date_sub($date, date_interval_create_from_date_string('2 month'));
				
			$month2Temp = date_format($date, 'M-y');//exit;
			$month2 = strtoupper(str_replace("-", "/", $month2Temp));
						
			
			/*echo date("M/y", strtotime(" -1 Months"));exit;
			$month = date("m", strtotime(" -1 months"));
			$month1 = strtoupper(date("m", strtotime($month)));
			
			$month = date("Y-m", strtotime( date( 'Y-m-01' )." -2 months"));
			$month2 = strtoupper(date("M/y", strtotime($month)));				
			*/
			
			$months = array($month1, $month2);
			//print_r($months);exit;
			
			if($DefectType == 'P1_P2')
				$fetchColumn = 'P1_P2';
			else
				$fetchColumn = 'P3_P4';
			
			foreach($months as $month){
				
				$this->oracle->openConnection('KPIDASHBOARD');
				$conn = $this->oracle->getConnection();
				
				$query2 = "SELECT PROJECTID, PROJECTNAME, CYCLE_ID, QC_TABLENAME, SCOPE, ".$fetchColumn."
						FROM KPI_PROJECTS WHERE
						TO_CHAR(ESTIMATED_PROD_LIVE_DATE, 'MON/YY') LIKE '%".$month."%'
						AND ACTIVE = 1
						ORDER BY PROJECTNAME";	
				
				//echo $query2."<br>";//exit;

				$query2Parse = oci_parse($conn, $query2);
				oci_execute($query2Parse);
				
				$DefectsResult[$month] = array();
				$DefectsResultTemp = array();
				
				
				while ($Projects = oci_fetch_array($query2Parse, OCI_ASSOC+OCI_RETURN_NULLS)) {
					
					$DefectsTemp = array();
				
					
					/* This KPI considers only Projects within DEV scope */
					
					$DefectsTemp['PROJECTID'] = $Projects['PROJECTID'];
					$DefectsTemp['PROJECTNAME'] = $Projects['PROJECTNAME'];
					
					if($Projects['SCOPE'] == 'Dev' || $Projects['SCOPE'] == 'Both' ){
					
						$DefectsTemp[$fetchColumn] = $Projects[$fetchColumn];						
					
						if($DefectsTemp[$fetchColumn] > 0) {
								
							$defectsum = $defectsum + $DefectsTemp[$fetchColumn];
							$DefectsTemp['RAG'] = 'red';
								
						} else {
							$DefectsTemp['RAG'] = 'green';
						}
						
						if($fetchColumn == 'P3_P4'){
							if(!empty($Projects['CYCLE_ID']) && !empty($Projects['QC_TABLENAME'])){
									
								oci_close($conn);
								//$this->oracle->closeConnection();
									
								$this->oracle->openConnection('QC');
								$conn = $this->oracle->getConnection();
									
								//try {
								$query1 = "SELECT SUM(COUNT)AS COUNT
								    FROM
								      (SELECT COUNT(1) AS COUNT
								      FROM ".$Projects['QC_TABLENAME'].".testcycl
								      WHERE tc_assign_rcyc IN (".$Projects['CYCLE_ID'].")
								      GROUP BY tc_assign_rcyc, tc_status
								      ORDER BY tc_assign_rcyc, tc_status)";
									
								//echo $query1."<br>";//exit;
								$queryParse1 = oci_parse($conn, $query1);
								oci_execute($queryParse1);
						
						
								$result = oci_fetch_array($queryParse1);
									
								$TestCaseCount = $result['COUNT'];
								$TotalTestCaseCount = $TotalTestCaseCount + $TestCaseCount;
								
								$DefectsTemp['TESTCASES'] = $TestCaseCount;				
								
								//echo "testcase count==".$TestCaseCount."<br>";
								
								//exit;
						
						
							} else {
								$DefectsTemp['RAG'] = 'Unknown';
								$DefectsTemp['TESTCASES'] = '';
								$denominator++;
								
							}
						}		
						
						
						$denominator++;
					
					} else {
					
						$DefectsTemp[$fetchColumn] = 'N/A';
						$DefectsTemp['RAG'] = 'N/A';
						$DefectsTemp['TESTCASES'] = 'N/A';
					
					}
					
					
					
					$DefectsResultTemp[] = $DefectsTemp;
					$dataCount++;
				}
				
				$DefectsResult[$month] = $DefectsResultTemp;				
				$dataCount++;
			}
			
		}
		
 		//echo "<pre>";print_r($DefectsResult);exit;
		
		if($Monthly == 1){
			if($dataCount > 0){					
				if($DefectType == 'P1_P2'){						
					if($denominator != 0){						
						$MonthlyResult = ( $defectsum / $denominator ) * 100;
						$MonthlyResult = number_format($MonthlyResult, 2)."%";
					}					
				}
				else {					
					if(!empty($TotalTestCaseCount)){
						
						//echo $TotalTestCaseCount;//exit;
						$MonthlyResult = ( $defectsum / $TotalTestCaseCount ) * 100;
						$MonthlyResult = number_format($MonthlyResult, 2)."%";
						
						$NewSLA = (1+(0.5*ceil((1191-1000)/1000)))/ceil(1191/1000);
						//$NewSLA = (1+(0.5*ceil((".$TotalTestCaseCount."-1000)/1000)))/ceil(".$TotalTestCaseCount."/1000);
						//exit;
						
						/* Update NEWLY Calculated SLA to DB for this P3, P4 KPI */
						
						/*
						oci_close($conn);
						
						$this->oracle->openConnection('KPIDASHBOARD');
						$conn = $this->oracle->getConnection();
						
						$query = "UPDATE KPI_PARAMETER SET
								SLA_VALUE = ".$NewSLA."
								WHERE KPI_ID = ".$KPID;
						
						//echo "new SLA==".$NewSLA."<br>";
						//echo $query;exit;
						
						$queryParse = oci_parse($conn, $query);
						oci_execute($queryParse);
						*/
						
						/* === */										
						
					}
				}				
				
			} else
				$MonthlyResult = 'Unknown';
				
			return $MonthlyResult;
		}
		else {
			return $DefectsResult;
		}		
		
		
	}
	
	private function getDeliverySlippageResult($KPID, $Month, $Monthly = ""){		
		
		if(isset($conn))
			$conn = $this->oracle->getConnection();
		else {
			$this->oracle->openConnection('KPIDASHBOARD');
			$conn = $this->oracle->getConnection();
		}	
		
		if(!empty($Month)){
			$query = "SELECT PROJECTID, PROJECTNAME, ESTIMATED_PROD_LIVE_DATE, ACTUAL_PROD_LIVE_DATE, DIFF_DATE_DELIVERY
					FROM KPI_PROJECTS
					WHERE ACTIVE = 1
					AND TO_CHAR(ESTIMATED_PROD_LIVE_DATE, 'MON/YY') LIKE '%". $Month . "%'
					ORDER BY PROJECTNAME";		
			
			$queryParse = oci_parse ( $conn, $query );
			oci_execute ( $queryParse );
			
			$DeliveryResult[$Month] = array();
			$DeliveryResultTemp = array();			
			
			$MonthlyResult = '';			
			$numerator = 0;
			$denominator = 0;
			$dataCount = 0;
			
			while ( $row = oci_fetch_array ( $queryParse, OCI_ASSOC + OCI_RETURN_NULLS ) ) {
				
				$DeliveryTemp = array();
				
				$date1 = new \DateTime ( $row ['ESTIMATED_PROD_LIVE_DATE'] );
				$date2 = new \DateTime ( $row ['ACTUAL_PROD_LIVE_DATE'] );
				
				$differenceInDate = $date2->diff ( $date1 );
				
				$DeliveryTemp['PROJECTID'] =  $row ['PROJECTID'];
				$DeliveryTemp['PROJECTNAME'] =  $row ['PROJECTNAME'];
				$DeliveryTemp['ESTIMATED_PROD_LIVE_DATE'] =  $row ['ESTIMATED_PROD_LIVE_DATE'];
				$DeliveryTemp['ACTUAL_PROD_LIVE_DATE'] =  $row ['ACTUAL_PROD_LIVE_DATE'];
				$DeliveryTemp['DATE_DIFF'] =  $row ['DIFF_DATE_DELIVERY'];
				
				if($date2 > $date1){					
					$DeliveryTemp['RAG'] = 'red';
					$numerator++;
				}				
				else {
					$DeliveryTemp['RAG'] = 'green';
				}
				
				/*  Get Cause/Action for KPI for this Project` for this Month */
					
				$query1 = "SELECT CAUSE, ACTION
					FROM KPI_CAUSE_ACTION
					WHERE KPI_ID = ".$KPID."
					AND PROJECT_ID = ".$row ['PROJECTID']."					
					AND MONTHLY IS NULL";			
				
					
				$queryParse1 = oci_parse ( $conn, $query1 );
				oci_execute ( $queryParse1 );
				$resultCauseAction = oci_fetch_array($queryParse1);
					
				$DeliveryTemp['CAUSE'] = $resultCauseAction['CAUSE'];
				$DeliveryTemp['ACTION'] = $resultCauseAction['ACTION'];			
				
				/* */				
				
				$DeliveryResultTemp[] = $DeliveryTemp;
				$denominator++;
				$dataCount++;
			}
			
			//echo $numerator."===".$denominator;
			
			$DeliveryResult[$Month] = $DeliveryResultTemp;			
			
		} else 
			$DeliveryResult[] = array();
		
			
		if($Monthly == 1){			
			if($dataCount > 0){
				if($denominator != 0){
					$MonthlyResult = ($numerator / $denominator)*100;
					$MonthlyResult = number_format($MonthlyResult, 2)."%";
				} else {
					$MonthlyResult = '0%';
				}
			} else {
				$MonthlyResult = 'Unknown';
			}
			
			return $MonthlyResult;
		}
		else {			
			return $DeliveryResult;
		}
	}
	
	private function getTDCEResult($KPID, $Month, $Monthly = ""){		
		
		if(isset($conn))
			 $conn = $this->oracle->getConnection();
		else {
			$this->oracle->openConnection('KPIDASHBOARD');
			$conn = $this->oracle->getConnection();
		}
		
		$query = "SELECT PROJECTID, PROJECTNAME, P1_P2, P3_P4, SCOPE, QC_TABLENAME, CYCLE_ID 
				FROM KPI_PROJECTS WHERE
				TO_CHAR(ESTIMATED_PROD_LIVE_DATE, 'MON/YY') LIKE '%".$Month."%'
				AND ACTIVE = 1
				ORDER BY PROJECTNAME";			
		
		$queryParse = oci_parse($conn, $query);
		oci_execute($queryParse);
		
		$ProjectsResult[$Month] = array();
		$ProjectsResultTemp = array();				

		$MonthlyResult = '';
		$numerator = 0;
		$denominator = 0;
		$dataCount = 0;
		
		$numeratorArr = array();
		$denominatorArr = array();
		
		$KPIData = $this->getKPIData($KPID);
		
		$KPISLA = $KPIData['SLA_VALUE'];
		$KPIOperator = $KPIData['OPERATOR'];
			
		while ($Projects = oci_fetch_array($queryParse, OCI_ASSOC+OCI_RETURN_NULLS)) {
			$ProjectsTemp = array();
			
			$ProjectsTemp['PROJECTID'] = $Projects['PROJECTID'];
			$ProjectsTemp['PROJECTNAME'] = $Projects['PROJECTNAME'];	
			
			$this->oracle->openConnection('KPIDASHBOARD');
			$conn = $this->oracle->getConnection();
				
			
			/*  Get Cause/Action for KPI for this Project` for this Month */
				
			$query1 = "SELECT CAUSE, ACTION
					FROM KPI_CAUSE_ACTION
					WHERE KPI_ID = ".$KPID."
					AND PROJECT_ID = ".$Projects ['PROJECTID']."
					AND MONTHLY IS NULL";
			
			//echo $query1;exit;
				
			$queryParse1 = oci_parse ( $conn, $query1 );
			oci_execute ( $queryParse1 );
			$resultCauseAction = oci_fetch_array($queryParse1);
				
			$ProjectsTemp['CAUSE'] = $resultCauseAction['CAUSE'];
			$ProjectsTemp['ACTION'] = $resultCauseAction['ACTION'];
			
			/* */
			
			
			if($Projects['SCOPE'] == 'Dev' || $Projects['SCOPE'] == 'Both' ){
				
				if(!empty($Projects['QC_TABLENAME']) && !empty($Projects['CYCLE_ID'])){
				
					oci_close($conn);
					//$this->oracle->closeConnection();
				
					$this->oracle->openConnection('QC');
					$conn = $this->oracle->getConnection();
				
					$query2 = $this->constants->getKPIQuery("ETA", $Projects['QC_TABLENAME'], trim($Projects['CYCLE_ID']), 2);
					$queryParse2 = oci_parse($conn, $query2);
					oci_execute($queryParse2);
					$count2Arr = oci_fetch_array($queryParse2);
				
					$numerator = $count2Arr[0];
					$numeratorArr[] = $numerator;
				
					$denominator = $numerator + $Projects['P1_P2'] + $Projects['P3_P4'];
					$denominatorArr[] = $denominator;
						
					$ProjectsTemp['PREDEFECTS'] =  $numerator;
					$ProjectsTemp['POSTDEFECTS'] =  $Projects['P1_P2'] + $Projects['P3_P4'];
				
				}
					
				
				if($denominator != 0){
					$actualTemp = ($numerator / $denominator) * 100;
					$ProjectsTemp['ACTUAL'] = number_format($actualTemp, 2)."%";
				}
				else
					$ProjectsTemp['ACTUAL'] = '0%';				
				
				if($KPIOperator == '>='){
					if(trim($ProjectsTemp['ACTUAL'], "%") >= $KPISLA){						
						$ProjectsTemp['RAG'] = 'green';
					}
					else { 
						$ProjectsTemp['RAG'] = 'red';
					}					
				}
				else if($KPIOperator == '<='){
					if($trim($ProjectsTemp['ACTUAL'], "%") <= $KPISLA){
						$ProjectsTemp['RAG'] = 'green';
					}
					else {
						$ProjectsTemp['RAG'] = 'red';
					}
				}					
				$dataCount++;
				
			} else {
				$ProjNAApplicable = 1;				
				$ProjectsTemp['ACTUAL'] = 'N/A';				
				$ProjectsTemp['RAG'] = 'N/A';
				$ProjectsTemp['PREDEFECTS'] =  'N/A';
				$ProjectsTemp['POSTDEFECTS'] =  'N/A';
				$ProjNAReason = 'Project Scope does not match to KPI Scope !!!';
			}				
			

			$ProjectsResultTemp[] = $ProjectsTemp;
			
		}
		
		$ProjectsResult[$Month] = $ProjectsResultTemp;
	
		//echo "<pre>";print_r($ProjectsResult);exit;
		
		if($Monthly == 1){
				if($dataCount > 0){
					$TotalNominator = array_sum($numeratorArr);
					$TotalDenominator = array_sum($denominatorArr);
					
					if($TotalDenominator != 0){
						$MonthlyResult = ($TotalNominator / $TotalDenominator) * 100;
						$MonthlyResult = number_format($MonthlyResult, 2)."%";
					}
					else
						$MonthlyResult = '0%';
				} else 
					$MonthlyResult = 'Unknown';
				
				return $MonthlyResult;
		}
		else {
			return $ProjectsResult;
		}
		
		
	}
	
	private function getAutomationResult($KPID, $Month, $Monthly = ""){
		return $MonthlyResult = 'Unknown';
	}
	
	private function getP1P2KPIResult($KPID, $Month, $Monthly = ""){		
		
		if(isset($conn))
			 $conn = $this->oracle->getConnection();
		else {
			$this->oracle->openConnection('KPIDASHBOARD');
			$conn = $this->oracle->getConnection();
		}
		
		if(!empty($Month)){
		
			$query = "SELECT PROJECTID, PROJECTNAME, P1_P2, SCOPE
					FROM KPI_PROJECTS WHERE
					TO_CHAR(ESTIMATED_PROD_LIVE_DATE, 'MON/YY') LIKE '%".$Month."%'
					AND ACTIVE = 1
					ORDER BY PROJECTNAME";			
			
			$queryParse = oci_parse($conn, $query);
			oci_execute($queryParse);
			
			$DefectsResult[$Month] = array();
			$DefectsResultTemp = array();				
	
			$MonthlyResult = '';
			$numerator = 0;
			$denominator = 0;
			
				
			while ($Projects = oci_fetch_array($queryParse, OCI_ASSOC+OCI_RETURN_NULLS)) {
				
				$this->oracle->openConnection('KPIDASHBOARD');
				$conn = $this->oracle->getConnection();
				
				$DefectsTemp = array();
				
				$DefectsTemp['PROJECTID'] = $Projects['PROJECTID'];
				$DefectsTemp['PROJECTNAME'] = $Projects['PROJECTNAME'];
					
				/* Calculate only if Project & KPI Scope are same */
				
				$KPIData = $this->getKPIData($KPID);
				$KPIProjectScope = $KPIData['SCOPE'];
				
				if($Projects['SCOPE'] == 'Dev' || $Projects['SCOPE'] == 'Both' ){
					
					$ProjNAApplicable = 0;
					$DefectsTemp['P1_P2'] = $Projects['P1_P2'];
					
					if($DefectsTemp['P1_P2'] == 0){
						$DefectsTemp['RAG'] = 'green';						
					}
					else {
						$numerator = $numerator + $DefectsTemp['P1_P2'];
						$DefectsTemp['RAG'] = 'red';						
					}
					
					$denominator++;
					
				} else {
					
					$ProjNAApplicable = 1;
					$DefectsTemp['RAG'] = 'N/A';
					$DefectsTemp['P1_P2'] = 'N/A';
					$ProjNAReason = 'Project Scope does not match to KPI Scope !!!';
				}		
	
				$DefectsResultTemp[] = $DefectsTemp;				
				
			}
			
			$DefectsResult[$Month] = $DefectsResultTemp;
		} 			
		else
			$DefectsResult = array();
		
		//echo $numerator."===".$denominator;//exit;
		
		if($Monthly == 1){

			if($denominator > 0){
				if($numerator == 0){
					$MonthlyResult = '100%';
				}
				else {
					if($denominator != 0){
						$MonthlyResult = ($numerator / $denominator) * 100;
						$MonthlyResult = number_format($MonthlyResult, 2)."%";
					}
					else
						$MonthlyResult = '0%';
				}
					
			} else 
				$MonthlyResult = 'Unknown';
			
			return $MonthlyResult;
		}
		else {
			return $DefectsResult;
		}
		
		
	}
	
	private function getP3P4KPIResult($KPID, $Month, $Monthly = ""){
		
		if(isset($conn))
			 $conn = $this->oracle->getConnection();
		else {
			$this->oracle->openConnection('KPIDASHBOARD');
			$conn = $this->oracle->getConnection();
		}
		
		if(!empty($Month)){
			$DefectsResult[$Month] = array();
			$DefectsResultTemp = array();
			$Projects = array();				
			
			$MonthlyResult = '';
			$numerator = 0;
			$denominator = 0;
			$dataCount = 0;
			
			$query = "SELECT PROJECTID, PROJECTNAME, P3_P4, SCOPE, CYCLE_ID, QC_TABLENAME
					FROM KPI_PROJECTS WHERE
					TO_CHAR(ESTIMATED_PROD_LIVE_DATE, 'MON/YY') LIKE '%".$Month."%'
					AND ACTIVE = 1
					ORDER BY PROJECTNAME";	
			
			$queryParse = oci_parse($conn, $query);
			oci_execute($queryParse);			
			
			//echo "<pre>";print_r($Projects);exit;
				
			while ($Projects = oci_fetch_array($queryParse, OCI_ASSOC+OCI_RETURN_NULLS)) {
				
				$DefectsTemp = array();
				
				$DefectsTemp['PROJECTID'] = $Projects['PROJECTID'];
				$DefectsTemp['PROJECTNAME'] = $Projects['PROJECTNAME'];
				
				/* Calculate only Project & KPI Scope are same */
				
				
				if($Projects['SCOPE'] == 'Dev' || $Projects['SCOPE'] == 'Both' ){
					
					$ProjNAApplicable = 0;
					$DefectsTemp['P3_P4'] = $Projects['P3_P4'];
					
					if($DefectsTemp['P3_P4'] == 0){
						$DefectsTemp['RAG'] = 'green';						
					}
					else {
						
						$numerator = $numerator + $DefectsTemp['P3_P4'];
						$DefectsTemp['RAG'] = 'red';						
					}			

					
					if(!empty($Projects['QC_TABLENAME']) && !empty($Projects['CYCLE_ID'])){
					
						oci_close($conn);
						
						$this->oracle->openConnection('QC');
						$conn = $this->oracle->getConnection();
						
						$query1 = "SELECT SUM(COUNT)AS COUNT
		    						FROM
		      						(SELECT COUNT(1) AS COUNT
		      						FROM ".$Projects['QC_TABLENAME'].".testcycl
		     						WHERE tc_assign_rcyc IN (".$Projects['CYCLE_ID'].")
		      						GROUP BY tc_assign_rcyc, tc_status
		     						ORDER BY tc_assign_rcyc, tc_status)";
						
						//echo $query1."==<br>";
						$queryParse1 = oci_parse($conn, $query1);
						oci_execute($queryParse1);
						$projQueryResult = oci_fetch_array($queryParse1);
						
						$TotalTestCases = $projQueryResult['COUNT'];
						$DefectsTemp['TESTCASES'] = $TotalTestCases;
						
						$denominator = $denominator + $TotalTestCases;
						
					
					}	
					//echo $numerator."===".$denominator."<br><br>";
					
					$dataCount++;
					
				} else {
					
					$ProjNAApplicable = 1;
					$DefectsTemp['RAG'] = 'N/A';
					$DefectsTemp['P3_P4'] = 'N/A';
					$DefectsTemp['TESTCASES'] = 'N/A';
					$ProjNAReason = 'Project Scope does not match to KPI Scope !!!';
				}
						
			$DefectsResultTemp[] = $DefectsTemp;
			
			
			}			
			
			$DefectsResult[$Month] = $DefectsResultTemp;
			
		} else 
			$DefectsResult = array();
		
	//	echo $numerator."===".$denominator;
		
		if($Monthly == 1){
			if($dataCount > 0){
				if($numerator == 0){
					$MonthlyResult = '0%';
				}
				else {
					if($denominator != 0){
						$MonthlyResult = ($numerator / $denominator) * 100;
						$MonthlyResult = number_format($MonthlyResult, 2)."%";
					}
					else
						$MonthlyResult = '0%';
				}
				
			} else
				$MonthlyResult = 'Unknown';	

			
			return $MonthlyResult;
		}
		else {
			return $DefectsResult;
		}		
		
	
	}
	
	private function get365KPIResult($KPID, $Month, $DefectType, $Monthly = ""){
		
		if(isset($conn))
			 $conn = $this->oracle->getConnection();
		else {
			$this->oracle->openConnection('KPIDASHBOARD');
			$conn = $this->oracle->getConnection();
		}		
		
		$MonthlyResult = '';
		$defectsum = 0;		
		$TotalTestCaseCount = 0;
		$TestCaseCount = 0;
		$denominator = 0;
		
		
		if(!empty($Month)){
				
			/* Get first 10 months from last year */
			
			$monthTemp = str_replace("/", "-", $Month);
			
			$date = date_create('01-'.$monthTemp);
			date_sub($date, date_interval_create_from_date_string(' 1 year '));			
			
			$month1Temp = date_format($date, 'M-y');
			$startmonth = strtoupper(str_replace("-", "/", $month1Temp));
			
			$date = date_create('01-'.$monthTemp);
			date_sub($date, date_interval_create_from_date_string(' 2 months '));
				
			$month2Temp = date_format($date, 'M-y');					
			$endmonth = strtoupper(str_replace("-", "/", $month2Temp));
			
			/*
			
			$month = date("Y-m", strtotime( date( 'Y-m-01' )." -1 year"));
			$startmonth = strtoupper(date("M/y", strtotime($month)));
			
			$month = date("Y-m", strtotime( date( 'Y-m-01' )." -3 months"));
			$endmonth = strtoupper(date("M/y", strtotime($month)));
			
			*/
			
			/* === */
			
				
			//echo $startmonth."===".$endmonth;exit;
				
			if($DefectType == 'P1_P2')
				$fetchColumn = 'P1_P2';
			else
				$fetchColumn = 'P3_P4';
						
			$query = "SELECT PROJECTID, PROJECTNAME, SCOPE, ".$fetchColumn.", CYCLE_ID, QC_TABLENAME
					FROM KPI_PROJECTS 
					WHERE
					TRUNC(ESTIMATED_PROD_LIVE_DATE) BETWEEN TO_DATE('01/".$startmonth."','DD/MON/YY') 
					AND TO_DATE('01/".$endmonth."','DD/MON/YY') 
					AND ACTIVE = 1
					ORDER BY PROJECTNAME";
			
			//echo $query;
			
			$queryParse = oci_parse($conn, $query);
			oci_execute($queryParse);

			$DefectsResult[$Month] = array();
			$DefectsResultTemp = array();			

			while ($Projects = oci_fetch_array($queryParse, OCI_ASSOC+OCI_RETURN_NULLS)) {
					
					$DefectsTemp = array();
				
					
					/* This KPI considers only Projects within DEV scope */
					
					$DefectsTemp['PROJECTID'] = $Projects['PROJECTID'];
					$DefectsTemp['PROJECTNAME'] = $Projects['PROJECTNAME'];
					
					if($Projects['SCOPE'] == 'Dev' || $Projects['SCOPE'] == 'Both' ){
					
						$DefectsTemp[$fetchColumn] = $Projects[$fetchColumn];						
					
						if($DefectsTemp[$fetchColumn] > 0) {
								
							$defectsum = $defectsum + $DefectsTemp[$fetchColumn];
							$DefectsTemp['RAG'] = 'red';
								
						} else {
							$DefectsTemp['RAG'] = 'green';
						}
						
						if($fetchColumn == 'P3_P4'){
							if(!empty($Projects['CYCLE_ID']) && !empty($Projects['QC_TABLENAME'])){
									
								oci_close($conn);
								//$this->oracle->closeConnection();
									
								$this->oracle->openConnection('QC');
								$conn = $this->oracle->getConnection();
									
								//try {
								$query1 = "SELECT SUM(COUNT)AS COUNT
								    FROM
								      (SELECT COUNT(1) AS COUNT
								      FROM ".$Projects['QC_TABLENAME'].".testcycl
								      WHERE tc_assign_rcyc IN (".$Projects['CYCLE_ID'].")
								      GROUP BY tc_assign_rcyc, tc_status
								      ORDER BY tc_assign_rcyc, tc_status)";
									
								//echo $query1."<br>";//exit;
								$queryParse1 = oci_parse($conn, $query1);
								oci_execute($queryParse1);
						
						
								$result = oci_fetch_array($queryParse1);
									
								$TestCaseCount = $result['COUNT'];
								$TotalTestCaseCount = $TotalTestCaseCount + $TestCaseCount;
								
								
								$DefectsTemp['TESTCASES'] = $TestCaseCount;				
								
								//echo "testcase count==".$TotalTestCaseCount."<br>";
								
								//exit;
						
						
							} else {
								$DefectsTemp['RAG'] = 'Unknown';
								$DefectsTemp['TESTCASES'] = '';
							}
						}		
						
						
						$denominator++;
					
					} else {
					
						$DefectsTemp[$fetchColumn] = 'N/A';
						$DefectsTemp['RAG'] = 'N/A';
						$DefectsTemp['TESTCASES'] = 'N/A';
						$denominator++;
					
					}
					
					
					
					$DefectsResultTemp[] = $DefectsTemp;
				}

				$DefectsResult[$Month] = $DefectsResultTemp;
			
			//echo $defectsum."<br>";
						
		} else 
			$DefectsResult = array();
		
		
		//echo $denominator;exit;
		//echo "<pre>";print_r($DefectsResult);exit;
		
		if($Monthly == 1){
			if($denominator > 0){
				
				//echo 'in if==';exit;
				if($DefectType == 'P1_P2'){						
					if($denominator != 0){						
						$MonthlyResult = ( $defectsum / $denominator ) * 100;
						$MonthlyResult = number_format($MonthlyResult, 2)."%";
					}					
				}
				else {	
					
					if(!empty($TotalTestCaseCount)){
						
						//echo $TotalTestCaseCount;exit;
						$MonthlyResult = ( $defectsum / $TotalTestCaseCount ) * 100;
						$MonthlyResult = number_format($MonthlyResult, 2)."%";
						
						//$NewSLA = (1+(0.5*ceil((1191-1000)/1000)))/ceil(1191/1000);
						//$TotalTestCaseCount = settype($TotalTestCaseCount, "float");
						//$NewSLA = (1+(0.5*ceil((".$TotalTestCaseCount."-1000)/1000)))/ceil(".$TotalTestCaseCount."/1000);
						//exit;
						
						/* Update NEWLY Calculated SLA to DB for this P3, P4 KPI */
						
						/*
						
						oci_close($conn);
						
						$this->oracle->openConnection('KPIDASHBOARD');
						$conn = $this->oracle->getConnection();
						
						$query = "UPDATE KPI_PARAMETER SET
								SLA_VALUE = ".$NewSLA."
								WHERE KPI_ID = ".$KPID;
						
						//echo "new SLA==".$NewSLA."<br>";
						//echo $query;exit;
						
						$queryParse = oci_parse($conn, $query);
						oci_execute($queryParse);
						*/
						
						/* === */										
						
					}
				}				
				
			} else
				$MonthlyResult = 'Unknown';
				
			return $MonthlyResult;
		}
		else {
			return $DefectsResult;
		}		
		
	}
	
	private function getReusabilityResult($KPID, $Month, $Monthly = ""){

		$this->oracle->openConnection('KPIDASHBOARD');
		$conn = $this->oracle->getConnection();
		
		if(!empty($Month)){
			$DefectsResult[$Month] = array();
			$DefectsResultTemp = array();
			$Projects = array();
			
		
			$MonthlyResult = '';
			$numerator = 0;			
			$dataCount = 0;
			
			$TotalTestCasesArr = array();
			$ReusableTestCasesArr = array();
			
			
			$query = "SELECT PROJECTID, PROJECTNAME, REUSABILITY, SCOPE, CYCLE_ID, QC_TABLENAME
				FROM KPI_PROJECTS WHERE
				TO_CHAR(ESTIMATED_PROD_LIVE_DATE, 'MON/YY') LIKE '%".$Month."%'
				AND ACTIVE = 1 ORDER BY PROJECTNAME";
		
			//echo $query."<br>";
		
			$queryParse = oci_parse($conn, $query);
			oci_execute($queryParse);
		
			//$Projects = oci_fetch_array($queryParse);
			//echo "<pre>";print_r($Projects);exit;
					
			while ($Projects = oci_fetch_array($queryParse, OCI_ASSOC+OCI_RETURN_NULLS)) {
				
				$ProjectsTemp = array();
				
				$ReusabilityCount = 0;
				$ReusabilityCountTemp = 0;
				$ProjectsTemp = array();
				
				$ProjectsTemp['PROJECTID'] = $Projects['PROJECTID'];
				$ProjectsTemp['PROJECTNAME'] = $Projects['PROJECTNAME'];
				$ProjectsTemp['REUSABILITY'] = $Projects['REUSABILITY'];
				
				$this->oracle->openConnection('KPIDASHBOARD');
				$conn = $this->oracle->getConnection();
				
				
				/*  Get Cause/Action for KPI for this Project` for this Month */
					
				$query1 = "SELECT CAUSE, ACTION
						FROM KPI_CAUSE_ACTION
						WHERE KPI_ID = ".$KPID."
						AND PROJECT_ID = ".$Projects ['PROJECTID']."
						AND MONTHLY IS NULL";			
					
				$queryParse1 = oci_parse ( $conn, $query1 );
				oci_execute ( $queryParse1 );
				$resultCauseAction = oci_fetch_array($queryParse1);
					
				$ProjectsTemp['CAUSE'] = $resultCauseAction['CAUSE'];
				$ProjectsTemp['ACTION'] = $resultCauseAction['ACTION'];
				
				/* */
					
				/* Calculate only Project & KPI Scope are same */
		
				$KPIData = $this->getKPIData($KPID);
				$KPIOperator = $KPIData['OPERATOR'];
				$KPISLA = $KPIData['SLA_VALUE'];
				
				$KPIProjectScope = $KPIData['SCOPE'];
					
				if($Projects['SCOPE'] == 'Testing' || $Projects['SCOPE'] == 'Both' ){
		
					$ProjNAApplicable = 0;						
					if(!empty($Projects['CYCLE_ID']) && !empty($Projects['QC_TABLENAME'])){						
								
						if($Projects['REUSABILITY'] != ''){
							
							oci_close($conn);
								
							$this->oracle->openConnection('QC');
							$conn = $this->oracle->getConnection();
								
							$query1 = "SELECT SUM(COUNT)AS COUNT
	    						FROM
	      						(SELECT COUNT(1) AS COUNT
	      						FROM ".$Projects['QC_TABLENAME'].".testcycl
	     						WHERE tc_assign_rcyc IN (".$Projects['CYCLE_ID'].")
	      						GROUP BY tc_assign_rcyc, tc_status
	     						ORDER BY tc_assign_rcyc, tc_status)";
								
							//echo $query1."==<br>";
							$queryParse1 = oci_parse($conn, $query1);
							oci_execute($queryParse1);
							$projQueryResult = oci_fetch_array($queryParse1);
								
							$TotalTestCases = $projQueryResult['COUNT'];

							$ReusabilityCountTemp = $TotalTestCases * (($Projects['REUSABILITY'])/100);
							
							$ReusabilityCount = $ReusabilityCount + round($ReusabilityCountTemp);
							//echo "denom--after===".$ReusabilityCount;
							//echo "<br>";
							$ProjectsTemp['REUSABLE_TESTCASES'] = round($ReusabilityCountTemp);
							$ProjectsTemp['TOTAL_TESTCASES'] = $TotalTestCases;							
													
								if($KPIOperator == '>='){
									if($Projects['REUSABILITY'] >= $KPISLA){
										$ProjectsTemp['RAG'] = 'green';
									}
									else { 
										$ProjectsTemp['RAG'] = 'red';
									}
								}
								else if($KPIOperator == '<='){
									if($Projects['REUSABILITY'] <= $KPISLA){
										$ProjectsTemp['RAG'] = 'green';
									}
									else {
										$ProjectsTemp['RAG'] = 'red';
									}
								}
								
								$ReusableTestCasesArr[] = $ReusabilityCount;
								$TotalTestCasesArr[] = $TotalTestCases;
								
						} else {
							$ProjectsTemp['REUSABLE_TESTCASES'] = '';
							$ProjectsTemp['TOTAL_TESTCASES'] = '';
								
							$ProjectsTemp['RAG'] = '';
						}
						
						
						$DefectsResultTemp[] = $ProjectsTemp;
						$dataCount++;
						
					} 
					
							
				} else {
					$ProjectsTemp['RAG'] = 'N/A';
				}			
					
			}
			
			$DefectsResult[$Month] = $DefectsResultTemp;
		} 
		else
			$DefectsResult = array();
		
			//echo $numerator."===".$denominator;
			//echo "<pre>";
			//print_r($DefectsResult);
			//echo "===";
			//print_r($TotalTestCasesArr);
		//	exit;			
			
		
		if($Monthly == 1){
			if($dataCount > 0){
				$ReusableTestCases = array_sum($ReusableTestCasesArr);
				$TotalTestCases = array_sum($TotalTestCasesArr);
				
				if($TotalTestCases != 0){
					$MonthlyResult = number_format((($ReusableTestCases / $TotalTestCases) * 100), 2)."%";
				} else 
					$MonthlyResult = '0%';
			} else 
				$MonthlyResult = 'Unknown';
			
			//$MonthlyResult = '36.40%';
			return $MonthlyResult;
		}
		else {
			return $DefectsResult;
		}
	
		
		
	}
		
	public function updateKPIAction($userID, $postData){
	//public function updateKPIAction($userID, $newVal, $KPIID, $month){
		
		$column = $postData['column'];
		$newVal = $postData['newVal'];
		$KPIID = $postData['KPIID'];
		$ProjectID = $postData['ProjectID'];
		$KPIType = $postData['kpi_type'];
		
		$this->oracle->openConnection('KPIDASHBOARD');
		$conn = $this->oracle->getConnection();
		
		/* If ProjectID is a number */
		
		if(is_numeric($ProjectID)){
		
			$query = "SELECT COUNT(*) AS COUNT FROM KPI_CAUSE_ACTION
					WHERE KPI_ID = ".$KPIID." AND PROJECT_ID = ".$ProjectID."";			
		
			$queryParse = oci_parse($conn, $query);		
			oci_execute($queryParse);
			$row = oci_fetch_array($queryParse);
			
			if($row['COUNT'] > 0){		
				$query = "UPDATE KPI_CAUSE_ACTION SET
						".$column." = '".trim(addslashes($newVal))."',										
						EDITED_BY = ".$userID.",
						EDITED_ON = '".strtoupper(date('d/M/y'))."'
						WHERE KPI_ID = ".$KPIID." 
						AND PROJECT_ID = ".$ProjectID."";
				
			}
			else {
				$query = "INSERT INTO KPI_CAUSE_ACTION (".$column.", PROJECT_ID, KPI_ID, ADDED_BY, ADDED_ON)
					VALUES ('".trim(addslashes($newVal))."', ".$ProjectID.", ".$KPIID.", ".$userID.",
							'".strtoupper(date('d/M/y'))."')";	

			}
		} 
		
		/* If ProjectID is a Project Name - for Intake Process & Quality Estimation */
		
		else {
			
			$query = "SELECT COUNT(*) AS COUNT FROM KPI_CAUSE_ACTION
					WHERE KPI_ID = ".$KPIID." AND PROJECT_NAME = '".$ProjectID."'";
			
			if(!empty($KPIType))
				$query .= " AND ".$KPIType." = 1";
					
			$queryParse = oci_parse($conn, $query);
			oci_execute($queryParse);
			$row = oci_fetch_array($queryParse);
				
			if($row['COUNT'] > 0){
				$query = "UPDATE KPI_CAUSE_ACTION SET
						".$column." = '".trim(addslashes($newVal))."',
						EDITED_BY = ".$userID.",
						EDITED_ON = '".strtoupper(date('d/M/y'))."'
						WHERE KPI_ID = ".$KPIID."
						AND PROJECT_NAME = '".trim(addslashes($ProjectID))."'";
			
			}
			else {
				if(!empty($KPIType)){
					$query = "INSERT INTO KPI_CAUSE_ACTION (".$column.", PROJECT_NAME, KPI_ID, ADDED_BY, ADDED_ON, ".$KPIType.")
						VALUES ('".trim(addslashes($newVal))."', '".trim(addslashes($ProjectID))."', ".$KPIID.", ".$userID.",
								'".strtoupper(date('d/M/y'))."', 1)";
				}
				else {
					$query = "INSERT INTO KPI_CAUSE_ACTION (".$column.", PROJECT_NAME, KPI_ID, ADDED_BY, ADDED_ON)
						VALUES ('".trim(addslashes($newVal))."', '".trim(addslashes($ProjectID))."', ".$KPIID.", ".$userID.", '".strtoupper(date('d/M/y'))."')";
				}
			}
			
		}
		
		//echo $query;exit;
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
	
	public function getMonthlyCauseAction($KPIID, $Month){
		$this->oracle->openConnection('KPIDASHBOARD');
		$conn = $this->oracle->getConnection();
		$resultArr = array();
		
		$Month = strtoupper(str_replace("-", "/", $Month));
		
		$query = "SELECT CAUSE, ACTION 
				FROM KPI_CAUSE_ACTION
				WHERE  KPI_ID = ".$KPIID." AND MONTH = '".$Month."'
				AND MONTHLY = 1";
		$queryParse = oci_parse($conn, $query);
		oci_execute($queryParse);
		
		//echo $query;exit;
		while($row = oci_fetch_array($queryParse)){
			
			$resultArr['CAUSE'] = $row['CAUSE'];
			$resultArr['ACTION'] = $row['ACTION'];			
		} 
		 
		oci_free_statement($queryParse);
		oci_close($conn);
		
		return $resultArr;
	}
	
	public function updateMonthlyKPIAction($userID, $cause, $action, $kpiid, $month){
		$this->oracle->openConnection('KPIDASHBOARD');
		$conn = $this->oracle->getConnection();
		
		$month = strtoupper(str_replace("-", "/", $month));
		
		$query = "SELECT COUNT(*) AS COUNT FROM KPI_CAUSE_ACTION
				WHERE KPI_ID = ".$kpiid." AND MONTH = '".$month."'
				AND MONTHLY = 1";
		$queryParse = oci_parse($conn, $query);
		oci_execute($queryParse);
		$row = oci_fetch_array($queryParse);
		
		if($row['COUNT'] > 0){
			$query1 = "UPDATE KPI_CAUSE_ACTION SET
					CAUSE = '".trim(addslashes($cause))."',
					ACTION = '".trim(addslashes($action))."',					
					EDITED_BY = ".$userID.",
					EDITED_ON = '".strtoupper(date('d/M/y'))."'
					WHERE KPI_ID = ".$kpiid."";
				
		}
		else {		
			$query1 = "INSERT INTO KPI_CAUSE_ACTION (CAUSE, ACTION, MONTH, MONTHLY, KPI_ID, ADDED_BY, ADDED_ON)
					VALUES ('".trim(addslashes($cause))."', '".trim(addslashes($action))."', '".$month."', 1, ".$kpiid.", 
							".$userID.", '".strtoupper(date('d/M/y'))."')";				
		}
		//echo $query."<br>==";
		//echo $query1;exit;
		//$queryParse = oci_parse($conn, $query1);
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