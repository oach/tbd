<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class ChangetypeModel extends CI_Model
{
	public function __construct() {
		parent::__construct();
	}
	
	public function selectForDropdown() {
		$this->db
			->select('id, changetype AS name')
			->from('changetype')
			->order_by('changetype', 'asc');

		$rs = $this->db->get();
		if ($rs->num_rows() > 0) {
        	return $rs->result_array();
		}
		return [];
	}
	
	public function checkExistsByID($id) {
		$this->db
			->select('id')
			->from('changetype')
			->where('id', $id);

		$rs = $this->db->get();
		if ($rs->num_rows() == 1) {
        	return true;
		}
		return false;
	}
	
	public function getByID($id) {
		$this->db
			->select('id, changetype')
			->from('changetype')
			->where('id', $id);

		$rs = $this->db->get();
		if ($rs->num_rows() == 1) {
        	return $rs->row();
		}
		return [];
	}
}
?>