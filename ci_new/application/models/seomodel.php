<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class SEOModel extends CI_Model
{	
	public function __construct() {
		parent::__construct();
	}
	
	public function getSEOInfo($uri) {
		$this->db
			->select(
	            'pagetitle AS pageTitle,
	            metadescription AS metaDescription,
	            metakeywords AS metaKeywords'
	        )
        	->from('seo')
        	->where('pageurl', $uri);
		
		$rs = $this->db->get();
        if ($rs->num_rows() == 1) {
			return $rs->row();
        }
		return new stdClass();
	}	
}
?>