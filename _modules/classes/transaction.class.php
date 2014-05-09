<?php
//THIS CLASS MANAGES ALL TRANSACTIONS 
class Transaction {
	
	private $trans_id;
	private $cust_id;
	private $trans_numbooks;
	private $trans_date;
	
	//CONSTRUCTOR THAT INITIALIZE VALUES TO DATA FIELDS
	public function __construct($trans_id, $cust_id, $trans_numbooks, $trans_date) {
		$this->trans_id = $trans_id;
		$this->cust_id = $cust_id;
		$this->trans_numbooks = $trans_numbooks;
		$this->trans_date = $trans_date;
	}
	
	//SET METHODS
	public function setCustomer($value) {
		$this->cust_id = $value;
	}
	public function setNumbooks($value) {
		$this->trans_numbooks = $value;
	}
	public function setDate($value) {
		$this->trans_date = $value;
	}
	
	//GET METHODS
	public function getId() {
		return $this->trans_id;
	}
	public function getCustomer() {
		return $this->cust_id;
	}
	public function getNumbooks() {
		return $this->trans_numbooks;
	}
	public function getDate() {
		return $this->trans_date;
	}
	
	
}

?>