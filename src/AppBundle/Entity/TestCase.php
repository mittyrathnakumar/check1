<?php

namespace AppBundle\Entity;

/**
 * @author James Whitehead
 */
class TestCase
{
		
	/**
	 * @var string
	 */
	private $testCaseName;
	
	/**
	 * @var string
	 */
	private $executionDate;
	
	/**
	 * @var string
	 */
	private $machine;
	
	/**
	 * @var string
	 */
	private $scriptType;
	
	/**
	 * @var string
	 */
	private $executionType;
	
	/**
	 * @var string
	 */
	private $testCaseType;
	
	/**
	 * @var string
	 */
	private $assignedTo;
	
	/**
	 * @var string
	 */
	private $assignedDate;
	
	/**
	 * @var string
	 */
	private $comments;
	
	/**
	 * @var string
	 */
	private $channel;
	
	/**
	 * @var string
	 */
	private $environment;
	
	/**
	 * @var string
	 */
	private $testData;
	
	/**
	 * @var string
	 */
	private $orderNumber;
	
	/**
	 * @var string
	 */
	private $orderStatus;
	
	/**
	 * @var string
	 */
	private $executionStatus;
	
	/**
	 * @var int
	 */
	private $scriptTotalRun;
	
	/**
	 * @var int
	 */
	private $scriptTotalPass;
	
	/**
	 * @var int
	 */
	private $scriptTotalFail;
	
	/**
	 * @var int
	 */
	private $scriptTotalInProgress;
	
	/**
	 * @var int
	 */
	private $scriptTotalNoRun;

	/**
	 * @var array
	 */
	
	private $channelTestCases;	
	
	
	public function getOrderStatusClass() {
		switch ($this->orderStatus) {
			case 'Complete':
				return 'success';
				break;
			case 'Completed':
				return 'success';
				break;
			case 'Failed':
				return 'danger';
				break;
			case 'Submitted':
				return 'info';
				break;
			case 'Pending':
				return 'warning';
				break;
			default:
				return '';
				break;
		}
	}
	
	/**
	 * @return string
	 */
	public function getExecutionStatusClass() {
		switch ($this->executionStatus) {
			case 'Passed':
				return 'success';
				break;
			case 'Failed':
				return 'danger';
				break;
			case 'Positive':
				return 'success';
				break;
			case 'Negative':
				return 'danger';
				break;
			case 'In-Progress':
				return 'info';
				break;
			case 'Blocked':
				return 'warning';
				break;
				
			
				
			// Automation DSR Specific classes
			
			case 'IN PROGRESS':
				return 'info';
				break;
			case 'BLOCKED':
				return 'warning';
				break;
			case 'COMPLETED':
				return 'success';
				break;
			case 'DESCOPED':
				return 'DEFAULT';
				break;				
			case 'IN REVIEW':
				return 'IN-REVIEW';
				break;
			default:
				return '';
				break;
			
		}
	}	
	
	
	/*private function calculateStatus(){
		if (isset($this->executionStatus)) {
			
			$total = count($this->executionStatus);
			$descopedCount = 0;
			$notstartedCount = 0;
			$blockedCount = 0;
			$inprogressCount = 0;
			$inreviewCount = 0;
			$completedCount = 0;			
			
			
			switch($this->executionStatus){
				case 'DESCOPED':
					$descopedCount++;
				break;
				
				case 'NOT STARTED':
					$notstartedCount++;
				break;

				case 'BLOCKED':
					$blockedCount++;
				break;
				
				case 'IN PROGRESS':
					$inprogressCount++;
				break;
					
				case 'IN REVIEW':
					$inreviewCount++;
				break;
				
				case 'COMPLETED':
					$completedCount++;
				break;			
							
			}
			
			$this->result = array(
				"total" => $total,
				"descoped" => $descopedCount,
				"notstarted" => $notstartedCount,
				"blocked" => $blockedCount,
				"inprogress" => $inprogressCount,
				"inreview" => $inreviewCount,
				"completed" => $completedCount					
			);
			
			//print_r($this->result);exit;
			
		} else {
			
			$this->result = array(
					"total" => 0,
					"descoped" => 0,
					"notstarted" => 0,
					"blocked" => 0,
					"inprogress" => 0,
					"inreview" => 0,
					"completed" => 0
			);
			
		}
	}*/
	

	public function getTestCaseName() {
		return $this->testCaseName;
	}
	
	public function setTestCaseName($testCaseName) {
		$this->testCaseName = $testCaseName;
		return $this;
	}
	
	public function getExecutionDate() {
		return $this->executionDate;
	}
	
	public function setExecutionDate($executionDate) {
		$this->executionDate = $executionDate;
		return $this;
	}
	
	public function getMachine() {
		return $this->machine;
	}
	
	public function setMachine($machine) {
		$this->machine = $machine;
		return $this;
	}
	
	public function getScriptType() {
		return $this->scriptType;
	}
	
	public function setScriptType($scriptType) {
		$this->scriptType = $scriptType;
		return $this;
	}
	
	public function getExecutionType() {
		return $this->executionType;
	}
	
	public function setExecutionType($executionType) {
		$this->executionType = $executionType;
		return $this;
	}
	
	public function getTestCaseType() {
		return $this->testCaseType;
	}
	
	public function setTestCaseType($testcaseType) {
		$this->testCaseType = $testcaseType;
		$this->executionStatus = $testcaseType;
		return $this;
	}
	
	public function getEnvironment() {
		return $this->environment;
	}
	
	public function setEnvironment($environment) {
		$this->environment = $environment;
		return $this;
	}
	
	public function getAssignedTo() {
		return $this->assignedTo;
	}
	
	public function setAssignedTo($assignedto) {
		$this->assignedTo = $assignedto;
		return $this;
	}
	
	public function getAssignedDate() {
		return $this->assignedDate;
	}
	
	public function setAssignedDate($assigneddate) {
		$this->assignedDate = $assigneddate;
		return $this;
	}
	
	public function getComments() {
		return $this->comments;
	}
	
	public function setComments($comments) {
		$this->comments = $comments;
		return $this;
	}
	
	public function getChannel() {
		return $this->channel;
	}
	
	public function setChannel($channel) {
		$this->channel = $channel;
		return $this;
	}
	
	public function getTestData() {
		return $this->testData;
	}
	
	public function setTestData($testData) {
		$this->testData = $testData;
		return $this;
	}
	
	public function getOrderNumber() {
		return $this->orderNumber;
	}
	
	public function setOrderNumber($orderNumber) {
		$this->orderNumber = $orderNumber;
		return $this;
	}
	
	public function getOrderStatus() {
		return $this->orderStatus;
	}
	
	public function setOrderStatus($orderStatus) {
		$this->orderStatus = $orderStatus;
		return $this;
	}
	
	public function getExecutionStatus() {
		return $this->executionStatus;
	}
	
	public function setExecutionStatus($executionStatus) {
		$this->executionStatus = $executionStatus;		
		return $this;
	}
	
	public function getScriptTotalRun() {
		return $this->scriptTotalRun;
	}
	
	public function setScriptTotalRun($totalrun) {
		$this->scriptTotalRun = $totalrun;
		return $this;
	}
	
	public function getScriptTotalPass() {
		return $this->scriptTotalPass;
	}
	
	public function setScriptTotalPass($totalpass) {
		$this->scriptTotalPass = $totalpass;
		return $this;
	}
	
	public function getScriptTotalFail() {
		return $this->scriptTotalFail;
	}
	
	public function setScriptTotalFail($totalfail) {
		$this->scriptTotalFail = $totalfail;
		return $this;
	}
	
	public function getScriptTotalInProgress() {
		return $this->scriptTotalInProgress;
	}
	
	public function setScriptTotalInProgress($totalinprogress) {
		$this->scriptTotalInProgress = $totalinprogress;
		return $this;
	}
	
	public function getScriptTotalNoRun() {
		return $this->scriptTotalNoRun;
	}
	
	public function setScriptTotalNoRun($totalnorun) {
		$this->scriptTotalNoRun = $totalnorun;
		return $this;
	}	
	
	public function getChannelTestCases() {
		return $this->channelTestCases;
	}
	
	public function setChannelTestCases(array $testCases) {
		$this->channelTestCases = $testCases;		
		return $this;
	}
}
