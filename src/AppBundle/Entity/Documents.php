<?php
namespace AppBundle\Entity;

/**
 * @author Dhara Sheth
 */

class Documents
{

	/**
	 * @var int
	 */
	private $projectid;
	
	/**
	 * @var int
	 */
	private $documentid;
	
	/**
	 * @var string
	 */
	private $projectname;
	
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
	
	/**
	 * @var string
	 */
	private $document_month;
	
	

	public function setProjectID($projectid){
		$this->projectid = $projectid;
	}
	
	public function getProjectID(){
		return $this->projectid;
	}
	

	public function setDocumentID($documentid){
		$this->documentid = $documentid;
	}
	
	public function getDocumentID(){
		return $this->documentid;
	}
	
	public function setProjectName($projectname){
		$this->projectname = $projectname;		
	}
	
	public function getProjectName(){
		return $this->projectname;
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
	
	public function setDeliverable($deliverables){
		$this->deliverable = $deliverables;
	}
	
	public function getDeliverable(){
		return $this->deliverable;
	}
	
	public function setDocumentMonth($docmonth){
		$this->document_month = $docmonth;
	}
	
	public function getDocumentMonth(){
		return $this->document_month;
	}
	
	
	
}