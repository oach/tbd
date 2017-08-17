<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class RatingSystemModel extends CI_Model
{
	public function __construct() {
		parent::__construct();
	}
	
	public function getRatingSystem() {
		$this->db
			->select('id, ratingValue, description')
			->from('rating_system')
			->order_by('ratingValue', 'desc');

		$rs = $this->db->get();
		if ($rs->num_rows() > 0)
        {
        	return $rs->result();
        }
		return [];
	}
}
?>