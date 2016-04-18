<?php

namespace AppBundle\Entity;

/**
 * @author James Whitehead
 */
class SafeEnvironmentDetails
{
	
	
	/**
	 * @var string
	 */
	private $host;
	
	/**
	 * @var string
	 */
	private $port;
	
	/**
	 * @var array
	 */
	
	private $serviceDetailsArray;
	
	public function getHost() {
		return $this->host;
	}
	
	public function setHost($host) {
		$this->host = $host;
		return $this;
	}
	

	public function getPort() {
		return $this->port;
	}
	
	public function setPort($port) {
		$this->port = $port;
		return $this;
	}
	
	public function setServiceDetailsArray(array $servicedetails) {
		$this->serviceDetailsArray = $servicedetails;
		return $this;
	}
	
	public function getServiceDetailsArray() {
		return $this->serviceDetailsArray;
	}
	
		
}
