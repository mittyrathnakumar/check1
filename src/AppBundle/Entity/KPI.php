<?php
namespace AppBundle\Entity;

class KPI
{

	/**
	 * @var int
	 */
	private $project_id;
	
	/**
	 * @var string
	 */
	private $project_name;
	
	/**
	 * @var string
	 */
	private $kpi_name;
	
	/**
	 * @var int
	 */
	private $kpi_id;

	/**
	 * @var string
	 */
	private $operator;

	/**
	 * @var string
	 */
	private $sla_value;
	
	/**
	 * @var string
	 */
	private $in_qc;
	
	/**
	 * @var string
	 */
	private $kpi_short_name;
	
	/**
	 * @var string
	 */
	private $scope;
	
	/**
	 * @var int
	 */
	private $p1p2;
	
	/**
	 * @var int
	 */
	private $p3p4;
	
	
	
	
	public function setKPIID($kpi_id){
		$this->kpi_id = $kpi_id;
	}
	
	public function getKPIID(){
		return $this->kpi_id;
	}	
	
	
	public function setProjectID($project_id){
		$this->project_id = $project_id;
	}
	
	public function getProjectID(){
		return $this->project_id;
	}
	
	public function setProjectName($project_name){
		$this->project_name = $project_name;
	}
	
	public function getProjectName(){
		return $this->project_name;
	}
	
	public function setKPIName($kpi_name){
		$this->kpi_name = $kpi_name;		
	}
	
	public function getKPIName(){
		return $this->kpi_name;
	}
	
	public function setOperator($operator){
		$this->operator = $operator;
	}
	
	public function getOperator(){
		return $this->operator;
	}
	
	public function setSLAValue($sla_value){
		$this->sla_value = $sla_value;
	}
	
	public function getSLAValue(){
		return $this->sla_value;
	}
	
	public function setInQC($inQC){
		$this->in_qc = $inQC;
	}
	
	public function getInQC(){
		return $this->in_qc;
	}
	
	public function setKPIShortName($kpi_short_name){
		$this->kpi_short_name = $kpi_short_name;
	}
	
	public function getKPIShortName(){
		return $this->kpi_short_name;
	}
	
	public function setScope($scope){
		$this->scope = $scope;
	}
	
	public function getScope(){
		return $this->scope;
	}
	public function setCaption($caption){
		$this->caption = $caption;
	}
	
	public function getCaption(){
		return $this->caption;
	}
	
	public function setP1P2($p1p2){
		$this->p1p2 = $p1p2;
	}
	
	public function getP1P2(){
		return $this->p1p2;
	}
	
	public function setP3P4($p3p4){
		$this->p3p4 = $p3p4;
	}
	
	public function getP3P4(){
		return $this->p3p4;
	}
	
	
	

}