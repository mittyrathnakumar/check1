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
	
	
	
	

}