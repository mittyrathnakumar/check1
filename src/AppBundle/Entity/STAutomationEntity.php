<?php
namespace AppBundle\Entity;

/**
 * @author Mitty
 */

class STAutomationEntity
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
	private $stAutomatedTestCases;
	
	/**
	 * @var string
	 */
	private $stTotalTestCases;
	
	/**
	 * @var string
	 */
	private $stAutomation;
	
	
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
	public function setSTAutomatedTestCases($stAutomatedTestCases){
		$this->stAutomatedTestCases = $stAutomatedTestCases;
	}
	
	public function getSTAutomatedTestCases(){
		return $this->stAutomatedTestCases;
	}
	public function setSTTotalTestCases($stTotalTestCases){
		$this->stTotalTestCases = $stTotalTestCases;
	}
	
	public function getSTTotalTestCases(){
		return $this->stTotalTestCases;
	}
	public function setSTAutomation($stAutomation){
		$this->stAutomation = $stAutomation;
	}
	
	public function getSTAutomation(){
		return $this->stAutomation;
	}

}