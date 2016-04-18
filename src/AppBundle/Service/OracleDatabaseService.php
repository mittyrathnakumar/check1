<?php

namespace AppBundle\Service;

/**
 * @author James Whitehead
 */
class OracleDatabaseService
{
	/* Production Values */
 	
	
	/* Development Values */	
  
	
	const HOSTNAME = 'localhost';
	const PORT = '1521';
	const SERVICE_NAME = 'orcl';
	
	const KPI_USERNAME = 'KPIDASHBOARD';
	const KPI_PASSWORD = 'KPIDASHBOARD';	
	
	
	private $db;
	private $conn;
	
	/**
	 * 
	 */
	public function __construct() {		
		// Easy Connect Naming Syntax		
		$this->db = self::HOSTNAME.':'.self::PORT.'/'.self::SERVICE_NAME;
	}
	
	/**
	 * Connects to the orcl service (i.e. database)
	 * 
	 * @param string $instance
	 */
	public function openConnection($instance = '') {
		switch ($instance) {
			case 'KPIDASHBOARD':
				$conn = oci_connect(self::KPI_USERNAME, self::KPI_PASSWORD, $this->db);
				break;
		}
		
		if (!$conn) {
			$e = oci_error();
			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		} else {
			$this->conn = $conn;
		}
	}
	
	/**
	 * 
	 */
	public function closeConnection() {
		oci_close($this->conn);
		$this->conn = null;
	}
	
	/**
	 * 
	 */
	public function getConnection() {
		return $this->conn;
	}
	
}