<?php
if(!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class StateModel extends CI_Model
{
	public function __construct() {
		parent::__construct();
	}
	
	public function getAllStates() {
		$query = '
			SELECT
				id
				, stateFull
				, stateAbbr
			FROM state
			ORDER BY
				stateFull ASC
		';
		
		$rs = $this->db->query($query);
		$array = array();
		if($rs->num_rows() > 0) {
			$array = $rs->result_array();
		}
		return $array;
	}
	
	public function getAllForDropDown() {
		$query = '
			SELECT
				id
				, stateFull AS name
			FROM state
			ORDER BY
				stateFull ASC
		';
		
		$rs = $this->db->query($query);
		$array = array();
		if($rs->num_rows() > 0) {
			$array = $rs->result_array();
		}
		return $array;
	}
	
	public function getStateByID($stateID) {
		$this->db
			->select('id, stateFull, stateAbbr')
			->from('state')
			->where('id', $stateID);

		$rs = $this->db->get();
		if ($rs->num_rows() == 1) {
        	return $rs->row();
		}
		return [];
	}
	
	public function getStateCheck($stateID) {
		$this->db
			->select('id')
			->from('state')
			->where('id', $stateID);

		$rs = $this->db->get();
		if ($rs->num_rows() > 0) {
        	return true;
		}
		return false;
	}
}
?>