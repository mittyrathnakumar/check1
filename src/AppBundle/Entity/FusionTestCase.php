<?php

namespace AppBundle\Entity;

/**
 * @author James Whitehead
 */
class FusionTestCase
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
	 * @var string
	 */
	private $serviceName;
	
	/**
	 * @var string
	 */
	private $operationName;
	
	/**
	 * @var string
	 */
	private $totalcaseCount;
	
	/**
	 * @var string
	 */
	private $testcasePassCount;
	
	/**
	 * @var string
	 */
	private $testcaseFailCount;
	
	/**
	 * @var string 
	 */
	private $xpath;
	
	/**
	 * @var string
	 */
	private $expectedvalue;
	
	/**
	 * @var string
	 */
	private $actualvalue;
	
	/**
	 * @var string
	 */
	private $assertionstatus;
	
	/*
	/**
	 * @var string
	 */
	//private $xml1;
	
	/**
	 * @var string
	 */
	//private $xml2;
	

	
	/**
	 * @return string
	 */
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
			case 'In-Progress':
				return 'info';
				break;
			case 'Blocked':
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
	public function getAssertionStatusClass() {
		switch ($this->assertionstatus) {
			case 'Passed':
				return 'success';
				break;
			case 'Failed':
				return 'danger';
				break;
			case 'Excluded':
				return 'info';
				break;
			case 'NoResult':
				return 'warning';
				break;
			default:
				return '';
				break;
		}
	}
	
	
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
	
	public function getServicename() {
		return $this->serviceName;
	}
	
	public function setServicename($servicename) {
		$this->serviceName = $servicename;
		return $this;
	}
	
	public function getOperationname() {
		return $this->operationName;
	}
	
	public function setOperationname($operationname) {
		$this->operationName = $operationname;
		return $this;
	}
	
	public function getTotalTestCount() {
		return $this->totalcaseCount;
	}
	
	public function setTotalTestCount($totaltestcasecount) {
		$this->totalcaseCount = $totaltestcasecount;
		return $this;
	}
	
	
	public function getOverallExecutionStatus() {
		return $this->executionStatus;
	}
	
	public function setOverallExecutionStatus($executionStatus) {
		$this->executionStatus = $executionStatus;
		return $this;
	}
	public function getTestPassCount() {
		return $this->testcasePassCount;
	}
	
	public function setTestPassCount($totalpasscount) {
		$this->testcasePassCount = $totalpasscount;
		return $this;
	}
	public function getTestFailCount() {
		return $this->testcaseFailCount;
	}
	
	public function setTestFailCount($totalfailcount) {
		$this->testcaseFailCount = $totalfailcount;
		return $this;
	}
	
	public function getXPath() {
		return $this->xpath;
	}
	
	public function setXPath($xpath) {
		$this->xpath = $xpath;
		return $this;
	}
	
	public function getExpectedValue() {
		return $this->expectedvalue;
	}
	
	public function setExpectedValue($expectedvalue) {
		$this->expectedvalue = $expectedvalue;
		return $this;
	}
	
	public function getActualValue() {
		return $this->actualvalue;
	}
	
	public function setActualValue($actualvalue) {
		$this->actualvalue = $actualvalue;
		return $this;
	}
	public function getAssertionStatus() {
		return $this->assertionstatus;
	}
	
	public function setAssertionStatus($assertionstatus) {
		$this->assertionstatus = $assertionstatus;
		return $this;
	}	

	/*public function getXml1() {
		return $this->xml1;
	}
	
	public function setXml1($xml1) {
		$this->xml1 = $xml1;
		return $this;
	}
	public function getXml2() {
		return $this->xml2;
	}
	
	public function setXml2($xml2) {
		$this->xml2 = $xml2;
		return $this;
	}
	*/
}
