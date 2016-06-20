<?php

namespace AppBundle\Entity;

class ProdTestAccountsEntity
{
	/**
	 * @var int
	 */
	private $rowId;
	/**
	 * @var string
	 */
	private $month;
	
	/**
	 * @var int
	 */
	private $custAccounts;
	
	/**
	 * @var int
	 */
	private $onlineCustAccounts;
	
	/**
	 * @var int
	 */
	private $custAddrAccounts;
	
	/**
	 * @var int
	 */
	private $onlineCustAddrAccounts;
	
	/**
	 * @var int
	 */
	private $nameAccounts;
	
	/**
	 * @var int
	 */
	private $onlineCustNameAccounts;
	
	/**
	 * @var int
	 */
	private $billAccounts;
	
	/**
	 * @var int
	 */
	private $onlineBillAccounts;
	
	/**
	 * @var int
	 */
	private $billAddrAccounts;
	
	/**
	 * @var int
	 */
	private $onlineBillAddrAccounts;
	
	/**
	 * @var int
	 */
	private $incorrectBillAccounts;
	
	/**
	 * @var int
	 */
	private $onlineIncorrectBillAccounts;
	
	/**
	 * @var int
	 */
	private $notSharedAccounts;
	
	/**
	 * @var int
	 */
	private $onlineNotSharedAccounts;
	
	/**
	 * @var int
	 */
	private $deviceOrders;
	
	/**
	 * @var int
	 */
	private $onlineDeviceOrders;
	
	/**
	 * @var int
	 */
	private $totalCustAccounts;
	
	/**
	 * @var int
	 */
	private $totalBillAccounts;
	
	/**
	 * @var int
	 */
	private $totalDeviceOrders;
	
	/**
	 * @var int
	 */
	private $totalAccounts;
	
	/**
	 * @var int
	 */
	private $addCompCustAccounts;
	
	/**
	 * @var int
	 */
	private $addCompBillAccounts;
	/**
	 * @var int
	 */
	private $addCompOrders;
	
	/**
	 * @var int
	 */
	private $nameCompCustAccounts;
	
	/**
	 * @var int
	 */
	private $billCompBillAccounts;
	
	/**
	 * @var int
	 */
	private $detailsSharedBillAccounts;
	
	/**
	 * @var int
	 */
	private $detailsSharedOrders;
	
	/**
	 * @var float
	 */
	private $consolidatedCustAccounts;
	
	/**
	 * @var float
	 */
	private $consolidatedBillAccounts;
	
	/**
	 * @var float
	 */
	private $consolidatedOrders;
	
	/**
	 * @var float
	 */
	private $consolidatedTotal;
	
	/**
	 * @var float
	 */
	private $prodTestAccounts;
	
	
	public function setRowId($rowId){
		$this->rowId = $rowId;
	}
	
	public function getRowId(){
		return $this->rowId;
	}
	
	public function setMonth($month){
		$this->month = $month;		
	}
	
	public function getMonth(){
		return $this->month;
	}
	
	public function setCustAccounts($custAccounts){
		$this->custAccounts = $custAccounts;
	}
	
	public function getCustAccounts(){
		return $this->custAccounts;
	}
	
	public function setOnlineCustAccounts($onlineCustAccounts){
		$this->onlineCustAccounts = $onlineCustAccounts;
	}
	
	public function getOnlineCustAccounts(){
		return $this->onlineCustAccounts;
	}
	public function setCustAddrAccounts($custAddrAccounts){
		$this->custAddrAccounts = $custAddrAccounts;
	}
	
	public function getCustAddrAccounts(){
		return $this->custAddrAccounts;
	}
	
	public function setOnlineCustAddrAccounts($onlineCustAddrAccounts){
		$this->onlineCustAddrAccounts = $onlineCustAddrAccounts;
	}
	
	public function getOnlineCustAddrAccounts(){
		return $this->onlineCustAddrAccounts;
	}
	
	public function setNameAccounts($nameAccounts){
		$this->nameAccounts = $nameAccounts;
	}
	
	public function getNameAccounts(){
		return $this->nameAccounts;
	}
	
	public function setOnlineCustNameAccounts($onlineCustNameAccounts){
		$this->onlineCustNameAccounts = $onlineCustNameAccounts;
	}
	
	public function getOnlineCustNameAccounts(){
		return $this->onlineCustNameAccounts;
	}
	
	public function setBillAccounts($billAccounts){
		$this->billAccounts = $billAccounts;
	}
	
	public function getBillAccounts(){
		return $this->billAccounts;
	}
	
	public function setOnlineBillAccounts($onlineBillAccounts){
		$this->onlineBillAccounts = $onlineBillAccounts;
	}
	
	public function getOnlineBillAccounts(){
		return $this->onlineBillAccounts;
	}
	
	public function setBillAddrAccounts($billAddrAccounts){
		$this->billAddrAccounts = $billAddrAccounts;
	}
	
	public function getBillAddrAccounts(){
		return $this->billAddrAccounts;
	}
	
	public function setOnlineBillAddrAccounts($onlineBillAddrAccounts){
		$this->onlineBillAddrAccounts = $onlineBillAddrAccounts;
	}
	
	public function getOnlineBillAddrAccounts(){
		return $this->onlineBillAddrAccounts;
	}
	
	public function setIncorrectBillAccounts($incorrectBillAccounts){
		$this->incorrectBillAccounts = $incorrectBillAccounts;
	}
	
	public function getIncorrectBillAccounts(){
		return $this->incorrectBillAccounts;
	}
	
	public function setOnlineIncorrectBillAccounts($onlineIncorrectBillAccounts){
		$this->onlineIncorrectBillAccounts = $onlineIncorrectBillAccounts;
	}
	
	public function getOnlineIncorrectBillAccounts(){
		return $this->onlineIncorrectBillAccounts;
	}
	
	public function setNotSharedAccounts($notSharedAccounts){
		$this->notSharedAccounts = $notSharedAccounts;
	}
	
	public function getNotSharedAccounts(){
		return $this->notSharedAccounts;
	}
	
	public function setOnlineNotSharedAccounts($onlineNotSharedAccounts){
		$this->onlineNotSharedAccounts = $onlineNotSharedAccounts;
	}
	
	public function getOnlineNotSharedAccounts(){
		return $this->onlineNotSharedAccounts;
	}
	
	public function setDeviceOrders($deviceOrders){
		$this->deviceOrders = $deviceOrders;
	}
	
	public function getDeviceOrders(){
		return $this->deviceOrders;
	}
	
	public function setOnlineDeviceOrders($onlineDeviceOrders){
		$this->onlineDeviceOrders = $onlineDeviceOrders;
	}
	
	public function getOnlineDeviceOrders(){
		return $this->onlineDeviceOrders;
	}
	
	public function setTotalCustAccounts($totalCustAccounts){
		$this->totalCustAccounts = $totalCustAccounts;
	}
	
	public function getTotalCustAccounts(){
		return $this->totalCustAccounts;
	}
	
	public function setTotalBillAccounts($totalBillAccounts){
		$this->totalBillAccounts = $totalBillAccounts;
	}
	
	public function getTotalBillAccounts(){
		return $this->totalBillAccounts;
	}
	
	public function setTotalDeviceOrders($totalDeviceOrders){
		$this->totalDeviceOrders = $totalDeviceOrders;
	}
	
	public function getTotalDeviceOrders(){
		return $this->totalDeviceOrders;
	}
	
	public function setTotalAccounts($totalAccounts){
		$this->totalAccounts = $totalAccounts;
	}
	
	public function getTotalAccounts(){
		return $this->totalAccounts;
	}
	
	public function setAddCompCustAccounts($addCompCustAccounts){
		$this->addCompCustAccounts = $addCompCustAccounts;
	}
	
	public function getaddCompCustAccounts(){
		return $this->addCompCustAccounts;
	}
	
	public function setAddCompBillAccounts($addCompBillAccounts){
		$this->addCompBillAccounts = $addCompBillAccounts;
	}
	
	public function getaddCompBillAccounts(){
		return $this->addCompBillAccounts;
	}
	
	public function setAddCompOrders($addCompOrders){
		$this->addCompOrders = $addCompOrders;
	}
	
	public function getAddCompOrders(){
		return $this->addCompOrders;
	}
	
	public function setNameCompCustAccounts($nameCompCustAccounts){
		$this->nameCompCustAccounts = $nameCompCustAccounts;
	}
	
	public function getNameCompCustAccounts(){
		return $this->nameCompCustAccounts;
	}
	
	public function setBillCompBillAccounts($billCompBillAccounts){
		$this->billCompBillAccounts = $billCompBillAccounts;
	}
	
	public function getBillCompBillAccounts(){
		return $this->billCompBillAccounts;
	}
	public function setDetailsSharedBillAccounts($detailsSharedBillAccounts){
		$this->detailsSharedBillAccounts = $detailsSharedBillAccounts;
	}
	
	public function getDetailsSharedBillAccounts(){
		return $this->detailsSharedBillAccounts;
	}
	
	public function setDetailsSharedOrders($detailsSharedOrders){
		$this->detailsSharedOrders = $detailsSharedOrders;
	}
	
	public function getDetailsSharedOrders(){
		return $this->detailsSharedOrders;
	}
	
	public function setConsolidatedCustAccounts($consolidatedCustAccounts){
		$this->consolidatedCustAccounts = $consolidatedCustAccounts;
	}
	
	public function getConsolidatedCustAccounts(){
		return $this->consolidatedCustAccounts;
	}
	
	public function setConsolidatedBillAccounts($consolidatedBillAccounts){
		$this->consolidatedBillAccounts = $consolidatedBillAccounts;
	}
	
	public function getConsolidatedBillAccounts(){
		return $this->consolidatedBillAccounts;
	}
	
	public function setConsolidatedOrders($consolidatedOrders){
		$this->consolidatedOrders = $consolidatedOrders;
	}
	
	public function getConsolidatedOrders(){
		return $this->consolidatedOrders;
	}
	
	public function setConsolidatedTotal($consolidatedTotal){
		$this->consolidatedTotal = $consolidatedTotal;
	}
	
	public function getConsolidatedTotal(){
		return $this->consolidatedTotal;
	}
	
	public function setProdTestAccounts($prodTestAccounts){
		$this->prodTestAccounts = $prodTestAccounts;
	}
	
	public function getProdTestAccounts(){
		return $this->prodTestAccounts;
	}
	
}