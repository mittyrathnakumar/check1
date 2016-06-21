<?php

namespace AppBundle\Service;

/**
 * @author James Whitehead
 */
class OracleDatabaseService
{
	/* Production Values */
 	
	
	/* Development Values */	
  
	
	//const HOSTNAME = 'VCOLNSYD154789';   // My Hostname
	
	/* const HOSTNAME = 'localhost';
	const PORT = '1521';
	const SERVICE_NAME = 'orcl';
	
	const KPI_USERNAME = 'KPIDASHBOARD';
	const KPI_PASSWORD = 'KPIDASHBOARD';	
	
	/* QC Connection Details 
	
	const HOSTNAME = 'localhost';
	const PORT = '3001';
	const SERVICE_NAME = 'QCPRD01';
	
	const QC_USERNAME = 'VHA61065152';
	const QC_PASSWORD = 'VhaLogin123';
	
	
	/*
	const HOSTNAME = 'vvsl60034.vodafone.com.au';
	const PORT = '2521';
	const SERVICE_NAME = 'TAU1I.vodafone.com.au';
	
	const KPI_USERNAME = 'Siebeldata';
	const KPI_PASSWORD = 'Siebeldata';
	*/
	
	
	//const QC_PASSWORD = 'VhaLogin123';	
	
	
	private $db;
	private $conn;
	
	/**
	 * 
	 */
	public function __construct() {		
		// Easy Connect Naming Syntax		
		//$this->db = self::HOSTNAME.':'.self::PORT.'/'.self::SERVICE_NAME;
		//$this->db = 'vvsl60034.vodafone.com.au:2521:2521/TAU1I.vodafone.com.au';
		
	}
	
	/**
	 * Connects to the orcl service (i.e. database)
	 * 
	 * @param string $instance
	 */
	public function openConnection($instance = '') {
		switch ($instance) {
			
			case 'KPIDASHBOARD':
				
				
				$HOSTNAME = 'localhost';
				$PORT = '1521';
				$SERVICE_NAME = 'orcl';
				
				$KPI_USERNAME = 'KPIDASHBOARD';
				$KPI_PASSWORD = 'KPIDASHBOARD';
				
				
				/*
				$HOSTNAME = 'vvsl60034.vodafone.com.au';
				$PORT = '2521';
				$SERVICE_NAME = 'TAU1I.vodafone.com.au';
				
				$KPI_USERNAME = 'KPIDASHBOARD';
				$KPI_PASSWORD = 'KPIDASHBOARD';
				*/
				
				$this->db = $HOSTNAME.':'.$PORT.'/'.$SERVICE_NAME;
				
				$conn = oci_connect($KPI_USERNAME, $KPI_PASSWORD, $this->db);
				break;
				
			case 'QC':
				
				$HOSTNAME = 'localhost';
				$PORT = '3001';
				$SERVICE_NAME = 'QCPRD01';
				
				$QC_USERNAME = 'VHA61065152';
				$QC_PASSWORD = 'VhaLogin123';
				
				$this->db = $HOSTNAME.':'.$PORT.'/'.$SERVICE_NAME;
				
				$conn = oci_connect($QC_USERNAME, $QC_PASSWORD, $this->db);
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