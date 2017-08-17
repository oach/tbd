<?php
if(!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class PackageModel extends CI_Model {
	public function __construct() {
		parent::__construct();
	}
	
	public function getAll() {
		$query = '
			SELECT
				id
				, package
			FROM package
			ORDER BY
				package ASC
		';
		
		$rs = $this->db->query($query);
		$array = array();
		if($rs->num_rows() > 0) {
			$array = $rs->result_array();
		}
		return $array;
	}
	
	public function getAllForDropDown() {
		$this->db
			->select('id, package AS name', false)
			->from('package')
			->order_by('package', 'asc');

		$rs = $this->db->get();
        if ($rs->num_rows() > 0)
        {
			return $rs->result_array();
        }
		return array();
	}
}
?>