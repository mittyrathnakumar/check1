<?php

namespace AppBundle\Service;

class Constants
{		
	
	public function getSiteRootPath(){
		return $_SERVER['DOCUMENT_ROOT'].'/ART';
	}
	
	public function getSiteRootWebPath(){
		return $_SERVER['DOCUMENT_ROOT'].'/ART/web';
	}
	
	public function getTestDataUploadPath(){
		return $_SERVER['DOCUMENT_ROOT'].'/ART/web/uploads';
	}
	
	public function getServicePath(){
		return $_SERVER['DOCUMENT_ROOT'].'/ART/src/AppBundle/Service';
	}
	
	public function getVendorPath(){
		return $_SERVER['DOCUMENT_ROOT'].'/ART/vendor';
	}
	
	public function getPHPExcelClassPath(){
		return $_SERVER['DOCUMENT_ROOT'].'/ART/vendor/PHPExcel';
	}
	
	public function getProjects(){
		$Projects = array("ETL_ISS_Replacement", "Lebera_APN", "HSS_Virt", "PGW_WBRM");
		return $Projects;
	}
	
	
	public function getKPIShortName($KPI = ""){
		if($KPI){
			switch($KPI){
				
				case "Effectiveness of Test Process" :
					return $KPIShornames = array("ETA");
				break;
				
				case "ST Defect Density" :
					return $KPIShornames = array("ST-DD");
				break;
				
				case "SIT Defect Density" :
					return $KPIShornames = array("SIT-DD");
				break;					
				
				case "UAT Defect Density" :
					return $KPIShornames = array("UAT-DD");
				break;					
				
				case "Reusability" :
					return $KPIShornames = array("RUSE");
				break;					
				
			}
		}
		else
			$KPIShornames = array("ETA", "ST-DD", "SIT-DD", "UAT-DD", "RUSE");
		
		return $KPIShornames;
	}
	
	public function getSLAs(){
		
		$SLAs = array(
				"Effectiveness of Test Process" => "15",
				"ST Defect Density" => "10",
				"SIT Defect Density" => "10",
				"UAT Defect Density" => "3"				
		);
		
		return $SLAs;
		
	}
	
	public function getKPINomDenom($KPIName){
		$Values = array();
		
		switch($KPIName){
			case "Effectiveness of Test Process" :
				
				$Values[0] = 'Total no. of Rejected/ Withdrawn defects';
				$Values[1] = 'Total no. of defects';
				
				break;
					
			case "ST Defect Density" :
				
				$Values[0] = 'Number of  Functional defects found in SIT/UAT where 1st Possible detection is ST';
				$Values[1] = 'Number of  Functional defects';
				
				break;
					
			case "SIT Defect Density" :
				
				$Values[0] = 'Functional Defects found in SIT';
				$Values[1] = 'Total no. of passed test cases in SIT';
				
				break;
			
			case "UAT Defect Density" :
				
				$Values[0] = 'Functional Defects found in UAT';
				$Values[1] = 'Total no. of passed test cases in UAT';
				
				break;
			
		}		
		
		return $Values;
		
	}
	
	
	public function getKPICaptions($KPIName){
		
		$Captions = array();
		
		switch($KPIName){
			
			case "Effectiveness of Test Process" :			
				return html_entity_decode("Measured when the scope of project is Testing.&#013;Data Source: QC database");
			break;
			
			case "ST Defect Density" :
				return html_entity_decode("Measured when the scope of project is Development.&#013;Data Source: QC database");
			break;
			
			case "SIT Defect Density" :
				return html_entity_decode("Measured when the scope of project is Development.&#013;Data Source: QC database");
				break;

			case "UAT Defect Density" :
				return html_entity_decode("Measured when the scope of project is Development & Testing.&#013;Data Source: QC database");
				break;
				
			case "Delivery Slippage" :
				return html_entity_decode("Measured when the scope of project is Development & Testing.&#013;Data Source: Information is received from the Delivery Managers");
				break;
				
			case "Defect free deliveries (P1 and P2)" :
				return html_entity_decode("Measured when the scope of project is Development.&#013;Data Source: Information is received from the Delivery Managers");
				break;						

			case "# of Defect (P3 and P4)" :
				return html_entity_decode("Measured when the scope of project is Development.&#013;Data Source: Information is received from the Delivery Managers");
				break;
					
			case "Total Defect Containment Effectiveness (TDCE)" :
				return html_entity_decode("Measured when the scope of project is Development.&#013;Data Source: Pre Production defects: QC database, Post production defects: Delivery");
				break;
			
			case "Automation" :
				return html_entity_decode("Measured when the scope of project is Testing.&#013;Data Source: QC database");
				break;
			
			case "Reusability" :
				return html_entity_decode("Measured when the scope of project is Testing.&#013;Data Source: QC database");
				break;
			
			case "Intake process" :
				return html_entity_decode("Measured when the scope of project is Development & Testing.&#013;Data Source: QC database");
				break;

			case "Quality of Estimation" :
				return html_entity_decode("Measured when the scope of project is Development & Testing.&#013;Data Source: QC database");
				break;
			
			case "ST Automation" :
				return html_entity_decode("Measured when the scope of project is Development.&#013;Data Source: Information is received from the Delivery Managers");
				break;
			
			case "Production Test Accounts" :
				return html_entity_decode("Measured when the scope of project is Testing.&#013;Data Source: Report is shared by Gauri");
				break;
			
			case "Documentation" :
				return html_entity_decode("Measured when the scope of project is Testing.&#013;Data Source: Information is received from the Delivery Managers");
				break;
					
			case "Knowledge Management" :
				return html_entity_decode("Measured when the scope of project is Development & Testing.&#013;Data Source: Information is received from the Delivery Managers");
				break;
					
			case "Defect Density (P1 and P2) - Production + 60 days" :
				return html_entity_decode("Measured when the scope of project is Development.&#013;Data Source: Information is received from the Delivery Managers");
				break;
					
			case "Defect Density (P3 and P4) - Production + 60 days" :
				return html_entity_decode("Measured when the scope of project is Development.&#013;Data Source: Information is received from the Delivery Managers");
				break;
					
			case "Defect Density (P1 and P2) - Production + 61 days to 365 days" :
				return html_entity_decode("Measured when the scope of project is Development.&#013;Data Source: Information is received from the Delivery Managers");
				break;				
			
			case "Defect Density (P3 and P4) - Production + 61 days to 365 days" :
				return html_entity_decode("Measured when the scope of project is Development.&#013;Data Source: Information is received from the Delivery Managers");
			break;
				
		}		
		
	}
	
	public function getKPIQuery($kpi, $table_name, $cycle_ids, $queryno = ""){		
		$queries = array();		
		switch($kpi){
			
			// TEST_Rel_16_1_DB
			
			case "ETA" :	
				
				$queries['q1'] = "SELECT count(UNIQUE(AU_ENTITY_ID)) AS Rejected_or_Withdrawn_Defects 
						FROM ".$table_name.".AUDIT_LOG INNER JOIN ".$table_name.".AUDIT_PROPERTIES 
						ON AU_ACTION_ID = AP_ACTION_ID 
						AND AU_ENTITY_TYPE = 'BUG' INNER JOIN ".$table_name.".BUG 
						ON AU_ENTITY_ID = BG_BUG_ID
 						AND bg_detected_in_rcyc in (".$cycle_ids.") 
						AND BG_USER_05 NOT IN('Valid Defect','Production Behaviour')
						WHERE AP_NEW_VALUE IN ('Rejected','Withdrawn') 
						OR AP_OLD_VAlUE IN ('Rejected','Withdrawn')";
				
				$queries['q2'] = "SELECT COUNT(*) AS q2Count 
						FROM ".$table_name.".BUG 
						WHERE bg_detected_in_rcyc IN (".$cycle_ids.") ";			
			
			break;
			
			case "ST-DD" :
				
				$queries['q1'] = "SELECT COUNT(*) AS Wd_hv_identified_in_ST 
						FROM ".$table_name.".BUG
						WHERE BG_USER_03 = 'Functional'
						AND bg_detected_in_rcyc IN (".$cycle_ids.")
						AND BG_USER_07 = '02. ST'
						AND BG_STATUS NOT IN ('Rejected','Withdrawn','Accepted into Production','Deferred')
						AND BG_PROJECT NOT IN('My Account','OLS (ATG)','BRM (Infranet)','QAS')";
				
			
				$queries['q2'] = "SELECT COUNT(*) AS Functional_Defects_found
			    		FROM ".$table_name.".BUG 
			    		WHERE BG_USER_03 = 'Functional' 
			    		AND bg_detected_in_rcyc IN (".$cycle_ids.")
			    		AND BG_STATUS NOT IN ('Rejected', 'Withdrawn', 'Accepted into Production', 'Deferred')";									
			break;

			case "SIT-DD" :				
			
				$queries['q1'] = "SELECT COUNT(*) AS Functional_SIT_Defects 
						FROM ".$table_name.".BUG 
						WHERE BG_USER_01 = '04. SIT'
 						AND BG_USER_03 = 'Functional'
						AND bg_detected_in_rcyc IN (".$cycle_ids.")
						AND BG_STATUS NOT IN ('Rejected','Withdrawn','Accepted  into Production','Deferred')
 						AND BG_PROJECT NOT IN('My Account','OLS (ATG)','BRM (Infranet)','QAS')";
				
				$queries['q2'] = "SELECT SUM(PASSED_TESTCASES) AS PASSED_TESTCASES 
						FROM 
						(SELECT tc_assign_rcyc AS CycleID, tc_status AS Status, COUNT(1) AS PASSED_TESTCASES
						FROM ".$table_name.".testcycl WHERE tc_assign_rcyc IN (".$cycle_ids.")
						AND tc_status IN ('Passed') GROUP BY tc_assign_rcyc, tc_status 
						ORDER BY tc_assign_rcyc , tc_status)";				
						
			break;
			
			case "UAT-DD" :
				
				$queries['q1'] = "SELECT COUNT(*) AS Functional_UAT_defects 
						FROM ".$table_name.".BUG 
						WHERE BG_USER_01 = '05. UAT'
						AND BG_USER_03 = 'Functional'
						AND bg_detected_in_rcyc IN (".$cycle_ids.")
						AND BG_STATUS NOT IN ('Rejected','Withdrawn','Accepted into Production','Deferred')
  						AND BG_USER_05 NOT IN('Business Requests')";	
				
				$queries['q2'] = "SELECT SUM(PASSED_TESTCASES) AS PASSED_TESTCASES 
						FROM 
						(SELECT tc_assign_rcyc AS CycleID, tc_status AS Status, COUNT(1) AS PASSED_TESTCASES 
						FROM ".$table_name.".testcycl WHERE tc_assign_rcyc IN (".$cycle_ids.") 
						AND tc_status  IN ('Passed') GROUP BY tc_assign_rcyc, tc_status 
						ORDER BY tc_assign_rcyc ,tc_status)";					
							
				break;				
			
		}
	
		
		if($queryno == 1)
			return $queries['q1'];
			
		else if($queryno == 2)
			return $queries['q2'];
		
		else					
			return $queries;		
		
	}
	
 
	
}