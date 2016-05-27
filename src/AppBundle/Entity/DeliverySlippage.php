<?php
namespace AppBundle\Entity;

/**
 * @author Mitty
 */

class DeliverySlippage
{

	/**
	 * @var int
	 */
	private $projectId;	
	
	/**
	 * @var string
	 */
	private $projectName;
	
	/**
	 * @var string
	 */
	private $estimatedProdLiveDate;
	
	/**
	 * @var string
	 */
	private $deliveryDate;
	
	/**
	 * @var string
	 */
	private $differenceInDate;
	
	public function setProjectId($projectId){
		$this->projectId = $projectId;
	}
	
	public function getProjectId(){
		return $this->projectId;
	}
	
	public function setProjectName($projectName){
		$this->projectName = $projectName;
	}
	
	public function getProjectName(){
		return $this->projectName;
	}
	public function setEstimatedProdLiveDate($estimatedProdLiveDate){
		$this->estimatedProdLiveDate = $estimatedProdLiveDate;
	}
	
	public function getEstimatedProdLiveDate(){
		return $this->estimatedProdLiveDate;
	}
	public function setDeliveryDate($deliveryDate){
		$this->deliveryDate = $deliveryDate;
	}
	
	public function getDeliveryDate(){
		return $this->deliveryDate;
	}
	public function setDifferenceInDate($differenceInDate){
		$this->differenceInDate = $differenceInDate;
	}
	
	public function getDifferenceInDate(){
		return $this->differenceInDate;
	}

}