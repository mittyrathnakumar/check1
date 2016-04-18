<?php

namespace AppBundle\Service;

class Constants
{		
	
	public function getSiteRootPath(){
		return $_SERVER['DOCUMENT_ROOT'].'/ART';
	}
	
	public function getSiteRootWebPath(){
		return $_SERVER['DOCUMENT_ROOT'].'/ART/web';
	}
	
	public function getTestDataUploadPath(){
		return $_SERVER['DOCUMENT_ROOT'].'/ART/web/uploads';
	}
	
	public function getServicePath(){
		return $_SERVER['DOCUMENT_ROOT'].'/ART/src/AppBundle/Service';
	}
	
	public function getVendorPath(){
		return $_SERVER['DOCUMENT_ROOT'].'/ART/vendor';
	}
	
	public function getPHPExcelClassPath(){
		return $_SERVER['DOCUMENT_ROOT'].'/ART/vendor/PHPExcel';
	}	
 
	
}