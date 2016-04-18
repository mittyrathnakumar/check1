<?php

namespace AppBundle\Entity;

/**
 * @author James Whitehead
 */
class SystemDetails
{		
	/**
	 * @var string
	 */
	private $hostname;
	
	/**
	 * @var string
	 */
	private $ip;
	
	/**
	 * @var string
	 */
	private $owner;
	
	/**
	 * @var string
	 */
	private $os;
	
	/**
	 * @var string
	 */
	private $vmhostname;
	
	/**
	 * @var string
	 */
	private $vmip;
	
	/**
	 * @var string
	 */
	private $vmos;
	
	/**
	 * @var strin
	 */
	private $vmallocatedto;	

	/**
	 * @var strin
	 */
	private $comments;	
	

	public function getHostName() {
		return $this->hostname;
	}
	
	public function setHostName($hostname) {
		$this->hostname = $hostname;
		return $this;
	}
	
	public function getIP() {
		return $this->ip;
	}
	
	public function setIP($ip) {
		$this->ip = $ip;
		return $this;
	}
	
	public function getOwner() {
		return $this->owner;
	}
	
	public function setOwner($owner) {
		$this->owner = $owner;
		return $this;
	}
	
	public function getOS() {
		return $this->os;
	}
	
	public function setOS($os) {
		$this->os = $os;
		return $this;
	}
	
	public function getVMHostName() {
		return $this->vmhostname;
	}
	
	public function setVMHostName($vmhostname) {
		$this->vmhostname = $vmhostname;
		return $this;
	}
	
	public function getVMIP() {
		return $this->vmip;
	}
	
	public function setVMIP($vmip) {
		$this->vmip = $vmip;
		return $this;
	}
	
	public function getVMOS() {
		return $this->vmos;
	}
	
	public function setVMOS($vmos) {
		$this->vmos = $vmos;		
		return $this;
	}
	
	public function getVMAllocatedTo() {
		return $this->vmallocatedto;
	}
	
	public function setVMAllocatedTo($vmallocatedto) {
		$this->vmallocatedto = $vmallocatedto;
		return $this;
	}	
	
	
	public function getComments(){
		return $this->comments;		
	}
	
	public function setComments($comments){		
		$this->comments = wordwrap($comments, 50);
	}
	
	
}
