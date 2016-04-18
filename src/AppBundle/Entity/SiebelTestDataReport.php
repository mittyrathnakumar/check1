<?php

namespace AppBundle\Entity;

/**
 * @author James Whitehead
 */
class SiebelTestDataReport
{	
	
	/**
	 * @var string
	 */
	private $MSISDN;
	
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
	private $consumerType;
	
	/**
	 * @var string
	 */
	private $proposition;
	
	/**
	 * @var string
	 */
	private $siebelPlan;
	
	/**
	 * @var int
	 */
	private $sim;
	
	/**
	 * @var int
	 */
	private $imei;	

	/**
	 * @var string
	 */
	private $creationDate;	
	
	
	public function getMSISDN() {
		return $this->MSISDN;
	}
	
	public function setMSISDN($MSISDN) {
		$this->MSISDN = $MSISDN;
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
	
	public function getConsumerType() {
		return $this->consumerType;
	}
	
	public function setConsumerType($consumerType) {
		$this->consumerType = $consumerType;
		return $this;
	}
	
	public function getProposition() {
		return $this->proposition;
	}
	
	public function setProposition($proposition) {
		$this->proposition = $proposition;
		return $this;
	}
	
	public function getSiebelPlan() {
		return $this->siebelPlan;
	}
	
	public function setSiebelPlan($siebelPlan) {
		$this->siebelPlan = $siebelPlan;
		return $this;
	}
	
	public function getSim() {
		return $this->sim;
	}
	
	public function setSim($sim) {
		$this->sim = $sim;
		return $this;
	}
	
	public function getImei() {
		return $this->imei;
	}
	
	public function setImei($imei) {
		$this->imei = $imei;		
		return $this;
	}
	
	public function getCreationDate() {
		return $this->creationDate;
	}
	
	public function setCreationDate($creationDate) {
		$this->creationDate = $creationDate;
		return $this;
	}
	public function getOrderStatusClass() {
		return $this->orderStatusClass;
	}
	
	public function setOrderStatusClass($orderStatus) {
		switch ($orderStatus){			
			case "Complete" :
				$this->orderStatusClass = "COMPLETE";
			break;

			case "Failed" :
				$this->orderStatusClass = "DELAYED";
			break;
		
			case "Provisioning Error" :
				$this->orderStatusClass = "DELAYED";
			break;
			
			default :
				$this->orderStatusClass = "DEFAULT";		
			break;
			
		}		
		return $this;
	}
	
	
}
