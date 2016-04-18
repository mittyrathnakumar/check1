<?php
namespace AppBundle\Entity;

class AutomationMatrices
{
	
	/**
	 * @var string
	 */
	private $envName;
	
	/**
	 * @var int
	 */
	private $envCount;
	
	
	public function setExecutionType($envName){
		$this->envName = $envName;
	}
	
	public function getExecutionType(){
		return $this->envName;
	}
	
	public function setExecutionTotal($envtotal){
		$this->envCount = $envtotal;
	}
	
	public function getExecutionTotal(){
		return $this->envCount;
	}
}