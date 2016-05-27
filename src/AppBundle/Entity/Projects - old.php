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
	 * @var float
	 */
	private $gate1variance;
	
	/**
	 * @var float
	 */
	private $gate2variance;
	
	
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
	private $reusability;	
	
	/**
 	 * @var string
 	 */
	private $cycle_id;
	

	/**
 	 * @var array
 	 */
	private $kpi_results;
	
	/**
	 * @var array
	 */
	private $total_kpi_results;
	
 	/**
	 * @var string
 	*/
	private $scope;

	/**
	 * @var string
	 */
	private $deliverable;
	
	/**
	 * @var string
	 */
	private $document_name;
	
	/**
	 * @var string
	 */
	private $document_type;
	
	/**
	 * @var string
	 */
	private $testing_applicable;
	
	/**
	 * @var string
	 */
	private $delivery_date;
	
	/**
	 * @var string
	 */
	private $signoff_date;
	
	/**
	 * @var string
	 */
	private $repository_link;
	
// 	/**
// 	 * @var string
// 	 */
// 	private $actual_prodlive_date;
	
	
	
// 	/**
// 	 * @var string
// 	 */
// 	private $remark;
	
// 	/**
// 	 * @var string
// 	 */
// 	private $engagement_date;
	
// 	/**
// 	 * @var string
// 	 */
// 	private $gate1_estimatation_delivery_date;
	
// 	/**
// 	 * @var string
// 	 */
// 	private $diff_dates;
	
// 	/**
// 	 * @var int
// 	 */
// 	private $gate1_estimation;
	
// 	/**
// 	 * @var int
// 	 */
// 	private $gate2_estimation;
	
// 	/**
// 	 * @var int
// 	 */
// 	private $final_estimation;
	
// 	/**
// 	 * @var int
// 	 */
// 	private $gate1_variance;
	
// 	/**
// 	 * @var int
// 	 */
// 	private $gate2_variance;
	
	
// 	/**
// 	 * @var int
// 	 */
// 	private $qc_project_name;
	
// 	
// 	/**
// 	 * @var string
// 	 */
// 	private $in_qc;
	
// 	/**
// 	 * @var string
// 	 */
// 	private $wp;
	

	

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
	
	public function setQCProjectName($qc_projectname){
		$this->qc_projectname = $qc_projectname;
	}
	
	public function getQCProjectName(){
		return $this->qc_projectname;
	}
		
	
	public function setPOC($poc){
		$this->poc = $poc;
	}
	
	public function getPOC(){
		return $this->poc;
	}
	
	public function setEstimatedProdLiveDate($prodlivedate){
		$this->estimated_prodlive_date = $prodlivedate;
	}
	
	public function getEstimatedProdLiveDate(){
		return $this->estimated_prodlive_date;
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
	
	public function setGate1Variance($gate1variance){
		$this->gate1variance = $gate1variance;
	}
	
	public function getGate1Variance(){
		return $this->gate1variance;
	}
	
	public function setGate2Variance($gate2variance){
		$this->gate2variance = $gate2variance;
	}
	
	public function getGate2Variance(){
		return $this->gate2variance;
	}
	
	public function setKPIResults($kpiarray){
		$this->kpi_results = $kpiarray;
	}
	
	public function getKPIResults(){
		return $this->kpi_results;
	}
	
	public function setTotalKPIResults($totalkpiarray){
		$this->total_kpi_results = $totalkpiarray;
	}
	
	public function getTotalKPIResults(){
		return $this->total_kpi_results;
	}
	

	public function setDeliverable($deliverables){
		$this->deliverable = $deliverables;
	}
	
	public function getDeliverable(){
		return $this->deliverable;
	}
	
	public function setDocumentName($documentname){
		$this->document_name = $documentname;
	}
	
	public function getDocumentName(){
		return $this->document_name;
	}

	public function setDocumentType($documenttype){
		$this->document_type = $documenttype;
	}
	
	public function getDocumentType(){
		return $this->document_type;
	}
	
	public function setTesting($testing){
		$this->testing_applicable = $testing;
	}
	
	public function getTesting(){
		return $this->testing_applicable;
	}
	
	public function setDeliveryDate($deliverydate){
		$this->delivery_date = $deliverydate;
	}
	
	public function getDeliveryDate(){
		return $this->delivery_date;
	}
	
	public function setSignOffDate($signoffdate){
		$this->signoff_date = $signoffdate;
	}
	
	public function getSignOffDate(){
		return $this->signoff_date;
	}
	
	public function setRepositoryLink($repolink){
		$this->repository_link = $repolink;
	}
	
	public function getRepositoryLink(){
		return $this->repository_link;
	}


}