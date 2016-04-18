<?php

namespace AppBundle\Entity;

/**
 * @author James Whitehead
 */
class AutomationSuite
{
	
	/**
	 * @var string
	 */
	private $suitename;
	
	/**
	 * @var string
	 */
	private $servicename;
	
	/**
	 * @var int
	 */
	private $testcount;
	
	/**
	 * @var int
	 */
	private $totaltestcount;
	
	/**
	 * @var string
	 */
	private $ciparameter;
	
	/**
	 * @var string
	 */
	private $toscanodepath;
	
	/**
	 * @var string
	 */
	private $testcase;
	
	/**
	 * @var string
	 */
	private $executionlistpath;
	
	
	public function getSuiteName() {
		return $this->suitename;
	}
	
	public function setSuiteName($suitename) {
		$this->suitename = $suitename;
		return $this;
	}
	
	public function getServiceName() {
		return $this->servicename;
	}
	
	public function setServiceName($servicename) {
		$this->servicename = $servicename;
		return $this;
	}
	
	public function getTestCount() {
		return $this->testcount;
	}
	
	public function setTestCount($testcount) {
		$this->testcount = $testcount;		
		return $this;
	}
	
	public function settotaltestcasecount($totalcount){
		$this->totaltestcount = $totalcount;
	}
	
	public function gettotaltestcasecount(){
		return $this->totaltestcount;
	}
	
	public function getCIParameter() {
		return $this->ciparameter;
	}
	
	public function setCIParameter($ciparameter) {
		$this->ciparameter = $ciparameter;
		return $this;
	}
	
	public function getExecutionListPath() {
		return $this->toscanodepath;
	}
	
	public function setExecutionListPath($toscanodepath) {
		$this->toscanodepath = $toscanodepath;
		return $this;
	}
	
	public function getTestCase() {
		return $this->testcase;
	}
	
	public function setTestCase($testcase) {
		$this->testcase = $testcase;
		return $this;
	}
	
		
}
