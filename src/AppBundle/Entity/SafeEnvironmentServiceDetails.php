<?php

namespace AppBundle\Entity;

/**
 * @author James Whitehead
 */
class SafeEnvironmentServiceDetails
{
	
	
	/**
	 * @var string
	 */
	private $servicehost;
	
	/**
	 * @var string
	 */
	private $serviceport;
	
	/**
	 * @var string
	 */
	private $servicename;
	
	/**
	 * @var string
	 */
	private $basepath;
	
	

	
	public function getServiceHost() {
		return $this->servicehost;
	}
	
	public function setServiceHost($servicehost) {
		$this->servicehost = $servicehost;
		return $this;
	}

	
	public function getServicePort() {
		return $this->serviceport;
	}
	
	public function setServicePort($serviceport) {
		$this->serviceport = $serviceport;
		return $this;
	}
	
	public function getServiceName() {
		return $this->servicename;
	}
	
	public function setServiceName($servicename) {
		$this->servicename = $servicename;
		return $this;
	}
	
	public function getBasePath() {
		return $this->basepath;
	}
	
	public function setBasePath($basepath) {
		$this->basepath = $basepath;
		return $this;
	}	
	
	
		
}
