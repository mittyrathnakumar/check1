<?php
namespace AppBundle\Entity;

/**
 * @author James Whitehead
 */
class Users
{

	/**
	 * @var string
	 */
	private $email;

	/**
	 * @var string
	 */
	private $firstname;

	/**
	 * @var string
	 */
	private $lastname;
	
	/**
	 * @var string
	 */
	private $userrole;

	/**
	 * @var string
	 */
	private $userroleid;	
	
	/**
	 * @var int
	 */
	private $userid;
	
	/**
	 * @var int
	 */
	private $active;
	
	/**
	 * @var string
	 */
	private $invalid;
	
	/**
	 * @var string
	 */
	private $module_id;
	
	/**
	 * @var string
	 */
	private $module_name;
	
	/**
	 * @var string
	 */
	private $module_arr;
	
	/**
	 * @var string
	 */
	private $user_home_page;
	
	
	
	
	
	public function setEmail($email){
		$this->email = $email;		
	}
	
	public function getEmail(){
		return $this->email;
	}
	
	public function setFirstName($firstname){
		$this->firstname = $firstname;
	}
	
	public function getFirstName(){
		return $this->firstname;
	}
	
	public function setLastName($lastname){
		$this->lastname = $lastname;
	}
	
	public function getLastName(){
		return $this->lastname;
	}
	
	public function setUserRole($userrole){
		$this->userrole = $userrole;
	}
	
	public function getUserRole(){
		return $this->userrole;
	}
	
	public function setUserRoleID($userroleid){
		$this->userroleid = $userroleid;
	}
	
	public function getUserRoleID(){
		return $this->userroleid;
	}
	
	
	public function setUserID($userid){
		return $this->userid = $userid;
	}
	
	public function getUserID(){
		return $this->userid;
	}
	
	public function setUserActive($active){
		$this->active = $active;
	}
	
	public function getUserActive(){
		return $this->active;
	}
	
	public function setInvalid($invalid){
		$this->invalid = $invalid;
	}
	
	public function getInvalid(){
		return $this->invalid;
	}
	
	public function setModuleIDs($module_id){
		$this->module_id = $module_id;
	}
	
	public function getModuleIDs(){
		return $this->module_id;
	}
	
	public function setModuleAccess($module_names){
		$this->module_name = $module_names;
	}
	
	public function getModuleAccess(){
		return $this->module_name;
	}
	
	public function setModuleArr($module_arr){
		$this->module_arr = $module_arr;
	}
	
	public function getModuleArr(){
		return $this->module_arr;
	}
	
	public function setUserHomePage($user_home_page){
		$this->user_home_page = $user_home_page;
	}
	
	public function getUserHomePage(){
		return $this->user_home_page;
	}
	

}