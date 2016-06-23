<?php
namespace AppBundle\Entity;

/**
 * @author Dhara Sheth
 */

class Projects
{

	/**
	 * @var int
	 */
	private $projectid;
	
	/**
	 * @var string
	 */
	private $projectname;
	
	/**
	 * @var string
	 */
	private $qc_projectname;
	
	/**
	 * @var string
	 */
	private $qc_tablename;	
	
	
	/**
	 * @var string
	 */
	private $wp;

	/**
	 * @var string
	 */
	private $poc;

		/**
	 * @var string
	 */
	private $domain;
	
	/**
	 * @var string
	 */
	private $estimated_prodlive_date;
	/**
	 * @var string
	 */
	private $actualProdLiveDate;
	
	/**
	 * @var string
	 */
	private $reusability;	
	
	/**
 	 * @var string
 	 */
	private $cycle_id;
	
 	/**
	 * @var string
 	*/
	private $scope;
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
	
	/**
	 * @var string
	 */
	private $uatApplicable;
	
	/**
	 * @var string
	 */
	private $projectAddedBy;
	
	/**
	 * @var string
	 */
	private $projectEditedBy;
	
	
	

	public function setProjectID($projectid){
		$this->projectid = $projectid;
	}
	
	public function getProjectID(){
		return $this->projectid;
	}
	
	public function setProjectName($projectname){
		$this->projectname = $projectname;		
	}
	
	public function getProjectName(){
		return $this->projectname;
	}
	public function setWP($wp){
		$this->wp = $wp;
	}
	
	public function getWP(){
		return $this->wp;
	}
	
	public function setQCProjectName($qc_projectname){
		$this->qc_projectname = $qc_projectname;
	}
	
	public function getQCProjectName(){
		return $this->qc_projectname;
	}
	
	public function setQCTableName($qc_tablename){
		$this->qc_tablename = $qc_tablename;
	}
	
	public function getQCTableName(){
		return $this->qc_tablename;
	}
	
	
	public function setPOC($poc){
		$this->poc = $poc;
	}
	
	public function getPOC(){
		return $this->poc;
	}
	
	public function setCycleID($cycle_id){
		$this->cycle_id = $cycle_id;
	}
	
	public function getCycleID(){
		return $this->cycle_id;
	}
	
	public function setEstimatedProdLiveDate($prodlivedate){
		$this->estimated_prodlive_date = $prodlivedate;
	}
	
	public function getEstimatedProdLiveDate(){
		return $this->estimated_prodlive_date;
	}
	
	public function setActualProdLiveDate($actualProdLiveDate){
		$this->actualProdLiveDate = $actualProdLiveDate;
	}
	
	public function getActualProdLiveDate(){
		return $this->actualProdLiveDate;
	}
	
	public function setReusability($reusability){
		$this->reusability = $reusability;
	}
	
	public function getReusability(){
		return $this->reusability;
	}
	
	public function setDomain($domain){
		$this->domain = $domain;
	}
	
	public function getDomain(){
		return $this->domain;
	}
	
	public function setScope($scope){
		$this->scope= $scope;
	}
	
	public function getScope(){
		return $this->scope;
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
	public function setSTAutomationApplicable($stAutomation){
		$this->stAutomation = $stAutomation;
	}
	
	public function getSTAutomationApplicable(){
		return $this->stAutomation;
	}
	
	public function setUatApplicable($uatApplicable){
		$this->uatApplicable = $uatApplicable;
	}
	
	public function getUatApplicable(){
		return $this->uatApplicable;
	}
	
	public function setProjectAddedBy($projectAddedBy){
		$this->projectAddedBy = $projectAddedBy;
	}
	
	public function getProjectAddedBy(){
		return $this->projectAddedBy;
	}
	
	public function setProjectEditedBy($projectEditedBy){
		$this->projectEditedBy = $projectEditedBy;
	}
	
	public function getProjectEditedBy(){
		return $this->projectEditedBy;
	}


}