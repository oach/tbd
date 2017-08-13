<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class CategoryModel extends CI_Model
{
	public function __construct() {
		parent::__construct();
	}
	
	public function getCategoryInfoByID($categoryID) {
		$this->db
			->select('id, name, description')
			->from('establishment_categories')
			->where('id', $categoryID);

		$rs = $this->db->get();
		if ($rs->num_rows() == 1)
        {
        	return $rs->row();
		}
		return [];
	}
}
?>