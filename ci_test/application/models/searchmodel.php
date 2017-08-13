<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class SearchModel extends CI_Model
{	
	public function __construct() {
		parent::__construct();
	}
    
    public function beer_search($search) {
        $this->db->select(
            'beers.id,
			beers.establishmentID,
			beers.beerName,
			beers.retired,
			beers.slug AS slug_beer,
			establishment.name,
			establishment.stateID,
			establishment.city,
			establishment.slug AS slug_establishment,
			state.stateFull,'
        );
        $this->db->from('beers');
        $this->db->join('establishment', 'establishment.id = beers.establishmentID', 'inner');
        $this->db->join('state', 'state.id = establishment.stateID', 'inner');
        $this->db->where('beers.active', '1');
        $this->db->where('establishment.active', '1');

		if (is_array($search) && count($search) > 0) {
			$this->db->where($this->_createLikeSearch($search, 'beers.beerName'));
        }
        else {
			$this->db->where('beers.beerName', $search);
		}
        
        $rs = $this->db->get();
        //var_dump($this->db->last_query());
		if ($rs->num_rows() > 0) {
			return $rs->result();
		}
		return false;
    }
    
    public function establishment_search($search) {
        $this->db->select(
            'establishment.id,
			establishment.name,
			establishment.stateID,
			establishment.city,
			establishment.slug AS slug_establishment,
			state.stateFull,'
        );
        $this->db->from('establishment');
        $this->db->join('state', 'state.id = establishment.stateID', 'inner');
        $this->db->where('establishment.active', '1');

        if (is_array($search) && count($search) > 0) {
			$this->db->where($this->_createLikeSearch($search, 'establishment.name'));
		}
		else {
			$this->db->where('establishment.name', $search);
		}
        
        $rs = $this->db->get();
        if ($rs->num_rows() > 0) {
			return $rs->result();
		}
		return false;
    }
    
    public function user_search($search) {
    	$this->db
    		->select(
    			'users.id,
    			users.username AS name,
    			users.avatarImage AS picture'
    		)
            ->from('users')
            ->like('users.username', $search, 'after')
            ->where('users.active', '1')
        	->where('users.banned', '0')
            ->order_by('users.username', 'desc');

    	$rs = $this->db->get();
    	if ($rs->num_rows() > 0) {
        	return $rs->result();
		}
		return [];

    	$this->db
    		->select(
	    		'id,
	    		username'
    		)
    		->from('users')
    		->where('active', '1')
    		->where('users.banned', '0');
    	//$this->db->where('username',  $search);           

    	if (is_array($search) && count($search) > 0) {
			$this->db->where($this->_createLikeSearch($search, 'username'));
		}
		else {
			$this->db->where('username', $search);
		}

		$this->db
			->order_by('users.username', 'desc')
    		->limit(8);
        
        $rs = $this->db->get();
        if ($rs->num_rows() > 0) {
			return $rs->result();
		}
		return false;
    }
	
	private function _createLikeSearch($search, $field) {
		$like = '';
		
		if (is_array($search) && count($search) > 0) {
			foreach ($search as $word) {
				$like .= (empty($like) ? '' : ' OR ') . $field .' LIKE "%' . $word . '%"';
			}
			$like = '(' . $like . ')';
		}
		
		return $like;
	}
}
?>