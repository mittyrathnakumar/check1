<?php

namespace AppBundle\Entity;

/**
 * @author James Whitehead
 */
class ExecutionDetails
{
	/**
	 * @var string
	 */
	private $project;
	
	/**
	 * @var string
	 */
	private $release;
	
	/**
	 * @var string
	 */
	private $environment;
	
	/**
	 * @var string
	 */
	private $executionType;
	
	/**
	 * @var string
	 */
	private $applicationType;
	
	/**
	 * @var string
	 */
	private $iteration;
	
	/**
	 * @var string
	 */
	private $executionDate;
	
	/**
	 * @var array
	 */
	private $testCases;
	
	/**
	 * @var array
	 */
	private $results;	

	/**
	 * @var int
	 */
	private $coreOrders;
	
	/**
	 * @var int
	 */
	private $openUIOrders;
	
	/**
	 * @var int
	 */
	private $TBUIOrders;
	
	/**
	 * @var int
	 */
	private $retrievels;
	
	/**
	 * @var int
	 */
	private $failed;
	
	/**
	 * @var int
	 */
	private $pass;
	
	/**
	 * @var int
	 */
	private $norun;
	
	/**
	 * @var int
	 */
	private $inProgress;
	
	/**
	 * @var int
	 */
	private $ordersFailedError;
	
	/**
	 * @var int
	 */
	private $ordersPass;
	
	/**
	 * @var int
	 */
	private $ordersNoRun;
	
	/**
	 * @var int
	 */
	private $ordersinProgress;
	
	/**
	 * @var int
	 */
	private $retrivelPass;
	
	/**
	 * @var int
	 */
	private $retrivelnoRun;
	
	/**
	 * @var int
	 */
	private $retrivelinProgress;
	
	/**
	 * @var int
	 */
	private $retrivelFailed;
	
	/**
	 * @var int
	 */
	private $orderCountforChart;
	
	/**
	 * @var int
	 */
	private $totalRun;
	
	/**
	 * @var array
	 */
	private $testcasecompletedcount;
	
	/**
	 * @var array
	 */
	private $testcaseassignedcount;
	
	/**
	 * @var array
	 */
	private	$testdatearr;
	
	/**
	 * @var array
	 */
	private	$executionCountArr;
	
	
	private function calculateResults($appType) {
		if (isset($this->testCases)) {
			$total = count($this->testCases);			
			if($appType == 'Siebel'){
				$passed = 0;
				$failed = 0;
				$inProgress = 0;
				$noResult = 0;
				
				$coreOrder = 0;
				$openUIOrder = 0;
				$tbUIOrder = 0;
				$retreivals = 0;
				
				$orderFailed = 0;
				$orderPassed = 0;
				$orderNoRun = 0;
				$orderProgressed = 0;
				$orderRetreivedPassed = 0;
				$orderRetreivedFailed = 0;			
				$orderRetreivedNoRun = 0;
				$orderRetreivedProgress = 0;
					
				
				foreach ($this->testCases as $testCase) {
					switch($testCase->getExecutionStatus()) {
						case 'Passed':
							$passed++;
							break;
						case 'Failed':
							$failed++;
							break;
						case 'Error':
							$failed++;
							break;
						case 'In-Progress':
							$inProgress++;
							break;
						default:
							$noResult++;
							break;
					}
					
				if($testCase->getScriptType() == 'ORDER' &&  ($testCase->getChannel() == 'CORE' || $testCase->getChannel() == 'CARE'))
					$coreOrder++;
						
				if($testCase->getScriptType() == 'ORDER' &&  $testCase->getChannel() == 'OPEN UI')
					$openUIOrder++;
							
				if($testCase->getScriptType() == 'ORDER' &&  $testCase->getChannel() == 'TBUI')
					$tbUIOrder++;
				
				if($testCase->getScriptType() == 'RETRIEVAL')
					$retreivals++;
				
				if($testCase->getScriptType() == 'ORDER' && ($testCase->getExecutionStatus() == 'Error' || $testCase->getExecutionStatus() == 'Failed'))
					$orderFailed++;
										
				if($testCase->getScriptType() == 'ORDER' && $testCase->getExecutionStatus() == 'Passed')
					$orderPassed++;
											
				if($testCase->getScriptType() == 'ORDER' && $testCase->getExecutionStatus() == 'NoResult')
					$orderNoRun++;
												
				if($testCase->getScriptType() == 'ORDER' && $testCase->getExecutionStatus() == 'In-Progress')
					$orderProgressed++;
													
				if($testCase->getScriptType() == 'RETRIEVAL' && $testCase->getExecutionStatus() == 'Passed')
					$orderRetreivedPassed++;
					
				if($testCase->getScriptType() == 'RETRIEVAL' && ($testCase->getExecutionStatus() == 'Error' || $testCase->getExecutionStatus() == 'Failed'))
					$orderRetreivedFailed++;
						
				if($testCase->getScriptType() == 'RETRIEVAL' && $testCase->getExecutionStatus() == 'NoResult')
					$orderRetreivedNoRun++;
				
				if($testCase->getScriptType() == 'RETRIEVAL' && $testCase->getExecutionStatus() == 'In-Progress')
					$orderRetreivedProgress++;
	
				}
				
				$this->results = array(
						"total" => $total,
						"passed" => $passed,
						"failed" => $failed,
						"in_progress" => $inProgress,
						"no_result" => $noResult,
						"coreOrder" => $coreOrder,				
						"openUIOrder" => $openUIOrder,
						"tbUIOrder" => $tbUIOrder,
						"retreivals" => $retreivals,
						"orderFailed" => $orderFailed,
						"orderPassed" => $orderPassed,
						"orderNoRun" => $orderNoRun,
						"orderProgressed" => $orderProgressed,
						"orderRetreivedPassed" => $orderRetreivedPassed,
						"orderRetreivedFailed" => $orderRetreivedFailed,
						"orderRetreivedNoRun" => $orderRetreivedNoRun,
						"orderRetreivedProgress" => $orderRetreivedProgress
						
				);
				
				
			}
			
			else if($appType == 'Oracle'){
				$totAccPay = 0;
				$totAccRec = 0;
				$totCSHMGM = 0;
				$totFIXASS = 0;			
				$totGENLED = 0;
				$totHRPAY = 0;
				$totINV = 0;
				$totOTL = 0;				
				$totORPUR = 0;
				$totORMAN = 0;
				$totPA = 0;
				$totFailed = 0;
				$totPassed = 0;
				$totNoRun = 0;
				$totProgress = 0;					
				
				foreach ($this->testCases as $testCase) {
					
					if($testCase->getChannel() == 'Accounts Payable')
						$totAccPay++;
					if($testCase->getChannel() == 'Accounts Receivable')
						$totAccRec++;
					if($testCase->getChannel() == 'Cash Management')
						$totCSHMGM++;
					if($testCase->getChannel() == 'Fixed Assets')
						$totFIXASS++;
					if($testCase->getChannel() == 'General Ledger')
						$totGENLED++;
					if($testCase->getChannel() == 'HR & Payroll')
						$totHRPAY++;
					if($testCase->getChannel() == 'Inventory')
						$totINV++;
					if($testCase->getChannel() == 'OTL')
						$totOTL++;
					if($testCase->getChannel() == 'Oracle Purchasing')
						$totORPUR++;
					if($testCase->getChannel() == 'Order Management')
						$totORMAN++;
					if($testCase->getChannel() == 'Project Accounting')
						$totPA++;
					if($testCase->getExecutionStatus() == 'Error' || $testCase->getExecutionStatus() == 'Failed')
						$totFailed++;					
					if($testCase->getExecutionStatus() == 'Passed')
						$totPassed++;							
					if($testCase->getExecutionStatus() == 'NoResult')
						$totNoRun++;													
					if($testCase->getExecutionStatus() == 'In-Progress')
						$totProgress++;
						
				}
				
				$this->results = array(
						"total" => $total,
						"totAccPay" => $totAccPay,
						"totAccRec" => $totAccRec,
						"totCSHMGM" => $totCSHMGM,
						"totFIXASS" => $totFIXASS,
						"totGENLED" => $totGENLED,
						"totHRPAY" => $totHRPAY,
						"totINV" => $totINV,
						"totOTL" => $totOTL,
						"totORPUR" => $totORPUR,
						"totORMAN" => $totORMAN,
						"totPA" => $totPA,
						"totFailed" => $totFailed,
						"totPassed" => $totPassed,
						"totNoRun" => $totNoRun,
						"totProgress" => $totProgress				
				);
				//var_dump($this->results);exit;
				
			}
			else if($appType == 'Tallyman'){

				$totSiebTall = 0;
				$totTallSieb = 0;
				$totFailed = 0;
				$totPassed = 0;
				$totNoRun = 0;
				$totProgress = 0;
				
				foreach ($this->testCases as $testCase) {
						
					if($testCase->getChannel() == 'Siebel-Tallyman')
						$totSiebTall++;
					if($testCase->getChannel() == 'Tallyman-Siebel')
						$totTallSieb++;
					if($testCase->getExecutionStatus() == 'Error' || $testCase->getExecutionStatus() == 'Failed')
						$totFailed++;
					if($testCase->getExecutionStatus() == 'Passed')
						$totPassed++;
					if($testCase->getExecutionStatus() == 'NoResult')
						$totNoRun++;
					if($testCase->getExecutionStatus() == 'In-Progress')
						$totProgress++;
				
				}
				
				$this->results = array(
					"total" => $total,
					"totSiebTall" => $totSiebTall,
					"totTallSieb" => $totTallSieb,
					"totFailed" => $totFailed,
					"totPassed" => $totPassed,
					"totNoRun" => $totNoRun,
					"totProgress" => $totProgress
				);
			} else {			
				
				
				if (isset($this->testCases)) {
						
					$total = 0;
					$descopedCount = 0;
					$notstartedCount = 0;
					$blockedCount = 0;
					$inprogressCount = 0;
					$inreviewCount = 0;
					$completedCount = 0;
						
					
					foreach ($this->testCases as $testCase) {
						switch($testCase->getExecutionStatus()) {
							case 'DESCOPED':
							$descopedCount++;
							$total++;
							break;
				
						case 'NOT STARTED':
							$notstartedCount++;
							$total++;
							break;
				
						case 'BLOCKED':
							$blockedCount++;
							$total++;
							break;
				
						case 'IN PROGRESS':
							$inprogressCount++;
							$total++;
							break;
								
						case 'IN REVIEW':
							$inreviewCount++;
							$total++;
							break;
				
						case 'COMPLETED':
							$completedCount++;
							$total++;
							break;
								
						}
					}				
					
						
					$this->results = array(
							"total" => $total,
							"descoped" => $descopedCount,
							"notstarted" => $notstartedCount,
							"blocked" => $blockedCount,
							"inprogress" => $inprogressCount,
							"inreview" => $inreviewCount,
							"completed" => $completedCount
					);
						
					//print_r($this->result);exit;
						
				} 
			}	
				
		} else {
			if($appType == 'Siebel'){
				$this->results = array(
						"total" => 0,
						"passed" => 0,
						"failed" => 0,
						"in_progress" => 0,
						"no_result" => 0,
						"coreOrder" => 0,
						"openUIOrder" => 0,
						"tbUIOrder" => 0,
						"retreivals" => 0,
						"orderFailed" => 0,
						"orderPassed" => 0,
						"orderNoRun" => 0,
						"orderProgressed" => 0,
						"orderRetreivedPassed" => 0,
						"orderRetreivedFailed" => 0,
						"orderRetreivedNoRun" => 0,
						"orderRetreivedProgress" => 0
						
				);
			}
			else if($appType == 'Oracle'){				
				$this->results = array(
						"total" => 0,
						"totAccPay" => 0,
						"totAccRec" => 0,
						"totCSHMGM" => 0,
						"totFIXASS" => 0,
						"totGENLED" => 0,
						"totHRPAY" => 0,
						"totINV" => 0,
						"totOTL" => 0,
						"totORPUR" => 0,
						"totORMAN" => 0,
						"totPA" => 0,
						"totFailed" => 0,
						"totPassed" => 0,
						"totNoRun" => 0,
						"totProgress" => 0
				);
			}
			else if($appType == 'Tallyman'){
				$this->results = array(
						"total" => 0,
						"totSiebTall" => 0,
						"totTallSieb" => 0,
						"totFailed" => 0,
						"totPassed" => 0,
						"totNoRun" => 0,
						"totProgress" => 0
				);
			} else {
						
					$this->results = array(
							"total" => 0,
							"descoped" => 0,
							"notstarted" => 0,
							"blocked" => 0,
							"inprogress" => 0,
							"inreview" => 0,
							"completed" => 0
					);
						
			}
				
		}
		
		
	}
	
	public function getProject() {
		return $this->project;
	}
	
	public function setProject($project) {
		$this->project = $project;
		return $this;
	}
	
	public function getRelease() {
		return $this->release;
	}
	
	public function setRelease($release) {
		$this->release = $release;
		return $this;
	}
	
	public function getEnvironment() {
		return $this->environment;
	}
	
	public function setEnvironment($environment) {
		$this->environment = $environment;
		return $this;
	}
	
	public function getExecutionType() {
		return $this->executionType;
	}
	
	public function setExecutionType($executionType) {
		$this->executionType = $executionType;
		return $this;
	}
	
	public function getIteration() {
		return $this->iteration;
	}
	
	public function setIteration($iteration) {
		$this->iteration = $iteration;
		return $this;
	}
	
	public function getExecutionDate() {
		return $this->executionDate;
	}
	
	public function setExecutionDate($executionDate) {
		$this->executionDate = $executionDate;
		return $this;
	}
	
	public function getApplicationType() {
		return $this->applicationType;
	}
	
	public function setApplicationType($applicationType) {
		$this->applicationType = $applicationType;		
		return $this;
	}	
	
	public function getTestCases() {
		return $this->testCases;
	}
	
	public function setTestCases(array $testCases) {		
		$this->testCases = $testCases;		
		$this->calculateResults($this->getApplicationType());
		return $this;
	}

	
	public function getResults() {
		return $this->results;
	}
	
	public function setResults(array $results) {
		$this->results = $results;
		return $this;
	}
	
	public function setOrderChartCount($label, $count) {
		//echo 'in';exit;
		return $this->orderCountforChart .= $label."_".parseInt($count).",";
	}
	
	public function getOrderChartCount(){
		return $this->orderCountforChart; 
	}
	
	public function getTotalRun(){
		return $this->totalRun;		
	}
	
	public function setTotalRun($totalRun){		
		$this->totalRun = $totalRun;
	}
	
	public function getTestCaseAssignedCount() {
		return $this->testcaseassignedcount;
	}
	
	public function setTestCaseAssignedCount(array $testcaseassignedcount) {
		$this->testcaseassignedcount = $testcaseassignedcount;
		return $this;
	}
	
	public function getTestCaseCompletedCount() {
		return $this->testcasecompletedcount;
	}
	
	public function setTestCaseCompletedCount(array $testcasecompletedcount) {
		$this->testcasecompletedcount = $testcasecompletedcount;
		return $this;
	}
	
	public function getDateArray() {
		return $this->testdatearr;
	}
	
	public function setDateArray(array $datearr) {
		$this->testdatearr = $datearr;
		return $this;
	}

	
	public function getExecutionTypeCountArray() {
		return $this->executionCountArr;
	}
	
	public function setExecutionTypeCountArray(array $executiontypecountarr) {
		$this->executionCountArr = $executiontypecountarr;
		return $this;
	}
	
	
	
	
	
	
}
