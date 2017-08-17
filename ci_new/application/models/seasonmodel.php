<?php
if (!defined('BASEPATH'))
{
	exit('No direct script access allowed');
}

class SeasonModel extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}
	
	public function getSeasonalForFrontPage() {
		$this->db
			->select('season, className, monthrange, beerstyles')
			->from('season')
			->where('FIND_IN_SET(MONTH(CURDATE()), monthrange)')
			->order_by('monthrange', 'desc');
		$rs = $this->db->get();

		if ($rs->num_rows() > 0)
		{
			return $rs->result();
		}
		return [];
	}
}
?>