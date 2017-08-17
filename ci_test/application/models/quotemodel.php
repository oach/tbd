<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class QuoteModel extends CI_Model
{
	public function __construct() {
		parent::__construct();
	}
	
	public function getRandom() {
		$this->db
			->select('id, quote, person')
			->from('quotes')
			->order_by('RAND()')
			->limit(1);

		$rs = $this->db->get();
        if ($rs->num_rows() == 1) {
        	return $rs->row_array();
		}
		return [];
	}
}
?>