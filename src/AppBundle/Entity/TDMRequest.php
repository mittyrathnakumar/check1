<?php

namespace AppBundle\Entity;

/**
 * @author James Whitehead
 */
class TDMRequest
{
	/**
	 * @var int
	 */
	private $referenceNo;
	
	/**
	 * @var string
	 */
	private $project;
	
	/**
	 * @var string
	 */
	private $release;
	
	/**
	 * @var string
	 */
	private $environment;
	
	/**
	 * @var string
	 */
	private $name;
	
	/**
	 * @var string
	 */
	private $comments;
	
	/**
	 * @var date
	 */
	private $dateRequested;
	
	/**
	 * @var date
	 */
	private $dateNeeded;
	
	/**
	 * @var string
	 */
	private $status;
	
	public function getRequestStatusClass($requestStatus) {
		switch ($requestStatus) {
			case 'COMPLETE':
				return 'complete';
				break;
			case 'New':
				return 'new';
				break;
			case 'IN-PROGRESS':
				return 'inprogress';
				break;							
			default:
				return 'red';
				break;
		}
	}		
	
	public function setReferenceNo($referenceNo) {
		$this->referenceNo = $referenceNo;
	}
	
	public function getReferenceNo() {
		return $this->referenceNo;
	}
	
	public function setProject($project) {
		$this->project = $project;
	}
	
	public function getProject() {
		return $this->project;
	}

	public function setRelease ($release) {
		$this->release = $release;
	}
	
	public function getRelease() {
		return $this->release;
	}
	
	public function setEnvironment($environment) {
		$this->environment = $environment;
	}
	
	public function getEnrionment() {
		return $this->environment;
	}
	
	public function setName($name) {
		$this->name = $name;
	}
	
	public function getName() {
		return $this->name;
	}
		
	public function setComments($comments) {
		$this->comments = $comments;
	}	

	public function getComments() {
		return $this->comments;
	}
	
	public function setDateRequested($dateRequested) {
		$this->dateRequested = $dateRequested;
	}
	
	public function getDateRequested() {
		return $this->dateRequested;
	}
	
	public function setDateNeeded($dateNeeded) {
		$this->dateNeeded = $dateNeeded;
	}
	
	public function getDateNeeded() {
		return $this->dateNeeded;
	}
	
	public function setStatus($status) {
		$this->dateNeeded = $status;
	}
	
	public function getStatus() {
		return $this->status;
	}
	
	
	
}
