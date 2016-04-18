<?php

namespace AppBundle\Entity;

/**
 * @author James Whitehead
 */
class FusionExecutionDetails
{
	
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
	private $iteration;
	
	/**
	 * @var string
	 */
	private $executionDate;
	
	/**
	 * @var string
	 */
	private $executionTime;
	
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
	private $totalRun;
	
	/**
	 * @var string
	 */
	private $totaltestcaseCount;
	/**
	 * @var string
	 */
	private $totalpasscasecount;
	/**
	 * @var string
	 */
	private $totalfailcasecount;
	
	
	private function calculateResults() {
		if (isset($this->testCases)) {
			$total = count($this->testCases);
			$passed = 0;
			$failed = 0;
			$inProgress = 0;
			$noResult = 0;
			
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
			}
			
			$this->results = array(
					"total" => $total,
					"passed" => $passed,
					"failed" => $failed,
					"in_progress" => $inProgress,
					"no_result" => $noResult
			);
		} else {
			$this->results = array(
					"total" => 0,
					"passed" => 0,
					"failed" => 0,
					"in_progress" => 0,
					"no_result" => 0
			);
		}
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
	
	public function getExecutionTime() {
		return $this->executionTime;
	}
	
	public function setExecutionTime($executionTime) {
		$this->executionTime = $executionTime;
		return $this;
	}
	
	public function getTestCases() {
		return $this->testCases;
	}
	
	public function setTestCases(array $testCases) {
		$this->testCases = $testCases;
		$this->calculateResults();
		return $this;
	}
	public function getFusionTestCases() {
		return $this->testCases;
	}
	
	public function setFusionTestCases(array $testCases) {
		$this->testCases = $testCases;
		return $this;
	}
	
	public function getResults() {
		return $this->results;
	}
	
	public function setResults(array $results) {
		$this->results = $results;
		return $this;
	}	
	
	
	public function getTotalRun(){
		return $this->totalRun;		
	}
	
	public function setTotalRun($totalRun){		
		$this->totalRun = $totalRun;
	}
	
	public function setTotalTestCaseCount($totaltestcaseCount){
		$this->totaltestcaseCount = $totaltestcaseCount;
		 
	}
	public function getTotalTestCaseCount(){
		return $this->totaltestcaseCount;
	}
	
	public function setTotalPassTestCaseCount($totalpasscasecount){
		$this->totalpasscasecount = $totalpasscasecount;
			
	}
	public function getTotalPassTestCaseCount(){
		return $this->totalpasscasecount;
	}
	
	public function setTotalFailTestCaseCount($totalfailcasecount){
		$this->totalfailcasecount = $totalfailcasecount;
			
	}
	public function getTotalFailTestCaseCount(){
		return $this->totalfailcasecount;
	}
	
}
